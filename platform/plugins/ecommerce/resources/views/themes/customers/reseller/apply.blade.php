@extends(EcommerceHelper::viewPath('customers.master'))

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="customer-page-title">{{ __('Apply for Reseller Program') }}</h2>
        </div>
        <div class="panel-body">
            @if ($application && $application->status == 'pending')
                <div class="alert alert-info">
                    {{ __('Your application is currently pending approval. We will notify you once it has been processed.') }}
                </div>
                <div class="mb-3">
                    <strong>{{ __('Your Notes:') }}</strong>
                    <p>{{ $application->notes }}</p>
                </div>
            @elseif ($application && $application->status == 'rejected')
                <div class="alert alert-danger">
                    {{ __('Your previous application was rejected.') }}
                    @if ($application->rejection_reason)
                        <br><strong>{{ __('Reason:') }}</strong> {{ $application->rejection_reason }}
                    @endif
                </div>
                <hr>
                <div class="form-content">
                    <form action="{{ route('customer.reseller.apply.post') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="notes" class="control-label required">{{ __('Why do you want to join our reseller program?') }}</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="5" placeholder="{{ __('Tell us about your experience, your platform, or how you plan to promote our products...') }}">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('Submit Application') }}</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="alert alert-info">
                    {{ __('Join our reseller program and start earning commissions from your referrals!') }}
                </div>
                
                <div class="form-content">
                    <form action="{{ route('customer.reseller.apply.post') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="notes" class="control-label required">{{ __('Why do you want to join our reseller program?') }}</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="5" placeholder="{{ __('Tell us about your experience, your platform, or how you plan to promote our products...') }}">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('Submit Application') }}</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
