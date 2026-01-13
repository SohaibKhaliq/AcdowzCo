try {
    window.$ = window.jQuery = require('jquery')

    require('bootstrap')
} catch (e) {
}

import { CheckoutAddress } from './partials/address'
import { DiscountManagement } from './partials/discount'

class MainCheckout {
    constructor() {
        new CheckoutAddress().init()
        new DiscountManagement().init()
    }

    static showNotice(messageType, message, messageHeader = '') {
        toastr.clear()

        toastr.options = {
            closeButton: true,
            positionClass: 'toast-bottom-right',
            onclick: null,
            showDuration: 1000,
            hideDuration: 1000,
            timeOut: 10000,
            extendedTimeOut: 1000,
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut',
        }

        if (!messageHeader) {
            switch (messageType) {
                case 'error':
                    messageHeader = window.messages.error_header
                    break
                case 'success':
                    messageHeader = window.messages.success_header
                    break
            }
        }

        toastr[messageType](message, messageHeader)
    }

    static handleError(data, $container) {
        if (typeof data.errors !== 'undefined' && !_.isArray(data.errors)) {
            MainCheckout.handleValidationError(data.errors, $container)
        } else {
            if (typeof data.responseJSON !== 'undefined') {
                if (typeof data.responseJSON.errors !== 'undefined') {
                    if (data.status === 422) {
                        MainCheckout.handleValidationError(data.responseJSON.errors, $container)
                    }
                } else if (typeof data.responseJSON.message !== 'undefined') {
                    MainCheckout.showError(data.responseJSON.message)
                } else {
                    $.each(data.responseJSON, (index, el) => {
                        $.each(el, (key, item) => {
                            MainCheckout.showError(item)
                        })
                    })
                }
            } else {
                MainCheckout.showError(data.statusText)
            }
        }
    }

    static dotArrayToJs(str) {
        const splittedStr = str.split('.')

        return splittedStr.length === 1 ? str : splittedStr[0] + '[' + splittedStr.splice(1).join('][') + ']'
    }

    static handleValidationError(errors, $container) {
        $.each(errors, (index, item) => {
            const inputName = MainCheckout.dotArrayToJs(index)
            let $input = $(`*[name="${inputName}"]`)

            if ($container) {
                $input = $container.find(`[name="${inputName}"]`)
            }

            if ($input.closest('.form-group').length) {
                $input.closest('.form-group').addClass('field-is-invalid')
            } else {
                $input.addClass('field-is-invalid')
            }

            if ($input.hasClass('form-control')) {
                $input.addClass('is-invalid')
                if ($input.is('select') && $input.closest('.select--arrow').length) {
                    $input.closest('.select--arrow').addClass('is-invalid')
                    $input.closest('.select--arrow').after(`<div class="invalid-feedback">${item}</div>`)
                } else {
                    $input.after(`<div class="invalid-feedback">${item}</div>`)
                }
            }
        })

        if (errors[0]) {
            MainCheckout.showError(errors[0])
        }
    }

    static showError(message, messageHeader = '') {
        this.showNotice('error', message, messageHeader)
    }

    static showSuccess(message, messageHeader = '') {
        this.showNotice('success', message, messageHeader)
    }

