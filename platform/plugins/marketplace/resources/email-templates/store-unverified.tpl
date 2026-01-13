{{ header }}

<div class="bb-main-content">
    <table class="bb-box" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td class="bb-content bb-pb-0" align="center">
                    <table class="bb-icon bb-icon-lg bb-bg-yellow" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td valign="middle" align="center">
                                <img src="{{ 'warning' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/marketplace::marketplace.email_templates.store_unverified_title' | trans }}</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pb-0">
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.dear_store_owner' | trans({'store_name': store_name}) }}</p>
                    <div>{{ 'plugins/marketplace::marketplace.email_templates.store_unverified_message' | trans({'site_title': site_title}) | raw }}</div>
                    
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.unverification_details' | trans }}:</p>
                    
                    <table class="bb-panel" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="bb-panel-content">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            {% if unverified_by %}
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <strong>{{ 'plugins/marketplace::marketplace.email.unverified_by' | trans }}:</strong> {{ unverified_by }}
                                                </td>
                                            </tr>
                                            {% endif %}
                                            
                                            {% if unverified_at %}
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <strong>{{ 'plugins/marketplace::marketplace.email.unverified_at' | trans }}:</strong> {{ unverified_at }}
                                                </td>
                                            </tr>
                                            {% endif %}
                                            
                                            {% if verification_note %}
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <strong>{{ 'plugins/marketplace::marketplace.email.verification_note' | trans }}:</strong> {{ verification_note }}
                                                </td>
                                            </tr>
                                            {% endif %}
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p>{{ 'plugins/marketplace::marketplace.email_templates.store_unverified_impacts' | trans }}:</p>
                    <ul>
                        <li>{{ 'plugins/marketplace::marketplace.email_templates.verified_badge_removed' | trans }}</li>
                        <li>{{ 'plugins/marketplace::marketplace.email_templates.verification_benefits_lost' | trans }}</li>
                    </ul>

                    <p>{{ 'plugins/marketplace::marketplace.email_templates.store_unverified_next_steps' | trans | raw }}</p>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center">
                    <a href="{{ site_url }}/contact" class="bb-btn bb-bg-blue">
                        <span class="bb-btn-text">{{ 'plugins/marketplace::marketplace.email_templates.contact_support' | trans }}</span>
                    </a>
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