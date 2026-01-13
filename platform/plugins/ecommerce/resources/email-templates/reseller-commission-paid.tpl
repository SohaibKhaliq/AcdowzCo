<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tbody>
        <tr>
            <td style="padding:0 20px 20px 20px;text-align:left;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:1.6;color:#333;">
                <p>Hello {{ $customer_name }},</p>

                <p>Excellent news! Your reseller commission has been processed and paid.</p>

                <table cellpadding="15" cellspacing="0" border="0" width="100%" style="margin:20px 0;border:1px solid #d1ecf1;border-radius:4px;background-color:#f0f7fb;">
                    <tbody>
                        <tr>
                            <td style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#0c5460;">
                                <table width="100%" cellpadding="10" cellspacing="0">
                                    <tr>
                                        <td style="border-bottom:1px solid #d1ecf1;"><strong>Commission ID:</strong></td>
                                        <td style="border-bottom:1px solid #d1ecf1;text-align:right;">{{ $commission_id }}</td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom:1px solid #d1ecf1;"><strong>Amount Paid:</strong></td>
                                        <td style="border-bottom:1px solid #d1ecf1;text-align:right;"><strong style="font-size:18px;color:#17a2b8;">${{ $amount_paid }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom:1px solid #d1ecf1;"><strong>Payment Method:</strong></td>
                                        <td style="border-bottom:1px solid #d1ecf1;text-align:right;">{{ $payment_method }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Date:</strong></td>
                                        <td style="text-align:right;">{{ $paid_at }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p>The funds have been transferred to your account. Depending on your bank, it may take 1-3 business days for the funds to appear in your account.</p>

                <p style="margin-top:20px;margin-bottom:20px;">
                    <a href="{{ $dashboard_url }}" style="display:inline-block;padding:10px 20px;background-color:#17a2b8;color:white;text-decoration:none;border-radius:4px;font-weight:bold;">
                        View Payment Details
                    </a>
                </p>

                <p>Thank you for being an active reseller! Keep up the great work.</p>

                <p style="margin-top:30px;border-top:1px solid #ddd;padding-top:20px;color:#666;font-size:12px;">
                    Best regards,<br>
                    The {{ setting('admin_title') }} Team
                </p>
            </td>
        </tr>
    </tbody>
</table>
