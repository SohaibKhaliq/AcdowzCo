<div>
    <x-core::form.label for="groups" class="required">
        {{ trans('plugins/ecommerce::product-specification.specification_tables.fields.groups') }}
    </x-core::form.label>

    <div>
        @foreach($groups as $id => $label)
            <label class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="groups[]" value="{{ $id }}" @checked(in_array($id, old('groups', $selectedGroups->pluck('id')->all())))>
                <span class="form-check-label">{{ $label }}</span>
            </label>
        @endforeach
    </div>
</div>

@if($selectedGroups->isNotEmpty())
    <x-core::table class="mt-5">
        <x-core::table.header>
            <x-core::table.header.cell>
                {{ trans('plugins/ecommerce::product-specification.specification_tables.fields.name') }}
            </x-core::table.header.cell>
            <x-core::table.header.cell>
                {{ trans('plugins/ecommerce::product-specification.specification_tables.fields.sorting') }}
            </x-core::table.header.cell>
        </x-core::table.header>

        <x-core::table.body>
            @foreach($selectedGroups->sortBy('pivot.order') as $group)
                <x-core::table.body.row>
                    <x-core::table.body.cell>
                        <input type="hidden" name="group_orders[{{ $group->id }}]" value="{{ $group->pivot->order }}">
                        {{ $group->name }}
                    </x-core::table.body.cell>
                    <x-core::table.body.cell>
                        <x-core::icon name="ti ti-arrows-sort" class="text-secondary" style="cursor: move;" />
                    </x-core::table.body.cell>
                </x-core::table.body.row>
            @endforeach
        </x-core::table.body>
    </x-core::table>

    <script>
        $(() => {
            $('table tbody').sortable({
                cursor: 'move',
                update: function() {
                    $(this).find('tr').each(function(index) {
                        $(this).find('input[type="hidden"]').val(index);
                    });
                }
            });
        });
    </script>
@endif
