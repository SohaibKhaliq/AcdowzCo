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
                            <img src="{{ 'check' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon">
                        </td>
                    </tr>
                    </tbody>
                </table>
                <h1 class="bb-text-start bb-m-0 bb-mt-md">Product Files Updated</h1>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-text-start">
                <p class="h1">Hello, {{ customer_name }}!</p>
                <p>The files for the product <a href="{{ product_link }}"><strong>{{ product_name }}</strong></a> have been updated.</p>
                <p>Update time: {{ update_time }}</p>
                <p>Updated files:</p>
                <ul>
                    {% for file in product_files %}
                        <li>{{ file.name }} ({{ file.size }})</li>
                    {% endfor %}
                </ul>
                <p>
                    You can download the updated files from the following link:
                    <a href="{{ download_link }}">{{ download_link }}</a>
                </p>
                <p>Thank you for your attention.</p>
            </td>
        </tr>
        <tr>
            <td class="bb-content bb-text-muted bb-pt-0 bb-text-start">
                If you have any questions, feel free to contact us.
            </td>
        </tr>
        </tbody>
    </table>
</div>

{{ footer }}
