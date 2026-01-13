@extends('core/base::layouts.master')

@section('content')
    <x-core::card>
        <x-core::card.body>
            <x-core::datagrid>
                <x-core::datagrid.item>
                    <x-slot:title>{{ __('Sent at') }}</x-slot:title>
                    {{ BaseHelper::formatDateTime($message->created_at) }}
                </x-core::datagrid.item>

                <x-core::datagrid.item>
                    <x-slot:title>{{ __('Name') }}</x-slot:title>
                    {{ $message->name }}
                </x-core::datagrid.item>

                <x-core::datagrid.item>
                    <x-slot:title>{{ __('Email') }}</x-slot:title>
                    <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                </x-core::datagrid.item>

                @if($message->store)
                    <x-core::datagrid.item>
                        <x-slot:title>{{ __('Store') }}</x-slot:title>
                        <a href="{{ route('marketplace.store.edit', $message->store->id) }}">{{ $message->store->name }}</a>
                    </x-core::datagrid.item>
                @endif

                @if ($message->customer)
                    <x-core::datagrid.item>
                        <x-slot:title>{{ __('Customer') }}</x-slot:title>
                        <a href="{{ route('customers.edit', $message->customer->id) }}">{{ $message->customer->name }}</a>
                    </x-core::datagrid.item>
                @endif
            </x-core::datagrid>

            <x-core::datagrid class="mt-3">
                <x-core::datagrid.item>
                    <x-slot:title>{{ __('Content') }}</x-slot:title>
                    <div class="bg-body-tertiary rounded p-2">
                        {!! BaseHelper::clean(nl2br($message->content)) !!}
                    </div>
                </x-core::datagrid.item>
            </x-core::datagrid>
        </x-core::card.body>

        <x-core::card.footer>
            <x-core::button tag="a" :href="route('marketplace.messages.index')" icon="ti ti-arrow-left">
                {{ __('Back') }}
            </x-core::button>
        </x-core::card.footer>
    </x-core::card>
@stop
