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
                                <img src="{{ 'cloud-download' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/ecommerce::email-templates.download_digital_products_title' | trans }}</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content">
                <p>{{ 'plugins/ecommerce::email-templates.download_digital_products_greeting' | trans({'customer_name': customer_name}) }}</p>
                <div>{{ 'plugins/ecommerce::email-templates.download_digital_products_thanks' | trans }}</div>
                <div>{{ 'plugins/ecommerce::email-templates.download_digital_products_message' | trans }}</div>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-pt-0">
                <h4>{{ 'plugins/ecommerce::email-templates.download_digital_products_order_summary' | trans }}</h4>

                <div class="table">
                    <table>
                        <tr>
                            <th>
                                &nbsp;
                            </th>
                            <th>
                                &nbsp;
                            </th>
                            <th style="text-align: left">
                                {{ 'plugins/ecommerce::email-templates.download_digital_products_download' | trans }}
                            </th>
                        </tr>

                        {% for product in digital_products %}
                            <tr>
                                <td>
                                    <img
                                        src="{{ product.product_image_url }}"
                                        alt="image"
                                        width="50"
                                    >
                                </td>
                                <td>
                                    <span>{{ product.product_name }}</span>

                                    {% if (product.product_attributes_text) %}
                                        <span class="bb-text-muted">{{ product.product_attributes_text }}</span>
                                    {% endif %}

                                    {% if (product.product_options_text) %}
                                        <span class="bb-text-muted">{{ product.product_options_text }}</span>
                                    {% endif %}
                                </td>
                                <td>
                                    {% if product.product_file_internal_count %}
                                        <div>
                                            <a href="{{ product.download_hash_url }}">{{ 'plugins/ecommerce::email-templates.download_digital_products_all_files' | trans }}</a>
                                        </div>
                                    {% endif %}
                                    {% if product.product_file_external_count %}
                                        <div>
                                            <a href="{{ product.download_external_url }}">{{ 'plugins/ecommerce::email-templates.download_digital_products_external_link_downloads' | trans }}</a>
                                        </div>
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
                    <h4>{{ 'plugins/ecommerce::email-templates.download_digital_products_payment_method' | trans }}</h4>
                    {{ payment_method }}
                </td>
            </tr>
        {% endif %}
        </tbody>
    </table>
</div>

{{ footer }}
