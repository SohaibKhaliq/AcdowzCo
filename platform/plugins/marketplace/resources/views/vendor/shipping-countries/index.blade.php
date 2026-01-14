@extends('plugins/marketplace::layouts.index')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="ti ti-truck"></i>
                        {{ trans('plugins/marketplace::store-shipping.vendor_title') }}
                    </h4>
                </div>
                
                <form action="{{ route('marketplace.vendor.shipping-countries.update') }}" method="POST">
                    @csrf
                    
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle"></i>
                            {{ trans('plugins/marketplace::store-shipping.vendor_help_text') }}
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('plugins/marketplace::store-shipping.shipping_countries') }}</label>
                            <div class="row">
                                @foreach($availableCountries as $country)
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <div class="form-check">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                name="countries[]" 
                                                value="{{ $country->id }}"
                                                id="country_{{ $country->id }}"
                                                @checked(in_array($country->id, $assignedCountries))
                                            >
                                            <label class="form-check-label" for="country_{{ $country->id }}">
                                                {{ $country->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            {{ trans('core/base::forms.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
