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
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">Account Blocked</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.dear_vendor' | trans({'vendor_name': store_name}) }}</p>
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.vendor_account_blocked_message' | trans({'site_title': site_title, 'block_date': block_date}) | raw }}</p>
                    <p><strong>{{ 'plugins/marketplace::marketplace.email_templates.vendor_account_blocked_reason' | trans({'block_reason': block_reason}) }}</strong></p>
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.vendor_account_blocked_contact_support' | trans }}</p>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-border-top bb-text-center">
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.email_sent_by_team' | trans({'site_title': site_title}) }}</p>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}
