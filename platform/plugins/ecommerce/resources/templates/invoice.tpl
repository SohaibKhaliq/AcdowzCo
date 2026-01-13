<!doctype html>
<html {{ html_attributes }}>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ 'plugins/ecommerce::order.invoice_for_order'|trans }} {{ invoice.code }}</title>

    {{ settings.font_css }}

    <style>
        {{ invoice_css | raw }}

        body {
            font-family: '{{ settings.font_family }}', Arial, sans-serif !important;
        }

        {{ settings.extra_css }}
    </style>

    {{ settings.header_html }}

    {{ invoice_header_filter | raw }}
</head>
<body {{ body_attributes }}>

{{ invoice_body_filter | raw }}

{% if (get_ecommerce_setting('enable_invoice_stamp', 1) == 1) %}
    {% if invoice.status == 'canceled' %}
        <div class="stamp is-failed">
            {{ invoice.status }}
        </div>
    {% elseif (payment_status_label) %}
        <div class="stamp {% if payment_status == 'completed' %} is-completed {% else %} is-failed {% endif %}">
            {{ payment_status_label }}
        </div>
    {% endif %}
{% endif %}

<table class="invoice-info-container">
    <tr>
        <td>
            <div class="logo-container">
                {% if logo %}
                    <img src="{{ logo_full_path }}" style="width:100%; max-width:150px;" alt="site_title">
                {% endif %}
            </div>
        </td>
        <td>
            {% if invoice.created_at %}
                <p>
                    <strong>{{ invoice.created_at|date(settings.date_format) }}</strong>
                </p>
            {% endif %}
            <p>
                <strong style="display: inline-block">{{ 'plugins/ecommerce::order.invoice'|trans }}: </strong>
                <span style="display: inline-block">{{ invoice.code }}</span>
            </p>
            <p>
                <strong style="display: inline-block">{{ 'plugins/ecommerce::order.order_id'|trans }}: </strong>
                <span style="display: inline-block">{{ invoice.reference.code }}</span>
            </p>
        </td>
    </tr>
</table>

<table class="invoice-info-container">
    <tr>
        <td>
            {% if company_name %}
                <p>{{ company_name }}</p>
            {% endif %}

            {% if company_address %}
                <p>{{ company_address }}</p>
            {% endif %}

            {% if company_phone %}
                <p>{{ company_phone }}</p>
            {% endif %}

            {% if company_email %}
                <p>{{ company_email }}</p>
            {% endif %}

            {% if company_tax_id %}
                <p>{{ 'plugins/ecommerce::ecommerce.tax_id'|trans }}: {{ company_tax_id }}</p>
            {% endif %}
        </td>
        <td>
            {% if invoice.customer_name %}
                <p>{{ invoice.customer_name }}</p>
            {% endif %}
            {% if invoice.customer_email %}
                <p>{{ invoice.customer_email }}</p>
            {% endif %}
            {% if invoice.customer_address %}
                <p>{{ invoice.customer_address }}</p>
            {% endif %}
            {% if invoice.customer_phone %}
                <p>{{ invoice.customer_phone }}</p>
            {% endif %}
            {% if invoice.customer_tax_id %}
                <p>{{ 'plugins/ecommerce::ecommerce.tax_id'|trans }}: {{ invoice.customer_tax_id }}</p>
            {% endif %}
        </td>
    </tr>
</table>

{% if invoice.description %}
    <table class="invoice-info-container">
        <tr style="text-align: left">
            <td style="text-align: left">
                <p>{{ 'plugins/ecommerce::order.note'|trans }}: {{ invoice.description }}</p>
            </td>
        </tr>
    </table>
{% endif %}

