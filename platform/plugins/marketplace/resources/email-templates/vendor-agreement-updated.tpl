{{ header }}

<div class="bb-main-content">
    <table class="bb-box" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td class="bb-content bb-pb-0" align="center">
                    <table class="bb-icon bb-icon-lg bb-bg-orange" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td valign="middle" align="center">
                                <img src="{{ 'alert-triangle' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'Agreement Updated' | trans }}</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pb-0">
                    <p>{{ 'Dear :vendor_name,' | trans({'vendor_name': vendor_name}) }}</p>

                    <p>{{ 'Your vendor agreement at :store_name has been updated by our team.' | trans({'store_name': store_name}) }}</p>

                    <p><strong>{{ 'New Agreement Details:' | trans }}</strong></p>

                    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                        <tr style="background: #f8f9fa;">
                            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong>{{ 'Agreement Type:' | trans }}</strong></td>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">{{ agreement_type | trans }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong>{{ 'Terms:' | trans }}</strong></td>
                            <td style="padding: 10px; border: 1px solid #dee2e6;">{{ agreement_display }}</td>
                        </tr>
                    </table>

                    <p>{{ 'These changes will take effect immediately. If you have any questions or concerns about this update, please contact our support team.' | trans }}</p>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center">
                    <a href="{{ site_url }}" class="bb-btn bb-bg-blue">{{ 'Visit Your Dashboard' | trans }}</a>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center bb-text-muted">
                    <p class="bb-text-xs">{{ 'This is an automated notification. Please do not reply to this email.' | trans }}</p>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}
