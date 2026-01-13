<x-core::form.select
    :label="trans('plugins/ecommerce::bulk-import.import_types.name')"
    name="type"
    :options="[
        'all' => trans('plugins/ecommerce::bulk-import.import_types.all'),
        'products' => trans('plugins/ecommerce::bulk-import.import_types.products'),
        'variations' => trans('plugins/ecommerce::bulk-import.import_types.variations'),
    ]"
    :required="true"
/>

<x-core::form-group>
    <x-core::form.checkbox
        name="update_existing_products"
        :label="trans('plugins/ecommerce::bulk-import.update_existing_products')"
        :helper-text="trans('plugins/ecommerce::bulk-import.update_existing_products_description')"
        value="1"
    />
</x-core::form-group>
