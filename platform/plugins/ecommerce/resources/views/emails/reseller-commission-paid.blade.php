@component('core/base::emails.base')
    <h2>{{ __('Commission Payment Processed') }}</h2>
    
    <p>{{ __('Hello :name,', ['name' => $customer_name]) }}</p>
    
    <p>{{ __('Your commission payment has been processed!') }}</p>
    
    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <tr style="background: #f8f9fa;">
            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong>{{ __('Order ID:') }}</strong></td>
            <td style="padding: 10px; border: 1px solid #dee2e6;">#{{ $order_id }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong>{{ __('Commission Amount:') }}</strong></td>
            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong>${{ number_format($commission_amount, 2) }}</strong></td>
        </tr>
        <tr style="background: #d4edda;">
            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong>{{ __('Status:') }}</strong></td>
            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong style="color: #155724;">{{ __('Paid') }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong>{{ __('Payment Date:') }}</strong></td>
            <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $payment_date }}</td>
        </tr>
    </table>
    
    <p>{{ __('The payment should appear in your account within 3-5 business days.') }}</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $dashboard_url }}" style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px; display: inline-block;">
            {{ __('View Payment History') }}
        </a>
    </div>
    
    <p>{{ __('Thank you for your continued partnership!') }}</p>
    
    <p>{{ __('Best regards,') }}<br>{{ __('The :site Team', ['site' => get_application_name()]) }}</p>
@endcomponent
