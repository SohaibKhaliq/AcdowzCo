{{ header }}

<div class="bb-main-content">
    <table class="bb-box" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td class="bb-content bb-pb-0" align="center">
                    <table class="bb-icon bb-icon-lg bb-bg-green" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td valign="middle" align="center">
                                <img src="{{ 'lock-open' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">Account Unblocked</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.dear_vendor' | trans({'vendor_name': store_name}) }}</p>
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.vendor_account_unblocked_message' | trans({'site_title': site_title, 'unblock_date': unblock_date}) | raw }}</p>
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.vendor_account_unblocked_resume' | trans }}</p>
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.vendor_account_unblocked_questions' | trans }}</p>
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
