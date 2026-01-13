<x-core::modal.action
    id="vendor-control-modal"
    :type="$isActivated ? 'danger' : 'info'"
    :title="$isActivated ? trans('plugins/marketplace::store.control.block') : trans('plugins/marketplace::store.control.unblock')"
    :form-action="$isActivated ? route('marketplace.vendors.block', $model) : route('marketplace.vendors.unblock', $model)"
    :form-attrs="['id' => 'vendor-control-form']"
>
    @if($isActivated)
        {{ trans('plugins/marketplace::store.control.block_confirmation') }}

        <textarea
            name="reason"
            class="form-control mt-3"
            placeholder="{{ trans('plugins/marketplace::store.control.block_reason') }}"
        ></textarea>
    @else
        {{ trans('plugins/marketplace::store.control.unblock_confirmation') }}
    @endif

    <x-slot:footer>
        <div class="w-100">
            <div class="row">
                <div class="col">
                    <x-core::button type="submit" :color="$isActivated ? 'danger' : 'info'" class="w-100" form="vendor-control-form">
                        {{ trans('core/base::tables.submit') }}
                    </x-core::button>
                </div>
                <div class="col">
                    <x-core::button type="button" class="w-100" data-bs-dismiss="modal">
                        {{ trans('core/base::base.close') }}
                    </x-core::button>
                </div>
            </div>
        </div>
    </x-slot:footer>
</x-core::modal.action>
