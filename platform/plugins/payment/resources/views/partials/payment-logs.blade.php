@php
    $paymentLogs = $payment->getPaymentLogs()->take(50);
@endphp

@if($paymentLogs->isNotEmpty())
    <div class="mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h3 class="mb-0">
                <x-core::icon name="ti ti-history" class="me-2" />
                {{ trans('plugins/payment::payment.payment_logs') }}
            </h3>
            <span class="badge bg-blue text-blue-fg">{{ $paymentLogs->count() }} {{ trans('plugins/payment::payment.log_entries') }}</span>
        </div>
        <ul class="timeline timeline-simple">
                @foreach($paymentLogs as $index => $log)
                    @php
                        $logType = 'default';
                        $icon = 'ti ti-circle-dot';
                        $iconColor = 'bg-secondary';
                        $title = '';

                        if(isset($log->request['webhook_event_id']) || isset($log->request['webhook_request'])) {
                            $logType = 'webhook';
                            $icon = 'ti ti-webhook';
                            $title = 'Webhook Event';
                        } elseif(isset($log->request['callback_request'])) {
                            $logType = 'callback';
                            $icon = 'ti ti-arrow-back-up';
                            $title = 'Payment Callback';
                        } elseif(isset($log->request['verification_attempt'])) {
                            $logType = 'verification';
                            $icon = 'ti ti-shield-check';
                            $title = 'Signature Verification';
                        }

                        if(isset($log->request['error'])) {
                            $iconColor = 'bg-danger';
                            $icon = 'ti ti-alert-circle';
                        } elseif(isset($log->request['success'])) {
                            $iconColor = 'bg-success';
                            $icon = 'ti ti-circle-check';
                        } elseif(isset($log->request['warning'])) {
                            $iconColor = 'bg-warning';
                            $icon = 'ti ti-alert-triangle';
                        }

                        // Extract meaningful title from log data
                        if(isset($log->request['event_type'])) {
                            $title = str_replace('_', ' ', ucwords($log->request['event_type']));
                        } elseif(isset($log->request['processing_payment'])) {
                            $title = 'Processing Payment';
                        } elseif(isset($log->request['order_lookup'])) {
                            $title = 'Order Lookup';
                        } elseif(isset($log->request['payment_processed_without_signature'])) {
                            $title = 'Payment Processed';
                        } elseif(isset($log->request['webhook_payment_processed'])) {
                            $title = 'Webhook Payment Processed';
                        }
                    @endphp

                    <li class="timeline-event">
                        <div class="timeline-event-icon {{ $iconColor }}">
                            <x-core::icon :name="$icon" />
                        </div>
                        <div class="card timeline-event-card">
                            <div class="card-body">
                                <div class="text-secondary float-end">
                                    <small>{{ $log->created_at->diffForHumans() }}</small>
                                </div>
                                <h4 class="mb-2">
                                    {{ $title ?: 'Payment Event' }}

                                    @if($logType == 'webhook')
                                        <span class="badge badge-sm bg-orange text-orange-fg ms-2">Webhook</span>
                                    @elseif($logType == 'callback')
                                        <span class="badge badge-sm bg-blue text-blue-fg ms-2">Callback</span>
                                    @elseif($logType == 'verification')
                                        <span class="badge badge-sm bg-cyan text-cyan-fg ms-2">Verification</span>
                                    @endif

                                    @if(isset($log->request['event_type']))
                                        <span class="badge badge-sm bg-purple text-purple-fg ms-2">{{ $log->request['event_type'] }}</span>
                                    @endif

                                    @if(isset($log->request['signature_verification']) && $log->request['signature_verification'] === 'success')
                                        <span class="badge badge-sm bg-green text-green-fg ms-1">
                                            <x-core::icon name="ti ti-shield-check" style="width: 12px; height: 12px;" />
                                            Verified
                                        </span>
                                    @elseif(isset($log->request['webhook_signature_verification']) && $log->request['webhook_signature_verification'] === 'success')
                                        <span class="badge badge-sm bg-teal text-teal-fg ms-1">
                                            <x-core::icon name="ti ti-webhook" style="width: 12px; height: 12px;" />
                                            Webhook Verified
                                        </span>
                                    @endif
                                </h4>

                                <div class="text-secondary small mb-2">
                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                    @if($log->ip_address)
                                        <span class="ms-2">• IP: {{ $log->ip_address }}</span>
                                    @endif

                                    @if(isset($log->request['error']))
                                        <span class="badge badge-sm bg-red text-red-fg ms-2">Error</span>
                                    @elseif(isset($log->request['success']))
                                        <span class="badge badge-sm bg-green text-green-fg ms-2">Success</span>
                                    @elseif(isset($log->request['warning']))
                                        <span class="badge badge-sm bg-yellow text-yellow-fg ms-2">Warning</span>
                                    @elseif(isset($log->request['info']))
                                        <span class="badge badge-sm bg-azure text-azure-fg ms-2">Info</span>
                                    @endif
                                </div>

                                @if(isset($log->request['error']))
                                    <div class="alert alert-danger alert-sm mb-2">
                                        <x-core::icon name="ti ti-alert-circle" class="me-1" />
                                        {{ is_array($log->request['error']) ? json_encode($log->request['error']) : $log->request['error'] }}
                                    </div>
                                @elseif(isset($log->request['warning']))
                                    <div class="alert alert-warning alert-sm mb-2">
                                        <x-core::icon name="ti ti-alert-triangle" class="me-1" />
                                        {{ is_array($log->request['warning']) ? json_encode($log->request['warning']) : $log->request['warning'] }}
                                    </div>
                                @elseif(isset($log->request['success']))
                                    <div class="alert alert-success alert-sm mb-2">
                                        <x-core::icon name="ti ti-circle-check" class="me-1" />
                                        {{ is_array($log->request['success']) ? json_encode($log->request['success']) : $log->request['success'] }}
                                    </div>
                                @endif

                                @if(isset($log->response['charge_id']) || isset($log->request['charge_id']))
                                    <div class="mb-2">
                                        <span class="text-muted">Charge ID:</span>
                                        <code class="text-primary">{{ $log->response['charge_id'] ?? $log->request['charge_id'] }}</code>
                                    </div>
                                @endif

                                @if(isset($log->response['order_id']) || isset($log->request['order_id']))
                                    <div class="mb-2">
                                        <span class="text-muted">Order ID:</span>
                                        <code class="text-primary">{{ is_array($log->response['order_id'] ?? $log->request['order_id']) ? implode(', ', $log->response['order_id'] ?? $log->request['order_id']) : ($log->response['order_id'] ?? $log->request['order_id']) }}</code>
                                    </div>
                                @endif

                                @if(isset($log->response['status']))
                                    <div class="mb-2">
                                        <span class="text-muted">Status:</span>
                                        @php
                                            $statusClass = match($log->response['status']) {
                                                'completed', 'success', 'captured', 'paid' => 'bg-green text-green-fg',
                                                'pending', 'processing' => 'bg-yellow text-yellow-fg',
                                                'failed', 'error' => 'bg-red text-red-fg',
                                                'refunded' => 'bg-orange text-orange-fg',
                                                'cancelled', 'canceled' => 'bg-pink text-pink-fg',
                                                default => 'bg-azure text-azure-fg'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ ucfirst($log->response['status']) }}</span>
                                    </div>
                                @endif

                                <div class="mt-3">
                                    <a href="#" class="btn btn-sm btn-ghost-secondary" data-bs-toggle="collapse" data-bs-target="#log-details-{{ $index }}" aria-expanded="false">
                                        <x-core::icon name="ti ti-code" class="me-1" />
                                        View Details
                                    </a>
                                </div>

                                <div class="collapse mt-3" id="log-details-{{ $index }}">
                                    <div class="card card-sm mb-3">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0 text-muted">{{ trans('plugins/payment::payment.request_data') }}</h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <pre class="m-0 p-3 bg-light" style="max-height: 300px; overflow-y: auto; font-size: 12px; line-height: 1.5; color: #333;">{{ json_encode($log->request, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </div>
                                    </div>

                                    <div class="card card-sm">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0 text-muted">{{ trans('plugins/payment::payment.response_data') }}</h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <pre class="m-0 p-3 bg-light" style="max-height: 300px; overflow-y: auto; font-size: 12px; line-height: 1.5; color: #333;">{{ json_encode($log->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </div>
                                    </div>

                                    @if(count(array_filter($log->request, fn($value) => !in_array($key = array_search($value, $log->request), ['event_type', 'webhook_event_id', 'webhook_request', 'callback_request', 'verification_attempt', 'error', 'success', 'warning', 'info', 'signature_verification', 'webhook_signature_verification']))) > 5)
                                        <div class="mt-3 text-center">
                                            <button type="button" class="btn btn-sm btn-ghost-secondary" onclick="this.style.display='none'; document.getElementById('log-full-{{ $index }}').style.display='block';">
                                                <x-core::icon name="ti ti-code-plus" class="me-1" />
                                                {{ __('Show Full JSON') }}
                                            </button>
                                        </div>
                                        <div id="log-full-{{ $index }}" style="display: none;" class="mt-3">
                                            <div class="card card-sm">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">{{ __('Full Log Data') }}</h5>
                                                    <div class="card-actions">
                                                        <button type="button" class="btn btn-sm btn-ghost-secondary" onclick="copyToClipboard(event, 'log-json-{{ $index }}')">
                                                            <x-core::icon name="ti ti-copy" />
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body p-0">
                                                    <pre id="log-json-{{ $index }}" class="m-0 p-3 bg-light" style="max-height: 500px; overflow-y: auto; font-size: 12px; line-height: 1.5; color: #333;">{{ json_encode(['timestamp' => $log->created_at->toIso8601String(), 'payment_method' => $log->payment_method, 'ip_address' => $log->ip_address, 'request' => $log->request, 'response' => $log->response], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
        </ul>
    </div>

    <script>
        function copyToClipboard(event, elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                const text = element.textContent || element.innerText;
                navigator.clipboard.writeText(text).then(function () {
                    const button = event.target.closest('button');
                    const originalHtml = button.innerHTML;
                    button.innerHTML = `<x-core::icon name="ti ti-check" /> Copied!`;
                    button.classList.add('btn-success');
                    button.classList.remove('btn-ghost-secondary');

                    setTimeout(() => {
                        button.innerHTML = originalHtml;
                        button.classList.remove('btn-success');
                        button.classList.add('btn-ghost-secondary');
                    }, 2000);
                }).catch(function(err) {
                    console.error('Failed to copy text: ', err);
                });
            }
        }
    </script>
@endif
