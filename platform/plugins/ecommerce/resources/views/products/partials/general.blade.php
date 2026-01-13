{!! apply_filters('ecommerce_product_variation_form_start', null, $product) !!}

<div class="row price-group">
    <input
        class="detect-schedule d-none"
        name="sale_type"
        type="hidden"
        value="{{ old('sale_type', $product ? $product->sale_type : 0) }}"
    >

    <div class="col-md-4">
        <x-core::form.text-input
            :label="trans('plugins/ecommerce::products.sku')"
            name="sku"
            :value="old('sku', $product ? $product->sku : (new Botble\Ecommerce\Models\Product())->generateSku())"
        />

        @if (($isVariation && !$product) || ($product && $product->is_variation && !$product->sku))
            <x-core::form.checkbox
                :label="trans('plugins/ecommerce::products.form.auto_generate_sku')"
                name="auto_generate_sku"
                :value="1"
            />
        @endif
    </div>

    <div class="col-md-4">
        <x-core::form.text-input
            :label="trans('plugins/ecommerce::products.form.price')"
            name="price"
            :data-thousands-separator="EcommerceHelper::getThousandSeparatorForInputMask()"
            :data-decimal-separator="EcommerceHelper::getDecimalSeparatorForInputMask()"
            :value="old('price', $product ? $product->price : $originalProduct->price ?? 0)"
            step="any"
            class="input-mask-number"
            :group-flat="true"
        >
            <x-slot:prepend>
                <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
            </x-slot:prepend>
        </x-core::form.text-input>
    </div>
    <div class="col-md-4">
        <x-core::form.text-input
            :label="trans('plugins/ecommerce::products.form.price_sale')"
            class="input-mask-number"
            name="sale_price"
            :data-thousands-separator="EcommerceHelper::getThousandSeparatorForInputMask()"
            :data-decimal-separator="EcommerceHelper::getDecimalSeparatorForInputMask()"
            :value="old('sale_price', $product ? $product->sale_price : $originalProduct->sale_price ?? null)"
            :group-flat="true"
            :data-sale-percent-text="trans('plugins/ecommerce::products.form.price_sale_percent_helper')"
        >
            <x-slot:helper-text>
                {!! trans('plugins/ecommerce::products.form.price_sale_percent_helper', ['percent' => '<strong>' . ($product ? $product->sale_percent : 0) . '%</strong>']) !!}
            </x-slot:helper-text>

            <x-slot:prepend>
                <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
            </x-slot:prepend>
            <x-slot:labelDescription>
                <a
                    class="turn-on-schedule"
                    @style(['display: none' => old('sale_type', $product ? $product->sale_type : $originalProduct->sale_type ?? 0) == 1])
                    href="javascript:void(0)"
                >
                    {{ trans('plugins/ecommerce::products.form.choose_discount_period') }}
                </a>
                <a
                    class="turn-off-schedule"
                    @style(['display: none' => old('sale_type', $product ? $product->sale_type : $originalProduct->sale_type ?? 0) == 0])
                    href="javascript:void(0)"
                >
                    {{ trans('plugins/ecommerce::products.form.cancel') }}
                </a>
            </x-slot:labelDescription>
        </x-core::form.text-input>
    </div>

    <div class="col-md-6 scheduled-time" @style(['display: none' => old('sale_type', $product ? $product->sale_type : $originalProduct->sale_type ?? 0) == 0])>
        <x-core::form.text-input
            :label="trans('plugins/ecommerce::products.form.date.start')"
            name="start_date"
            class="form-date-time"
            :value="old('start_date', $product ? $product->start_date : $originalProduct->start_date ?? null)"
            :placeholder="BaseHelper::getDateTimeFormat()"
        />
    </div>
    <div class="col-md-6 scheduled-time" @style(['display: none' => old('sale_type', $product ? $product->sale_type : $originalProduct->sale_type ?? 0) == 0])>
        <x-core::form.text-input
            :label="trans('plugins/ecommerce::products.form.date.end')"
            name="end_date"
            :value="old('end_date', $product ? $product->end_date : $originalProduct->end_date ?? null)"
            :placeholder="BaseHelper::getDateTimeFormat()"
            class="form-date-time"
        />
    </div>

    <div class="col-md-6">
        <x-core::form.text-input
            :label="trans('plugins/ecommerce::products.form.cost_per_item')"
            name="cost_per_item"
            :value="old('cost_per_item', $product ? $product->cost_per_item : $originalProduct->cost_per_item ?? 0)"
            :placeholder="trans('plugins/ecommerce::products.form.cost_per_item_placeholder')"
            step="any"
            class="input-mask-number"
            :group-flat="true"
            :helper-text="trans('plugins/ecommerce::products.form.cost_per_item_helper')"
        >
            <x-slot:prepend>
                <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
            </x-slot:prepend>
        </x-core::form.text-input>
    </div>
    <input
        name="product_id"
        type="hidden"
        value="{{ $product->id ?? null }}"
    >
    <div class="col-md-6">
        <x-core::form.text-input
            :label="trans('plugins/ecommerce::products.form.barcode')"
            name="barcode"
            type="text"
            :value="old('barcode', $product)"
            step="any"
            :placeholder="trans('plugins/ecommerce::products.form.barcode_placeholder')"
            :required="(bool) get_ecommerce_setting('make_product_barcode_required', false)"
            :helper-text="trans('plugins/ecommerce::products.form.barcode_helper')"
        />
    </div>
