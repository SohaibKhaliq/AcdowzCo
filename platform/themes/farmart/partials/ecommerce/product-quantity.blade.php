<div class="quantity">
    <label class="label-quantity">{{ __('Quantity') }}:</label>
    <div class="qty-box">
        <span class="svg-icon decrease">
            <svg>
                <use
                    href="#svg-icon-decrease"
                    xlink:href="#svg-icon-decrease"
                ></use>
            </svg>
        </span>
        <input
            class="input-text qty"
            name="{{ $name ?? 'qty' }}"
            type="number"
            value="{{ $value ?? $product->min_cart_quantity }}"
            min="{{ $product->min_cart_quantity }}"
            max="{{ $product->max_cart_quantity }}"
            title="Qty"
            tabindex="0"
            step="1"
            required
        >
        <span class="svg-icon increase">
            <svg>
                <use
                    href="#svg-icon-increase"
                    xlink:href="#svg-icon-increase"
                ></use>
            </svg>
        </span>
    </div>
</div>
