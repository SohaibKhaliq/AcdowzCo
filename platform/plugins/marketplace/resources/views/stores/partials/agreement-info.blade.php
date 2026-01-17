@if ($store->id && $store->agreement_updated_at)
    <x-core::alert type="info" class="mb-3">
        <div class="d-flex align-items-center">
            <x-core::icon name="ti ti-info-circle" class="me-2" />
            <div>
                <strong>{{ __('Agreement Last Updated') }}:</strong>
                {{ $store->agreement_updated_at->format('Y-m-d H:i:s') }}
                @if ($store->agreementUpdatedBy)
                    {{ __('by') }} <strong>{{ $store->agreementUpdatedBy->name }}</strong>
                @endif
            </div>
        </div>
    </x-core::alert>

    @if ($store->agreement_accepted_at)
        <x-core::alert type="success" class="mb-3">
            <div class="d-flex align-items-center">
                <x-core::icon name="ti ti-check-circle" class="me-2" />
                <div>
                    <strong>{{ __('Agreement Accepted') }}:</strong>
                    {{ $store->agreement_accepted_at->format('Y-m-d H:i:s') }}
                </div>
            </div>
        </x-core::alert>
    @else
        <x-core::alert type="warning" class="mb-3">
            <div class="d-flex align-items-center">
                <x-core::icon name="ti ti-alert-triangle" class="me-2" />
                <div>
                    {{ __('Vendor has not yet accepted the agreement') }}
                </div>
            </div>
        </x-core::alert>
    @endif

    @if ($store->agreement_history && count($store->agreement_history) > 0)
        <x-core::card class="mb-3">
            <x-core::card.header>
                <x-core::card.title>
                    {{ __('Agreement Change History') }}
                </x-core::card.title>
            </x-core::card.header>
            <x-core::card.body>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Changed By') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Value') }}</th>
                                <th>{{ __('Commission Rate') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (array_reverse($store->agreement_history) as $history)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($history['changed_at'])->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if ($history['changed_by'])
                                            {{ \Botble\ACL\Models\User::find($history['changed_by'])?->name ?? __('Unknown') }}
                                        @else
                                            <em>{{ __('System') }}</em>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-secondary">{{ $history['old_values']['type'] ?? '-' }}</span>
                                        <x-core::icon name="ti ti-arrow-right" size="sm" />
                                        <span
                                            class="badge bg-primary">{{ $history['new_values']['type'] ?? '-' }}</span>
                                    </td>
                                    <td>
                                        {{ number_format($history['old_values']['value'] ?? 0, 2) }}
                                        <x-core::icon name="ti ti-arrow-right" size="sm" />
                                        {{ number_format($history['new_values']['value'] ?? 0, 2) }}
                                    </td>
                                    <td>
                                        {{ number_format($history['old_values']['commission_rate'] ?? 0, 2) }}%
                                        <x-core::icon name="ti ti-arrow-right" size="sm" />
                                        {{ number_format($history['new_values']['commission_rate'] ?? 0, 2) }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-core::card.body>
        </x-core::card>
    @endif
@endif