</div>

{!! apply_filters('ecommerce_product_variation_form_middle', null, $product) !!}

<x-core::form.on-off.checkbox
    :label="trans('plugins/ecommerce::products.form.storehouse.storehouse')"
    name="with_storehouse_management"
    class="storehouse-management-status"
    :checked="old('with_storehouse_management', $product ? $product->with_storehouse_management : $originalProduct->with_storehouse_management ?? 0) == 1"
/>

<x-core::form.fieldset class="storehouse-info" @style(['display: none' => old('with_storehouse_management', $product ? $product->with_storehouse_management : $originalProduct->with_storehouse_management ?? 0) == 0])>
    <x-core::form.text-input
        :label="trans('plugins/ecommerce::products.form.storehouse.quantity')"
        name="quantity"
        :value="old('quantity', $product ? $product->quantity : $originalProduct->quantity ?? 0)"
        class="input-mask-number"
    />

    <x-core::form.on-off.checkbox
        :label="trans('plugins/ecommerce::products.form.stock.allow_order_when_out')"
        name="allow_checkout_when_out_of_stock"
        :checked="old('allow_checkout_when_out_of_stock', $product ? $product->allow_checkout_when_out_of_stock : $originalProduct->allow_checkout_when_out_of_stock ?? 0) == 1"
    />
</x-core::form.fieldset>

<x-core::form.fieldset class="stock-status-wrapper" @style(['display: none' => old('with_storehouse_management', $product ? $product->with_storehouse_management : $originalProduct->with_storehouse_management ?? 0) == 1])>
    <x-core::form.label for="stock_status">
        {{ trans('plugins/ecommerce::products.form.stock_status') }}
    </x-core::form.label>
    @foreach (Botble\Ecommerce\Enums\StockStatusEnum::labels() as $status => $label)
        <x-core::form.checkbox
            :label="$label"
            name="stock_status"
            type="radio"
            :value="$status"
            :checked="old('stock_status', $product ? $product->stock_status : 'in_stock') == $status"
            :inline="true"
        />
    @endforeach
</x-core::form.fieldset>

