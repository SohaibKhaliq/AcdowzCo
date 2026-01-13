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
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/marketplace::marketplace.email_templates.store_new_order_title' | trans }}</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <div>{{ 'plugins/marketplace::marketplace.email_templates.dear_vendor' | trans({'vendor_name': store_name}) }}</div>
                    <div>{{ 'plugins/marketplace::marketplace.email_templates.store_new_order_message' | trans({'site_title': site_title}) }}</div>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pt-0">
                    <table class="bb-row bb-mb-md" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="bb-bb-col">
                                    <h4 class="bb-m-0">{{ 'plugins/marketplace::marketplace.email_templates.customer_information' | trans }}</h4>
                                    <div>{{ 'plugins/marketplace::marketplace.email_templates.field_name' | trans }}: <strong>{{ customer_name }}</strong></div>
                                    {% if customer_phone %}
                                    <div>{{ 'plugins/marketplace::marketplace.email_templates.field_phone' | trans }}: <strong>{{ customer_phone }}</strong></div>
                                    {% endif %}
                                    {% if customer_email %}
                                    <div>{{ 'plugins/marketplace::marketplace.email_templates.field_email' | trans }}: <strong>{{ customer_email }}</strong></div>
                                    {% endif %}
                                    {% if customer_address %}
                                    <div>{{ 'plugins/marketplace::marketplace.email_templates.field_address' | trans }}: <strong>{{ customer_address }}</strong></div>
                                    {% endif %}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pt-0">
                    <h4>{{ 'plugins/marketplace::marketplace.email_templates.order_items_header' | trans }}</h4>
                    {{ product_list }}

                    {% if order_note %}
                        <div>{{ 'plugins/marketplace::marketplace.email_templates.field_note' | trans }}: {{ order_note }}</div>
                    {% endif %}
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-border-top">
                    <table class="bb-row bb-mb-md" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="bb-bb-col">
                                    <h4 class="bb-m-0">{{ 'plugins/marketplace::marketplace.email_templates.order_number' | trans }}</h4>
                                    <div>{{ order.code }}</div>
                                </td>
                                <td class="bb-col-spacer"></td>
                                <td class="bb-col">
                                    <h4 class="bb-mb-0">{{ 'plugins/marketplace::marketplace.email_templates.order_date' | trans }}</h4>
                                    <div>{{ order.created_at }}</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="bb-row" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="bb-col">
                                    {% if shipping_method %}
                                    <h4 class="bb-m-0">{{ 'plugins/marketplace::marketplace.email_templates.shipping_method' | trans }}</h4>
                                    <div>
                                        {{ shipping_method }}
                                    </div>
                                    {% endif %}
                                </td>

                                <td class="bb-col-spacer"></td>
                                <td class="bb-col">
                                    <h4 class="bb-m-0">{{ 'plugins/marketplace::marketplace.email_templates.payment_method' | trans }}</h4>
                                    <div>
                                        {{ payment_method }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}
