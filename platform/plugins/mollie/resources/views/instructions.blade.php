<ol>
    <li>
        <p>
            <a
                href="https://www.mollie.com/dashboard/signup"
                target="_blank"
            >
                {{ __('Register an account on :name', ['name' => 'Mollie']) }}
            </a>
        </p>
    </li>
    <li>
        <p>
            {{ __('After registration at :name, you will have API key', ['name' => 'Mollie']) }}
        </p>
    </li>
    <li>
        <p>{{ __('Enter API key into the box in right hand') }}</p>
    </li>
    <li>
        <p><strong>{{ __('Webhook Configuration:') }}</strong></p>
        <p>{{ __('In your Mollie Dashboard, configure the webhook URL as:') }}</p>
        <code style="background: #f5f5f5; padding: 5px; display: block; margin: 10px 0;">
            {{ url('mollie/payment/webhook/{token}') }}
        </code>
        <p><small>{{ __('Note: Replace {token} with the actual payment token. The webhook will be automatically called by Mollie to update payment status.') }}</small></p>
    </li>
    <li>
        <p><strong>{{ __('Security (Optional):') }}</strong></p>
        <p>{{ __('For enhanced security, you can configure a webhook secret in your Mollie Dashboard under Developers > Webhooks, then enter it in the Webhook Secret field.') }}</p>
    </li>
</ol>
