<x-core::form-group>
    <x-core::form.checkbox
        name="update_existing_customers"
        :label="trans('plugins/ecommerce::customer.import.update_existing_customers')"
        :helper-text="trans('plugins/ecommerce::customer.import.update_existing_customers_description')"
        value="1"
    />
</x-core::form-group>