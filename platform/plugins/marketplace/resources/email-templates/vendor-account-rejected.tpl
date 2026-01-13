{{ header }}

<div class="bb-main-content">
    <table class="bb-box" cellpadding="0" cellspacing="0">
        <tbody>
        <tr>
            <td class="bb-content bb-pb-0" align="center">
                <table class="bb-icon bb-icon-lg bb-bg-red" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td valign="middle" align="center">
                            <img src="{{ 'alert-triangle' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                        </td>
                    </tr>
                    </tbody>
                </table>
                <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/marketplace::marketplace.email_templates.vendor_account_rejected_title' | trans }}</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-pb-0">
                <p>{{ 'plugins/marketplace::marketplace.email_templates.dear_vendor' | trans({'vendor_name': store_name}) }}</p>
                <div>{{ 'plugins/marketplace::marketplace.email_templates.vendor_account_rejected_message' | trans({'site_url': site_url}) | raw }}</div>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-text-center">
                <div>{{ 'plugins/marketplace::marketplace.email_templates.thank_you_understanding' | trans }}</div>
            </td>
        </tr>
        </tbody>
    </table>
</div>

{{ footer }}
