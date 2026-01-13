<script>
    window.addEventListener('load', function() {
        if (typeof fbq !== 'function') {
            return;
        }

        var trackedEvents = window.fbTrackedEvents || {};
        
        function isEventTracked(eventName, productId) {
            var key = eventName + '_' + (productId || 'global');
            var now = Date.now();
            if (trackedEvents[key] && (now - trackedEvents[key]) < 2000) {
                return true;
            }
            trackedEvents[key] = now;
            return false;
        }

        function formatProductData(element) {
            var productId = element.data('product-id') || element.data('id');
            var productName = element.data('product-name') || element.data('name') || '';
            var productPrice = parseFloat(element.data('product-price') || element.data('price')) || 0;

            return {
                content_ids: [String(productId)],
                content_name: productName,
                content_type: 'product',
                value: productPrice,
                currency: '{{ get_application_currency()->title }}'
            };
        }

        $(document).on('click', '[data-bb-toggle="add-to-cart-in-form"]', function (e) {
            var currentTarget = $(e.currentTarget);
            var form = currentTarget.closest('form');
            var productId = currentTarget.data('product-id') || currentTarget.data('id');
            
            if (!isEventTracked('AddToCart', productId)) {
                var productName = currentTarget.data('product-name') || currentTarget.data('name') || '';
                var price = parseFloat(currentTarget.data('product-price') || currentTarget.data('price')) || 0;
                var quantity = parseInt(form.find('input[name="qty"]').val()) || 1;

                fbq('track', 'AddToCart', {
                    content_ids: [String(productId)],
                    content_name: productName,
                    content_type: 'product',
                    contents: [{
                        id: String(productId),
                        quantity: quantity
                    }],
                    value: price * quantity,
                    currency: '{{ get_application_currency()->title }}'
                });
            }
        });

        $(document).on('click', '[data-bb-toggle="add-to-cart"], .add-to-cart-button', function (e) {
            var currentTarget = $(e.currentTarget);
            var productId = currentTarget.data('product-id') || currentTarget.data('id');
            
            if (!isEventTracked('AddToCart', productId)) {
                var productName = currentTarget.data('product-name') || currentTarget.data('name') || '';
                var productPrice = parseFloat(currentTarget.data('product-price') || currentTarget.data('price')) || 0;
                var quantity = parseInt(currentTarget.data('quantity')) || 1;

                if (currentTarget.hasClass('product-image-link') || currentTarget.parent().hasClass('product-image')) {
                    fbq('track', 'AddToCart', {
                        content_ids: [String(productId)],
                        content_name: productName,
                        content_type: 'product',
                        contents: [{
                            id: String(productId),
                            quantity: quantity
                        }],
                        value: productPrice * quantity,
                        currency: '{{ get_application_currency()->title }}'
                    });
                } else if (!currentTarget.hasClass('newsletter-submit') && !currentTarget.hasClass('subscribe-button')) {
                    fbq('track', 'AddToCart', {
                        content_ids: [String(productId)],
                        content_name: productName,
                        content_type: 'product',
                        contents: [{
                            id: String(productId),
                            quantity: quantity
                        }],
                        value: productPrice * quantity,
                        currency: '{{ get_application_currency()->title }}'
                    });
                }
            }
        });

        $(document).on('click', '[data-bb-toggle="remove-from-cart"]', function (e) {
            var currentTarget = $(e.currentTarget);
            var productRow = currentTarget.closest('tr');
            var productId = currentTarget.data('product-id') || currentTarget.data('id') || '';
            var productName = productRow.find('.product-name').text() || '';
            var productPrice = parseFloat(productRow.find('.product-price').data('price')) || 0;
            var quantity = parseInt(productRow.find('input[name="qty"]').val()) || 1;

            fbq('trackCustom', 'RemoveFromCart', {
                content_ids: [String(productId)],
                content_name: productName,
                content_type: 'product',
                contents: [{
                    id: String(productId),
                    quantity: quantity
                }],
                value: productPrice * quantity,
                currency: '{{ get_application_currency()->title }}'
            });
        });

        $(document).on('click', '[data-bb-toggle="add-to-wishlist"]', function (e) {
            var currentTarget = $(e.currentTarget);
            var productData = formatProductData(currentTarget);

            fbq('track', 'AddToWishlist', productData);
        });

        $(document).on('click', '[data-bb-toggle="add-to-compare"]', function (e) {
            var currentTarget = $(e.currentTarget);
            var productId = currentTarget.data('product-id') || currentTarget.data('id');
            var productName = currentTarget.data('product-name') || currentTarget.data('name') || '';
            var productPrice = parseFloat(currentTarget.data('product-price') || currentTarget.data('price')) || 0;

            fbq('trackCustom', 'AddToCompare', {
                content_ids: [String(productId)],
                content_name: productName,
                content_type: 'product',
                value: productPrice,
                currency: '{{ get_application_currency()->title }}'
            });
        });

        $(document).on('click', '[data-bb-toggle="product-link"], .product-item a:not([data-bb-toggle]), .product-card a:not([data-bb-toggle])', function (e) {
            var currentTarget = $(e.currentTarget);
            if (currentTarget.data('bb-toggle')) {
                return;
            }

            var productElement = currentTarget.closest('[data-product-id]').length
                ? currentTarget.closest('[data-product-id]')
                : currentTarget;

            var productId = productElement.data('product-id') || productElement.data('id');
            if (!productId) {
                return;
            }

            var productData = formatProductData(productElement);
            fbq('track', 'ViewContent', productData);
        });

        $(document).on('submit', 'form.products-filter-form, form[name="search-form"], form.bb-form-quick-search', function(e) {
            var form = $(this);
            var searchQuery = form.find('input[name="q"], input[name="search"], input[type="search"]').val();

            if (searchQuery) {
                fbq('track', 'Search', {
                    search_string: searchQuery,
                    content_type: 'product'
                });
            }
        });

        $(document).on('submit', 'form.generic-form[action*="newsletter"], form.newsletter-form, .form--subscribe', function(e) {
            var form = $(this);
            var email = form.find('input[type="email"]').val();

            if (email) {
                fbq('track', 'CompleteRegistration', {
                    content_name: 'Newsletter Subscription',
                    status: true,
                    value: 0,
                    currency: '{{ get_application_currency()->title }}'
                });
            }
        });

        $(document).on('submit', 'form.contact-form', function(e) {
            fbq('track', 'Lead', {
                content_name: 'Contact Form Submission',
                content_category: 'Lead Generation'
            });
        });

        if (window.location.pathname.includes('/cart')) {
            if (!isEventTracked('ViewCart', 'page')) {
                fbq('track', 'ViewContent', {
                    content_type: 'product_group',
                    content_name: 'Shopping Cart'
                });
            }
        }

        $(document).on('ecommerce.category.viewed', function(e, categoryData) {
            if (categoryData && categoryData.name) {
                fbq('track', 'ViewCategory', {
                    content_category: categoryData.name,
                    content_type: 'product_group'
                });
            }
        });

        $(document).on('ecommerce.payment.selected', function(e, paymentData) {
            if (paymentData && paymentData.value) {
                fbq('track', 'AddPaymentInfo', {
                    value: paymentData.value,
                    currency: '{{ get_application_currency()->title }}',
                    payment_type: paymentData.method || ''
                });
            }
        });

        document.addEventListener('ecommerce.cart.added', function(e) {
            var detail = e.detail;
            if (detail && detail.data && detail.data.product_id) {
                trackedEvents['AddToCart_' + detail.data.product_id] = Date.now();
            }
        });

        document.addEventListener('ecommerce.cart.removed', function(e) {
            var detail = e.detail;
            if (detail && detail.data) {
                fbq('trackCustom', 'RemoveFromCart', {
                    content_type: 'product',
                    currency: '{{ get_application_currency()->title }}'
                });
            }
        });

        @if(get_ecommerce_setting('facebook_pixel_debug_mode'))
            console.log('%c Facebook Pixel Debug Mode Active ', 'background: #1877F2; color: white; padding: 2px 5px; border-radius: 3px;');

            var originalFbq = window.fbq;
            window.fbq = function() {
                if (arguments[0] === 'track' || arguments[0] === 'trackCustom') {
                    var eventName = arguments[1];
                    var eventData = arguments[2] || {};
                    var source = 'Client-Side';
                    
                    if (window.fbqServerEvents && window.fbqServerEvents[eventName]) {
                        source = 'Server-Side';
                        delete window.fbqServerEvents[eventName];
                    }
                    
                    console.log('%c FB Pixel Event [' + source + '] ', 'background: #42b883; color: white; padding: 2px 5px; border-radius: 3px;', eventName, eventData);
                }
                return originalFbq.apply(this, arguments);
            };
        @endif
    });
    
    window.fbqServerEvents = window.fbqServerEvents || {};
    @if($__pixelEvents ?? false)
        @foreach($__pixelEvents as $event => $data)
            window.fbqServerEvents['{{ $event }}'] = true;
        @endforeach
    @endif
</script>