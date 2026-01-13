<!DOCTYPE html>
<html {!! Theme::htmlAttributes() !!}>
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <title> @yield('title', __('Checkout')) </title>

    @if ($favicon = Theme::getFavicon())
        <link
            href="{{ RvMedia::getImageUrl($favicon) }}"
            rel="shortcut icon"
        >
    @endif

    {!! Theme::typography()->renderCssVariables() !!}

    <style>
        :root {
            --primary-color: {{ $primaryColor = theme_option('primary_color', '#58b3f0') }};
            --primary-color-rgb: {{ implode(',', BaseHelper::hexToRgb($primaryColor)) }};
        }
    </style>

    {!! Html::style('vendor/core/core/base/libraries/font-awesome/css/fontawesome.min.css?v=' . ($assetsVersion = EcommerceHelper::getAssetVersion())) !!}
    {!! Html::style('vendor/core/core/base/libraries/ckeditor/content-styles.css?v=' . $assetsVersion) !!}
    @if (BaseHelper::isRtlEnabled())
        {!! Html::style('vendor/core/plugins/ecommerce/libraries/bootstrap/bootstrap.rtl.min.css?v=' . $assetsVersion) !!}
    @else
        {!! Html::style('vendor/core/plugins/ecommerce/libraries/bootstrap/bootstrap.min.css?v=' . $assetsVersion) !!}
    @endif

    {!! Html::style('vendor/core/plugins/ecommerce/css/front-theme.css?v=' . $assetsVersion) !!}

    @if (BaseHelper::isRtlEnabled())
        {!! Html::style('vendor/core/plugins/ecommerce/css/front-theme-rtl.css?v=' . $assetsVersion) !!}
    @endif

    {!! Html::style('vendor/core/core/base/libraries/toastr/toastr.min.css?v=' . $assetsVersion) !!}

    {!! Html::script('vendor/core/plugins/ecommerce/js/checkout.js?v=' . $assetsVersion) !!}

    @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
        <link
            href="{{ asset('vendor/core/core/base/libraries/select2/css/select2.min.css?v=' . $assetsVersion) }}"
            rel="stylesheet"
        >
        <script src="{{ asset('vendor/core/core/base/libraries/select2/js/select2.min.js?v=' . $assetsVersion) }}"></script>
        <script src="{{ asset('vendor/core/plugins/location/js/location.js?v=' . $assetsVersion) }}"></script>
    @endif

    {!! apply_filters('ecommerce_checkout_header', null) !!}

    @stack('header')
</head>

@php
    Theme::addBodyAttributes([
        'class' => 'checkout-page',
    ]);
@endphp

<body{!! Theme::bodyAttributes() !!}>
    {!! apply_filters('ecommerce_checkout_body', null) !!}
    <div class="container my-0 my-md-3 my-lg-5 checkout-content-wrap">
        @yield('content')
    </div>

    @stack('footer')

    {!! Html::script('vendor/core/plugins/ecommerce/js/utilities.js?v=' . $assetsVersion) !!}
    {!! Html::script('vendor/core/core/base/libraries/toastr/toastr.min.js?v=' . $assetsVersion) !!}

    <script type="text/javascript">
        window.messages = {
            error_header: '{{ __('Error') }}',
            success_header: '{{ __('Success') }}',
        }
    </script>

    @if (session()->has('success_msg') || session()->has('error_msg') || isset($errors))
        <script type="text/javascript">
            $(document).ready(function() {
                @if (session()->has('success_msg') && session('success_msg'))
                    MainCheckout.showNotice('success', '{{ session('success_msg') }}');
                @endif
                @if (session()->has('error_msg'))
                    MainCheckout.showNotice('error', '{{ session('error_msg') }}');
                @endif
                @if (isset($errors) && $errors->count())
                    MainCheckout.showNotice('error', '{{ $errors->first() }}');
                @endif
            });
        </script>
    @endif

    {!! apply_filters('ecommerce_checkout_footer', null) !!}

    <script>
        // Initialize floating labels for checkout forms
        document.addEventListener('DOMContentLoaded', function() {
            // Add placeholder=" " to all inputs in form-input-wrapper to enable :placeholder-shown
            document.querySelectorAll('.form-input-wrapper input.form-control').forEach(function(input) {
                if (!input.hasAttribute('placeholder')) {
                    input.setAttribute('placeholder', ' ');
                }
                
                // Check if input has value on load (for autofilled or pre-filled fields)
                if (input.value && input.value.trim() !== '') {
                    input.classList.add('has-value');
                }
                
                // Add/remove class on input change
                input.addEventListener('input', function() {
                    if (this.value && this.value.trim() !== '') {
                        this.classList.add('has-value');
                    } else {
                        this.classList.remove('has-value');
                    }
                });
                
                // Handle autofill
                input.addEventListener('change', function() {
                    if (this.value && this.value.trim() !== '') {
                        this.classList.add('has-value');
                    } else {
                        this.classList.remove('has-value');
                    }
                });
            });
            
            // Handle select elements - they should always have floating labels
            document.querySelectorAll('.form-input-wrapper select.form-control').forEach(function(select) {
                select.classList.add('has-value');
            });
        });
    </script>

</body>
</html>
