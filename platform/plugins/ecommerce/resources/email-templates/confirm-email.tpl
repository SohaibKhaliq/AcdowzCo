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
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/ecommerce::email-templates.confirm_email_title' | trans }}</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center">
                    <p class="h1">{{ 'plugins/ecommerce::email-templates.confirm_email_greeting' | trans({'customer_name': customer_name}) }}</p>
                    <p>{{ 'plugins/ecommerce::email-templates.confirm_email_instruction' | trans }}</p>
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
                                                <a href="{{ verify_link }}" class="bb-btn bb-bg-blue bb-border-blue">
                                                    <span class="btn-span">{{ 'plugins/ecommerce::email-templates.confirm_email_button' | trans }}</span>
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
            <tr>
                <td class="bb-content bb-text-muted bb-pt-0 bb-text-center">
                    {{ 'plugins/ecommerce::email-templates.confirm_email_trouble' | trans({'verify_link': verify_link}) }}
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}
