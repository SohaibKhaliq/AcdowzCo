<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tbody>
        <tr>
            <td style="padding:0 20px 20px 20px;text-align:left;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:1.6;color:#333;">
                <p>{{ $vendor_name }},</p>

                <p>We are writing to inform you about an important notice regarding your vendor account.</p>

                <table cellpadding="15" cellspacing="0" border="0" width="100%" style="margin:20px 0;border-left:4px solid #ff6b6b;background-color:#fff3f3;">
                    <tbody>
                        <tr>
                            <td style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#555;">
                                <p style="margin:0 0 10px 0;"><strong>{{ $warning_title }}</strong></p>
                                <p style="margin:0 0 10px 0;">
                                    <span style="display:inline-block;padding:4px 12px;border-radius:4px;background-color:
                                    {% if warning_severity == 'critical' %}#ff6b6b{% elseif warning_severity == 'high' %}#ffa94d{% else %}#ffd43b{% endif %};color:white;font-weight:bold;font-size:11px;">
                                        {{ warning_severity|upper }}
                                    </span>
                                </p>
                                <p style="margin:0;">{{ $warning_content }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p>This warning was issued by: <strong>{{ $issued_by_name }}</strong></p>
                <p>Issued at: <strong>{{ $issued_at }}</strong></p>

                <p style="margin-top:20px;margin-bottom:20px;">
                    <a href="{{ $dashboard_url }}" style="display:inline-block;padding:10px 20px;background-color:#007bff;color:white;text-decoration:none;border-radius:4px;font-weight:bold;">
                        View Details in Dashboard
                    </a>
                </p>

                <p>If you have any questions or believe this warning was issued in error, please contact our support team immediately.</p>

                <p style="margin-top:30px;border-top:1px solid #ddd;padding-top:20px;color:#666;font-size:12px;">
                    Best regards,<br>
                    The {{ setting('admin_title') }} Team
                </p>
            </td>
        </tr>
    </tbody>
</table>
