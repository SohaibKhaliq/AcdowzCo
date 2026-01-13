'use strict'

let MartApp = MartApp || {}
window.MartApp = MartApp
MartApp.$iconChevronLeft =
    '<span class="slick-prev-arrow svg-icon"><svg><use href="#svg-icon-chevron-left" xlink:href="#svg-icon-chevron-left"></use></svg></span>'
MartApp.$iconChevronRight =
    '<span class="slick-next-arrow svg-icon"><svg><use href="#svg-icon-chevron-right" xlink:href="#svg-icon-chevron-right"></use></svg></span>'

if (typeof ScrollBarHelper !== 'undefined') {
    window._scrollBar = new ScrollBarHelper()
}

MartApp.isRTL = $('body').prop('dir') === 'rtl'
;(function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    })

    function basicEvents() {
        $('.form--quick-search .form-group--icon').show()
        let $categoryLabel = $('.product-category-label .text')
        $(document).on('change', '.product-category-select', function () {
            $categoryLabel.text($.trim($(this).find('option:selected').text()))
        })

        $categoryLabel.text($.trim($('.product-category-select option:selected').text()))

        $(document).ready(function () {
            $('.preloader').addClass('fade-in')
        })
    }

    function subMenuToggle() {
        $(document).on('click', '.menu-item-has-children > a > .sub-toggle', function (e) {
            e.preventDefault()
            const $this = $(this)
            const $parent = $this.closest('.menu-item-has-children')
            $parent.toggleClass('active')
        })

        $(document).on('click', '.mega-menu__column > a > .sub-toggle', function (e) {
            e.preventDefault()
            const $this = $(this)
            const $parent = $this.closest('.mega-menu__column')
            $parent.toggleClass('active')
        })
    }

    function siteToggleAction() {
        $('.toggle--sidebar').on('click', function (e) {
            e.preventDefault()

            let url = $(this).attr('href')

            $(this).toggleClass('active')
            $(this).siblings('a').removeClass('active')

            $(url).toggleClass('active')

            $(url).siblings('.panel--sidebar').removeClass('active')
            _scrollBar.hide()
        })

        $(document).on('click', '.close-toggle--sidebar', function (e) {
            e.preventDefault()
            let $panel

            if ($(this).data('toggle-closest')) {
                $panel = $(this).closest($(this).data('toggle-closest'))
            }

            if (!$panel || !$panel.length) {
                $panel = $(this).closest('.panel--sidebar')
            }

            $panel.removeClass('active')
            _scrollBar.reset()
        })

        $('body').on('click', function (e) {
            if ($(e.target).siblings('.panel--sidebar').hasClass('active')) {
                $('.panel--sidebar').removeClass('active')
                _scrollBar.reset()
            }
        })
    }

    $(function () {
        basicEvents()
        subMenuToggle()
        siteToggleAction()

        window.addEventListener('ecommerce.categories-dropdown.loaded', function () {
            subMenuToggle()
        })
    })

    MartApp.showModal = function ($modal) {
        if (typeof $.fn.modal === 'function') {
            $modal.modal('show')
        } else {
            // Fallback: show modal manually
            $modal.addClass('show')
            $modal.css('display', 'block')
            $modal.attr('aria-hidden', 'false')
            $('body').addClass('modal-open')

            // Create backdrop if it doesn't exist
            if (!$('.modal-backdrop').length) {
                $('<div class="modal-backdrop fade show"></div>').appendTo('body')
            }
        }
    }

    MartApp.hideModal = function ($modal) {
        if (typeof $.fn.modal === 'function') {
            $modal.modal('hide')
        } else {
            // Fallback: hide modal manually
            $modal.removeClass('show')
            $modal.css('display', 'none')
            $modal.attr('aria-hidden', 'true')
            $('body').removeClass('modal-open')
            $('.modal-backdrop').remove()
        }
    }
    
    MartApp.initQuickShopVariationListeners = function ($modal) {
        // Save original callbacks
        const originalSuccessCallback = window.onChangeSwatchesSuccess
        
        // Save original history methods
        const originalPushState = window.history.pushState
        const originalReplaceState = window.history.replaceState
        
        // Override history methods to prevent URL updates in quick shop modal
        window.history.pushState = function() {
            // Check if we're dealing with product attribute changes in quick shop modal
            if (arguments[0] && arguments[0].product_attributes_id && 
                $('#' + arguments[0].product_attributes_id).closest('#product-quick-shop-modal').length) {
                // Skip the pushState for quick shop modal
                return
            }
            // Otherwise, call the original method
            return originalPushState.apply(window.history, arguments)
        }
        
        window.history.replaceState = function() {
            // Check if we're dealing with product attribute changes in quick shop modal
            if (arguments[0] && arguments[0].product_attributes_id && 
                $('#' + arguments[0].product_attributes_id).closest('#product-quick-shop-modal').length) {
                // Skip the replaceState for quick shop modal
                return
            }
            // Otherwise, call the original method
            return originalReplaceState.apply(window.history, arguments)
        }
        
        // Override the success callback to update stock status
        window.onChangeSwatchesSuccess = function(res, $productAttributes) {
            // Call original callback if exists
            if (originalSuccessCallback && typeof originalSuccessCallback === 'function') {
                originalSuccessCallback(res, $productAttributes)
            }
            
            // Update stock status only if in quick shop modal
            if ($productAttributes.closest('#product-quick-shop-modal').length) {
                const data = res.data
                if (data) {
                    const $availabilityStatus = $modal.find('.availability-status .status-value')
                    const $addToCartBtn = $modal.find('.add-to-cart-button')
                    const $quantityInput = $modal.find('input[name="qty"]')
                    
                    const inStockText = $availabilityStatus.data('in-stock-text') || 'In stock'
                    const outOfStockText = $availabilityStatus.data('out-of-stock-text') || 'Out of stock'
                    
                    if (data.product && data.product.is_out_of_stock) {
                        $availabilityStatus.html('<span class="text-danger">' + outOfStockText + '</span>')
                        $addToCartBtn.addClass('disabled').prop('disabled', true)
                        if ($quantityInput.length) {
                            $quantityInput.prop('readonly', true)
                        }
                    } else if (data.product && data.product.with_storehouse_management && data.product.quantity < 1) {
                        $availabilityStatus.html('<span class="text-danger">' + outOfStockText + '</span>')
                        $addToCartBtn.addClass('disabled').prop('disabled', true)
                        if ($quantityInput.length) {
                            $quantityInput.prop('readonly', true)
                        }
                    } else {
                        $availabilityStatus.html('<span class="text-success">' + inStockText + '</span>')
                        $addToCartBtn.removeClass('disabled').prop('disabled', false)
                        if ($quantityInput.length) {
                            $quantityInput.prop('readonly', false)
                        }
                    }
                }
            }
        }
        
        // Restore original methods when modal is closed
        $modal.on('hidden.bs.modal', function() {
            window.onChangeSwatchesSuccess = originalSuccessCallback
            window.history.pushState = originalPushState
            window.history.replaceState = originalReplaceState
        })
    }

    MartApp.updateQuickShopAvailability = function ($modal) {
        const $form = $modal.find('form.cart-form')
        const $availabilityStatus = $modal.find('.availability-status .status-value')
        const $addToCartBtn = $form.find('.add-to-cart-button')
        const $quantityInput = $form.find('input[name="qty"]')
        
        if (!$availabilityStatus.length) {
            return
        }

        // Check if product is available based on the form state
        const isOutOfStock = $addToCartBtn.hasClass('disabled') || $addToCartBtn.prop('disabled')
        
        const inStockText = $availabilityStatus.data('in-stock-text') || 'In stock'
        const outOfStockText = $availabilityStatus.data('out-of-stock-text') || 'Out of stock'
        
        if (isOutOfStock) {
            $availabilityStatus.html('<span class="text-danger">' + outOfStockText + '</span>')
            // Disable quantity input when out of stock
            if ($quantityInput.length) {
                $quantityInput.prop('readonly', true)
            }
        } else {
            $availabilityStatus.html('<span class="text-success">' + inStockText + '</span>')
            // Enable quantity input when in stock
            if ($quantityInput.length) {
                $quantityInput.prop('readonly', false)
            }
        }
    }

    MartApp.init = function () {
        MartApp.$body = $(document.body)

        MartApp.formSearch = '.bb-product-form-filter'
        MartApp.$formSearch = $(document).find(MartApp.formSearch)
        MartApp.productListing = '.products-listing'
        MartApp.$productListing = $(MartApp.productListing)

        this.lazyLoad(null, true)
        this.productQuickView()
        this.productQuickShop()
        this.slickSlides()
        this.productQuantity()
        this.addProductToWishlist()
        this.addProductToCompare()
        this.addProductToCart()
        this.applyCouponCode()
        this.productGallery()
        this.lightBox()
        this.handleTabBootstrap()
        this.toggleViewProducts()
        this.filterSlider()
        this.toolbarOrderingProducts()
        this.productsFilter()
        this.ajaxUpdateCart()
        this.removeCartItem()
        this.removeWishlistItem()
        this.removeCompareItem()
        this.customerDashboard()
        this.newsletterForm()
        this.contactSellerForm()
        this.stickyAddToCart()
        this.backToTop()
        this.stickyHeader()
        this.recentlyViewedProducts()

        MartApp.$body.on('click', '.catalog-sidebar .backdrop, #cart-mobile .backdrop', function (e) {
            e.preventDefault()
            $(this).parent().removeClass('active')
            _scrollBar.reset()
        })

        MartApp.$body.on('click', '.sidebar-filter-mobile', function (e) {
            e.preventDefault()
            MartApp.toggleSidebarFilterProducts('open', $(e.currentTarget).data('toggle'))
        })
    }

    MartApp.toggleSidebarFilterProducts = function (status = 'close', target = 'product-categories-primary-sidebar') {
        const $el = $('[data-toggle-target="' + target + '"]')
        if (status === 'close') {
            $el.removeClass('active')
            _scrollBar.reset()
        } else {
            $el.addClass('active')
            _scrollBar.hide()
        }
    }

    MartApp.productQuickView = function () {
        const $modal = $('#product-quick-view-modal')

        MartApp.$body.on('click', '.product-quick-view-button .quick-view', function (e) {
            e.preventDefault()
            const _self = $(e.currentTarget)
            _self.addClass('loading')
            $modal.removeClass('loaded').addClass('loading')
            $modal.modal('show')
            $modal.find('.product-modal-content').html('')
            $.ajax({
                url: _self.data('url'),
                type: 'GET',
                success: (res) => {
                    if (!res.error) {
                        $modal.find('.product-modal-content').html(res.data)
                        setTimeout(function() {
                            if (typeof EcommerceApp !== 'undefined') {
                                EcommerceApp.initProductGallery(true)
                            }

                            MartApp.lazyLoad($modal[0])
                        }, 100)

                        if (typeof Theme.lazyLoadInstance !== 'undefined') {
                            Theme.lazyLoadInstance.update()
                        }

                        document.dispatchEvent(new CustomEvent('ecommerce.quick-view.initialized'))
                    }
                },
                error: () => {},
                complete: () => {
                    $modal.addClass('loaded').removeClass('loading')
                    _self.removeClass('loading')
                },
            })
        })
    }

    MartApp.productQuickShop = function () {
        MartApp.$body.on('click', '.js-quick-shop-button', function (e) {
            e.preventDefault()
            e.stopPropagation()

            const $btn = $(this)
            const $modal = $('#product-quick-shop-modal')

            if ($btn.hasClass('loading')) {
                return
            }

            $btn.addClass('loading')
            $modal.removeClass('loaded').addClass('loading')

            MartApp.showModal($modal)

            $.ajax({
                url: $btn.data('url'),
                type: 'GET',
                success: (res) => {
                    if (!res.error) {
                        $modal.find('.product-quick-shop-content').html(res.data)

                        // Mark the quick shop form to prevent duplicate handling
                        $modal.find('form.cart-form').addClass('quick-shop-form')

                        // Wrap the content with product-attributes class if needed
                        if (!$modal.find('.product-attributes').length && $modal.find('.attribute-swatches-wrapper').length) {
                            $modal.find('.ps-product--quickshop').addClass('product-attributes')
                        }

                        // Initialize product swatches
                        if (typeof window.ChangeProductSwatches !== 'undefined') {
                            // Trigger the swatch initialization
                            $modal.find('.attribute-swatches-wrapper input:checked').trigger('change')
                        }

                        if (typeof MartApp.initProductQuantity === 'function') {
                            MartApp.initProductQuantity($modal)
                        }
                        
                        // Initialize variation change listeners for stock availability updates
                        MartApp.initQuickShopVariationListeners($modal)
                        // Initial availability update
                        MartApp.updateQuickShopAvailability($modal)
                    } else {
                        MartApp.showError(res.message)
                        MartApp.hideModal($modal)
                    }
                },
                error: (res) => {
                    MartApp.handleError(res)
                    $modal.modal('hide')
                },
                complete: () => {
                    $modal.addClass('loaded').removeClass('loading')
                    $btn.removeClass('loading')
                },
            })
        })

        // Handle modal close button
        MartApp.$body.on('click', '#product-quick-shop-modal .btn-close', function (e) {
            e.preventDefault()
            MartApp.hideModal($('#product-quick-shop-modal'))
        })

        // Handle backdrop click
        MartApp.$body.on('click', '.modal-backdrop', function (e) {
            e.preventDefault()
            MartApp.hideModal($('#product-quick-shop-modal'))
        })

        // Handle quick shop form submission
        MartApp.$body.on('click', '.quick-shop-form button[type=submit]', function (e) {
            e.preventDefault()
            e.stopPropagation()

            const $btn = $(this)
            const $form = $btn.closest('form.cart-form')
            const $modal = $('#product-quick-shop-modal')

            $btn.addClass('loading')

            let data = $form.serializeArray()
            data.push({ name: 'checkout', value: $btn.prop('name') === 'checkout' ? 1 : 0 })

            $.ajax({
                type: 'POST',
                url: $form.prop('action'),
                data: $.param(data),
                success: (res) => {
                    if (res.error) {
                        MartApp.showError(res.message)
                        if (res.data && res.data.next_url !== undefined) {
                            setTimeout(() => {
                                window.location.href = res.data.next_url
                            }, 500)
                        }
                        return false
                    }

                    if (res.data && res.data.next_url !== undefined) {
                        window.location.href = res.data.next_url
                        return false
                    }

                    MartApp.hideModal($modal)
                    MartApp.showSuccess(res.message)
                    MartApp.loadAjaxCart()
                },
                error: (res) => {
                    MartApp.handleError(res, $form)
                },
                complete: () => {
                    $btn.removeClass('loading')
                },
            })
        })
    }

    MartApp.productGallery = function (destroy, $gallery) {
        if (!$gallery || !$gallery.length) {
            $gallery = $('.product-gallery')
        }

        if ($gallery.length) {
            const first = $gallery.find('.product-gallery__wrapper')
            const second = $gallery.find('.product-gallery__variants')
            if (destroy) {
                if (first.length && first.hasClass('slick-initialized')) {
                    first.slick('unslick')
                }

                if (second.length && second.hasClass('slick-initialized')) {
                    second.slick('unslick')
                }
            }

            first.not('.slick-initialized').slick({
                rtl: MartApp.isRTL,
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: false,
                asNavFor: second,
                dots: false,
                prevArrow: MartApp.$iconChevronLeft,
                nextArrow: MartApp.$iconChevronRight,
                lazyLoad: 'ondemand',
            })

            second.not('.slick-initialized').slick({
                rtl: MartApp.isRTL,
                slidesToShow: 8,
                slidesToScroll: 1,
                infinite: false,
                focusOnSelect: true,
                asNavFor: first,
                vertical: true,
                prevArrow:
                    '<span class="slick-prev-arrow svg-icon"><svg><use href="#svg-icon-arrow-up" xlink:href="#svg-icon-arrow-up"></use></svg></span>',
                nextArrow:
                    '<span class="slick-next-arrow svg-icon"><svg><use href="#svg-icon-chevron-down" xlink:href="#svg-icon-chevron-down"></use></svg></span>',
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 6,
                            vertical: false,
                        },
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 3,
                            vertical: false,
                        },
                    },
                ],
            })
        }
    }

    MartApp.lightBox = function () {
        let $productGallery = $('.product-gallery--with-images')
        if ($productGallery.data('lightGallery')) {
            $productGallery.data('lightGallery').destroy(true)
        }

        $productGallery.lightGallery({
            selector: '.item a',
            thumbnail: true,
            share: false,
            fullScreen: false,
            autoplay: false,
            autoplayControls: false,
            actualSize: false,
        })

        let $galleries = $('.review-images-total.review-images')
        if ($galleries.length) {
            $galleries.map((index, value) => {
                if (!$(value).data('lightGallery')) {
                    $(value).lightGallery({
                        selector: 'a',
                        thumbnail: true,
                        share: false,
                        fullScreen: false,
                        autoplay: false,
                        autoplayControls: false,
                        actualSize: false,
                    })
                }
            })
        }
    }

    MartApp.slickSlide = function (el) {
        const $el = $(el)
        if ($el.length && $el.not('.slick-initialized')) {
            let slickOptions = $el.data('slick') || {}
            if (slickOptions.appendArrows) {
                slickOptions.appendArrows = $el.parent().find(slickOptions.appendArrows)
            }
            slickOptions = Object.assign(slickOptions, {
                rtl: MartApp.isRTL,
                prevArrow: MartApp.$iconChevronLeft,
                nextArrow: MartApp.$iconChevronRight,
            })
            $el.slick(slickOptions)
        }
    }

    MartApp.slickSlides = function () {
        $('.slick-slides-carousel')
            .not('.slick-initialized')
            .map(function (i, e) {
                MartApp.slickSlide(e)
            })
    }

    MartApp.lazyLoad = function (container, init = false) {
        if (init) {
            MartApp.lazyLoadInstance = new LazyLoad({
                elements_selector: '.lazyload',
                callback_error: (img) => {
                    img.setAttribute('src', siteConfig.img_placeholder)
                },
            })
        } else {
            new LazyLoad({
                container: container,
                elements_selector: '.lazyload',
                callback_error: (img) => {
                    img.setAttribute('src', siteConfig.img_placeholder)
                },
            })
        }
    }

    MartApp.productQuantity = function () {
        MartApp.$body.on('click', '.quantity .increase, .quantity .decrease', function (e) {
            e.preventDefault()
            let $this = $(this),
                $wrapperBtn = $this.closest('.product-button'),
                $btn = $wrapperBtn.find('.quantity_button'),
                $price = $this.closest('.quantity').siblings('.box-price').find('.price-current'),
                $priceCurrent = $price.html(),
                $qty = $this.siblings('.qty'),
                step = parseInt($qty.attr('step'), 10),
                current = parseInt($qty.val(), 10),
                min = parseInt($qty.attr('min'), 10),
                max = parseInt($qty.attr('max'), 10)
            min = min || 1
            max = max || current + 1
            if ($this.hasClass('decrease') && current > min) {
                $qty.val(current - step)
                $qty.trigger('change')
                let numQuantity = +$btn.attr('data-quantity')
                numQuantity = numQuantity - 1
                $btn.attr('data-quantity', numQuantity)
                let $total2 = ($priceCurrent * 1 - $priceCurrent / current).toFixed(2)
                $price.html($total2)
            }
            if ($this.hasClass('increase') && current < max) {
                $qty.val(current + step)
                $qty.trigger('change')
                let numQuantity = +$btn.attr('data-quantity')
                numQuantity = numQuantity + 1
                $btn.attr('data-quantity', numQuantity)
                let $total = ($priceCurrent * 1 + $priceCurrent / current).toFixed(2)
                $price.html($total)
            }

            MartApp.processUpdateCart($this)
        })
        MartApp.$body.on('keyup', '.quantity .qty', function (e) {
            e.preventDefault()
            let $this = $(this),
                $wrapperBtn = $this.closest('.product-button'),
                $btn = $wrapperBtn.find('.quantity_button'),
                $price = $this.closest('.quantity').siblings('.box-price').find('.price-current'),
                $priceFirst = $price.data('current'),
                current = parseInt($this.val(), 10),
                min = parseInt($this.attr('min'), 10),
                max = parseInt($this.attr('max'), 10)
            let min_check = min ? min : 1
            let max_check = max ? max : current + 1
            if (current <= max_check && current >= min_check) {
                $btn.attr('data-quantity', current)
                let $total = ($priceFirst * current).toFixed(2)
                $price.html($total)
            }

            MartApp.processUpdateCart($this)
        })
    }

    MartApp.addProductToWishlist = function () {
        MartApp.$body.on('click', '.wishlist-button .wishlist', function (e) {
            e.preventDefault()
            const $btn = $(e.currentTarget)
            $btn.addClass('loading')

            $.ajax({
                url: $btn.data('url'),
                method: 'POST',
                success: (res) => {
                    if (res.error) {
                        MartApp.showError(res.message)
                        return false
                    }

                    MartApp.showSuccess(res.message)
                    $('.btn-wishlist .header-item-counter').text(res.data.count)
                    if (res.data?.added) {
                        $('.wishlist-button .wishlist[data-url="' + $btn.data('url') + '"]').addClass(
                            'added-to-wishlist'
                        )
                    } else {
                        $('.wishlist-button .wishlist[data-url="' + $btn.data('url') + '"]').removeClass(
                            'added-to-wishlist'
                        )
                    }
                },
                error: (res) => {
                    MartApp.showError(res.message)
                },
                complete: () => {
                    $btn.removeClass('loading')
                },
            })
        })
    }

    MartApp.addProductToCompare = function () {
        MartApp.$body.on('click', '.compare-button .compare', function (e) {
            e.preventDefault()
            const $btn = $(e.currentTarget)
            $btn.addClass('loading')

            $.ajax({
                url: $btn.data('url'),
                method: 'POST',
                success: (res) => {
                    if (res.error) {
                        MartApp.showError(res.message)
                        return false
                    }
                    MartApp.showSuccess(res.message)
                    $('.btn-compare .header-item-counter').text(res.data.count)
                },
                error: (res) => {
                    MartApp.showError(res.message)
                },
                complete: () => {
                    $btn.removeClass('loading')
                },
            })
        })
    }

    MartApp.addProductToCart = function () {
        MartApp.$body.on('click', 'form.cart-form button[type=submit]', function (e) {
            e.preventDefault()
            const $form = $(this).closest('form.cart-form')

            // Skip if this is a quick shop form (handled separately)
            if ($form.hasClass('quick-shop-form')) {
                return
            }

            const $btn = $(this)
            $btn.addClass('loading')

            let data = $form.serializeArray()
            data.push({ name: 'checkout', value: $btn.prop('name') === 'checkout' ? 1 : 0 })

            $.ajax({
                type: 'POST',
                url: $form.prop('action'),
                data: $.param(data),
                success: (res) => {
                    if (res.error) {
                        MartApp.showError(res.message)
                        if (res.data && res.data.next_url !== undefined) {
                            setTimeout(() => {
                                window.location.href = res.data.next_url
                            }, 500);
                        }

                        return false
                    }

                    if (res.data && res.data.next_url !== undefined) {
                        window.location.href = res.data.next_url
                        return false
                    }

                    MartApp.showSuccess(res.message)
                    MartApp.loadAjaxCart()
                },
                error: (res) => {
                    MartApp.handleError(res, $form)
                },
                complete: () => {
                    $btn.removeClass('loading')
                },
            })
        })
    }

    MartApp.applyCouponCode = function () {
        $(document).on('keypress', '.form-coupon-wrapper .coupon-code', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault()
                e.stopPropagation()
                $(e.currentTarget).closest('.form-coupon-wrapper').find('.btn-apply-coupon-code').trigger('click')
                return false
            }
        })

        $(document).on('click', '.btn-apply-coupon-code', (e) => {
            e.preventDefault()
            let _self = $(e.currentTarget)

            $.ajax({
                url: _self.data('url'),
                type: 'POST',
                data: {
                    coupon_code: _self.closest('.form-coupon-wrapper').find('.coupon-code').val(),
                },
                beforeSend: () => {
                    _self.prop('disabled', true).addClass('loading')
                },
                success: (res) => {
                    if (!res.error) {
                        let url = window.location.href
                        url = url.substring(0, url.indexOf('?'))

                        $('.cart-page-content').load(url + '?applied_coupon=1 .cart-page-content > *', function () {
                            _self.prop('disabled', false).removeClass('loading')
                            MartApp.showSuccess(res.message)
                        })
                    } else {
                        MartApp.showError(res.message)
                    }
                },
                error: (data) => {
                    MartApp.handleError(data)
                },
                complete: (res) => {
                    if (!(res.status == 200 && res?.responseJSON?.error == false)) {
                        _self.prop('disabled', false).removeClass('loading')
                    }
                },
            })
        })

        $(document).on('click', '.btn-remove-coupon-code', (e) => {
            e.preventDefault()
            const _self = $(e.currentTarget)
            const buttonText = _self.text()
            _self.text(_self.data('processing-text'))

            $.ajax({
                url: _self.data('url'),
                type: 'POST',
                success: (res) => {
                    if (!res.error) {
                        let url = window.location.href
                        url = url.substring(0, url.indexOf('?'))

                        $('.cart-page-content').load(url + ' .cart-page-content > *', function () {
                            _self.text(buttonText)
                        })
                    } else {
                        MartApp.showError(res.message)
                    }
                },
                error: (data) => {
                    MartApp.handleError(data)
                },
                complete: (res) => {
                    if (!(res.status == 200 && res?.responseJSON?.error == false)) {
                        _self.text(buttonText)
                    }
                },
            })
        })
    }

    MartApp.loadAjaxCart = function () {
        if (window.siteConfig?.ajaxCart) {
            $.ajax({
                url: window.siteConfig.ajaxCart,
                method: 'GET',
                success: function (res) {
                    if (!res.error) {
                        $('.mini-cart-content .widget-shopping-cart-content').html(res.data.html)
                        $('.btn-shopping-cart .header-item-counter').text(res.data.count)
                        $('.cart--mini .cart-price-total .cart-amount span').text(res.data.total_price)
                        $('.menu--footer .icon-cart .cart-counter').text(res.data.count)
                        MartApp.lazyLoad($('.mini-cart-content')[0])
                    }
                },
            })
        }
    }

    MartApp.changeInputInSearchForm = function (parseParams) {
        isReadySubmitTrigger = false
        $(document).find(MartApp.formSearch).find('input, select, textarea').each(function (e, i) {
            const $el = $(i)
            const name = $el.attr('name')
            let value = parseParams[name] || null
            const type = $el.attr('type')
            switch (type) {
                case 'checkbox':
                    $el.prop('checked', false)
                    if (Array.isArray(value)) {
                        $el.prop('checked', value.includes($el.val()))
                    } else {
                        $el.prop('checked', !!value)
                    }
                    break
                default:
                    if ($el.is('[name=max_price]')) {
                        $el.val(value || $el.data('max'))
                    } else if ($el.is('[name=min_price]')) {
                        $el.val(value || $el.data('min'))
                    } else if ($el.val() != value) {
                        $el.val(value)
                    }
                    break
            }
        })
        isReadySubmitTrigger = true
    }

    MartApp.convertFromDataToArray = function (formData) {
        let data = []
        formData.forEach(function (obj) {
            if (obj.value) {
                // break with price
                if (['min_price', 'max_price'].includes(obj.name)) {
                    const dataValue = $(document).find(MartApp.formSearch)
                        .find('input[name=' + obj.name + ']')
                        .data(obj.name.substring(0, 3))
                    if (dataValue == parseInt(obj.value)) {
                        return
                    }
                }
                data.push(obj)
            }
        })
        return data
    }

    let isReadySubmitTrigger = true

    MartApp.productsFilter = function () {
        $('.catalog-toolbar__ordering input[name=sort-by]').on('change', function (e) {
            $(document).find(MartApp.formSearch).find('input[name=sort-by]').val($(e.currentTarget).val())
            $(document).find(MartApp.formSearch).trigger('submit')
        })

        MartApp.$body.on('click', '.cat-menu-close', function (e) {
            e.preventDefault()
            $(this).closest('li').toggleClass('opened')
        })
    }

    MartApp.parseParamsSearch = function (query, includeArray = false) {
        let pairs = query || window.location.search.substring(1)
        let re = /([^&=]+)=?([^&]*)/g
        let decodeRE = /\+/g // Regex for replacing addition symbol with a space
        let decode = function (str) {
            return decodeURIComponent(str.replace(decodeRE, ' '))
        }
        let params = {},
            e
        while ((e = re.exec(pairs))) {
            let k = decode(e[1]),
                v = decode(e[2])
            if (k.substring(k.length - 2) == '[]') {
                if (includeArray) {
                    k = k.substring(0, k.length - 2)
                }
                ;(params[k] || (params[k] = [])).push(v)
            } else params[k] = v
        }
        return params
    }

    MartApp.processUpdateCart = function ($this) {
        const $form = $('.cart-page-content').find('.form--shopping-cart')

        if (!$form.length) {
            return false
        }

        $.ajax({
            type: 'POST',
            cache: false,
            url: $form.prop('action'),
            data: new FormData($form[0]),
            contentType: false,
            processData: false,
            beforeSend: () => {
                $this.addClass('loading')
            },
            success: (res) => {
                if (res.error) {
                    MartApp.showError(res.message)
                    return false
                }

                $('.cart-page-content').load(window.siteConfig.cartUrl + ' .cart-page-content > *', function () {
                    MartApp.lazyLoad($('.cart-page-content')[0])
                })

                MartApp.loadAjaxCart()

                MartApp.showSuccess(res.message)
            },
            error: (res) => {
                $this.closest('.ps-table--shopping-cart').removeClass('content-loading')
                MartApp.handleError(res)
            },
            complete: () => {
                $this.removeClass('loading')
            },
        })
    }

    MartApp.ajaxUpdateCart = function (_self) {
        $(document).on('click', '.cart-page-content .update_cart', function (e) {
            e.preventDefault()
            const $this = $(e.currentTarget)

            MartApp.processUpdateCart($this)
        })
    }

    MartApp.removeCartItem = function () {
        $(document).on('click', '.remove-cart-item', function (event) {
            event.preventDefault()
            let _self = $(this)

            $.ajax({
                url: _self.data('url'),
                method: 'GET',
                beforeSend: () => {
                    _self.addClass('loading')
                },
                success: (res) => {
                    if (res.error) {
                        MartApp.showError(res.message)
                        return false
                    }

                    const $cartContent = $('.cart-page-content')

                    if ($cartContent.length && window.siteConfig?.cartUrl) {
                        $cartContent.load(window.siteConfig.cartUrl + ' .cart-page-content > *', function () {
                            MartApp.lazyLoad($cartContent[0])
                        })
                    }

                    MartApp.loadAjaxCart()
                },
                error: (res) => {
                    MartApp.handleError(res)
                },
                complete: () => {
                    _self.removeClass('loading')
                },
            })
        })
    }

    MartApp.removeWishlistItem = function () {
        $(document).on('click', '.remove-wishlist-item', function (event) {
            event.preventDefault()
            let _self = $(this)

            $.ajax({
                url: _self.data('url'),
                method: 'POST',
                data: {
                    _method: 'DELETE',
                },
                beforeSend: () => {
                    _self.addClass('loading')
                },
                success: (res) => {
                    if (res.error) {
                        MartApp.showError(res.message)
                    } else {
                        MartApp.showSuccess(res.message)
                        $('.btn-wishlist .header-item-counter').text(res.data.count)
                        _self.closest('tr').remove()
                    }
                },
                error: (res) => {
                    MartApp.handleError(res)
                },
                complete: () => {
                    _self.removeClass('loading')
                },
            })
        })
    }

    MartApp.removeCompareItem = function () {
        $(document).on('click', '.remove-compare-item', function (event) {
            event.preventDefault()
            let _self = $(this)

            $.ajax({
                url: _self.data('url'),
                method: 'POST',
                data: {
                    _method: 'DELETE',
                },
                beforeSend: () => {
                    _self.addClass('loading')
                },
                success: (res) => {
                    if (res.error) {
                        MartApp.showError(res.message)
                    } else {
                        MartApp.showSuccess(res.message)
                        $('.btn-compare .header-item-counter').text(res.data.count)
                        $('.compare-page-content').load(window.location.href + ' .compare-page-content > *')
                    }
                },
                error: (res) => {
                    MartApp.handleError(res)
                },
                complete: () => {
                    _self.removeClass('loading')
                },
            })
        })
    }

    MartApp.handleTabBootstrap = function () {
        let hash = window.location.hash
        if (hash) {
            let tabTriggerEl = $('a[href="' + hash + '"]')
            if (tabTriggerEl.length) {
                let tab = new bootstrap.Tab(tabTriggerEl[0])
                tab.show()
            }
        }
    }

    MartApp.filterSlider = function () {
        $(document)
            .find('.nonlinear')
            .each(function (index, element) {
                let $element = $(element)
                let min = $element.data('min')
                let max = $element.data('max')
                let $wrapper = $(element).closest('.nonlinear-wrapper')
                noUiSlider.create(element, {
                    connect: true,
                    behaviour: 'tap',
                    start: [
                        $wrapper.find('.product-filter-item-price-0').val(),
                        $wrapper.find('.product-filter-item-price-1').val(),
                    ],
                    range: {
                        min: min,
                        '10%': max * 0.1,
                        '20%': max * 0.2,
                        '30%': max * 0.3,
                        '40%': max * 0.4,
                        '50%': max * 0.5,
                        '60%': max * 0.6,
                        '70%': max * 0.7,
                        '80%': max * 0.8,
                        '90%': max * 0.9,
                        max: max,
                    },
                })

                let nodes = [$wrapper.find('.slider__min'), $wrapper.find('.slider__max')]

                element.noUiSlider.on('update', function (values, handle) {
                    nodes[handle].html(EcommerceApp.formatPrice(Math.round(values[handle])))
                })

                element.noUiSlider.on('change', function (values, handle) {
                    $wrapper
                        .find('.product-filter-item-price-' + handle)
                        .val(Math.round(values[handle]))
                        .trigger('change')
                })
            })
    }

    MartApp.customerDashboard = function () {
        if ($.fn.datepicker) {
            $('#date_of_birth').datepicker({
                format: 'yyyy-mm-dd',
                orientation: 'bottom',
            })
        }

        $('#avatar').on('change', (event) => {
            let input = event.currentTarget
            if (input.files && input.files[0]) {
                let reader = new FileReader()
                reader.onload = (e) => {
                    $('.userpic-avatar').attr('src', e.target.result)
                }
                reader.readAsDataURL(input.files[0])
            }
        })

        $(document).on('click', '.btn-trigger-delete-address', function (event) {
            event.preventDefault()
            $('.btn-confirm-delete').data('url', $(this).data('url'))
            $('#confirm-delete-modal').modal('show')
        })

        $(document).on('click', '.btn-confirm-delete', function (event) {
            event.preventDefault()
            let $current = $(this)
            $.ajax({
                url: $current.data('url'),
                type: 'GET',
                beforeSend: () => {
                    $current.addClass('loading')
                },
                success: (res) => {
                    $current.closest('.modal').modal('hide')
                    if (res.error) {
                        MartApp.showError(res.message)
                    } else {
                        MartApp.showSuccess(res.message)
                        $('.btn-trigger-delete-address[data-url="' + $current.data('url') + '"]')
                            .closest('.col')
                            .remove()
                    }
                },
                error: (res) => {
                    MartApp.handleError(res)
                },
                complete: () => {
                    $current.removeClass('loading')
                },
            })
        })
    }

    MartApp.newsletterForm = function () {
        $(document).on('submit', 'form.subscribe-form', function (e) {
            e.preventDefault()
            e.stopPropagation()
            const $this = $(e.currentTarget)

            let _self = $this.find('button[type=submit]')

            $.ajax({
                type: 'POST',
                cache: false,
                url: $this.prop('action'),
                data: new FormData($this[0]),
                contentType: false,
                processData: false,
                beforeSend: () => {
                    _self.prop('disabled', true).addClass('button-loading')
                },
                success: (res) => {
                    if (typeof refreshRecaptcha !== 'undefined') {
                        refreshRecaptcha()
                    }

                    if (!res.error) {
                        $this.find('input[type=email]').val('')
                        MartApp.showSuccess(res.message)
                    } else {
                        MartApp.showError(res.message)
                    }
                },
                error: (res) => {
                    if (typeof refreshRecaptcha !== 'undefined') {
                        refreshRecaptcha()
                    }
                    MartApp.handleError(res)
                },
                complete: () => {
                    _self.prop('disabled', false).removeClass('button-loading')
                },
            })
        })
    }

    MartApp.contactSellerForm = function () {
        $(document).on('click', 'form.form-contact-store button[type=submit]', function (e) {
            e.preventDefault()
            e.stopPropagation()
            const $this = $(e.currentTarget)

            let $form = $this.closest('form')

            $.ajax({
                type: 'POST',
                cache: false,
                url: $form.prop('action'),
                data: new FormData($form[0]),
                contentType: false,
                processData: false,
                beforeSend: () => {
                    $this.prop('disabled', true).addClass('button-loading')
                },
                success: (res) => {
                    if (typeof refreshRecaptcha !== 'undefined') {
                        refreshRecaptcha()
                    }

                    if (!res.error) {
                        $form.find('input[type=email]:not(:disabled)').val('')
                        $form.find('input[type=text]:not(:disabled)').val('')
                        $form.find('textarea').val('')
                        MartApp.showSuccess(res.message)
                    } else {
                        MartApp.showError(res.message)
                    }
                },
                error: (res) => {
                    if (typeof refreshRecaptcha !== 'undefined') {
                        refreshRecaptcha()
                    }
                    MartApp.handleError(res)
                },
                complete: () => {
                    $this.prop('disabled', false).removeClass('button-loading')
                },
            })
        })
    }

    MartApp.recentlyViewedProducts = function () {
        MartApp.$body.find('.header-recently-viewed').each(function () {
            const $el = $(this)
            let loading
            $el.hover(function () {
                const $recently = $el.find('.recently-viewed-products')
                if ($el.data('loaded') || loading) {
                    return
                }
                const url = $el.data('url')
                if (!url) {
                    return
                }
                $.ajax({
                    type: 'GET',
                    url,
                    beforeSend: () => {
                        loading = true
                    },
                    success: (res) => {
                        if (!res.error) {
                            $recently.html(res.data)

                            if ($recently.find('.product-list li').length > 0) {
                                MartApp.slickSlide($recently.find('.product-list'))
                            }
                            $el.data('loaded', true).find('.loading--wrapper').addClass('d-none')
                        } else {
                            MartApp.showError(res.message)
                        }
                    },
                    error: (res) => {
                        MartApp.handleError(res)
                    },
                    complete: () => {
                        loading = false
                    },
                })
            })
        })
    }

    MartApp.showNotice = function (messageType, message) {
        Theme.showNotice(messageType, message)
    }

    MartApp.showError = function (message) {
        Theme.showError(message)
    }

    MartApp.showSuccess = function (message) {
        Theme.showSuccess(message)
    }

    MartApp.handleError = (data) => {
        Theme.handleError(data)
    }

    MartApp.handleValidationError = (errors) => {
        Theme.handleValidationError(errors)
    }

    MartApp.toggleViewProducts = function () {
        $(document).on('click', '.store-list-filter-button', function (e) {
            e.preventDefault()
            $('#store-listing-filter-form-wrap').toggle(500)
        })

        MartApp.$body.on('click', '.toolbar-view__icon a', function (e) {
            e.preventDefault()
            const $this = $(e.currentTarget)
            $this.closest('.toolbar-view__icon').find('a').removeClass('active')
            $this.addClass('active')
            $($this.data('target')).removeClass($this.data('class-remove')).addClass($this.data('class-add'))

            $(document).find(MartApp.formSearch).find('input[name=layout]').val($this.data('layout'))

            const params = new URLSearchParams(window.location.search)
            params.set('layout', $this.data('layout'))
            const nextHref =
                window.location.protocol +
                '//' +
                window.location.host +
                window.location.pathname +
                '?' +
                params.toString()
            if (nextHref != window.location.href) {
                window.history.pushState(MartApp.$productListing.html(), '', nextHref)
            }
        })
    }

    MartApp.toolbarOrderingProducts = function () {
        MartApp.$body.on('click', '.catalog-toolbar__ordering .dropdown .dropdown-menu a', function (e) {
            e.preventDefault()
            const $this = $(e.currentTarget)
            const $parent = $this.closest('.dropdown')
            $parent.find('li').removeClass('active')
            $this.closest('li').addClass('active')
            $parent.find('a[data-bs-toggle=dropdown').html($this.html())
            $this
                .closest('.catalog-toolbar__ordering')
                .find('input[name=sort-by]')
                .val($this.data('value'))
                .trigger('change')
        })
    }

    MartApp.backToTop = function () {
        let scrollPos = 0
        let element = $('#back2top')
        $(window).scroll(function () {
            let scrollCur = $(window).scrollTop()
            if (scrollCur > scrollPos) {
                // scroll down
                if (scrollCur > 500) {
                    element.addClass('active')
                } else {
                    element.removeClass('active')
                }
            } else {
                // scroll up
                element.removeClass('active')
            }

            scrollPos = scrollCur
        })

        element.on('click', function () {
            $('html, body').animate(
                {
                    scrollTop: '0px',
                },
                0
            )
        })
    }

    MartApp.initMegaMenu = function () {
        setTimeout(function () {
            const $megaMenu = $(document).find('.mega-menu-wrapper')

            if (! $megaMenu.length) {
                return
            }

            if ($(window).width() > 1200 && typeof $.fn.masonry !== 'undefined') {
                $megaMenu.masonry({
                    itemSelector: '.mega-menu__column',
                    columnWidth: 200
                })
            }
        }, 500)
    }

    MartApp.stickyHeader = function () {
        let header = $('.header-js-handler')
        let checkpoint = header.height()
        header.each(function () {
            if ($(this).data('sticky') === true) {
                let el = $(this)
                $(window).scroll(function () {
                    let currentPosition = $(this).scrollTop()
                    if (currentPosition > checkpoint) {
                        el.addClass('header--sticky')

                        MartApp.initMegaMenu()
                    } else {
                        el.removeClass('header--sticky')
                    }
                })
            }
        })
    }

    MartApp.stickyAddToCart = function () {
        let $headerProduct = $('.header--product')
        $(window).scroll(function () {
            let currentPosition = $(this).scrollTop()
            if (currentPosition > 50) {
                $headerProduct.addClass('header--sticky')
            } else {
                $headerProduct.removeClass('header--sticky')
            }
        })

        $('.header--product ul li > a ').on('click', function (e) {
            e.preventDefault()
            let target = $(this).attr('href')
            $(this).closest('li').siblings('li').removeClass('active')
            $(this).closest('li').addClass('active')
            $(target).closest('.product-detail-tabs').find('a').removeClass('active')

            $(target).addClass('active')
            $('.header--product ul li').removeClass('active')
            $('.header--product ul li a[href="' + target + '"]')
                .closest('li')
                .addClass('active')

            $('#product-detail-tabs-content > .tab-pane').removeClass('active show')
            $($(target).attr('href')).addClass('active show')

            $('html, body').animate(
                {
                    scrollTop: $(target).offset().top - $('.header--product .navigation').height() - 165 + 'px',
                },
                0
            )
        })

        const $trigger = $('.product-details .entry-product-header'),
            $stickyBtn = $('.sticky-atc-wrap')

        if ($stickyBtn.length && $trigger.length && $(window).width() < 768) {
            let summaryOffset = $trigger.offset().top + $trigger.outerHeight(),
                _footer = $('.footer-mobile'),
                off_footer = 0,
                ck_footer = _footer.length > 0

            const stickyAddToCartToggle = function () {
                let windowScroll = $(window).scrollTop(),
                    windowHeight = $(window).height(),
                    documentHeight = $(document).height()
                if (ck_footer) {
                    off_footer = _footer.offset().top - _footer.height()
                } else {
                    off_footer = windowScroll
                }
                if (
                    windowScroll + windowHeight === documentHeight ||
                    summaryOffset > windowScroll ||
                    windowScroll > off_footer
                ) {
                    $stickyBtn.removeClass('sticky-atc-shown')
                } else if (summaryOffset < windowScroll && windowScroll + windowHeight !== documentHeight) {
                    $stickyBtn.addClass('sticky-atc-shown')
                }
            }

            stickyAddToCartToggle()

            $(window).scroll(stickyAddToCartToggle)
        }
    }

    $(function () {
        MartApp.init()

        window.onBeforeChangeSwatches = function (data, $attrs) {
            const $product = $attrs.closest('.product-details').length ?
                $attrs.closest('.product-details') :
                $attrs.closest('.ps-product--quickshop')
            const $form = $product.find('.cart-form')

            $product.find('.error-message').hide()
            $product.find('.success-message').hide()
            $product.find('.number-items-available').html('').hide()
            const $submit = $form.find('button[type=submit]')
            $submit.addClass('loading')

            if (data && data.attributes) {
                $submit.prop('disabled', true)
            }
        }

        window.onChangeSwatchesSuccess = function (res, $attrs) {
            const $product = $attrs.closest('.product-details').length ?
                $attrs.closest('.product-details') :
                $attrs.closest('.ps-product--quickshop')
            const $form = $product.find('.cart-form')
            const $footerCartForm = $('.footer-cart-form')
            $product.find('.error-message').hide()
            $product.find('.success-message').hide()

            if (res) {
                let $submit = $form.find('button[type=submit]')
                $submit.removeClass('loading')
                if (res.error) {
                    $submit.prop('disabled', true)
                    $product
                        .find('.number-items-available')
                        .html('<span class="text-danger">(' + res.message + ')</span>')
                        .show()
                    $form.find('.hidden-product-id').val('')
                    $footerCartForm.find('.hidden-product-id').val('')
                } else {
                    const data = res.data
                    // Find the price container in either product details or quick shop modal
                    let $priceContainer = $product.find('.ps-product__header').length ?
                        $product.find('.ps-product__header') :
                        $(document).find('.js-product-content')
                    const $salePrice = $priceContainer.find('.product-price-sale')
                    const $originalPrice = $priceContainer.find('.product-price-original')

                    if (data.sale_price !== data.price) {
                        $salePrice.removeClass('d-none')
                        $originalPrice.addClass('d-none')
                    } else {
                        $salePrice.addClass('d-none')
                        $originalPrice.removeClass('d-none')
                    }

                    $salePrice.find('ins .amount').text(data.display_sale_price)
                    $salePrice.find('del .amount').text(data.display_price)
                    $originalPrice.find('.amount').text(data.display_sale_price)

                    if (data.sku) {
                        $product.find('.meta-sku .meta-value').text(data.sku)
                        $product.find('.meta-sku').removeClass('d-none')
                    } else {
                        $product.find('.meta-sku').addClass('d-none')
                    }

                    $form.find('.hidden-product-id').val(data.id)
                    $footerCartForm.find('.hidden-product-id').val(data.id)
                    $submit.prop('disabled', false)

                    if (data.error_message) {
                        $submit.prop('disabled', true)
                        $product
                            .find('.number-items-available')
                            .html('<span class="text-danger">(' + data.error_message + ')</span>')
                            .show()
                    } else if (data.success_message) {
                        $product.find('.number-items-available').html(res.data.stock_status_html).show()
                        $product.find('.product-quantity-available').text(res.data.success_message)
                        $product.find('.out-of-stock').removeClass('out-of-stock')
                    } else {
                        $product.find('.number-items-available').html('').hide()
                    }

                    $product.find('.bb-product-attribute-swatch-item').removeClass('disabled')
                    $product.find('.bb-product-attribute-swatch-list select option').prop('disabled', false)

                    const unavailableAttributeIds = data.unavailable_attribute_ids || []

                    if (unavailableAttributeIds.length) {
                        unavailableAttributeIds.map((id) => {
                            let $swatchItem = $product.find(`.bb-product-attribute-swatch-item[data-id="${id}"]`)

                            if ($swatchItem.length) {
                                $swatchItem.addClass('disabled')
                                $swatchItem.find('input').prop('checked', false)
                            } else {
                                $swatchItem = $product.find(`.bb-product-attribute-swatch-list select option[data-id="${id}"]`)

                                if ($swatchItem.length) {
                                    $swatchItem.prop('disabled', true)
                                }
                            }
                        })
                    }

                    let imageHtml = ''
                    let thumbHtml = ''

                    if (!data.image_with_sizes.origin.length) {
                        data.image_with_sizes.origin.push(siteConfig.img_placeholder)
                    } else {
                        data.image_with_sizes.origin.forEach(function (item) {
                            imageHtml += `
                    <a href="${item}">
                        <img src="${item}" alt="${data.name}">
                    </a>
                `
                        })
                    }

                    if (!data.image_with_sizes.thumb.length) {
                        data.image_with_sizes.thumb.push(siteConfig.img_placeholder)
                    } else {
                        data.image_with_sizes.thumb.forEach(function (item) {
                            thumbHtml += `
                    <div>
                        <img src="${item}" alt="${data.name}">
                    </div>
                `
                        })
                    }

                    const $galleryImages = $(document).find('.bb-product-gallery-wrapper')

                    $galleryImages.find('.bb-product-gallery-thumbnails').slick('unslick').html(thumbHtml)

                    const $quickViewGalleryImages = $(document).find('.bb-quick-view-gallery-images')

                    if ($quickViewGalleryImages.length) {
                        $quickViewGalleryImages.slick('unslick').html(imageHtml)
                    }

                    $galleryImages.find('.bb-product-gallery-images').slick('unslick').html(imageHtml)

                    if (typeof EcommerceApp !== 'undefined') {
                        EcommerceApp.initProductGallery()
                    }
                }
            }
        }

        if (jQuery().mCustomScrollbar) {
            $(document).find('.ps-custom-scrollbar').mCustomScrollbar({
                theme: 'dark',
                scrollInertia: 0,
            })
        }

        $(document).on('click', '.toggle-show-more', function (event) {
            event.preventDefault()

            $('#store-short-description').fadeOut()

            $(this).addClass('d-none')

            $('#store-content').removeClass('d-none').slideDown(500)

            $('.toggle-show-less').removeClass('d-none')
        })

        $(document).on('click', '.toggle-show-less', function (event) {
            event.preventDefault()

            $(this).addClass('d-none')

            $('#store-content').slideUp(500).addClass('d-none')

            $('#store-short-description').fadeIn()

            $('.toggle-show-more').removeClass('d-none')
        })

        let collapseBreadcrumb = function () {
            $('.page-breadcrumbs ol li').each(function () {
                let $this = $(this)
                if (!$this.is(':first-child') && !$this.is(':nth-child(2)') && !$this.is(':last-child')) {
                    if (!$this.is(':nth-child(3)')) {
                        $this.find('a').closest('li').hide()
                    } else {
                        $this.find('a').hide()
                        $this.find('.extra-breadcrumb-name').text('...').show()
                    }
                }
            })
        }

        if ($(window).width() < 768) {
            collapseBreadcrumb()
        }

        $(window).on('resize', function () {
            collapseBreadcrumb()
        })

        $('.product-entry-meta .anchor-link').on('click', function (e) {
            e.preventDefault()
            let target = $(this).attr('href')

            $('#product-detail-tabs a').removeClass('active')
            $(target).addClass('active')

            $('#product-detail-tabs-content > .tab-pane').removeClass('active show')
            $($(target).attr('href')).addClass('active show')

            $('html, body').animate(
                {
                    scrollTop: $(target).offset().top - $('.header--product .navigation').height() - 250 + 'px',
                },
                0
            )
        })

        $(document).on('click', '#sticky-add-to-cart .add-to-cart-button', (e) => {
            e.preventDefault()
            e.stopPropagation()

            const $this = $(e.currentTarget)

            $this.addClass('button-loading')

            setTimeout(function () {
                let target = '.js-product-content .cart-form button[name=' + $this.prop('name') + '].add-to-cart-button'

                $(document).find(target).trigger('click')

                $this.removeClass('button-loading')
            }, 200)
        })

        $(document).ready(function() {
            MartApp.initMegaMenu()
        })

        document.addEventListener('ecommerce.product-filter.before', () => {
            MartApp.$productListing.find('.loading').show()
        })

        document.addEventListener('ecommerce.product-filter.completed', () => {
            MartApp.lazyLoad(MartApp.$productListing[0])
        })

        document.addEventListener('ecommerce.categories-dropdown.success', () => {
            MartApp.initMegaMenu()
        })
        
        // Listen for quick shop completed event to initialize variation listeners
        document.addEventListener('ecommerce.quick-shop.completed', (e) => {
            const { modal } = e.detail
            if (modal && modal.length) {
                MartApp.initQuickShopVariationListeners(modal)
            }
        })
    })
})(jQuery)
