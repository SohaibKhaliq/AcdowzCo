@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('ecommerce.reseller-penalties.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ trans('plugins/ecommerce::reseller.penalties.create') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="reseller_id" class="form-label">{{ trans('plugins/ecommerce::reseller.penalties.reseller') }} <span class="text-danger">*</span></label>
                            <select name="reseller_id" id="reseller_id" class="form-control" required>
                                <option value="">-- {{ trans('core/base::forms.select') }} --</option>
                                @foreach($resellers as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="order_id" class="form-label">{{ trans('plugins/ecommerce::reseller.penalties.order') }}</label>
                            <select name="order_id" id="order_id" class="form-control">
                                <option value="">-- {{ trans('core/base::forms.select') }} --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="product_id" class="form-label">{{ trans('plugins/ecommerce::reseller.penalties.product') }}</label>
                            <input type="number" name="product_id" id="product_id" class="form-control" placeholder="Product ID (optional)">
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">{{ trans('plugins/ecommerce::reseller.penalties.amount') }} <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">{{ trans('plugins/ecommerce::reseller.penalties.reason') }} <span class="text-danger">*</span></label>
                            <textarea name="reason" id="reason" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ trans('core/base::forms.save') }}</button>
                        <a href="{{ route('ecommerce.reseller-penalties.index') }}" class="btn btn-secondary">{{ trans('core/base::forms.cancel') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('footer')
    <script>
        $(document).ready(function() {
            $('#reseller_id').on('change', function() {
                var resellerId = $(this).val();
                if (resellerId) {
                    $.ajax({
                        url: '{{ route('ecommerce.reseller-penalties.ajax.orders') }}',
                        type: 'GET',
                        data: { reseller_id: resellerId },
                        success: function(data) {
                            $('#order_id').empty().append('<option value="">-- {{ trans('core/base::forms.select') }} --</option>');
                            $.each(data, function(index, order) {
                                $('#order_id').append('<option value="' + order.id + '">' + order.text + '</option>');
                            });
                        }
                    });
                } else {
                    $('#order_id').empty().append('<option value="">-- {{ trans('core/base::forms.select') }} --</option>');
                }
            });
        });
    </script>
@endpush
