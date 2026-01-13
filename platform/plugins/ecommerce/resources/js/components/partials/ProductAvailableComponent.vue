<template>
    <div>
        <div v-if="item.is_out_of_stock" class="text-danger">
            <small>&nbsp;({{ __('order.out_of_stock') }})</small>
        </div>
        <template v-else-if="item.with_storehouse_management">
            <small v-if="item.quantity > 0">&nbsp;({{ item.quantity }} {{ __('order.products_available') }})</small>
            <small v-else class="text-warning">&nbsp;({{ item.quantity }} {{ __('order.products_available') }})</small>
        </template>
        <span class="text-info ps-1">({{ item.formatted_price }})</span>
    </div>
</template>

<script>
export default {
    props: {
        item: {
            type: Object,
            default: () => {},
            required: true,
        },
    },
}
</script>
