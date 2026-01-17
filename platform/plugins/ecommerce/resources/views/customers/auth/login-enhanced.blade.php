@extends('plugins/ecommerce::customers.master')

@section('title', __('Enhanced Login'))

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div
                class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-sky-500 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl">
            </div>
            <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">
                <div class="max-w-md mx-auto">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900">Welcome Back</h1>
                        <p class="text-gray-600 mt-2">Sign in to your account</p>
                    </div>

                    <!-- Enhanced Auth Tabs -->
                    <div class="mb-6">
                        <nav class="flex space-x-4" aria-label="Tabs">
                            <button type="button" id="email-tab"
                                class="auth-tab active px-3 py-2 font-medium text-sm rounded-md bg-indigo-100 text-indigo-700">
                                <i class="fas fa-envelope mr-2"></i>Email
                            </button>
                            <button type="button" id="phone-tab"
                                class="auth-tab px-3 py-2 font-medium text-sm rounded-md text-gray-500 hover:text-gray-700">
                                <i class="fas fa-phone mr-2"></i>Phone
                            </button>
                            <button type="button" id="oauth-tab"
                                class="auth-tab px-3 py-2 font-medium text-sm rounded-md text-gray-500 hover:text-gray-700">
                                <i class="fab fa-google mr-2"></i>Social
                            </button>
                        </nav>
                    </div>

                    <!-- Email Login Form -->
                    <div id="email-form" class="auth-form active">
                        {!! Form::open(['route' => 'customer.login.post', 'method' => 'POST', 'class' => 'space-y-6']) !!}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                {!! Form::email('email', old('email'), [
                                    'class' =>
                                        'block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                                    'placeholder' => 'Enter your email',
                                    'required' => true,
                                ]) !!}
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                {!! Form::password('password', [
                                    'class' =>
                                        'block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                                    'placeholder' => 'Enter your password',
                                    'required' => true,
                                ]) !!}
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember" name="remember" type="checkbox"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
                            </div>
                            <div class="text-sm">
                                <a href="{{ route('customer.password.reset') }}"
                                    class="font-medium text-indigo-600 hover:text-indigo-500">
                                    Forgot password?
                                </a>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Sign In
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    <!-- Phone OTP Form -->
                    <div id="phone-form" class="auth-form hidden">
                        <div id="phone-send-step">
                            <form id="phone-otp-form" class="space-y-6">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone
                                        Number</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-phone text-gray-400"></i>
                                        </div>
                                        <input type="tel" id="phone" name="phone"
                                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                            placeholder="+1 234 567 8900" required>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">We'll send you a 6-digit verification code</p>
                                </div>

                                <button type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Send OTP Code
                                </button>
                            </form>
                        </div>

                        <div id="phone-verify-step" class="hidden">
                            <form id="phone-verify-form" class="space-y-6">
                                <div>
                                    <label for="otp-code" class="block text-sm font-medium text-gray-700">Verification
                                        Code</label>
                                    <div class="mt-1">
                                        <input type="text" id="otp-code" name="code" maxlength="6"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-center text-2xl tracking-widest"
                                            placeholder="000000" required>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Enter the 6-digit code sent to your phone</p>
                                </div>

                                <button type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                                    <i class="fas fa-check mr-2"></i>
                                    Verify & Login
                                </button>

                                <button type="button" id="resend-otp"
                                    class="w-full text-center py-2 px-4 text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                    Didn't receive code? Resend
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- OAuth Login Form -->
                    <div id="oauth-form" class="auth-form hidden">
                        <div class="space-y-4">
                            <a href="{{ route('auth.google') }}"
                                class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-200">
                                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                                    <path fill="#4285F4"
                                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                    <path fill="#34A853"
                                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                    <path fill="#FBBC05"
                                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                    <path fill="#EA4335"
                                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                </svg>
                                Continue with Google
                            </a>

                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-500">More options coming soon</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            Don't have an account?
                            <a href="{{ route('customer.register') }}"
                                class="font-medium text-indigo-600 hover:text-indigo-500">
                                Sign up here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('footer')
        <style>
            .auth-tab {
                transition: all 0.2s ease-in-out;
            }

            .auth-tab.active {
                background-color: rgb(165 180 252);
                color: rgb(55 48 163);
            }

            .auth-form {
                transition: opacity 0.3s ease-in-out;
            }

            .auth-form.hidden {
                display: none;
            }

            .auth-form.active {
                display: block;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tab switching functionality
                const tabs = document.querySelectorAll('.auth-tab');
                const forms = document.querySelectorAll('.auth-form');

                tabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        const targetForm = this.id.replace('-tab', '-form');

                        // Remove active class from all tabs and forms
                        tabs.forEach(t => t.classList.remove('active'));
                        forms.forEach(f => {
                            f.classList.remove('active');
                            f.classList.add('hidden');
                        });

                        // Add active class to clicked tab and corresponding form
                        this.classList.add('active');
                        const targetElement = document.getElementById(targetForm);
                        if (targetElement) {
                            targetElement.classList.remove('hidden');
                            targetElement.classList.add('active');
                        }
                    });
                });

                // Phone OTP functionality
                const phoneOtpForm = document.getElementById('phone-otp-form');
                const phoneVerifyForm = document.getElementById('phone-verify-form');
                const resendOtpBtn = document.getElementById('resend-otp');

                if (phoneOtpForm) {
                    phoneOtpForm.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        const phone = document.getElementById('phone').value;
                        const submitBtn = this.querySelector('button[type="submit"]');

                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';

                        try {
                            const response = await fetch('{{ route('api.ecommerce.send-phone-otp') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    phone
                                })
                            });

                            const data = await response.json();

                            if (data.success) {
                                document.getElementById('phone-send-step').classList.add('hidden');
                                document.getElementById('phone-verify-step').classList.remove('hidden');

                                // Store phone for verification
                                document.getElementById('phone-verify-form').dataset.phone = phone;
                            } else {
                                alert(data.message || 'Failed to send OTP');
                            }
                        } catch (error) {
                            alert('Network error. Please try again.');
                        } finally {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Send OTP Code';
                        }
                    });
                }

                if (phoneVerifyForm) {
                    phoneVerifyForm.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        const phone = this.dataset.phone;
                        const code = document.getElementById('otp-code').value;
                        const submitBtn = this.querySelector('button[type="submit"]');

                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Verifying...';

                        try {
                            const response = await fetch('{{ route('api.ecommerce.verify-phone-otp') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    phone,
                                    code
                                })
                            });

                            const data = await response.json();

                            if (data.success) {
                                // Store token and redirect
                                localStorage.setItem('auth_token', data.token);
                                window.location.href = '{{ route('customer.overview') }}';
                            } else {
                                alert(data.message || 'Invalid verification code');
                            }
                        } catch (error) {
                            alert('Network error. Please try again.');
                        } finally {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Verify & Login';
                        }
                    });
                }

                if (resendOtpBtn) {
                    resendOtpBtn.addEventListener('click', function() {
                        document.getElementById('phone-send-step').classList.remove('hidden');
                        document.getElementById('phone-verify-step').classList.add('hidden');
                    });
                }

                // OTP input formatting
                const otpInput = document.getElementById('otp-code');
                if (otpInput) {
                    otpInput.addEventListener('input', function(e) {
                        // Only allow numbers
                        this.value = this.value.replace(/[^0-9]/g, '');

                        // Auto-submit when 6 digits entered
                        if (this.value.length === 6) {
                            phoneVerifyForm.dispatchEvent(new Event('submit'));
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
