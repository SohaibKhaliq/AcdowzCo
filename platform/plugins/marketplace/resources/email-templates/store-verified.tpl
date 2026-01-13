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
                                <img src="{{ 'check' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/marketplace::marketplace.email_templates.store_verified_title' | trans }}</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pb-0">
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.dear_store_owner' | trans({'store_name': store_name}) }}</p>
                    <div>{{ 'plugins/marketplace::marketplace.email_templates.store_verified_message' | trans({'site_title': site_title}) | raw }}</div>
                    
                    <p>{{ 'plugins/marketplace::marketplace.email_templates.verification_details' | trans }}:</p>
                    
                    <table class="bb-panel" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="bb-panel-content">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            {% if verified_by %}
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <strong>{{ 'plugins/marketplace::marketplace.email.verified_by' | trans }}:</strong> {{ verified_by }}
                                                </td>
                                            </tr>
                                            {% endif %}
                                            
                                            {% if verified_at %}
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <strong>{{ 'plugins/marketplace::marketplace.email.verified_at' | trans }}:</strong> {{ verified_at }}
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

                    <p>{{ 'plugins/marketplace::marketplace.email_templates.store_verified_benefits' | trans }}:</p>
                    <ul>
                        <li>{{ 'plugins/marketplace::marketplace.email_templates.verified_badge_benefit' | trans }}</li>
                        <li>{{ 'plugins/marketplace::marketplace.email_templates.increased_trust_benefit' | trans }}</li>
                        <li>{{ 'plugins/marketplace::marketplace.email_templates.higher_visibility_benefit' | trans }}</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center">
                    <a href="{{ store_url }}" class="bb-btn bb-bg-green">
                        <span class="bb-btn-text">{{ 'plugins/marketplace::marketplace.email_templates.visit_your_store' | trans }}</span>
                    </a>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center">
                    <div>{{ 'plugins/marketplace::marketplace.email_templates.congratulations_verified' | trans }}</div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}