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
                                <img src="{{ 'hourglass' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/marketplace::marketplace.email_templates.pending_product_approval_title' | trans }}</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.dear_admin' | trans }}</p>
                    <div>{{ 'plugins/marketplace::marketplace.email_templates.pending_product_approval_message' | trans({'store_name': store_name, 'product_url': product_url, 'product_name': product_name}) | raw }}</div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}
