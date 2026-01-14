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
                                <img src="{{ 'truck-delivery' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">Your Order Has Been Shipped!</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <p>Hello {{ customer_name }},</p>
                    <div>Great news! Your order <strong>#{{ order_id }}</strong> has been shipped and is on its way to you.</div>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pt-0">
                    <table class="bb-row bb-mb-md" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="bb-bb-col">
                                    <h4 class="bb-m-0">Tracking Information</h4>
                                    <div>Tracking ID: <strong>{{ tracking_id }}</strong></div>
                                    {% if vendor_name %}
                                        <div>Shipped by: <strong>{{ vendor_name }}</strong></div>
                                    {% endif %}
                                    <div class="bb-mt-sm">
                                        You can track your shipment using the tracking ID provided above.
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-border-top bb-text-center">
                    <p>If you have any questions about your order, please contact our customer support team.</p>
                    <a href="{{ site_url }}" class="bb-btn bb-btn-primary">Visit Our Store</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}
