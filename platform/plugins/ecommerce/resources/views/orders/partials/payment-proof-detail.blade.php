<div class="mt-3">
    <x-core::alert type="info" :icon="false" class="mb-0">
        <div class="w-100">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                    <div class="d-flex align-items-center mb-2">
                        <x-core::icon name="ti ti-file-invoice" class="alert-icon me-2" />
                        <strong>{{ trans('plugins/ecommerce::order.payment_proof') }}</strong>
                    </div>
                </div>
                <div class="ms-3">
                    <a href="{{ $downloadUrl ?? route('orders.download-proof', $order->id) }}" target="_blank" class="btn btn-info btn-sm">
                        {{ trans('plugins/ecommerce::order.download') }}
                        <x-core::icon name="ti ti-download" class="ms-1" />
                    </a>
                </div>
            </div>
            <div class="small text-muted mt-1 text-start">
                @php
                    $fileName = basename($order->proof_file);
                    $displayName = strlen($fileName) > 30 ? substr($fileName, 0, 15) . '...' . substr($fileName, -12) : $fileName;
                    $fileInfo = Storage::disk('local')->exists($order->proof_file) ? Storage::disk('local')->lastModified($order->proof_file) : null;
                @endphp
                <div class="mb-1">
                    <x-core::icon name="ti ti-file" class="me-1" style="font-size: 14px;" />
                    <span title="{{ $fileName }}">{{ $displayName }}</span>
                </div>
                @if($fileInfo)
                    <div>
                        <x-core::icon name="ti ti-clock" class="me-1" style="font-size: 14px;" />
                        {{ __('Uploaded') }}: {{ Carbon\Carbon::createFromTimestamp($fileInfo)->format('M d, Y H:i') }}
                    </div>
                @endif
            </div>
        </div>
    </x-core::alert>
</div>