@if (
    ! EcommerceHelper::isEnabledSupportDigitalProducts()
    || (! EcommerceHelper::isDisabledPhysicalProduct() && !$product && ! $originalProduct && request()->input('product_type') != Botble\Ecommerce\Enums\ProductTypeEnum::DIGITAL)
    || (! EcommerceHelper::isDisabledPhysicalProduct() && $originalProduct && $originalProduct->isTypePhysical()) || ($product && $product->isTypePhysical())
)
    <x-core::form.fieldset>
        <legend>
            <h3>{{ trans('plugins/ecommerce::products.form.shipping.title') }}</h3>
        </legend>
        <div class="row">
            <div class="col-md-3 col-md-6">
                <x-core::form.text-input
                    label="{{ trans('plugins/ecommerce::products.form.shipping.weight') }} ({{ ecommerce_weight_unit() }})"
                    name="weight"
                    :value="old('weight', $product ? $product->weight : $originalProduct->weight ?? 0)"
                    class="input-mask-number"
                    :group-flat="true"
                >
                    <x-slot:prepend>
                        <span class="input-group-text">{{ ecommerce_weight_unit() }}</span>
                    </x-slot:prepend>
                </x-core::form.text-input>
            </div>
            <div class="col-md-3 col-md-6">
                <x-core::form.text-input
                    label="{{ trans('plugins/ecommerce::products.form.shipping.length') }} ({{ ecommerce_width_height_unit() }})"
                    name="length"
                    :value="old('length', $product ? $product->length : $originalProduct->length ?? 0)"
                    class="input-mask-number"
                    :group-flat="true"
                >
                    <x-slot:prepend>
                        <span class="input-group-text">{{ ecommerce_width_height_unit() }}</span>
                    </x-slot:prepend>
                </x-core::form.text-input>
            </div>
            <div class="col-md-3 col-md-6">
                <x-core::form.text-input
                    label="{{ trans('plugins/ecommerce::products.form.shipping.wide') }} ({{ ecommerce_width_height_unit() }})"
                    name="wide"
                    :value="old('wide', $product ? $product->wide : $originalProduct->wide ?? 0)"
                    class="input-mask-number"
                    :group-flat="true"
                >
                    <x-slot:prepend>
                        <span class="input-group-text">{{ ecommerce_width_height_unit() }}</span>
                    </x-slot:prepend>
                </x-core::form.text-input>
            </div>
            <div class="col-md-3 col-md-6">
                <x-core::form.text-input
                    label="{{ trans('plugins/ecommerce::products.form.shipping.height') }} ({{ ecommerce_width_height_unit() }})"
                    :value="old('height', $product ? $product->height : $originalProduct->height ?? 0)"
                    name="height"
                    class="input-mask-number"
                    :group-flat="true"
                >
                    <x-slot:prepend>
                        <span class="input-group-text">{{ ecommerce_width_height_unit() }}</span>
                    </x-slot:prepend>
                </x-core::form.text-input>
            </div>
        </div>
    </x-core::form.fieldset>
@endif

