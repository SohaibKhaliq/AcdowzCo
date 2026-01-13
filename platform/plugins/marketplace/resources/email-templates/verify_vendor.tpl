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
                                    <img src="{{ 'check' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/marketplace::marketplace.email_templates.verify_vendor_title' | trans }}</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pb-0">
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.dear_admin' | trans }}</p>
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.verify_vendor_message' | trans({'site_title': site_title}) }}</p>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pt-0 bb-pb-0">
                    <table class="bb-row bb-mb-md" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td class="bb-bb-col">
                                <h4>{{ 'plugins/marketplace::marketplace.email_templates.vendor_information' | trans }}</h4>
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
                    <table class="bb-row bb-mb-md" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td class="bb-bb-col">
                                <h4>{{ 'plugins/marketplace::marketplace.email_templates.shop_information' | trans }}</h4>
                                <div>{{ 'plugins/marketplace::marketplace.email_templates.field_store_name' | trans }}: <strong>{{ store_name }}</strong></div>
                                <div>{{ 'plugins/marketplace::marketplace.email_templates.field_store_phone' | trans }}: <strong>{{ store_phone }}</strong></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center bb-pt-0 bb-pb-xl">
                    <table cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                            <td align="center">
                                <table cellpadding="0" cellspacing="0" border="0" class="bb-bg-blue bb-rounded bb-w-auto">
                                    <tr>
                                        <td align="center" valign="top" class="lh-1">
                                            <a href="{{ store_url }}" class="bb-btn bb-bg-blue bb-border-blue">
                                                <span class="btn-span">{{ 'plugins/marketplace::marketplace.email_templates.visit_store_button' | trans }}</span>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
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
