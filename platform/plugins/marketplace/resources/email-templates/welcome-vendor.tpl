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
                                <img src="{{ 'confetti' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/marketplace::marketplace.email_templates.welcome_vendor_title' | trans }}</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pb-0">
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.dear_vendor' | trans({'vendor_name': vendor_name}) }}</p>
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.welcome_vendor_greeting' | trans({'store_name': store_name}) }}</p>

                    <p>{{ 'plugins/marketplace::marketplace.email_templates.welcome_vendor_registration_complete' | trans }}</p>

                    <p>{{ 'plugins/marketplace::marketplace.email_templates.welcome_vendor_next_steps' | trans }}</p>

                    <ol>
                        <li>{{ 'plugins/marketplace::marketplace.email_templates.welcome_vendor_step_login' | trans }}</li>
                        <li>{{ 'plugins/marketplace::marketplace.email_templates.welcome_vendor_step_add_products' | trans }}</li>
                    </ol>

                    <p>{{ 'plugins/marketplace::marketplace.email_templates.welcome_vendor_support' | trans }}</p>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center bb-pb-0">
                    <a href="{{ site_url }}" class="bb-btn bb-bg-blue">{{ 'plugins/marketplace::marketplace.email_templates.login_vendor_account_button' | trans }}</a>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center">
                    <div>{{ 'plugins/marketplace::marketplace.email_templates.welcome_vendor_closing' | trans({'site_title': site_title}) }}</div>
                </td>
            </tr>
        </tbody>
    </table>
</div>


{{ footer }}
