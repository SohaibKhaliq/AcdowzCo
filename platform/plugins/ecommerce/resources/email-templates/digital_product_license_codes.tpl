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
                                <img src="{{ 'key' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h1 class="bb-text-center bb-m-0 bb-mt-md">Your License Codes</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content">
                <p>Dear {{ customer_name }},</p>
                <div>Thank you for purchasing our digital products.</div>
                <div>Below are your license codes for the products you have purchased:</div>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-pt-0">
                <h4>Here's what you ordered:</h4>
                <div class="table">
                    <table>
                        <tr>
                            <th style="text-align: left">
                                Product Image
                            </th>
                            <th style="text-align: left">
                                Product Name
                            </th>
                            <th style="text-align: left">
                                License Code
                            </th>
                        </tr>

                        {% for product in digital_product_list %}
                            <tr>
                                <td>
                                    <img
                                        src="{{ product.product_image_url }}"
                                        alt="{{ product.product_name }}"
                                        width="50"
                                    >
                                </td>
                                <td>
                                    <span>{{ product.product_name }}</span>

                                    {% if (product.product_options_text) %}
                                        <span class="bb-text-muted">{{ product.product_options_text }}</span>
                                    {% endif %}
                                </td>
                                <td>
                                    {% if product.license_code %}
                                        <code style="background-color: #f8f9fa; padding: 8px 12px; border-radius: 4px; font-family: monospace; font-size: 14px; font-weight: bold; color: #495057;">
                                            {{ product.license_code }}
                                        </code>
                                    {% else %}
                                        <span style="color: #6c757d;">N/A</span>
                                    {% endif %}
                                </td>
                            </tr>
                        {% endfor %}
                    </table><br>
                </div>
            </td>
        </tr>
        {% if payment_method %}
            <tr>
                <td class="bb-content bb-pt-0">
                    <h4>Payment Method</h4>
                    {{ payment_method }}
                </td>
            </tr>
        {% endif %}
        <tr>
            <td class="bb-content bb-pt-0">
                <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 20px;">
                    <h4 style="margin-top: 0; color: #495057;">Important Notes:</h4>
                    <ul style="margin-bottom: 0; color: #6c757d;">
                        <li>Please keep your license codes safe and secure</li>
                        <li>Each license code is unique and can only be used once</li>
                        <li>Do not share your license codes with others</li>
                        <li>If you have any issues with your license codes, please contact our support team</li>
                    </ul>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>

{{ footer }}
