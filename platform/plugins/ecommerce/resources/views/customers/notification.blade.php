@if (! $data->confirmed_at)
    <x-core::alert
        type="warning"
    >
        {!! BaseHelper::clean(
        trans('plugins/ecommerce::customer.verify_email.notification', [
            'approve_link' => Html::link(
                route('customers.verify-email', $data->id),
                trans('plugins/ecommerce::customer.verify_email.approve_here'),
                ['class' => 'verify-customer-email-button'],
            ),
        ])) !!}

        <div class="mt-2">
            <button type="button" class="btn btn-sm btn-primary resend-verification-email-button" data-customer-id="{{ $data->id }}">
                <x-core::icon name="mail-forward" /> {{ trans('plugins/ecommerce::customer.resend_verification_email') }}
            </button>
        </div>
    </x-core::alert>

    @push('footer')
        <x-core::modal
            id="verify-customer-email-modal"
            type="warning"
            :title="trans('plugins/ecommerce::customer.verify_email.confirm_heading')"
            button-id="confirm-verify-customer-email-button"
            :button-label="trans('plugins/ecommerce::customer.verify_email.confirm_button')"
        >
            {!! trans('plugins/ecommerce::customer.verify_email.confirm_description') !!}
        </x-core::modal>

        <x-core::modal.action
            id="resend-verification-email-modal"
            type="info"
            :title="trans('plugins/ecommerce::customer.resend_verification_email')"
            :description="trans('plugins/ecommerce::customer.resend_verification_email_confirmation')"
            :submit-button-label="trans('plugins/ecommerce::customer.send')"
            :submit-button-attrs="['id' => 'confirm-resend-verification-email-button']"
            :close-button-label="trans('plugins/ecommerce::customer.cancel')"
        />

        <script>
            $(document).ready(function() {
                var customerId = null;

                $('.resend-verification-email-button').on('click', function(e) {
                    e.preventDefault();
                    customerId = $(this).data('customer-id');
                    $('#resend-verification-email-modal').modal('show');
                });

                $('#confirm-resend-verification-email-button').on('click', function(e) {
                    e.preventDefault();

                    var button = $(this);
                    var originalText = button.html();

                    button.prop('disabled', true);
                    button.html('<span class="spinner-border spinner-border-sm me-1"></span> ' + button.text());

                    $.ajax({
                        url: '{{ route('customers.resend-verification-email', ['id' => '__id__']) }}'.replace('__id__', customerId),
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            $('#resend-verification-email-modal').modal('hide');
                            if (data.error) {
                                Botble.showError(data.message);
                            } else {
                                Botble.showSuccess(data.message);
                            }
                        },
                        error: function() {
                            $('#resend-verification-email-modal').modal('hide');
                            Botble.showError('{{ trans('plugins/ecommerce::customer.error_sending_verification_email') }}');
                        },
                        complete: function() {
                            button.prop('disabled', false);
                            button.html(originalText);
                        }
                    });
                });
            });
        </script>
    @endpush
@endif
