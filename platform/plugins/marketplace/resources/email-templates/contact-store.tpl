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
                                <img src="{{ 'mail' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">New Contact Message</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <div class="bb-box-content">
                        <p>{{ customer_message }}</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-border-top">
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.contact_store_sent_from' | trans({'customer_name': customer_name, 'customer_email': customer_email, 'site_title': site_title}) }}</p>
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.contact_store_reply_instruction' | trans({'customer_name': customer_name, 'customer_email': customer_email}) }}</p>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}
