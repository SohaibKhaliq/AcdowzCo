<!DOCTYPE html>
<html {{ html_attributes }}>
    <head>
        <meta charset="UTF-8">
        <title>{{ 'plugins/marketplace::withdrawal.invoice.title'|trans }} #{{ withdrawal.id }}</title>

        {{ settings.font_css }}

        <style>
            body {
                font-size: 15px;
                font-family: '{{ settings.font_family }}', Arial, sans-serif !important;
                position: relative;
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .header h1 {
                margin: 0;
                font-size: 24px;
            }
            .header .logo {
                max-width: 200px;
                margin: 0 auto 10px auto;
            }
            .header p {
                margin: 0;
                font-size: 14px;
                color: #666666;
            }
            .invoice-details {
                margin-bottom: 20px;
            }
            .invoice-details h2 {
                margin: 0 0 10px 0;
                font-size: 18px;
            }
            .invoice-details p {
                margin: 0;
                font-size: 14px;
                color: #666666;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            table, th, td {
                border: 1px solid #dddddd;
            }
            th, td {
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            .total {
                text-align: right;
                font-size: 18px;
            }
            .notes {
                margin-top: 20px;
            }
            .notes p {
                margin: 0;
                font-size: 14px;
                color: #666666;
            }

            small {
                font-size: 80%;
            }

            .stamp {
                border: 2px solid #555;
                color: #555;
                display: inline-block;
                font-size: 18px;
                line-height: 1;
                opacity: .5;
                padding: .3rem .75rem;
                position: fixed;
                text-transform: uppercase;
                top: 40%;
                left: 40%;
                transform: rotate(-14deg);
            }

            .is-failed {
                border-color: #d23;
                color: #d23;
            }

            .is-completed {
                border-color: #0a9928;
                color: #0a9928;
            }

            body[dir=rtl] {
                direction: rtl;
            }

            body[dir=rtl] .right {
                text-align: left;
            }

            body[dir=rtl] table tr td:last-child {
                text-align: left;
            }

            body[dir=rtl] .line-items-container th.heading-price {
                text-align: left;
            }

            body[dir=rtl] .line-items-container th:last-child {
                text-align: left;
            }

            body[dir=rtl] .line-items-container th {
                text-align: right;
            }

            {{ settings.extra_css }}
        </style>
    </head>
    <body {{ body_attributes }}>
        <div class="header">
            {% if company.logo %}
                <img src="{{ company.logo }}" alt="{{ company.name }}" class="logo">
            {% else %}
                <h1>{{ company.name }}</h1>
            {% endif %}
            <p>{{ company.address }}</p>
            <p>{{ company.city }}, {{ company.state }} {{ company.zipcode }}</p>
            <p>{{ company.email }}</p>
            <p>{{ company.phone }}</p>
        </div>
        <div class="invoice-details">
            <h2>{{ 'plugins/marketplace::withdrawal.invoice.title'|trans }} #{{ withdrawal.id }}</h2>
            <p>{{ 'plugins/marketplace::withdrawal.invoice.created_at'|trans }}: {{ withdrawal.created_at }}</p>
            <p>{{ 'plugins/marketplace::withdrawal.invoice.customer_name'|trans }}: {{ withdrawal.customer.name }}</p>
            <p>{{ 'plugins/marketplace::withdrawal.invoice.payment_method'|trans }}: {{ withdrawal_payment_channel }}</p>
        </div>
        <table>
            <thead>
            <tr>
                <th>{{ 'plugins/marketplace::withdrawal.invoice.earnings'|trans }}</th>
                <th>{{ 'plugins/marketplace::withdrawal.invoice.fee'|trans }}</th>
                <th>{{ 'plugins/marketplace::withdrawal.invoice.total'|trans }}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ withdrawal.amount|price_format }}</td>
                <td>{{ withdrawal.fee|price_format }} ({{ withdrawal_fee_percentage }}%)</td>
                <td>{{ (withdrawal.amount - withdrawal.fee)|price_format }}</td>
            </tr>
            </tbody>
        </table>
        <div class="total">
            <p>{{ 'plugins/marketplace::withdrawal.invoice.total_due'|trans }}: {{ (withdrawal.amount - withdrawal.fee)|price_format }}</p>
            {% if (get_ecommerce_setting('enable_invoice_stamp', 1) == 1) %}
                {% if withdrawal.status == 'canceled' %}
                    <span class="stamp is-failed">
                {{ withdrawal.status }}
            </span>
                {% elseif (withdrawal_status) %}
                    <span class="stamp {% if withdrawal.status == 'completed' %} is-completed {% else %} is-failed {% endif %}">
                {{ withdrawal_status }}
            </span>
                {% endif %}
            {% endif %}
        </div>
        {% if withdrawal.description %}
            <div class="notes">
                <h2>{{ 'plugins/marketplace::withdrawal.invoice.notes'|trans }}:</h2>
                <p>{{ withdrawal.description }}</p>
            </div>
        {% endif %}
        {% if withdrawal.bank_info %}
            <div class="notes">
                <h2>{{ 'plugins/marketplace::withdrawal.invoice.payment_instructions'|trans }}:</h2>
                <p>{{ 'plugins/marketplace::withdrawal.invoice.bank_name'|trans }}: {{ withdrawal.bank_info.name }}</p>
                <p>{{ 'plugins/marketplace::withdrawal.invoice.bank_account_number'|trans }}: {{ withdrawal.bank_info.number }}</p>
                <p>{{ 'plugins/marketplace::withdrawal.invoice.bank_account_name'|trans }}: {{ withdrawal.bank_info.full_name }}</p>
            </div>
        {% endif %}
    </body>
</html>