<table class="line-items-container">
    <thead>
    <tr>
        <th class="heading-description">{{ 'plugins/ecommerce::products.form.product'|trans }}</th>
        <th class="heading-options">{{ 'plugins/ecommerce::products.form.options'|trans }}</th>
        <th class="heading-quantity">{{ 'plugins/ecommerce::products.form.quantity'|trans }}</th>
        <th class="heading-price">{{ 'plugins/ecommerce::products.form.price'|trans }}</th>
        {% if has_multiple_products %}
            <th class="heading-tax">{{ 'plugins/ecommerce::products.form.tax'|trans }}</th>
        {% endif %}
        <th class="heading-subtotal">{{ 'plugins/ecommerce::products.form.total'|trans }}</th>
    </tr>
    </thead>
    <tbody>
    {% for item in invoice.items %}
        <tr class="product-row">
            <td class="product-cell">
                <div class="product-name">{{ item.name }}</div>
                {% if item.options.sku %}
                    <div class="product-sku">SKU: {{ item.options.sku }}</div>
                {% endif %}
            </td>
            <td class="options-cell">
                {% if item.options and (item.options.attributes or item.options.product_options or item.options.license_code) %}
                    {% if item.options.attributes %}
                        <div class="option-item">
                            <small> {{ item.options.attributes }} </small>
                        </div>
                    {% endif %}
                    {% if item.options.product_options %}
                        <div class="option-item">
                            <strong>{{ 'plugins/ecommerce::invoice.detail.product_options'|trans }}:</strong> {{ item.options.product_options }}
                        </div>
                    {% endif %}
                    {% if item.options.license_code %}
                        <div class="option-item">
                            <strong>{{ 'plugins/ecommerce::invoice.detail.license_code'|trans }}:</strong> {{ item.options.license_code }}
                        </div>
                    {% endif %}
                {% else %}
                    <span class="text-muted">-</span>
                {% endif %}
            </td>
            <td class="qty-cell">{{ item.qty }}</td>
            <td class="price-cell">{{ item.price|price_format }}</td>
            {% if has_multiple_products %}
                <td class="tax-cell">
                    {% if item.tax_amount > 0 %}
                        <div class="tax-amount">{{ item.tax_amount|price_format }}</div>
                        {% if item.options.taxClasses %}
                            <div class="tax-details">
                                {% for taxName, taxRate in item.options.taxClasses %}
                                    <span class="tax-class">{{ taxName }} ({{ taxRate }}%)</span>{% if not loop.last %}, {% endif %}
                                {% endfor %}
                            </div>
                        {% endif %}
                    {% else %}
                        <span class="text-muted">-</span>
                    {% endif %}
                </td>
            {% endif %}
            <td class="total-cell">{{ item.sub_total|price_format }}</td>
        </tr>
    {% endfor %}

    <tr>
        <td colspan="{% if has_multiple_products %}5{% else %}4{% endif %}" class="right">
            {{ 'plugins/ecommerce::invoice.detail.quantity'|trans }}
        </td>
        <td class="bold">
            {{ total_quantity|number_format }}
        </td>
    </tr>

    <tr>
        <td colspan="{{ summary_colspan }}" class="right">
            {{ 'plugins/ecommerce::products.form.sub_total'|trans }}
        </td>
        <td class="bold">
            {{ invoice.sub_total|price_format }}
        </td>
    </tr>

    {% if invoice.tax_amount > 0 %}
        {% if tax_groups %}
            {% for tax_name, tax_amount in tax_groups %}
                <tr>
                    <td colspan="{{ summary_colspan }}" class="right">
                        {{ 'plugins/ecommerce::products.form.tax'|trans }} <small>({{ tax_name }})</small>
                    </td>
                    <td class="bold">
                        {{ tax_amount|price_format }}
                    </td>
                </tr>
            {% endfor %}
        {% else %}
            <tr>
                <td colspan="{{ summary_colspan }}" class="right">
                    {{ 'plugins/ecommerce::products.form.tax'|trans }}
                </td>
                <td class="bold">
                    {{ invoice.tax_amount|price_format }}
                </td>
            </tr>
        {% endif %}
    {% endif %}

    {% if invoice.payment_fee > 0 %}
        <tr>
            <td colspan="{{ summary_colspan }}" class="right">
                {{ 'plugins/payment::payment.payment_fee'|trans }}
            </td>
            <td class="bold">
                {{ invoice.payment_fee|price_format }}
            </td>
        </tr>
    {% endif %}

    {% if invoice.shipping_amount > 0 %}
        <tr>
            <td colspan="{{ summary_colspan }}" class="right">
                {{ 'plugins/ecommerce::products.form.shipping_fee'|trans }}
            </td>
            <td class="bold">
                {{ invoice.shipping_amount|price_format }}
            </td>
        </tr>
    {% endif %}

    {% if invoice.discount_amount > 0 %}
        <tr>
            <td colspan="{{ summary_colspan }}" class="right">
                {{ 'plugins/ecommerce::products.form.discount'|trans }}
            </td>
            <td class="bold">
                {{ invoice.discount_amount|price_format }}
            </td>
        </tr>
    {% endif %}
    </tbody>
</table>

<table class="line-items-container">
    <thead>
    <tr>
        <th>{{ 'plugins/ecommerce::order.payment_info'|trans }}</th>
        <th>{{ 'plugins/ecommerce::order.total_amount'|trans }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="payment-info">
            {% if payment_method %}
                <div>
                    {{ 'plugins/ecommerce::order.payment_method'|trans }}: <strong>{{ payment_method }}</strong>
                </div>
            {% endif %}

            {% if payment_status %}
                <div>
                    {{ 'plugins/ecommerce::order.payment_status_label'|trans }}: <strong>{{ payment_status_label }}</strong>
                </div>
            {% endif %}

            {% if payment_description %}
                <div>
                    {{ 'plugins/ecommerce::order.payment_info'|trans }}: <strong>{{ payment_description | raw }}</strong>
                </div>
            {% endif %}

            {{ invoice_payment_info_filter | raw }}
        </td>
        <td class="large total"><p>{{ invoice.amount|price_format }}</p></td>
    </tr>
    </tbody>
</table>
{{ ecommerce_invoice_footer | raw }}
</body>
</html>
