@php
    use Botble\Ecommerce\Models\ProductSpecificationAttributeTranslation;
@endphp

<p class="text-secondary mb-0 p-3">
    {{ trans('plugins/ecommerce::product-specification.product.specification_table.description') }}
</p>

<div class="specification-table"></div>

<script>
    $(() => {
        $(document)
            .on('change', '#specification_table_id', function() {
                const $this = $(this);
                const $form = $this.closest('form');
                const $table = $this.val();

                if ($table) {
                    $.ajax({
                        url: '{{ $getTableUrl }}',
                        data: {
                            table: $table,
                            @if($model)
                                product: '{{ $model->getKey() }}',
                                ref_lang: '{{ request()->input('ref_lang') }}',
                            @endif
                        },
                        success: function(response) {
                            if (response.data) {
                                $form.find('.specification-table').html(response.data);
                                $('.product-specification-table p').hide();

                                @if (ProductSpecificationAttributeTranslation::isEditingDefaultLanguage())
                                    $form.find('.specification-table table tbody').sortable({
                                        update: function() {
                                            $(this).find('tr').each(function(index) {
                                                $(this).find('input[name$="[order]"]').val(index);
                                            });
                                        },
                                    });
                                @endif
                            }
                        },
                    });
                } else {
                    $form.find('.specification-table').html('');
                    $('.product-specification-table p').show();
                }
            });

        $('#specification_table_id').trigger('change');
    });
</script>
