<form action="{{ ! empty($route) ? route($route, $order->id) : route('orders.edit', $order->id) }}">
    <x-core::form.textarea
        :label="(trans('plugins/ecommerce::order.note') . ' ' . trans('plugins/ecommerce::order.note_description'))"
        name="description"
        :placeholder="trans('plugins/ecommerce::order.add_note')"
        :helper-text="trans('plugins/ecommerce::order.add_note_helper')"
        :value="$order->description"
        class="textarea-auto-height"
    />

    <x-core::form.textarea
        :label="trans('plugins/ecommerce::order.admin_private_notes')"
        name="private_notes"
        :placeholder="trans('plugins/ecommerce::order.add_note')"
        :helper-text="trans('plugins/ecommerce::order.admin_private_notes_helper')"
        :value="$order->private_notes"
        class="textarea-auto-height"
    />

    <x-core::button type="button" class="btn-update-order">
        {{ trans('plugins/ecommerce::order.save') }}
    </x-core::button>
</form>
