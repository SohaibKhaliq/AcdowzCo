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
                            <img src="{{ 'arrow-back-up' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                        </td>
                    </tr>
                    </tbody>
                </table>
                <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/ecommerce::email-templates.order_return_request_title' | trans }}</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content">
                <div>{{ 'plugins/ecommerce::email-templates.order_return_request_message' | trans({'customer_name': customer_name}) }}</div>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-pt-0">
                <table class="bb-row bb-mb-md" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="bb-bb-col">
                                <h4 class="bb-m-0">{{ 'plugins/ecommerce::email-templates.order_return_request_return_reason' | trans }}</h4>
                                <div><strong>{{ return_reason }}</strong></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-pt-0">
                <table class="bb-row bb-mb-md" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td class="bb-bb-col">
                                <h4 class="bb-m-0">{{ 'plugins/ecommerce::email-templates.order_return_request_customer_info' | trans }}</h4>
                                <div>{{ 'plugins/ecommerce::email-templates.order_return_request_name' | trans }}: <strong>{{ customer_name }}</strong></div>
                                {% if customer_phone %}
                                <div>{{ 'plugins/ecommerce::email-templates.order_return_request_phone' | trans }}: <strong>{{ customer_phone }}</strong></div>
                                {% endif %}
                                {% if customer_email %}
                                <div>{{ 'plugins/ecommerce::email-templates.order_return_request_email' | trans }}: <strong>{{ customer_email }}</strong></div>
                                {% endif %}
                                {% if customer_address %}
                                <div>{{ 'plugins/ecommerce::email-templates.order_return_request_address' | trans }}: <strong>{{ customer_address }}</strong></div>
                                {% endif %}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-pt-0">
                <h4>{{ 'plugins/ecommerce::email-templates.order_return_request_order_summary' | trans }}</h4>
                {{ product_list }}

                {% if order_note %}
                <div>{{ 'plugins/ecommerce::email-templates.order_return_request_note' | trans }}: {{ order_note }}</div>
                {% endif %}
            </td>
        </tr>
        </tbody>
    </table>
</div>

{{ footer }}
