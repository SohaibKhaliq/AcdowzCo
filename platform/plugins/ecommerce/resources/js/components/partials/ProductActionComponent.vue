<template>
    <div class="row align-items-center gap-2">
        <div class="col d-flex align-content-center" :class="{'overflow-auto': product.variation_attributes}">
            <span class="text-success" v-if="product.variation_attributes">
                {{ product.variation_attributes }}
            </span>
            <span v-else>{{ product.name }}</span>
            <ProductAvailable
                v-if="product.is_variation || !product.variations.length"
                :item="product"
            ></ProductAvailable>
        </div>
        <ProductOption
            v-show="!product.is_variation"
            :ref="'product_options_' + product.id"
            :product="product"
            :options="product.product_options"
        ></ProductOption>
        <div class="w-100 w-sm-auto col-auto" v-if="(product.is_variation || !product.variations.length) && !product.is_out_of_stock">
            <button
                class="btn btn-outline-primary btn-sm"
                type="button"
                @click="$emit('select-product', product, $refs['product_options_' + product.id] || [])"
            >
                <i class="icon-sm ti ti-plus"></i>
                {{ __('order.add') }}
            </button>
        </div>
    </div>
</template>

<script>
import ProductAvailable from './ProductAvailableComponent.vue'
import ProductOption from './ProductOptionComponent.vue'

export default {
    props: {
        product: {
            type: Object,
            default: {},
            required: false,
        },
    },
    components: {
        ProductAvailable,
        ProductOption,
    },
}
</script>
