@component('core/base::emails.base')
    <h2>{{ __('Important Notice: :title', ['title' => $warning_title]) }}</h2>
    
    <p>{{ __('Hello :name,', ['name' => $vendor_name]) }}</p>
    
    <p>{{ __('You have received a :severity notice regarding your store.', ['severity' => '<strong>' . ucfirst($warning_severity) . '</strong>']) }}</p>
    
    <div style="background: #f5f5f5; padding: 15px; margin: 20px 0; border-left: 4px solid #ff6b6b;">
        <h3 style="margin-top: 0;">{{ $warning_title }}</h3>
        <p>{{ $warning_content }}</p>
    </div>
    
    <p><strong>{{ __('Issued by:') }}</strong> {{ $issued_by_name }}</p>
    <p><strong>{{ __('Date:') }}</strong> {{ $issued_at }}</p>
    
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $dashboard_url }}" style="background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px; display: inline-block;">
            {{ __('View in Dashboard') }}
        </a>
    </div>
    
    <p>{{ __('Please acknowledge this notice by logging into your vendor dashboard.') }}</p>
    
    <p>{{ __('If you have any questions or concerns, please contact our support team.') }}</p>
    
    <p>{{ __('Best regards,') }}<br>{{ __('The :site Team', ['site' => get_application_name()]) }}</p>
@endcomponent
