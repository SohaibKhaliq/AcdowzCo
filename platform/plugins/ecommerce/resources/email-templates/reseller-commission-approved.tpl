<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tbody>
        <tr>
            <td style="padding:0 20px 20px 20px;text-align:left;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:1.6;color:#333;">
                <p>Hello {{ $customer_name }},</p>

                <p>Great news! One of your reseller commissions has been approved.</p>

                <table cellpadding="15" cellspacing="0" border="0" width="100%" style="margin:20px 0;border:1px solid #d4edda;border-radius:4px;background-color:#f0f9f6;">
                    <tbody>
                        <tr>
                            <td style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#155724;">
                                <table width="100%" cellpadding="10" cellspacing="0">
                                    <tr>
                                        <td style="border-bottom:1px solid #d4edda;"><strong>Order ID:</strong></td>
                                        <td style="border-bottom:1px solid #d4edda;text-align:right;">{{ $order_id }}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom:1px solid #d4edda;"><strong>Order Amount:</strong></td>
                                        <td style="border-bottom:1px solid #d4edda;text-align:right;">${{ $order_amount }}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom:1px solid #d4edda;"><strong>Commission Rate:</strong></td>
                                        <td style="border-bottom:1px solid #d4edda;text-align:right;">{{ $commission_rate }}%</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Commission Amount:</strong></td>
                                        <td style="text-align:right;"><strong style="font-size:16px;color:#28a745;">${{ $commission_amount }}</strong></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p>Your commission is now approved and will be paid according to our payment schedule. You can track the payment status in your dashboard.</p>

                <p style="margin-top:20px;margin-bottom:20px;">
                    <a href="{{ $dashboard_url }}" style="display:inline-block;padding:10px 20px;background-color:#28a745;color:white;text-decoration:none;border-radius:4px;font-weight:bold;">
                        View in Dashboard
                    </a>
                </p>

                <p>If you have any questions about your commission, feel free to contact our support team.</p>

                <p style="margin-top:30px;border-top:1px solid #ddd;padding-top:20px;color:#666;font-size:12px;">
                    Best regards,<br>
                    The {{ setting('admin_title') }} Team
                </p>
            </td>
        </tr>
    </tbody>
</table>