    init() {
        const $checkoutForm = $('form.checkout-form')
        const shippingForm = '#main-checkout-product-info'
        const customerShippingAddressForm = '.customer-address-payment-form .address-form-wrapper'
        const customerBillingAddressForm = '.customer-billing-address-form'
        const customerTaxInformationForm = '.customer-tax-information-form'

        const disablePaymentMethodsForm = () => {
            $('.payment-info-loading').show()
            $('.payment-checkout-btn').prop('disabled', true)
        }

        const enablePaymentMethodsForm = () => {
            $('.payment-info-loading').hide()
            $('.payment-checkout-btn').prop('disabled', false)

            document.dispatchEvent(new CustomEvent('payment-form-reloaded'))
        }

        const updateCheckoutButtonStatus = () => {
            // Make a quick AJAX call to check if checkout is valid
            $.ajax({
                url: $checkoutForm.data('update-url'),
                method: 'POST',
                data: new FormData($checkoutForm.get(0)),
                processData: false,
                contentType: false,
                success: ({ data }) => {
                    // Update checkout button status
                    if (data.checkout_button) {
                        $('.payment-checkout-btn, .payment-checkout-btn-step').replaceWith(data.checkout_button)
                    }

                    // Update checkout warnings
                    if (data.checkout_warnings !== undefined) {
                        const $warningsContainer = $('.checkout-warnings')
                        if (data.checkout_warnings.trim() === '') {
                            $warningsContainer.remove()
                        } else {
                            if ($warningsContainer.length) {
                                $warningsContainer.replaceWith(data.checkout_warnings)
                            } else {
                                // Insert warnings at the beginning of the form
                                $checkoutForm.prepend(data.checkout_warnings)
                            }
                        }
                    }
                }
            })
        }

        const calculateShippingFee = (methods) => {
            const formData = new FormData($checkoutForm.get(0))

            for (let key in methods) {
                formData.set(key, methods[key])
            }

            $.ajax({
                url: $checkoutForm.data('update-url'),
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                beforeSend: () => {
                    disablePaymentMethodsForm()
                    $('.shipping-info-loading').show()
                },
                success: ({ data }) => {
                    $('.cart-item-wrapper').html(data.amount)
                    $('[data-bb-toggle="checkout-payment-methods-area"]').html(data.payment_methods)
                    $('[data-bb-toggle="checkout-shipping-methods-area"]').html(data.shipping_methods)

                    // Update checkout button status
                    if (data.checkout_button) {
                        $('.payment-checkout-btn, .payment-checkout-btn-step').replaceWith(data.checkout_button)
                    }

                    // Update checkout warnings
                    if (data.checkout_warnings !== undefined) {
                        const $warningsContainer = $('.checkout-warnings')
                        if (data.checkout_warnings.trim() === '') {
                            $warningsContainer.remove()
                        } else {
                            if ($warningsContainer.length) {
                                $warningsContainer.replaceWith(data.checkout_warnings)
                            } else {
                                // Insert warnings at the beginning of the form
                                $checkoutForm.prepend(data.checkout_warnings)
                            }
                        }
                    }
                },
                complete: () => {
                    enablePaymentMethodsForm()
                    $('.shipping-info-loading').hide()
                },
            })
        }

        $(document).on('change', 'input.shipping_method_input', (event) => {
            const data = {}

            if ($(event.currentTarget).closest('.checkout-products-marketplace').length) {
                const shippingMethods = $(shippingForm).find('input.shipping_method_input')

                if (shippingMethods.length) {
                    shippingMethods.map((i, shm) => {
                        const val = $(shm).filter(':checked').val()
                        const sId = $(shm).data('id')

                        if (val) {
                            data[`shipping_method[${sId}]`] = val
                            data[`shipping_option[${sId}]`] = $(shm).data('option')
                        }
                    })
                }
            } else {
                const $this = $(event.currentTarget)
                $('input[name=shipping_option]').val($this.data('option'))

                $('.mobile-total').text('...')

                const data = {
                    shipping_method: $this.val(),
                    shipping_option: $this.data('option'),
                    payment_method: '',
                    address: {
                        address_id: $('#address_id').val(),
                    },
                }

                const paymentMethod = $(document).find('input[name=payment_method]:checked').first()
                if (paymentMethod.length) {
                    data.payment_method = paymentMethod.val()
                }
            }

            calculateShippingFee(data)
        })

        $(document).on('change', 'input[name=payment_method]', (event) => {
            calculateShippingFee({
                payment_method: $(event.target).val()
            })
        })

        document.addEventListener('coupon:applied', function() {
            const paymentMethod = $(document).find('input[name=payment_method]:checked').first()

            calculateShippingFee(paymentMethod)
        })

        document.addEventListener('coupon:removed', function() {
            const paymentMethod = $(document).find('input[name=payment_method]:checked').first()

            calculateShippingFee(paymentMethod)
        })

        const validatedFormFields = () => {
            const addressId = $('#address_id').val()

            if (addressId && addressId !== 'new') {
                return true
            }

            let validated = true

            $.each($(document).find('.form-control[required]'), (index, el) => {
                if (!$(el).val() || $(el).val() === 'null') {
                    validated = false
                }
            })

            return validated
        }

        if ($checkoutForm.find('.list-customer-address').length) {
            calculateShippingFee()
        }

        // Trigger initial calculation to ensure payment fee is calculated on page load
        $(document).ready(() => {
            const paymentMethod = $(document).find('input[name=payment_method]:checked').first()
            if (paymentMethod.length) {
                paymentMethod.trigger('change')
            }
        })

        const onChangeShippingForm = (event) => {
            const _self = $(event.currentTarget)
            _self.closest('.form-group').find('.text-danger').remove()
            const $form = _self.closest('form')

            if (validatedFormFields() && $form.valid && $form.valid()) {
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: $('#save-shipping-information-url').val(),
                    data: new FormData($form[0]),
                    contentType: false,
                    processData: false,
                    success: ({ error }) => {
                        if (!error && (/country|state|city|address/.test($(event.target).prop('name')))) {
                            calculateShippingFee()
                        }
                    },
                    error: (response) => {
                        MainCheckout.handleError(response, $form)
                    },
                })
            }
        }

        $(document).on('change', '#address_country, #address_state, #address_city, #address_zip_code', (event) => {
            const _self = $(event.currentTarget)
            const $form = _self.closest('form')

            $.ajax({
                type: 'POST',
                cache: false,
                url: $('#update-checkout-tax-url').val(),
                data: new FormData($form[0]),
                contentType: false,
                processData: false,
                success: ({ data }) => {
                    $('.cart-item-wrapper').html(data.amount)
                },
                error: (response) => {
                    MainCheckout.handleError(response, $form)
                },
            })
        })

        $(document).on('change', `${customerShippingAddressForm} .form-control`, (event) => {
            onChangeShippingForm(event)
        })

        $(document).on('change', '.list-customer-address .form-control', (event) => {
            onChangeShippingForm(event)
        })

        $(document).on('change', `${customerBillingAddressForm} #billing_address_same_as_shipping_address`, (event) => {
            const _self = $(event.currentTarget)
            const val = _self.find(':selected').val()
            if (val) {
                $('.billing-address-form-wrapper').hide()
            } else {
                $('.billing-address-form-wrapper').show()
            }
        })

        $(document).on('change', `${customerTaxInformationForm} #with_tax_information`, (event) => {
            const _self = $(event.currentTarget)

            $('.tax-information-form-wrapper').toggle(_self.is(':checked'))
        })

        const $addressId = $('#address_id')

        if ($addressId.length && $addressId.val() && $addressId.val() !== 'new') {
            $addressId.trigger('change')
        }

        $(document)
            .on('click', '[data-bb-toggle="decrease-qty"]', (e) => {
                const $input = $(e.currentTarget).parent().find('input')

                let count = parseInt($input.val()) - 1

                if (count < 1) {
                    return
                }

                count = count < 1 ? 1 : count
                $input.val(count)
                $input.trigger('change')
            })
            .on('click', '[data-bb-toggle="increase-qty"]', (e) => {
                const $input = $(e.currentTarget).parent().find('input')

                const max = $input.prop('max')

                if (max && parseInt($input.val()) >= parseInt(max)) {
                    return
                }

                $input.val(parseInt($input.val()) + 1)
                $input.trigger('change')
            })
            .on('change', '[data-bb-toggle="update-cart"]', (e) => {
                const $currentTarget = $(e.currentTarget)
                const $parent = $currentTarget.parent()

                const qtyKey = $currentTarget.prop('name')
                const qtyValue = $currentTarget.val()
                const rowId = $parent.data('row-id')

                $.ajax({
                    type: 'POST',
                    url: $parent.data('url'),
                    data: {
                        _token: $('meta[name="csrf-token"]').prop('content'),
                        [qtyKey]: qtyValue,
                        [`items[${rowId}][rowId]`]: rowId,
                    },
                    success: ({ error, message, data }) => {
                        if (error) {
                            MainCheckout.showError(message)
                            return
                        }

                        calculateShippingFee()
                    },
                    error: (error) => {
                        MainCheckout.handleError(error)
                    }
                })
            })
    }
}

$(() => {
    new MainCheckout().init()

    window.MainCheckout = MainCheckout
})
