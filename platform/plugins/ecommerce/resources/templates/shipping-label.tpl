<!doctype html>
<html {{ html_attributes }}>
    <head>
        <meta charset="UTF-8">
        <title>{{ 'plugins/ecommerce::shipping.shipping_label.name'|trans }} {{ shipment.code }}</title>

        {{ settings.font_css }}

        <style>
            @page {
                margin: 0;
            }

            * {
                margin: 0;
            }

            body {
                font-size: 13px;
                font-family: '{{ settings.font_family }}', Arial, sans-serif !important;
            }

            table {
                border-collapse: collapse;
                width: 100%;
                table-layout: fixed;
            }

            table tr td {
                padding: 0;
                vertical-align: top;
                word-wrap: break-word;
                overflow: hidden;
            }

            .sender-table {
                width: 100%;
            }

            .sender-table td {
                vertical-align: top;
                overflow: hidden;
            }

            .sender-table .logo-cell {
                width: 100px;
                max-width: 100px;
                text-align: center;
                padding-left: 10px;
            }

            .container {
                min-height: 80%;
                border: 3px solid black;
                margin: 30px 15px 15px 15px;
                border-radius: 4px;
                page-break-inside: avoid;
                position: relative;
            }

            .section {
                padding: 15px 20px;
                border-bottom: 1px solid black;
                position: relative;
            }

            .section:last-child {
                border-bottom: none;
            }

            .sender-info h4 {
                margin: 0 0 5px 0;
                font-size: 14px;
                font-weight: bold;
            }

            .sender-info p {
                margin: 0 0 3px 0;
                font-size: 12px;
            }

            .receiver-info h2 {
                margin: 0 0 10px 0;
                font-size: 18px;
                font-weight: bold;
            }

            .receiver-info h4 {
                margin: 0 0 6px 0;
                font-size: 14px;
                font-weight: normal;
            }

            .details-table td {
                padding-bottom: 14px;
                width: 33.33%;
            }

            .details-table td span {
                font-size: 12px;
                color: #666;
            }

            .details-table td h3 {
                margin: 2px 0 0 0;
                font-size: 14px;
                font-weight: bold;
            }

            .notes-section {
                padding: 15px 20px;
            }

            .note-item {
                margin-bottom: 6px;
                overflow-wrap: break-word;
                word-wrap: break-word;
            }

            .note-item span {
                font-size: 12px;
                color: #666;
            }

            .note-item strong {
                font-weight: bold;
            }

            .qr-section {
                margin-top: 20px;
            }

            .qr-section td:first-child {
                width: 160px;
                text-align: center;
            }

            .qr-section td:last-child {
                font-size: 12px;
                padding-left: 15px;
                vertical-align: middle;
            }

            .qr-code {
                max-height: 160px;
                width: auto;
                height: auto;
                display: block;
            }

            .logo {
                max-width: 100px;
                max-height: 80px;
                width: auto;
                height: auto;
                display: block;
                object-fit: contain;
            }

            {{ settings.extra_css }}
        </style>

        {{ settings.header_html }}
    </head>
    <body {{ body_attributes }}>
        <div style="height: 1px;"></div>
        <div class="container">
            <div class="section">
                <table class="sender-table">
                    <tr>
                        <td style="width: 18%;">
                            {{ 'plugins/ecommerce::shipping.shipping_label.sender'|trans }}:
                        </td>
                        <td class="sender-info">
                            <h4>{{ sender.name }}</h4>
                            <p>{{ sender.full_address }}</p>
                            <p>{{ sender.phone }}</p>
                            <p>{{ sender.email }}</p>
                        </td>
                        {% if sender.logo %}
                            <td class="logo-cell">
                                <img src="{{ sender.logo }}" alt="{{ sender.name }}" class="logo">
                            </td>
                        {% endif %}
                    </tr>
                </table>
            </div>

            <div class="section receiver-info">
                <h2>{{ receiver.name }}</h2>
                <h4>{{ receiver.full_address }}</h4>
                <h4>{{ receiver.email }}</h4>
                <h4>{{ receiver.phone }}</h4>
            </div>

            <div class="section">
                <table class="details-table">
                    <tr>
                        <td>
                            <span>{{ 'plugins/ecommerce::shipping.shipment_id'|trans }}:</span>
                            <h3>{{ shipment.code }}</h3>
                        </td>
                        <td>
                            <span>{{ 'plugins/ecommerce::shipping.order_id'|trans }}:</span>
                            <h3>{{ shipment.order_number }}</h3>
                        </td>
                        <td>
                            <span>{{ 'plugins/ecommerce::shipping.shipping_label.order_date'|trans }}:</span>
                            <h3>{{ shipment.created_at }}</h3>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>{{ 'plugins/ecommerce::shipping.shipping_method'|trans }}:</span>
                            <h3>{{ shipment.shipping_method }}</h3>
                        </td>
                        <td>
                            <span>{{ 'plugins/ecommerce::shipping.weight_unit'|trans({unit: shipment.weight_unit}) }}:</span>
                            <h3>{{ shipment.weight }} {{ shipment.weight_unit }}</h3>
                        </td>
                        <td>
                            <span>{{ 'plugins/ecommerce::shipping.shipping_fee'|trans }}:</span>
                            <h3>{{ shipment.shipping_fee }}</h3>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="notes-section">
                {% if shipment.note %}
                    <div class="note-item">
                        <span>{{ 'plugins/ecommerce::shipping.delivery_note'|trans }}:</span>
                        <strong>{{ shipment.note }}</strong>
                    </div>
                {% endif %}

                {% if receiver.note %}
                <div class="note-item">
                    <span>{{ 'plugins/ecommerce::shipping.customer_note'|trans }}:</span>
                    <strong>{{ receiver.note }}</strong>
                </div>
                {% endif %}

                <table class="qr-section">
                    <tr>
                        <td>
                            <img src="data:image/svg+xml;base64,{{ shipment.qr_code }}" class="qr-code" alt="QR code">
                        </td>
                        <td>
                            {{ 'plugins/ecommerce::shipping.shipping_label.scan_qr_code'|trans }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{ settings.footer_html }}
    </body>
</html>
