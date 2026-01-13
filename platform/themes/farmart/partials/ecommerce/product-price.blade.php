@if (! EcommerceHelper::hideProductPrice() || EcommerceHelper::isCartEnabled())
    <span class="product-price">
        <span class="product-price-sale bb-product-price @if (!$product->isOnSale()) d-none @endif">
            <ins>
                <span class="price-amount">
                    <bdi>
                        <span class="amount bb-product-price-text" data-bb-value="product-price">{{ format_price($product->front_sale_price_with_taxes) }}</span>
                    </bdi>
                </span>
            </ins>
            &nbsp;
            <del aria-hidden="true">
                <span class="price-amount">
                    <bdi>
                        <span class="amount bb-product-price-text-old" data-bb-value="product-original-price">{{ format_price($product->price_with_taxes) }}</span>
                    </bdi>
                </span>
            </del>
        </span>
        <span class="product-price-original bb-product-price @if ($product->isOnSale()) d-none @endif">
            <span class="price-amount">
                <bdi>
                    <span class="amount bb-product-price-text" data-bb-value="product-price">{{ format_price($product->front_sale_price_with_taxes) }}</span>
                </bdi>
            </span>
        </span>
    </span>
@endif
