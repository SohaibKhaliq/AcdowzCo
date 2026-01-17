{{-- Enhanced Buy Now Button Component --}}
@php
    $product = $product ?? null;
    $showQuantitySelector = $showQuantitySelector ?? true;
    $buttonSize = $buttonSize ?? 'normal'; // normal, small, large
    $buttonClass = match ($buttonSize) {
        'small' => 'px-4 py-2 text-sm',
        'large' => 'px-8 py-4 text-lg',
        default => 'px-6 py-3 text-base',
    };
@endphp

<div class="buy-now-component" data-product-id="{{ $product->id ?? '' }}">
    @if ($showQuantitySelector)
        <div class="flex items-center space-x-4 mb-4">
            <label for="quantity-{{ $product->id ?? 'default' }}"
                class="text-sm font-medium text-gray-700">Quantity:</label>
            <div class="flex items-center border border-gray-300 rounded-md">
                <button type="button" class="quantity-btn decrease p-2 text-gray-500 hover:text-gray-700"
                    data-action="decrease">
                    <i class="fas fa-minus text-xs"></i>
                </button>
                <input type="number" id="quantity-{{ $product->id ?? 'default' }}" name="quantity" value="1"
                    min="1" max="{{ $product->quantity ?? 999 }}"
                    class="quantity-input w-16 text-center border-0 focus:ring-0 py-2" readonly>
                <button type="button" class="quantity-btn increase p-2 text-gray-500 hover:text-gray-700"
                    data-action="increase">
                    <i class="fas fa-plus text-xs"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Add to Cart Button -->
        <button type="button"
            class="add-to-cart-btn {{ $buttonClass }} bg-gray-100 text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center"
            data-product-id="{{ $product->id ?? '' }}">
            <i class="fas fa-shopping-cart mr-2"></i>
            <span class="button-text">Add to Cart</span>
        </button>

        <!-- Buy Now Button -->
        <button type="button"
            class="buy-now-btn {{ $buttonClass }} bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-lg hover:from-orange-600 hover:to-red-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center shadow-lg"
            data-product-id="{{ $product->id ?? '' }}">
            <i class="fas fa-bolt mr-2"></i>
            <span class="button-text">Buy Now</span>
        </button>
    </div>

    <!-- Buy Now Modal -->
    <div id="buy-now-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-bolt text-orange-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Quick Checkout
                        </h3>
                        <div class="mt-4">
                            <!-- Authentication Options -->
                            <div class="space-y-4">
                                <!-- Guest Checkout -->
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-3">Guest Checkout</h4>
                                    <form id="guest-checkout-form" class="space-y-3">
                                        <div>
                                            <input type="email" id="guest-email" name="guest_email"
                                                placeholder="Your email address"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                                required>
                                        </div>
                                        <div>
                                            <input type="text" id="guest-name" name="name"
                                                placeholder="Your full name"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                        </div>
                                        <button type="submit"
                                            class="w-full bg-orange-500 text-white py-2 px-4 rounded-md hover:bg-orange-600 transition-colors">
                                            <i class="fas fa-shopping-bag mr-2"></i>
                                            Continue as Guest
                                        </button>
                                    </form>
                                </div>

                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-2 bg-white text-gray-500">or</span>
                                    </div>
                                </div>

                                <!-- Phone Login -->
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 mb-3">Phone Login</h4>
                                    <div id="phone-login-step">
                                        <form id="phone-login-form" class="space-y-3">
                                            <div>
                                                <input type="tel" id="phone-number" name="phone"
                                                    placeholder="+1 (555) 123-4567"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    required>
                                            </div>
                                            <button type="submit"
                                                class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors">
                                                <i class="fas fa-mobile-alt mr-2"></i>
                                                Send OTP Code
                                            </button>
                                        </form>
                                    </div>

                                    <div id="phone-verify-step" class="hidden">
                                        <form id="phone-otp-verify-form" class="space-y-3">
                                            <div>
                                                <input type="text" id="otp-code-modal" name="code" maxlength="6"
                                                    placeholder="Enter 6-digit code"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-xl tracking-widest"
                                                    required>
                                            </div>
                                            <button type="submit"
                                                class="w-full bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition-colors">
                                                <i class="fas fa-check mr-2"></i>
                                                Verify & Checkout
                                            </button>
                                            <button type="button" id="back-to-phone"
                                                class="w-full text-blue-600 hover:text-blue-800 text-sm">
                                                ← Change phone number
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if (route_exists('auth.google'))
                                    <div class="border rounded-lg p-4">
                                        <a href="{{ route('auth.google') }}?redirect=buy-now&product_id={{ $product->id ?? '' }}"
                                            class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
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
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" id="close-modal"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('footer')
    <style>
        .buy-now-component .quantity-input::-webkit-outer-spin-button,
        .buy-now-component .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .buy-now-component .quantity-input[type=number] {
            -moz-appearance: textfield;
        }

        .buy-now-btn {
            position: relative;
            overflow: hidden;
        }

        .buy-now-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .buy-now-btn:hover:before {
            left: 100%;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const component = document.querySelector('.buy-now-component');
            if (!component) return;

            const productId = component.dataset.productId;
            const quantityInput = component.querySelector('.quantity-input');
            const addToCartBtn = component.querySelector('.add-to-cart-btn');
            const buyNowBtn = component.querySelector('.buy-now-btn');
            const modal = document.getElementById('buy-now-modal');

            // Quantity controls
            component.querySelectorAll('.quantity-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const action = this.dataset.action;
                    const currentValue = parseInt(quantityInput.value);
                    const maxValue = parseInt(quantityInput.max);

                    if (action === 'decrease' && currentValue > 1) {
                        quantityInput.value = currentValue - 1;
                    } else if (action === 'increase' && currentValue < maxValue) {
                        quantityInput.value = currentValue + 1;
                    }
                });
            });

            // Add to Cart functionality
            if (addToCartBtn) {
                addToCartBtn.addEventListener('click', async function() {
                    const quantity = quantityInput ? quantityInput.value : 1;
                    const buttonText = this.querySelector('.button-text');
                    const originalText = buttonText.textContent;

                    this.disabled = true;
                    buttonText.textContent = 'Adding...';

                    try {
                        const response = await fetch('{{ route('public.cart.add-to-cart') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                id: productId,
                                qty: quantity
                            })
                        });

                        const data = await response.json();

                        if (data.error === false) {
                            buttonText.textContent = 'Added!';
                            setTimeout(() => {
                                buttonText.textContent = originalText;
                            }, 2000);

                            // Update cart counter if exists
                            const cartCounter = document.querySelector('.cart-counter');
                            if (cartCounter && data.data && data.data.count) {
                                cartCounter.textContent = data.data.count;
                            }
                        } else {
                            throw new Error(data.message || 'Failed to add to cart');
                        }
                    } catch (error) {
                        alert(error.message);
                        buttonText.textContent = originalText;
                    } finally {
                        this.disabled = false;
                    }
                });
            }

            // Buy Now functionality
            if (buyNowBtn) {
                buyNowBtn.addEventListener('click', function() {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }

            // Modal close functionality
            const closeModal = () => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                // Reset forms
                document.querySelectorAll('#buy-now-modal form').forEach(form => form.reset());
                document.getElementById('phone-login-step').classList.remove('hidden');
                document.getElementById('phone-verify-step').classList.add('hidden');
            };

            document.getElementById('close-modal').addEventListener('click', closeModal);
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });

            // Guest checkout form
            document.getElementById('guest-checkout-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const quantity = quantityInput ? quantityInput.value : 1;

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

                try {
                    const response = await fetch('{{ route('api.ecommerce.buy-now') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity,
                            guest_email: formData.get('guest_email'),
                            name: formData.get('name')
                        })
                    });

                    const data = await response.json();

                    if (data.success !== false && data.data && data.data.payment_url) {
                        window.location.href = data.data.payment_url;
                    } else {
                        throw new Error(data.message || 'Checkout failed');
                    }
                } catch (error) {
                    alert(error.message);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });

            // Phone login forms
            const phoneLoginForm = document.getElementById('phone-login-form');
            const phoneVerifyForm = document.getElementById('phone-otp-verify-form');
            let currentPhone = '';

            phoneLoginForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const phone = document.getElementById('phone-number').value;
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;

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
                        currentPhone = phone;
                        document.getElementById('phone-login-step').classList.add('hidden');
                        document.getElementById('phone-verify-step').classList.remove('hidden');
                    } else {
                        throw new Error(data.message || 'Failed to send OTP');
                    }
                } catch (error) {
                    alert(error.message);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });

            phoneVerifyForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const code = document.getElementById('otp-code-modal').value;
                const quantity = quantityInput ? quantityInput.value : 1;
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Verifying...';

                try {
                    // First verify OTP
                    const verifyResponse = await fetch(
                    '{{ route('api.ecommerce.verify-phone-otp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            phone: currentPhone,
                            code
                        })
                    });

                    const verifyData = await verifyResponse.json();

                    if (verifyData.success) {
                        // Now proceed with buy now
                        const buyResponse = await fetch('{{ route('api.ecommerce.buy-now') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Authorization': 'Bearer ' + verifyData.data.token
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                quantity: quantity,
                                phone: currentPhone
                            })
                        });

                        const buyData = await buyResponse.json();

                        if (buyData.success !== false && buyData.data && buyData.data.payment_url) {
                            // Store token for future use
                            localStorage.setItem('auth_token', verifyData.data.token);
                            window.location.href = buyData.data.payment_url;
                        } else {
                            throw new Error(buyData.message || 'Checkout failed');
                        }
                    } else {
                        throw new Error(verifyData.message || 'Invalid verification code');
                    }
                } catch (error) {
                    alert(error.message);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });

            document.getElementById('back-to-phone').addEventListener('click', function() {
                document.getElementById('phone-login-step').classList.remove('hidden');
                document.getElementById('phone-verify-step').classList.add('hidden');
            });

            // Auto-format OTP input
            const otpInput = document.getElementById('otp-code-modal');
            otpInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length === 6) {
                    phoneVerifyForm.dispatchEvent(new Event('submit'));
                }
            });
        });
    </script>
@endpush
