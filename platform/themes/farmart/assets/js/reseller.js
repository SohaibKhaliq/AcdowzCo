(function($) {
    'use strict';

    // Buy Now functionality
    $(document).on('click', '.add-to-cart-button[name="checkout"]', function(e) {
        e.preventDefault();
        const $button = $(this);
        const productId = $('input[name="id"]').val();
        const quantity = $('input[name="qty"]').val() || 1;
        const attributes = {};
        
        $('select[name^="attribute"]').each(function() {
            const name = $(this).attr('name');
            const value = $(this).val();
            if (value) {
                attributes[name] = value;
            }
        });

        $.ajax({
            url: '/buy-now/' + productId,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                quantity: quantity,
                attributes: attributes
            },
            beforeSend: function() {
                $button.prop('disabled', true).addClass('loading');
            },
            success: function(response) {
                if (response.next_url) {
                    window.location.href = response.next_url;
                } else if (response.error) {
                    alert(response.message);
                    $button.prop('disabled', false).removeClass('loading');
                }
            },
            error: function() {
                alert('Error processing Buy Now request');
                $button.prop('disabled', false).removeClass('loading');
            }
        });
    });

    // Reseller link generator
    window.generateResellerLink = function(productId) {
        $.ajax({
            url: '/customer/reseller/generate-link/' + (productId || ''),
            method: 'GET',
            success: function(response) {
                if (response.data && response.data.link) {
                    copyToClipboard(response.data.link);
                    showNotification('Reseller link copied to clipboard!', 'success');
                }
            },
            error: function() {
                showNotification('Error generating reseller link', 'error');
            }
        });
    };

    // Copy to clipboard helper
    function copyToClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text);
        } else {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        }
    }

    // Notification helper
    function showNotification(message, type) {
        if (typeof Botble !== 'undefined' && Botble.showNotice) {
            Botble.showNotice(type, message);
        } else if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            alert(message);
        }
    }

})(jQuery);
