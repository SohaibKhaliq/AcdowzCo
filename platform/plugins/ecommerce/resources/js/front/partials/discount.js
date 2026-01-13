import sanitizeHTML from 'sanitize-html'

export class DiscountManagement {
    init() {
        $(document).on('click', '.btn-open-coupon-form', (event) => {
            event.preventDefault()
            $(document).find('.coupon-wrapper').toggle()
        })

        $('.coupon-wrapper .coupon-code').keypress((event) => {
            if (event.keyCode === 13) {
                $('.apply-coupon-code').trigger('click')
                event.preventDefault()
                event.stopPropagation()
                return false
            }
        })

        // Define targets for both desktop and mobile layouts
        let target = '.checkout-order-info'
        let mobileTarget = '.cart-item-wrapper'
        let couponWrapperTarget = '.coupon-wrapper.mt-2'
        let couponItemTarget = '.checkout__coupon-item'
        let couponSectionTarget = '.checkout__coupon-section'

        $(document).on('click', '.apply-coupon-code', (event) => {
            event.preventDefault()

            const currentTarget = $(event.currentTarget)

            $.ajax({
                url: currentTarget.data('url'),
                type: 'POST',
                data: {
                    coupon_code: currentTarget.closest('.coupon-wrapper').find('.coupon-code').val(),
                    token: $('#checkout-token').val(),
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                beforeSend: () => {
                    currentTarget.find('i').remove()
                    currentTarget.html(`<i class="fa fa-spin fa-spinner"></i> ${currentTarget.html()}`)
                },
                success: ({ error, message }) => {
                    if (!error) {
                        // Use a more reliable approach for both desktop and mobile
                        $.ajax({
                            url: window.location.href + '?applied_coupon=1',
                            type: 'GET',
                            success: (response) => {
                                // Extract the target content from the response
                                const tempDiv = document.createElement('div');
                                tempDiv.innerHTML = response;

                                // Update desktop layout if it exists
                                const desktopContent = $(tempDiv).find(target);
                                if (desktopContent.length && $(target).length) {
                                    $(target).html(desktopContent.html());
                                }

                                // Update mobile layout
                                const mobileContent = $(tempDiv).find(mobileTarget);
                                if (mobileContent.length && $(mobileTarget).length) {
                                    $(mobileTarget).html(mobileContent.html());
                                }

                                // Update coupon wrapper section for mobile
                                const couponWrapperContent = $(tempDiv).find(couponWrapperTarget);
                                if (couponWrapperContent.length && $(couponWrapperTarget).length) {
                                    $(couponWrapperTarget).html(couponWrapperContent.html());
                                } else if (couponWrapperContent.length) {
                                    // If coupon wrapper doesn't exist yet, append it after the cart-item-wrapper
                                    $(mobileTarget).after(couponWrapperContent);
                                }

                                // Update coupon items
                                const couponItemsContent = $(tempDiv).find(couponItemTarget);
                                if (couponItemsContent.length) {
                                    // Update each coupon item or the entire section
                                    const couponSection = $(tempDiv).find(couponSectionTarget);
                                    if (couponSection.length && $(couponSectionTarget).length) {
                                        $(couponSectionTarget).html(couponSection.html());
                                    } else {
                                        // Update individual coupon items
                                        $(couponItemTarget).each(function(index) {
                                            if (couponItemsContent[index]) {
                                                $(this).replaceWith(couponItemsContent[index]);
                                            }
                                        });
                                    }
                                }

                                // If nothing was updated, reload the page
                                if ((!desktopContent.length && !mobileContent.length && !couponWrapperContent.length) ||
                                    ($(target).length === 0 && $(mobileTarget).length === 0)) {
                                    window.location.reload();
                                }

                                // Dispatch event for coupon applied
                                document.dispatchEvent(new CustomEvent('coupon:applied'));

                                currentTarget.find('i').remove();
                            },
                            error: () => {
                                // Fallback to page reload if AJAX extraction fails
                                window.location.reload();
                            }
                        });
                    } else {
                        $('.coupon-error-msg .text-danger').html(sanitizeHTML(message))
                        currentTarget.find('i').remove()
                    }

                    $('html, body').animate({
                        scrollTop: $('.coupon-wrapper').offset().top
                    });
                },
                error: (data) => {
                    if (typeof data.responseJSON !== 'undefined') {
                        if (data.responseJSON.errors !== 'undefined') {
                            $.each(data.responseJSON.errors, (index, el) => {
                                $.each(el, (key, item) => {
                                    $('.coupon-error-msg .text-danger').text(item)
                                })
                            })
                        } else if (typeof data.responseJSON.message !== 'undefined') {
                            $('.coupon-error-msg .text-danger').text(data.responseJSON.message)
                        }
                    } else {
                        $('.coupon-error-msg .text-danger').text(data.status.text)
                    }
                    currentTarget.find('i').remove()
                },
            })
        })

        $(document).on('click', '.remove-coupon-code', (event) => {
            event.preventDefault()
            let _self = $(event.currentTarget)
            _self.find('i').remove()
            _self.html('<i class="fa fa-spin fa-spinner"></i> ' + _self.html())

            $.ajax({
                url: _self.data('url'),
                type: 'POST',
                data: {
                    token: $('#checkout-token').val(),
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: (res) => {
                    if (!res.error) {
                        // Use a more reliable approach for both desktop and mobile
                        $.ajax({
                            url: window.location.href,
                            type: 'GET',
                            success: (response) => {
                                // Extract the target content from the response
                                const tempDiv = document.createElement('div');
                                tempDiv.innerHTML = response;

                                // Update desktop layout if it exists
                                const desktopContent = $(tempDiv).find(target);
                                if (desktopContent.length && $(target).length) {
                                    $(target).html(desktopContent.html());
                                }

                                // Update mobile layout
                                const mobileContent = $(tempDiv).find(mobileTarget);
                                if (mobileContent.length && $(mobileTarget).length) {
                                    $(mobileTarget).html(mobileContent.html());
                                }

                                // Update or remove coupon wrapper section for mobile
                                const couponWrapperContent = $(tempDiv).find(couponWrapperTarget);
                                if (couponWrapperContent.length && $(couponWrapperTarget).length) {
                                    $(couponWrapperTarget).html(couponWrapperContent.html());
                                } else {
                                    // If coupon was removed, remove the wrapper
                                    $(couponWrapperTarget).remove();
                                }

                                // Update coupon items
                                const couponItemsContent = $(tempDiv).find(couponItemTarget);
                                if (couponItemsContent.length) {
                                    // Update each coupon item or the entire section
                                    const couponSection = $(tempDiv).find(couponSectionTarget);
                                    if (couponSection.length && $(couponSectionTarget).length) {
                                        $(couponSectionTarget).html(couponSection.html());
                                    } else {
                                        // Update individual coupon items
                                        $(couponItemTarget).each(function(index) {
                                            if (couponItemsContent[index]) {
                                                $(this).replaceWith(couponItemsContent[index]);
                                            }
                                        });
                                    }
                                }

                                // If nothing was updated, reload the page
                                if ((!desktopContent.length && !mobileContent.length) ||
                                    ($(target).length === 0 && $(mobileTarget).length === 0)) {
                                    window.location.reload();
                                }

                                // Dispatch event for coupon removed
                                document.dispatchEvent(new CustomEvent('coupon:removed'));

                                _self.find('i').remove();
                            },
                            error: () => {
                                // Fallback to page reload if AJAX extraction fails
                                window.location.reload();
                            }
                        });
                    } else {
                        $('.coupon-error-msg .text-danger').text(res.message)
                        _self.find('i').remove()
                    }
                },
                error: (data) => {
                    if (typeof data.responseJSON !== 'undefined') {
                        if (data.responseJSON.errors !== 'undefined') {
                            $.each(data.responseJSON.errors, (index, el) => {
                                $.each(el, (key, item) => {
                                    $('.coupon-error-msg .text-danger').text(item)
                                })
                            })
                        } else if (typeof data.responseJSON.message !== 'undefined') {
                            $('.coupon-error-msg .text-danger').text(data.responseJSON.message)
                        }
                    } else {
                        $('.coupon-error-msg .text-danger').text(data.status.text)
                    }
                    _self.find('i').remove()
                },
            })
        })


        $(document).on('click', '[data-bb-toggle="apply-coupon-code"]', async function (e) {
            e.preventDefault();

            const button = $(this);
            const discountCode = button.data('discount-code');
            const originalText = button.text();

            // Show loading state
            button.find('i').remove();
            button.html(`<i class="fa fa-spin fa-spinner"></i> ${originalText}`);

            try {
                // If there's an existing coupon, remove it first and wait for completion
                if ($(document).find('.remove-coupon-code').length) {
                    await new Promise((resolve, reject) => {
                        // Listen for the AJAX completion of coupon removal
                        const removeButton = $(document).find('.remove-coupon-code');

                        // Set up one-time event listeners for AJAX completion
                        $(document).one('ajaxComplete.couponRemoval', function(_, xhr, settings) {
                            if (settings.url && settings.url.includes('coupon') && settings.url.includes('remove')) {
                                if (xhr.status === 200) {
                                    // Wait a bit more for DOM updates
                                    setTimeout(resolve, 300);
                                } else {
                                    reject(new Error('Failed to remove existing coupon'));
                                }
                            }
                        });

                        // Set up timeout fallback
                        const timeout = setTimeout(() => {
                            $(document).off('ajaxComplete.couponRemoval');
                            reject(new Error('Timeout waiting for coupon removal'));
                        }, 10000);

                        // Trigger the removal
                        removeButton.trigger('click');

                        // Clear timeout when resolved
                        $(document).one('ajaxComplete.couponRemoval', () => {
                            clearTimeout(timeout);
                        });
                    });
                }

                // Now apply the new coupon
                await new Promise((resolve, reject) => {
                    // Prepare the coupon input
                    $(document).find('.coupon-wrapper').show();
                    $('.coupon-wrapper .coupon-code').val(discountCode);

                    // Listen for the AJAX completion of coupon application
                    $(document).one('ajaxComplete.couponApplication', function(_, xhr, settings) {
                        if (settings.url && (settings.url.includes('coupon') || settings.url.includes('apply'))) {
                            if (xhr.status === 200) {
                                resolve();
                            } else {
                                reject(new Error('Failed to apply new coupon'));
                            }
                        }
                    });

                    // Set up timeout fallback
                    const timeout = setTimeout(() => {
                        $(document).off('ajaxComplete.couponApplication');
                        reject(new Error('Timeout waiting for coupon application'));
                    }, 10000);

                    // Trigger the application
                    $('.apply-coupon-code').trigger('click');

                    // Clear timeout when resolved
                    $(document).one('ajaxComplete.couponApplication', () => {
                        clearTimeout(timeout);
                    });
                });

            } catch (error) {
                console.error('Error during coupon switching:', error);
                // Restore button state on error
                button.html(originalText);

                // Show error message if possible
                if ($('.coupon-error-msg .text-danger').length) {
                    $('.coupon-error-msg .text-danger').text('Failed to switch coupon. Please try again.');
                }
            } finally {
                // Clean up event listeners
                $(document).off('ajaxComplete.couponRemoval');
                $(document).off('ajaxComplete.couponApplication');
            }
        });

        // Mobile coupon modal functionality
        this.initMobileCouponModal();
    }

    initMobileCouponModal() {
        // Handle coupon application from mobile modal
        $(document).on('click', '.mobile-coupon-item [data-bb-toggle="apply-coupon-code"]', function() {
            const modal = $('#mobile-coupon-modal');

            // Close modal after a delay to allow the coupon switching to complete
            setTimeout(() => {
                modal.modal('hide');
            }, 1000); // Longer delay to ensure coupon switching completes
        });

        // Handle coupon removal from mobile modal
        $(document).on('click', '.mobile-coupon-item .remove-coupon-code', function() {
            const modal = $('#mobile-coupon-modal');

            // Close modal after a delay to allow the removal to complete
            setTimeout(() => {
                modal.modal('hide');
            }, 800); // Delay to ensure removal completes
        });

        // Update modal content after coupon operations
        $(document).on('coupon:applied coupon:removed', () => {
            // Refresh modal content if it's open
            const modal = $('#mobile-coupon-modal');
            if (modal.hasClass('show')) {
                setTimeout(() => {
                    // Reload the page to get updated coupon states
                    // This ensures the modal shows the correct applied/remove buttons
                    window.location.reload();
                }, 500);
            }
        });
    }
}
