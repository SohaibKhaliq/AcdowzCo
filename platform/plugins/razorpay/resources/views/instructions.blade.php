<div class="alert alert-warning mb-3 d-block">
    <p class="mb-1"><strong>{{ __('Important: Webhook Configuration Required') }}</strong></p>
    <p class="mb-0">{{ __('Webhooks are REQUIRED to prevent orders from going to "Incomplete" status. Without webhooks, payments may succeed but orders will not be marked as completed.') }}</p>
</div>

<ol>
    <li>
        <p>
            <a
                href="https://razorpay.com"
                target="_blank"
            >
                {{ __('Register an account on :name', ['name' => 'Razorpay']) }}
            </a>
        </p>
    </li>
    <li>
        <p>
            {{ __('After registration at :name, you will have API Key ID and API Key Secret', ['name' => 'Razorpay']) }}
        </p>
    </li>
    <li>
        <p>
            {{ __('Enter API Key ID and API Key Secret into the box in right hand') }}
        </p>
    </li>
    <li>
        <p>
            <strong class="text-danger">{{ __('CRITICAL STEP:') }}</strong>
            {!!
                BaseHelper::clean(__('You MUST create a webhook to handle payment notifications. Go to <strong>Account & Settings</strong> → <strong>Webhooks</strong> → <strong>Add New Webhook</strong> in your Razorpay Dashboard.'))
            !!}
        </p>

        <p class="mt-2">{{ __('Webhook URL:') }}</p>
        <code class="d-block p-2 bg-light">{{ route('payments.razorpay.webhook') }}</code>

        <p class="mt-3">
            {!!
                BaseHelper::clean(__('Select these <strong>Required Events</strong>:'))
            !!}
        </p>

        <ul class="ps-3 mt-2">
            <li><strong>payment.authorized</strong> - {{ __('When a payment is authorized') }}</li>
            <li><strong>payment.captured</strong> - {{ __('When a payment is captured') }}</li>
            <li><strong>payment.failed</strong> - {{ __('When a payment fails') }}</li>
            <li><strong>order.paid</strong> - {{ __('When an order is paid') }}</li>
        </ul>

        <div class="alert alert-danger mt-3 d-block">
            <p class="mb-1"><strong>{{ __('Warning:') }}</strong></p> {{ __('If you skip webhook configuration, your orders will show as "Incomplete" even after successful payment!') }}
        </div>

        <p class="mt-3">
            {!!
                BaseHelper::clean(__('After creating the webhook, Razorpay will show a <strong>Webhook Secret</strong>. Copy this secret and paste it into the <strong>Webhook Secret</strong> field below. This ensures secure communication between Razorpay and your site.'))
            !!}
        </p>
    </li>
    <li>
        <p>
            <strong>{{ __('Test Your Integration:') }}</strong>
        </p>
        <ul class="ps-3">
            <li>{{ __('Make a test payment in Test Mode first') }}</li>
            <li>{{ __('Check if the order status updates to "Completed"') }}</li>
            <li>{{ __('If orders remain "Incomplete", verify your webhook configuration') }}</li>
            <li>{{ __('Check payment logs in storage/logs/payment-*.log for debugging') }}</li>
        </ul>
    </li>
</ol>

<div class="alert alert-info mt-3 d-block">
    <p class="mb-1"><strong>{{ __('Troubleshooting Tips:') }}</strong></p>
    <ul class="mb-0 mt-2">
        <li>{{ __('Ensure your site has a valid SSL certificate (HTTPS)') }}</li>
        <li>{{ __('Verify webhook URL is publicly accessible (not localhost)') }}</li>
        <li>{{ __('Check that no firewall blocks Razorpay webhook requests') }}</li>
        <li>{{ __('For production, ensure Razorpay is in Live Mode, not Test Mode') }}</li>
    </ul>
</div>
