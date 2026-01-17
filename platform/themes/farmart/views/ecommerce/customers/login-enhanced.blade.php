@extends(EcommerceHelper::viewPath('customers.master'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <h4 class="mb-2">{{ __('Welcome Back') }}</h4>
                            <p class="text-muted">{{ __('Sign in to your account') }}</p>
                        </div>

                        <!-- Google OAuth Login -->
                        @if (get_ecommerce_setting('google_oauth_enabled', false))
                            <div class="d-grid gap-2 mb-4">
                                <a href="{{ route('customer.oauth.google') }}" class="btn btn-outline-danger btn-lg">
                                    <i class="fab fa-google me-2"></i>
                                    {{ __('Continue with Google') }}
                                </a>
                            </div>

                            <div class="text-center mb-4">
                                <span class="text-muted small">{{ __('or sign in with') }}</span>
                            </div>
                        @endif

                        <!-- Tabs for different login methods -->
                        <ul class="nav nav-pills nav-justified mb-4" id="loginTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="email-tab" data-bs-toggle="pill"
                                    data-bs-target="#email-login" type="button" role="tab">
                                    <i class="ti ti-mail me-1"></i>{{ __('Email') }}
                                </button>
                            </li>
                            @if (get_ecommerce_setting('enable_phone_verification', false))
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="phone-tab" data-bs-toggle="pill"
                                        data-bs-target="#phone-login" type="button" role="tab">
                                        <i class="ti ti-phone me-1"></i>{{ __('Phone') }}
                                    </button>
                                </li>
                            @endif
                        </ul>

                        <div class="tab-content" id="loginTabContent">
                            <!-- Email/Password Login -->
                            <div class="tab-pane fade show active" id="email-login" role="tabpanel">
                                <form method="POST" action="{{ route('customer.login') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label">{{ __('Email') }}</label>
                                        <input type="email"
                                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required
                                            autocomplete="email">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">{{ __('Password') }}</label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control form-control-lg @error('password') is-invalid @enderror"
                                                id="password" name="password" required>
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePassword('password')">
                                                <i class="ti ti-eye" id="password-icon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                                        </div>
                                        <a href="{{ route('customer.password.request') }}" class="text-decoration-none">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">{{ __('Sign In') }}</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Phone/OTP Login -->
                            @if (get_ecommerce_setting('enable_phone_verification', false))
                                <div class="tab-pane fade" id="phone-login" role="tabpanel">
                                    <div id="phone-step">
                                        <div class="mb-3">
                                            <label for="phone-number" class="form-label">{{ __('Phone Number') }}</label>
                                            <input type="tel" class="form-control form-control-lg" id="phone-number"
                                                placeholder="{{ __('Enter your phone number') }}" required>
                                            <div class="form-text">{{ __('We\'ll send you a verification code') }}</div>
                                        </div>

                                        <div class="d-grid">
                                            <button type="button" class="btn btn-primary btn-lg" id="send-otp-btn">
                                                <span class="spinner-border spinner-border-sm me-2 d-none"
                                                    id="otp-spinner"></span>
                                                {{ __('Send Verification Code') }}
                                            </button>
                                        </div>
                                    </div>

                                    <div id="otp-step" class="d-none">
                                        <div class="text-center mb-3">
                                            <i class="ti ti-message-circle text-primary" style="font-size: 3rem;"></i>
                                            <h6 class="mt-2">{{ __('Enter Verification Code') }}</h6>
                                            <p class="text-muted small" id="otp-sent-message"></p>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-center">
                                                <div class="otp-inputs">
                                                    @for ($i = 0; $i < 6; $i++)
                                                        <input type="text" class="otp-input" maxlength="1"
                                                            data-index="{{ $i }}">
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-primary btn-lg" id="verify-otp-btn">
                                                <span class="spinner-border spinner-border-sm me-2 d-none"
                                                    id="verify-spinner"></span>
                                                {{ __('Verify & Sign In') }}
                                            </button>

                                            <button type="button" class="btn btn-outline-secondary" id="resend-otp-btn">
                                                {{ __('Resend Code') }}
                                            </button>

                                            <button type="button" class="btn btn-link" onclick="resetPhoneLogin()">
                                                {{ __('Use Different Phone Number') }}
                                            </button>
                                        </div>

                                        <div class="text-center mt-3">
                                            <div id="otp-timer" class="small text-muted"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Register Link -->
                        <div class="text-center mt-4 pt-4 border-top">
                            <p class="mb-0">
                                {{ __("Don't have an account?") }}
                                <a href="{{ route('customer.register') }}"
                                    class="text-decoration-none">{{ __('Sign up') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('header')
    <style>
        .otp-inputs {
            display: flex;
            gap: 0.5rem;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 1.25rem;
            font-weight: bold;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            transition: border-color 0.2s;
        }

        .otp-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
            outline: none;
        }

        .otp-input.filled {
            border-color: #28a745;
            background-color: #f8f9fa;
        }
    </style>
@endpush

@push('footer')
    <script>
        $(document).ready(function() {
            // Password visibility toggle
            window.togglePassword = function(fieldId) {
                const field = document.getElementById(fieldId);
                const icon = document.getElementById(fieldId + '-icon');

                if (field.type === 'password') {
                    field.type = 'text';
                    icon.className = 'ti ti-eye-off';
                } else {
                    field.type = 'password';
                    icon.className = 'ti ti-eye';
                }
            };

            // Phone/OTP functionality
            @if (get_ecommerce_setting('enable_phone_verification', false))
                let otpTimer;

                // Send OTP
                $('#send-otp-btn').click(function() {
                    const phone = $('#phone-number').val().trim();

                    if (!phone) {
                        alert('{{ __('Please enter your phone number') }}');
                        return;
                    }

                    const $btn = $(this);
                    const $spinner = $('#otp-spinner');

                    $btn.prop('disabled', true);
                    $spinner.removeClass('d-none');

                    $.post('{{ route('customer.otp.send') }}', {
                            _token: '{{ csrf_token() }}',
                            phone: phone
                        })
                        .done(function(response) {
                            if (response.error) {
                                alert(response.message);
                            } else {
                                $('#phone-step').addClass('d-none');
                                $('#otp-step').removeClass('d-none');
                                $('#otp-sent-message').text(response.message);
                                startOtpTimer();
                                $('.otp-input').first().focus();
                            }
                        })
                        .fail(function() {
                            alert('{{ __('An error occurred. Please try again.') }}');
                        })
                        .always(function() {
                            $btn.prop('disabled', false);
                            $spinner.addClass('d-none');
                        });
                });

                // OTP input handling
                $('.otp-input').on('input', function() {
                    const $this = $(this);
                    const index = parseInt($this.data('index'));
                    const value = $this.val();

                    // Only allow digits
                    if (!/^\d*$/.test(value)) {
                        $this.val('');
                        return;
                    }

                    $this.toggleClass('filled', value !== '');

                    // Move to next input
                    if (value && index < 5) {
                        $('.otp-input').eq(index + 1).focus();
                    }

                    // Auto-verify when all inputs are filled
                    if ($('.otp-input').toArray().every(input => input.value)) {
                        setTimeout(verifyOtp, 500);
                    }
                });

                // Handle backspace
                $('.otp-input').on('keydown', function(e) {
                    const $this = $(this);
                    const index = parseInt($this.data('index'));

                    if (e.key === 'Backspace' && !$this.val() && index > 0) {
                        $('.otp-input').eq(index - 1).focus();
                    }
                });

                // Verify OTP
                function verifyOtp() {
                    const otp = $('.otp-input').map(function() {
                        return $(this).val();
                    }).get().join('');
                    const phone = $('#phone-number').val();

                    if (otp.length !== 6) {
                        alert('{{ __('Please enter the complete verification code') }}');
                        return;
                    }

                    const $btn = $('#verify-otp-btn');
                    const $spinner = $('#verify-spinner');

                    $btn.prop('disabled', true);
                    $spinner.removeClass('d-none');

                    $.post('{{ route('customer.otp.verify') }}', {
                            _token: '{{ csrf_token() }}',
                            phone: phone,
                            otp: otp
                        })
                        .done(function(response) {
                            if (response.error) {
                                alert(response.message);
                                $('.otp-input').val('').removeClass('filled').first().focus();
                            } else {
                                window.location.href = response.data.next_url;
                            }
                        })
                        .fail(function() {
                            alert('{{ __('Verification failed. Please try again.') }}');
                            $('.otp-input').val('').removeClass('filled').first().focus();
                        })
                        .always(function() {
                            $btn.prop('disabled', false);
                            $spinner.addClass('d-none');
                        });
                }

                $('#verify-otp-btn').click(verifyOtp);

                // Resend OTP
                $('#resend-otp-btn').click(function() {
                    const phone = $('#phone-number').val();

                    $.post('{{ route('customer.otp.resend') }}', {
                            _token: '{{ csrf_token() }}',
                            phone: phone
                        })
                        .done(function(response) {
                            alert(response.message);
                            if (!response.error) {
                                startOtpTimer();
                                $('.otp-input').val('').removeClass('filled').first().focus();
                            }
                        });
                });

                function startOtpTimer() {
                    let timeLeft = {{ get_ecommerce_setting('otp_expiry_minutes', 5) * 60 }};

                    otpTimer = setInterval(function() {
                        const minutes = Math.floor(timeLeft / 60);
                        const seconds = timeLeft % 60;

                        $('#otp-timer').text(
                            `{{ __('Code expires in') }} ${minutes}:${seconds.toString().padStart(2, '0')}`
                            );

                        if (timeLeft <= 0) {
                            clearInterval(otpTimer);
                            $('#otp-timer').text('{{ __('Code expired. Please request a new one.') }}');
                        }

                        timeLeft--;
                    }, 1000);
                }

                window.resetPhoneLogin = function() {
                    $('#otp-step').addClass('d-none');
                    $('#phone-step').removeClass('d-none');
                    $('.otp-input').val('').removeClass('filled');
                    $('#phone-number').val('').focus();
                    clearInterval(otpTimer);
                };
            @endif
        });
    </script>
@endpush
