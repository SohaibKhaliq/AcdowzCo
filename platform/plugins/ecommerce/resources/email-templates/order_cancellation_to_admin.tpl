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
                                    <img src="{{ 'shopping-cart-x' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/ecommerce::email-templates.order_cancellation_to_admin_title' | trans({'order_id': order_id}) }}</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <p>{{ 'plugins/ecommerce::email-templates.order_cancellation_to_admin_greeting' | trans }}</p>
                    <p>{{ 'plugins/ecommerce::email-templates.order_cancellation_to_admin_message' | trans({'customer_name': customer_name, 'order_id': order_id, 'cancellation_reason': cancellation_reason}) | raw }}</p>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pt-0">
                    <h4>{{ 'plugins/ecommerce::email-templates.order_cancellation_to_admin_order_summary' | trans }}</h4>
                    {{ product_list }}

                    {% if order_note %}
                    <div>{{ 'plugins/ecommerce::email-templates.order_cancellation_to_admin_note' | trans }}: {{ order_note }}</div>
                    {% endif %}
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}
