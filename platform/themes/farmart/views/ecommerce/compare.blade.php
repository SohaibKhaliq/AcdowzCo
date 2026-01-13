<div class="row compare-page-content py-5 mt-3">
    <div class="col-12">
        @if ($products->isNotEmpty())
            <div class="table-responsive">
                <table
                    class="table table-bordered table-striped"
                    role="grid"
                    cellpadding="0"
                    cellspacing="0"
                >
                    <thead>
                        <tr
                            role="row"
                            style="height: 0px;"
                        >
                            <th
                                style="padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px; width: 0px;"
                                rowspan="1"
                                colspan="1"
                            ></th>
                            @foreach ($products as $product)
                                <td
                                    style="padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px; width: 0px;"
                                    rowspan="1"
                                    colspan="1"
                                ></td>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <tr class="d-none">
                            <th></th>
                            @foreach ($products as $product)
                                <td></td>
                            @endforeach
                        </tr>
                        <tr>
                            <th></th>
                            @foreach ($products as $product)
                                <td>
                                    <div style="max-width: 150px">
                                        <div class="img-fluid-eq">
                                            <div class="img-fluid-eq__dummy"></div>
                                            <div class="img-fluid-eq__wrap">
                                                <a href="{{ $product->url }}" title="{{ $product->name }}">
                                                    <img
                                                        class="lazyload"
                                                        data-src="{{ RvMedia::getImageUrl($product->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                                                        src="{{ image_placeholder($product->image, 'thumb') }}"
                                                        alt="{{ $product->name }}"
                                                    />
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>{{ __('Title') }}</th>
                            @foreach ($products as $product)
                                <td><a href="{{ $product->url }}" title="{{ $product->name }}">{{ $product->name }}</a></td>
                            @endforeach
                        </tr>
                        <tr class="price">
                            <th>{{ __('Price') }}</th>
                            @foreach ($products as $product)
                                <td>
                                    {!! Theme::partial('ecommerce.product-price', compact('product')) !!}
                                </td>
                            @endforeach
                        </tr>
                        @if (EcommerceHelper::isCartEnabled())
                            <tr class="add-to-cart">
                                <th>{{ __('Add to cart') }}</th>
                                @foreach ($products as $product)
                                    <td>
                                        {!! Theme::partial('ecommerce.product-cart-form', compact('product')) !!}
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                        <tr class="description">
                            <th>{{ __('Description') }}</th>
                            @foreach ($products as $product)
                                <td>
                                    {!! BaseHelper::clean($product->description) !!}
                                </td>
                            @endforeach
                        </tr>
                        <tr class="sku">
                            <th>{{ __('SKU') }}</th>
                            @foreach ($products as $product)
                                <td>{{ $product->sku ? '#' . $product->sku : '' }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>{{ __('Availability') }}</th>
                            @foreach ($products as $product)
                                <td>
                                    <div
                                        class="without-bg product-stock @if ($product->isOutOfStock()) out-of-stock @else in-stock @endif">
                                        @if ($product->isOutOfStock())
                                            {{ __('Out of stock') }}
                                        @else
                                            {{ __('In stock') }}
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                        @foreach ($attributeSets as $attributeSet)
                            @if ($attributeSet->is_comparable)
                                <tr>
                                    <th class="heading">
                                        {{ $attributeSet->title }}
                                    </th>

                                    @foreach ($products as $product)
                                        <td>
                                            {{ render_product_attributes_view_only($product, $attributeSet) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach

                        <tr>
                            <th></th>
                            @foreach ($products as $product)
                                <td>
                                    <button
                                        class="fs-4 remove btn remove-compare-item"
                                        data-url="{{ route('public.compare.remove', $product->id) }}"
                                        type="button"
                                        href="#"
                                        aria-label="{{ __('Remove this item') }}"
                                    >
                                        <span class="svg-icon">
                                            <svg>
                                                <use
                                                    href="#svg-icon-trash"
                                                    xlink:href="#svg-icon-trash"
                                                ></use>
                                            </svg>
                                        </span>
                                    </button>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center">{{ __('No products in compare list!') }}</p>
        @endif
    </div>
</div>
