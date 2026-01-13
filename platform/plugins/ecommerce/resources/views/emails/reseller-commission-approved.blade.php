@component('core/base::emails.base')
    <h2>{{ __('Commission Approved') }}</h2>
    
    <p>{{ __('Hello :name,', ['name' => $customer_name]) }}</p>
    
    <p>{{ __('Great news! Your commission has been approved.') }}</p>
    
    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <tr style="background: #f8f9fa;">
            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong>{{ __('Order ID:') }}</strong></td>
            <td style="padding: 10px; border: 1px solid #dee2e6;">#{{ $order_id }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong>{{ __('Order Amount:') }}</strong></td>
            <td style="padding: 10px; border: 1px solid #dee2e6;">${{ number_format($order_amount, 2) }}</td>
        </tr>
        <tr style="background: #f8f9fa;">
            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong>{{ __('Commission Rate:') }}</strong></td>
            <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $commission_rate }}%</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong>{{ __('Commission Earned:') }}</strong></td>
            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong>${{ number_format($commission_amount, 2) }}</strong></td>
        </tr>
        <tr style="background: #d4edda;">
            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong>{{ __('Status:') }}</strong></td>
            <td style="padding: 10px; border: 1px solid #dee2e6;"><strong style="color: #155724;">{{ __('Approved') }}</strong></td>
        </tr>
    </table>
    
    <p>{{ __('Your commission will be paid according to our payment schedule.') }}</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $dashboard_url }}" style="background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px; display: inline-block;">
            {{ __('View Your Dashboard') }}
        </a>
    </div>
    
    <p>{{ __('Thank you for being a valued reseller!') }}</p>
    
    <p>{{ __('Best regards,') }}<br>{{ __('The :site Team', ['site' => get_application_name()]) }}</p>
@endcomponent
