<x-core::table>
    <x-core::table.header>
        <x-core::table.header.cell>
            {{ trans('plugins/ecommerce::product-specification.product.specification_table.options') }}
        </x-core::table.header.cell>
        <x-core::table.header.cell />
    </x-core::table.header>

    <x-core::table.body>
        @foreach(is_array($options) ? $options : json_decode($options, true) as $option)
            <x-core::table.body.row>
                <x-core::table.body.cell>
                    <input
                        type="text"
                        class="form-control"
                        name="options[]"
                        value="{{ $option }}"
                        data-bb-toggle="option-value"
                    />
                </x-core::table.body.cell>
                <x-core::table.body.cell style="width: 7%">
                    <x-core::button
                        type="button"
                        :icon-only="true"
                        icon="ti ti-trash"
                        data-bb-toggle="remove-option"
                    />
                </x-core::table.body.cell>
            </x-core::table.body.row>
        @endforeach
    </x-core::table.body>
</x-core::table>

<script>
    $(function() {
        $(document)
            .on('change', '.js-base-form select[name="type"]', (e) => {
                const $currentTarget = $(e.currentTarget)
                const $options = $currentTarget.closest('form').find('.specification-attribute-options')

                if ($currentTarget.val() === 'select' || $currentTarget.val() === 'radio') {
                    $options.show()
                } else {
                    $options.hide()
                }
            })
            .on('click', '[data-bb-toggle="add-option"]', (e) => {
                e.preventDefault()

                const $currentTarget = $(e.currentTarget)
                const $table = $currentTarget.closest('.card').find('table')

                const $tr = $table.find('tr').last().clone()
                $tr.find('[data-bb-toggle="option-value"]').val('').prop('name', `options[]`)

                $table.append(`<x-core::table.body.row>
                    <x-core::table.body.cell>
                        <input
                            type="text"
                            class="form-control"
                            name="options[]"
                            data-bb-toggle="option-value"
                        />
                    </x-core::table.body.cell>
                        <x-core::table.body.cell style="width: 7%">
                            <x-core::button
                                type="button"
                                :icon-only="true"
                                icon="ti ti-trash"
                                data-bb-toggle="remove-option"
                            />
                        </x-core::table.body.cell>
                    </x-core::table.body.row>`)
            })
            .on('click', '[data-bb-toggle="remove-option"]', (e) => {
                e.preventDefault()

                const $currentTarget = $(e.currentTarget)
                const $tr = $currentTarget.closest('tr')

                $tr.remove()
            })
    });
</script>
