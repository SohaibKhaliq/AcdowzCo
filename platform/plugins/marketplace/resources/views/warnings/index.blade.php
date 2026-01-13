@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::card>
        <x-core::card.header>
            <x-core::card.title>
                {{ trans('Vendor Warnings') }}
            </x-core::card.title>
            <div class="card-actions">
                <x-core::button
                    tag="a"
                    :href="route('marketplace.vendor-warnings.create')"
                    icon="ti ti-plus"
                >
                    {{ trans('Create Warning') }}
                </x-core::button>
            </div>
        </x-core::card.header>

        <x-core::card.body>
            <x-core::table>
                <x-core::table.header>
                    <x-core::table.header.cell>
                        {{ trans('Store') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell>
                        {{ trans('Title') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell class="text-center">
                        {{ trans('Severity') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell class="text-center">
                        {{ trans('Date') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell class="text-center">
                        {{ trans('Acknowledged') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell class="text-end">
                        {{ trans('Actions') }}
                    </x-core::table.header.cell>
                </x-core::table.header>
                <x-core::table.body>
                    @forelse($warnings as $warning)
                        <x-core::table.body.row>
                            <x-core::table.body.cell>
                                <a href="{{ route('marketplace.store.edit', $warning->store_id) }}" target="_blank">
                                    {{ $warning->store->name }}
                                    <x-core::icon name="ti ti-external-link" size="sm" />
                                </a>
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                {{ $warning->title }}
                            </x-core::table.body.cell>
                            <x-core::table.body.cell class="text-center">
                                <x-core::badge 
                                    :color="match($warning->severity->value ?? $warning->severity) {
                                        'critical' => 'danger',
                                        'warning' => 'warning',
                                        'notice' => 'info',
                                        default => 'secondary'
                                    }"
                                >
                                    {{ ucfirst($warning->severity) }}
                                </x-core::badge>
                            </x-core::table.body.cell>
                            <x-core::table.body.cell class="text-center">
                                {{ $warning->created_at->format('Y-m-d H:i') }}
                            </x-core::table.body.cell>
                            <x-core::table.body.cell class="text-center">
                                @if($warning->acknowledged)
                                    <x-core::icon name="ti ti-check" class="text-success" />
                                    {{ $warning->acknowledged_at->diffForHumans() }}
                                @else
                                    <x-core::icon name="ti ti-x" class="text-muted" />
                                @endif
                            </x-core::table.body.cell>
                            <x-core::table.body.cell class="text-end">
                                <div class="btn-list justify-content-end">
                                    <x-core::button
                                        tag="a"
                                        :href="route('marketplace.vendor-warnings.show', $warning->id)"
                                        size="sm"
                                        color="info"
                                        :outlined="true"
                                    >
                                        {{ trans('View') }}
                                    </x-core::button>
                                    
                                    <x-core::button
                                        tag="button"
                                        size="sm"
                                        color="danger"
                                        :outlined="true"
                                        data-bb-toggle="delete-action"
                                        data-url="{{ route('marketplace.vendor-warnings.destroy', $warning->id) }}"
                                    >
                                        {{ trans('Delete') }}
                                    </x-core::button>
                                </div>
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    @empty
                        <x-core::table.body.row>
                            <x-core::table.body.cell colspan="6" class="text-center">
                                {{ trans('No warnings found') }}
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    @endforelse
                </x-core::table.body>
            </x-core::table>
        </x-core::card.body>

        @if($warnings->hasPages())
            <x-core::card.footer>
                {{ $warnings->links() }}
            </x-core::card.footer>
        @endif
    </x-core::card>
@endsection