@if (
    EcommerceHelper::isDisabledPhysicalProduct()
    ||
    (EcommerceHelper::isEnabledSupportDigitalProducts()
    && (
        (!$product &&  !$originalProduct && request()->input('product_type') == Botble\Ecommerce\Enums\ProductTypeEnum::DIGITAL)
        || ($originalProduct && $originalProduct->isTypeDigital()) || ($product && $product->isTypeDigital())
    ))
)
    @if (EcommerceHelper::isEnabledLicenseCodesForDigitalProducts())
    <x-core::form.on-off.checkbox
        :label="trans('plugins/ecommerce::products.digital_attachments.generate_license_code_after_purchasing_product')"
        name="generate_license_code"
        :checked="old('generate_license_code', $product ? $product->generate_license_code : $originalProduct->generate_license_code ?? 0)"
        data-bb-toggle="collapse"
        data-bb-target="#license-code-options"
    />

    <div class="collapse @if(old('generate_license_code', $product ? $product->generate_license_code : $originalProduct->generate_license_code ?? 0)) show @endif" id="license-code-options">
        <x-core::form-group class="mt-3">
            <x-core::form.label for="license_code_type" :value="trans('plugins/ecommerce::products.license_codes.type.title')" />
            <x-core::form.select name="license_code_type" id="license_code_type">
                <option value="auto_generate" @if(old('license_code_type', $product ? $product->license_code_type : 'auto_generate') === 'auto_generate') selected @endif>
                    {{ trans('plugins/ecommerce::products.license_codes.type.auto_generate') }}
                </option>
                <option value="pick_from_list" @if(old('license_code_type', $product ? $product->license_code_type : 'auto_generate') === 'pick_from_list') selected @endif>
                    {{ trans('plugins/ecommerce::products.license_codes.type.pick_from_list') }}
                </option>
            </x-core::form.select>
            <x-core::form.helper-text>
                {{ trans('plugins/ecommerce::products.license_codes.type.description') }}
            </x-core::form.helper-text>
        </x-core::form-group>
    </div>

    <x-core::form-group class="product-license-codes-management mb-5" id="license-codes-management" @style(['display: none' => !($product && $product->generate_license_code && $product->license_code_type === 'pick_from_list')])>
        <x-core::form.label for="license_codes" class="mb-3">
            {{ trans('plugins/ecommerce::products.license_codes.title') }}
            @if($product && $product->is_variation)
                <small class="text-muted d-block">
                    {{ trans('plugins/ecommerce::products.license_codes.variation_specific_note') }}
                </small>
            @elseif($product && $product->has_variation)
                <small class="text-muted d-block">
                    {{ trans('plugins/ecommerce::products.license_codes.main_product_note') }}
                </small>
            @endif

            <x-slot:description>
                <div class="btn-list mt-3 mb-3">
                    @if($product && \Botble\Ecommerce\Http\Controllers\ProductLicenseCodeController::canAccessLicenseCodeManagement($product))
                        <a href="{{ route('products.license-codes.index', $product->id) }}"
                           class="btn btn-sm btn-primary"
                           target="_blank">
                            <x-core::icon name="ti ti-external-link" />
                            {{ trans('plugins/ecommerce::products.license_codes.manage_codes') }}
                        </a>
                    @endif

                    <x-core::button type="button" class="license-code-add-btn" size="sm" icon="ti ti-plus">
                        {{ trans('plugins/ecommerce::products.license_codes.add') }}
                    </x-core::button>

                    <x-core::button type="button" class="license-code-generate-btn" size="sm" icon="ti ti-refresh">
                        {{ trans('plugins/ecommerce::products.license_codes.generate') }}
                    </x-core::button>
                </div>

                @if (get_ecommerce_setting('hide_used_license_codes_in_product_form', false) && $product && $product->licenseCodes->filter(fn($code) => $code->isUsed())->count() > 0)
                    <div class="alert alert-info alert-sm mt-2">
                        <x-core::icon name="ti ti-info-circle" />
                        {{ trans('plugins/ecommerce::products.license_codes.used_codes_hidden', ['count' => $product->licenseCodes->filter(fn($code) => $code->isUsed())->count()]) }}
                    </div>
                @endif
            </x-slot:description>
        </x-core::form.label>

        <div class="clearfix"></div>

        <div class="table-responsive">
            <x-core::table>
                <x-core::table.header>
                    <x-core::table.header.cell>
                        {{ trans('plugins/ecommerce::products.license_codes.code') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell>
                        {{ trans('plugins/ecommerce::products.license_codes.status') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell>
                        {{ trans('plugins/ecommerce::products.license_codes.assigned_at') }}
                    </x-core::table.header.cell>
                    <x-core::table.header.cell />
                </x-core::table.header>

                <x-core::table.body id="license-codes-table-body">
                @if($product)
                    @php
                        $hideUsedCodes = get_ecommerce_setting('hide_used_license_codes_in_product_form', false);
                        $licenseCodes = $hideUsedCodes ? $product->licenseCodes->filter(fn($code) => $code->isAvailable()) : $product->licenseCodes;
                    @endphp
                    @foreach ($licenseCodes as $licenseCode)
                        <x-core::table.body.row data-license-code-id="{{ $licenseCode->id }}">
                            <x-core::table.body.cell>
                                <input type="text"
                                       name="license_codes[{{ $licenseCode->id }}][code]"
                                       value="{{ $licenseCode->license_code }}"
                                       class="form-control license-code-input"
                                    {{ $licenseCode->isUsed() ? 'readonly' : '' }}>
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                {!! $licenseCode->status->toHtml() !!}
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                @if($licenseCode->assigned_at && $licenseCode->assignedOrderProduct && $licenseCode->assignedOrderProduct->order)
                                    <div>
                                        {{ BaseHelper::formatDate($licenseCode->assigned_at) }}
                                        <br>
                                        <a href="{{ route('orders.edit', $licenseCode->assignedOrderProduct->order->id) }}"
                                           class="text-primary"
                                           target="_blank">
                                            <x-core::icon name="ti ti-external-link" />
                                            {{ trans('plugins/ecommerce::order.view_order') }} #{{ $licenseCode->assignedOrderProduct->order->code }}
                                        </a>
                                    </div>
                                @else
                                    {{ $licenseCode->assigned_at ? BaseHelper::formatDate($licenseCode->assigned_at) : '-' }}
                                @endif
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                @if($licenseCode->isAvailable())
                                    <x-core::button type="button"
                                                    class="license-code-delete-btn"
                                                    size="sm"
                                                    color="danger"
                                                    icon="ti ti-trash"
                                                    data-license-code-id="{{ $licenseCode->id }}">
                                        {{ trans('core/base::tables.delete') }}
                                    </x-core::button>
                                @endif
                            </x-core::table.body.cell>
                        </x-core::table.body.row>
                    @endforeach
                @endif
            </x-core::table.body>
        </x-core::table>
        </div>
    </x-core::form-group>
    @endif

    <x-core::form-group class="product-type-digital-management">
        <x-core::form.label for="product_file" class="mb-3">
            {{ trans('plugins/ecommerce::products.digital_attachments.title') }}

            <x-slot:description>
                <div class="btn-list">
                    <x-core::button type="button" class="digital_attachments_btn" size="sm" icon="ti ti-paperclip">
                        {{ trans('plugins/ecommerce::products.digital_attachments.add') }}
                    </x-core::button>

                    <x-core::button type="button" class="digital_attachments_external_btn" size="sm" icon="ti ti-link">
                        {{ trans('plugins/ecommerce::products.digital_attachments.add_external_link') }}
                    </x-core::button>
                </div>
            </x-slot:description>
        </x-core::form.label>

        <x-core::table>
            <x-core::table.header>
                <x-core::table.header.cell />
                <x-core::table.header.cell>
                    {{ trans('plugins/ecommerce::products.digital_attachments.file_name') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell>
                    {{ trans('plugins/ecommerce::products.digital_attachments.file_size') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell>
                    {{ trans('core/base::tables.created_at') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell />
            </x-core::table.header>

            <x-core::table.body>
                @if($product)
                    @foreach ($product->productFiles as $file)
                        <x-core::table.body.row>
                            <x-core::table.body.cell>
                                <x-core::form.checkbox
                                    name="product_files[{{ $file->id }}]"
                                    class="digital-attachment-checkbox"
                                    :checked="true"
                                    :single="true"
                                />
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                @if ($file->is_external_link)
                                    <a href="{{ $file->url }}" target="_blank">
                                        <x-core::icon name="ti ti-link" />
                                        {{ $file->basename ? Str::limit($file->basename, 50) : $file->url }}
                                    </a>
                                @else
                                    <x-core::icon name="ti ti-paperclip" />
                                    {{ Str::limit($file->basename, 50) }}
                                @endif
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                {{ $file->file_size ? BaseHelper::humanFileSize($file->file_size) : '-' }}
                            </x-core::table.body.cell>
                            <x-core::table.body.cell>
                                {{ BaseHelper::formatDate($file->created_at) }}
                            </x-core::table.body.cell>
                            <x-core::table.body.cell />
                        </x-core::table.body.row>
                    @endforeach
                @endif
            </x-core::table.body>
        </x-core::table>

        <div class="digital_attachments_input">
            <input
                name="product_files_input[]"
                data-id="{{ Str::random(10) }}"
                type="file"
            >
        </div>
    </x-core::form-group>

    @if($product)
        <x-core::form.checkbox
            name="notify_attachment_updated"
            :label="trans('plugins/ecommerce::products.digital_attachments.notify_attachment_updated')"
            :checked="old('notify_attachment_updated', $product->notify_attachment_updated)"
            :value="true"
        />
    @endif

    @if (request()->ajax())
        @include('plugins/ecommerce::products.partials.digital-product-file-template')
        @if (EcommerceHelper::isEnabledLicenseCodesForDigitalProducts())
            @include('plugins/ecommerce::products.partials.license-code-template', ['isVariation' => $isVariation ?? false])
        @endif
    @else
        @pushOnce('footer')
            @include('plugins/ecommerce::products.partials.digital-product-file-template')
            @if (EcommerceHelper::isEnabledLicenseCodesForDigitalProducts())
                @include('plugins/ecommerce::products.partials.license-code-template', ['isVariation' => $isVariation ?? false])
            @endif
        @endpushOnce
    @endif
@endif

{!! apply_filters('ecommerce_product_variation_form_end', null, $product) !!}
