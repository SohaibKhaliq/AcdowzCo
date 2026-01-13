<x-core::alert type="warning" class="mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <span>{{ trans('plugins/ecommerce::customer.verify_email.notification', ['approve_link' => '']) }}</span>
        <x-core::button
            type="button"
            color="warning"
            size="sm"
            class="btn-resend-verification-email"
            data-url="{{ route('customers.resend-verification-email', $customer->id) }}"
        >
            {{ trans('plugins/ecommerce::customer.resend_verification_email') }}
        </x-core::button>
    </div>
</x-core::alert>

<script>
    $(document).ready(function () {
        $('.btn-resend-verification-email').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            var url = $this.data('url');
            
            $this.prop('disabled', true);
            
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    Botble.showSuccess(data.message);
                    $this.prop('disabled', false);
                },
                error: function (data) {
                    Botble.showError(data.responseJSON.message || 'An error occurred');
                    $this.prop('disabled', false);
                }
            });
        });
    });
</script>