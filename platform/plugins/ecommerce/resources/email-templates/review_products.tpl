{{ header }}

<div class="bb-main-content">
    <table class="bb-box" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td class="bb-content bb-pb-0" align="center">
                    <table class="bb-icon bb-icon-lg bb-bg-blue" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td valign="middle" align="center">
                                    <img src="{{ 'shopping-cart' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/ecommerce::email-templates.review_products_title' | trans }}</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <p>{{ 'plugins/ecommerce::email-templates.review_products_greeting' | trans({'customer_name': customer_name}) }}</p>
                    <div>{{ 'plugins/ecommerce::email-templates.review_products_message' | trans }}</div>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pt-0">
                    <h4>{{ 'plugins/ecommerce::email-templates.review_products_products' | trans }}</h4>
                    {{ product_review_list }}
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}
