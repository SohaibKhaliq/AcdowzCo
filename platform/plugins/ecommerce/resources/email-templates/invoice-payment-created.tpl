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
                                <img src="{{ 'wallet' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/ecommerce::email-templates.invoice_payment_created_title' | trans }}</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content">
                <p class="h1">{{ 'plugins/ecommerce::email-templates.invoice_payment_created_greeting' | trans({'customer_name': customer_name}) }}</p>
                <p>{{ 'plugins/ecommerce::email-templates.invoice_payment_created_message' | trans({'site_title': site_title}) | raw }}</p>

                {% if invoice_link %}
                    <p>{{ 'plugins/ecommerce::email-templates.invoice_payment_created_invoice_link_message' | trans({'invoice_link': invoice_link, 'invoice_code': invoice_code}) | raw }}</p>

                    <div class="bb-pt-md bb-text-center">
                        <a href="{{ invoice_link }}" class="bb-btn bb-bg-blue bb-border-blue">
                            <span class="btn-span">{{ 'plugins/ecommerce::email-templates.invoice_payment_created_view_online' | trans }}</span>
                        </a>
                    </div>
                {% else %}
                    <p>{{ 'plugins/ecommerce::email-templates.invoice_payment_created_invoice_message' | trans({'invoice_code': invoice_code}) }}</p>
                {% endif %}
            </td>
        </tr>
        </tbody>
    </table>
</div>

{{ footer }}
