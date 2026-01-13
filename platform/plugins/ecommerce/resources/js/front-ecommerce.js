class Ecommerce {
    quickSearchAjax = null
    filterAjax = null
    lastFilterFormData = null
    lastFilterFormAction = null
    filterTimeout = null

    constructor() {
        this.initClipboard()

        $(document)
            .on('click', '[data-bb-toggle="toggle-product-categories-tree"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                currentTarget.toggleClass('active')
                currentTarget.closest('.bb-product-filter-item').find('> .bb-product-filter-items').slideToggle().toggleClass('active')
            })
            .on('click', '[data-bb-toggle="toggle-filter-sidebar"]', () => {
                $('.bb-filter-offcanvas-area').toggleClass('offcanvas-opened')
                $('.body-overlay').toggleClass('opened')
            })
            .on('click', '.body-overlay', () => {
                $('.bb-filter-offcanvas-area').removeClass('offcanvas-opened')
                $('.body-overlay').removeClass('opened')
            })
            .on('submit', 'form.bb-product-form-filter', (e) => {
                e.preventDefault()

                const form = $(e.currentTarget)

                // Create a new FormData object
                const formData = this.#transformFormData(form.serializeArray())
                const url = form.prop('action')
                let nextUrl = url

                // Use URLSearchParams to prevent duplicate parameters
                const searchParams = new URLSearchParams()

                // Group parameters by name to handle arrays and prevent duplicates
                const paramsByName = {}
                const attributeArrays = {}

                // Get current URL parameters to check if search query has changed
                const currentUrlParams = new URLSearchParams(window.location.search)
                const currentSearchQuery = currentUrlParams.get('q') || ''
                let newSearchQuery = ''

                formData.forEach((item) => {
                    // Handle attribute arrays like attributes[color][]
                    if (item.name.includes('attributes[') && item.name.endsWith('[]')) {
                        if (!attributeArrays[item.name]) {
                            attributeArrays[item.name] = []
                        }
                        attributeArrays[item.name].push(item.value)
                    } else if (!paramsByName[item.name]) {
                        paramsByName[item.name] = item.value
                        // Track the new search query
                        if (item.name === 'q') {
                            newSearchQuery = item.value
                        }
                    }
                })

                // If search query has changed, remove the page parameter
                if (newSearchQuery !== currentSearchQuery && paramsByName['page']) {
                    delete paramsByName['page']
                }

                // Add regular parameters to URLSearchParams
                Object.keys(paramsByName).forEach(name => {
                    if (paramsByName[name]) {
                        searchParams.set(name, paramsByName[name])
                    }
                })

                // Add attribute arrays to URLSearchParams
                Object.keys(attributeArrays).forEach(name => {
                    attributeArrays[name].forEach(value => {
                        searchParams.append(name, value)
                    })
                })

                // Build the URL
                const queryString = searchParams.toString()
                if (queryString) {
                    nextUrl += `?${queryString}`
                }

                // Add timestamp to formData for AJAX but not for URL
                const formDataWithTimestamp = [...formData, { name: '_', value: Date.now() }]

                // Don't reload if URL is the same
                if (window.location.href === nextUrl) {
                    return
                }

                // Don't send duplicate requests with the same parameters and same action URL
                const formDataString = JSON.stringify(formData)
                const currentFormAction = form.prop('action')

                if (this.lastFilterFormData === formDataString && this.lastFilterFormAction === currentFormAction) {
                    return
                }

                this.lastFilterFormData = formDataString
                this.lastFilterFormAction = currentFormAction

                // Cancel any pending AJAX request
                if (this.filterAjax) {
                    this.filterAjax.abort()
                }

                // Clear any pending timeout
                if (this.filterTimeout) {
                    clearTimeout(this.filterTimeout)
                }

                // Add a small delay to prevent rapid-fire requests
                this.filterTimeout = setTimeout(() => {
                    this.#ajaxFilterForm(url, formDataWithTimestamp, nextUrl)
                }, 100)
            })
            .on('change', 'form.bb-product-form-filter input, form.bb-product-form-filter select', (e) => {
                const currentTarget = $(e.currentTarget)
                const form = currentTarget.closest('form')

                const inputName = currentTarget.attr('name')

                if (inputName.includes('attributes[')) {
                    form.trigger('submit')
                } else if (currentTarget.attr('type') === 'checkbox' && inputName && inputName.endsWith('[]')) {
                    // Special handling for checkboxes with array notation
                    const baseName = inputName.slice(0, -2)
                    const checkboxes = form.find(`input[name="${inputName}"]`).filter(':checked')

                    // Find or create the non-array input
                    let singleInput = form.find(`input[name="${baseName}"]`)
                    if (!singleInput.length) {
                        // Create a hidden input with the base name
                        form.append(`<input type="hidden" name="${baseName}" value="">`)
                        singleInput = form.find(`input[name="${baseName}"]`)
                    }

                    // Collect values from checked checkboxes
                    const values = checkboxes.map(function() {
                        return $(this).val()
                    }).get()

                    // Set the comma-separated values
                    singleInput.val(values.join(','))
                }

                form.trigger('submit')
            })
            .on('keyup', '.bb-form-quick-search input', (e) => {
                this.#ajaxSearchProducts($(e.currentTarget).closest('form'))
            })
            .on('click', 'body', (e) => {
                if (!$(e.target).closest('.bb-form-quick-search').length) {
                    $('.bb-quick-search-results').removeClass('show').html('')
                }
            })
            .on('click', '[data-bb-toggle="quick-shop"]', (e) => {
                e.preventDefault()
                e.stopPropagation()

                const currentTarget = $(e.currentTarget)
                const modal = $('#quick-shop-modal')

                $.ajax({
                    url: currentTarget.data('url'),
                    type: 'GET',
                    beforeSend: () => {
                        if (modal.find('.quick-shop-content').length) {
                            modal.find('.quick-shop-content').html('')
                            modal.find('.modal-body').addClass('modal-empty')
                            modal.find('.loading-spinner').removeClass('d-none').show()
                            modal.find('.quick-shop-content').hide()
                        } else {
                            modal.find('.modal-body').html('')
                        }

                        modal.modal('show')

                        document.dispatchEvent(
                            new CustomEvent('ecommerce.quick-shop.before-send', {
                                detail: {
                                    element: currentTarget,
                                    modal,
                                },
                            })
                        )
                    },
                    success: ({ data }) => {
                        if (modal.find('.quick-shop-content').length) {
                            modal.find('.quick-shop-content').html(data)
                            modal.find('.loading-spinner').addClass('d-none').hide()
                            modal.find('.modal-body').removeClass('modal-empty')
                            modal.find('.quick-shop-content').show()
                        } else {
                            modal.find('.modal-body').html(data)
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => {
                        document.dispatchEvent(
                            new CustomEvent('ecommerce.quick-shop.completed', {
                                detail: {
                                    element: currentTarget,
                                    modal,
                                },
                            })
                        )
                    },
                })
            })
            .on('click', '.bb-product-filter-link', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)
                const form = currentTarget.closest('form')
                const parent = currentTarget.closest('.bb-product-filter')
                const categoryId = currentTarget.data('id')

                // Check if we have existing categories input
                let categoriesInput = form.find('input[name="categories"]')

                // If we don't have a single categories input, look for array notation
                if (!categoriesInput.length) {
                    categoriesInput = form.find('input[name="categories[]"]')
                }

                parent.find('.bb-product-filter-link').removeClass('active')
                currentTarget.addClass('active')

                // Remove pagination parameters when changing categories
                form.find('input[name="page"]').remove()
                form.find('input[name="per-page"]').remove()

                if (categoriesInput.length && categoryId) {
                    // If using array notation
                    if (categoriesInput.attr('name') === 'categories[]') {
                        categoriesInput.val(categoryId).trigger('change')
                    } else {
                        // If using comma-separated values
                        categoriesInput.val(categoryId).trigger('change')
                    }
                } else {
                    if (!categoryId) {
                        if (categoriesInput.length) {
                            categoriesInput.val(null)
                        }
                    }

                    form.prop('action', currentTarget.prop('href')).trigger('submit')
                }
            })
            .on('click', '.bb-product-filter-clear', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                this.#ajaxFilterForm(currentTarget.prop('href'))
            })
            .on('click', '.bb-product-filter-clear-all', (e) => {
                e.preventDefault()

                const form = $('.bb-product-form-filter')

                // Clear all inputs
                form.find(
                    'input[type="text"], input[type="hidden"], input[type="radio"], select'
                ).val(null)

                // Uncheck all checkboxes
                form.find('input[type="checkbox"]').prop('checked', false)

                form.trigger('submit')
            })
            .on('submit', 'form#cancel-order-form', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)
                const modal = currentTarget.closest('.modal')
                const button = modal.find('button[type="submit"]')

                $.ajax({
                    url: currentTarget.prop('action'),
                    type: 'POST',
                    data: currentTarget.serialize(),
                    beforeSend: () => {
                        button.addClass('btn-loading')
                    },
                    success: ({ error, message }) => {
                        if (error) {
                            Theme.showError(message)

                            return
                        }

                        Theme.showSuccess(message)

                        modal.modal('hide')

                        setTimeout(() => window.location.reload(), 1000)
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => button.removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="add-to-compare"]', function (e) {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                const url = currentTarget.hasClass('active')
                    ? currentTarget.data('remove-url')
                    : currentTarget.data('url')
                let data = {}

                if (currentTarget.hasClass('active')) {
                    data = { _method: 'DELETE' }
                }

                $.ajax({
                    url,
                    method: 'POST',
                    data,
                    beforeSend: () => currentTarget.addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            Theme.showSuccess(message)
                            currentTarget.toggleClass('active')

                            if (data.count !== undefined) {
                                $('[data-bb-value="compare-count"]').text(data.count)
                            }

                            if (currentTarget.hasClass('active')) {
                                document.dispatchEvent(
                                    new CustomEvent('ecommerce.compare.added', {
                                        detail: { data, element: currentTarget },
                                    })
                                )
                            } else {
                                document.dispatchEvent(
                                    new CustomEvent('ecommerce.compare.removed', {
                                        detail: { data, element: currentTarget },
                                    })
                                )
                            }
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="remove-from-compare"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)
                const table = currentTarget.closest('table')

                $.ajax({
                    url: currentTarget.data('url'),
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                    },
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            Theme.showSuccess(message)

                            document.dispatchEvent(
                                new CustomEvent('ecommerce.compare.removed', {
                                    detail: { data, element: currentTarget },
                                })
                            )

                            if (data.count !== undefined) {
                                $('[data-bb-value="compare-count"]').text(data.count)
                            }

                            if (data.count > 0) {
                                table.find(`td:nth-child(${currentTarget.closest('td').index() + 1})`).remove()
                            } else {
                                window.location.reload()
                            }
                        }
                    },
                    error: (error) => Theme.handleError(error),
                })
            })
            .on('click', '[data-bb-toggle="add-to-wishlist"]', function (e) {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                const url = currentTarget.data('url')

                $.ajax({
                    url,
                    method: 'POST',
                    beforeSend: () => currentTarget.addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            if (data.count !== undefined) {
                                $('[data-bb-value="wishlist-count"]').text(data.count)
                            }

                            Theme.showSuccess(message)

                            document.dispatchEvent(
                                new CustomEvent('ecommerce.wishlist.added', {
                                    detail: { data, element: currentTarget },
                                })
                            )
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="remove-from-wishlist"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                $.ajax({
                    url: currentTarget.data('url'),
                    method: 'POST',
                    data: { _method: 'DELETE' },
                    beforeSend: () => currentTarget.addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            Theme.showSuccess(message)

                            if (data.count !== undefined) {
                                $('[data-bb-value="wishlist-count"]').text(data.count)
                            }

                            currentTarget.closest('tr').remove()

                            if (data.count === 0) {
                                window.location.reload()
                            }

                            document.dispatchEvent(
                                new CustomEvent('ecommerce.wishlist.removed', {
                                    detail: { data, element: currentTarget },
                                })
                            )
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="add-to-cart"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)
                const data = {
                    id: currentTarget.data('id'),
                }

                const quantity = currentTarget.closest('tr').find('input[name="qty"]')

                if (quantity) {
                    data.qty = quantity.val()
                }

                $.ajax({
                    url: currentTarget.data('url'),
                    method: 'POST',
                    data: data,
                    dataType: 'json',
                    beforeSend: () => currentTarget.addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)

                            if (data.next_url !== undefined) {
                                setTimeout(() => {
                                    window.location.href = data.next_url
                                }, 500);
                            }

                            return false
                        }

                        if (data && data.next_url !== undefined) {
                            window.location.href = data.next_url

                            return false
                        }

                        let showSuccess = true

                        if (currentTarget.data('show-toast-on-success') !== undefined) {
                            showSuccess = currentTarget.data('show-toast-on-success')
                        }

                        if (showSuccess) {
                            Theme.showSuccess(message)
                        }

                        if (data.count !== undefined) {
                            $('[data-bb-value="cart-count"]').text(data.count)
                        }

                        document.dispatchEvent(
                            new CustomEvent('ecommerce.cart.added', {
                                detail: { data, element: currentTarget, message },
                            })
                        )
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="remove-from-cart"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                $.ajax({
                    url: currentTarget.prop('href') || currentTarget.data('url'),
                    method: 'GET',
                    beforeSend: () => currentTarget.addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            Theme.showSuccess(message)

                            currentTarget.closest('tr').remove()

                            if (data.count !== undefined) {
                                $('[data-bb-value="cart-count"]').text(data.count)
                            }

                            if (data.count === 0) {
                                window.location.reload()
                            }

                            document.dispatchEvent(
                                new CustomEvent('ecommerce.cart.removed', {
                                    detail: { data, element: currentTarget },
                                })
                            )
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.removeClass('btn-loading'),
                })
            })
            .on('submit', '[data-bb-toggle="coupon-form"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)
                const button = currentTarget.find('button[type="submit"]')

                $.ajax({
                    url: currentTarget.prop('action'),
                    type: 'POST',
                    data: currentTarget.serialize(),
                    beforeSend: () => button.prop('disabled', true).addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            Theme.showSuccess(message)

                            document.dispatchEvent(
                                new CustomEvent('ecommerce.coupon.applied', {
                                    detail: { data, element: currentTarget },
                                })
                            )
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => button.prop('disabled', false).removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="quick-view-product"]', (e) => {
                e.preventDefault()

                const currentTarget = $(e.currentTarget)

                $.ajax({
                    url: currentTarget.data('url'),
                    type: 'GET',
                    beforeSend: () => currentTarget.prop('disabled', true).addClass('btn-loading'),
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)
                        } else {
                            const quickViewModal = $('[data-bb-toggle="quick-view-modal"]')
                            quickViewModal.modal('show')
                            quickViewModal.find('.modal-body').html(data)

                            document.dispatchEvent(
                                new CustomEvent('ecommerce.quick-view.initialized', {
                                    detail: { data, element: currentTarget },
                                })
                            )

                            setTimeout(() => {
                                this.initProductGallery(true)
                            }, 100)
                        }
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.prop('disabled', false).removeClass('btn-loading'),
                })
            })
            .on('click', '[data-bb-toggle="product-form"] button[type="submit"]', (e) => {
                e.preventDefault()
                const currentTarget = $(e.currentTarget)
                const form = currentTarget.closest('form')
                const data = form.serializeArray()

                if (form.find('input[name="id"]').val() === '') {
                    return
                }

                data.push({ name: 'checkout', value: currentTarget.prop('name') === 'checkout' ? 1 : 0 })

                $.ajax({
                    type: 'POST',
                    url: form.prop('action'),
                    data: data,
                    beforeSend: () => {
                        currentTarget.prop('disabled', true).addClass('btn-loading')
                    },
                    success: ({ error, message, data }) => {
                        if (error) {
                            Theme.showError(message)

                            return
                        }
                        Theme.showSuccess(message)

                        form.find('input[name="qty"]').val(1)

                        if (data.count !== undefined) {
                            $('[data-bb-value="cart-count"]').text(data.count)
                        }

                        document.dispatchEvent(
                            new CustomEvent('ecommerce.cart.added', {
                                detail: { data, element: currentTarget },
                            })
                        )
                    },
                    error: (error) => Theme.handleError(error),
                    complete: () => currentTarget.prop('disabled', false).removeClass('btn-loading'),
                })
            })
            .on('change', '[data-bb-toggle="product-form-filter-item"]', (e) => {
                const currentTarget = $(e.currentTarget)
                const $form = $('.bb-product-form-filter')
                const name = currentTarget.prop('name')
                const value = currentTarget.val()

                if (name.endsWith('[]') && ! name.includes('attributes[')) {
                    const baseName = name.slice(0, -2)
                    let $input = $form.find(`input[name="${baseName}"]`)

                    // If we don't have a single input, create one
                    if (!$input.length) {
                        $form.append(`<input type="hidden" name="${baseName}" value="">`)
                        $input = $form.find(`input[name="${baseName}"]`)
                    }

                    // Get current values as array and remove duplicates
                    let currentValues = $input.val() ? $input.val().split(',') : []

                    // Use a Set to automatically remove duplicates
                    const uniqueValues = new Set(currentValues)

                    // Add the new value
                    uniqueValues.add(value)

                    // Convert back to array and set the comma-separated values
                    $input.val(Array.from(uniqueValues).join(','))
                } else {
                    const isCheckbox = currentTarget.is(':checkbox')

                    if (isCheckbox) {
                        const isChecked = currentTarget.prop('checked')
                        const $input = $form.find(`input[type="hidden"][name="${name}"]`)

                        if ($input.length) {
                            $input.val(isChecked ? value : '')
                        } else {
                            $form.append(`<input type="hidden" name="${name}" value="${isChecked ? value : ''}">`)
                        }
                    } else {
                        const $input = $form.find(`input[name="${name}"]`)

                        if ($input.length) {
                            $input.val(value)
                        }
                    }
                }

                $form.trigger('submit')
            })

        if ($('.bb-product-price-filter').length) {
            this.initPriceFilter()
        }

        this.#initCategoriesDropdown()
    }

    /**
     * @returns {boolean}
     */
    isRtl() {
        return document.body.getAttribute('dir') === 'rtl'
    }

    /**
     * @param {JQuery} element
     */
    initLightGallery(element) {
        if (!element.length) {
            return
        }

        if (element.data('lightGallery')) {
            element.data('lightGallery').destroy(true)
        }

        element.lightGallery({
            selector: 'a',
            thumbnail: true,
            share: false,
            fullScreen: false,
            autoplay: false,
            autoplayControls: false,
            actualSize: false,
        })
    }

    initProductGallery(onlyQuickView = false) {
        if (!onlyQuickView) {
            const $gallery = $(document).find('.bb-product-gallery-images')

            if (!$gallery.length) {
                return
            }

            const $thumbnails = $(document).find('.bb-product-gallery-thumbnails')

            function postMessageToPlayer(player, command) {
                if (player == null || command == null) return
                player.contentWindow.postMessage(JSON.stringify(command), '*')
            }

            function playPauseVideo(slick, control) {
                let currentSlide, slideType, startTime, player, video

                currentSlide = slick.find('.slick-current')
                slideType = currentSlide.data('provider')
                player = currentSlide.get(0)
                startTime = currentSlide.data('video-start')

                if (slideType === 'vimeo') {
                    switch (control) {
                        case 'play':
                            if (startTime != null && startTime > 0 && !currentSlide.hasClass('started')) {
                                currentSlide.addClass('started')
                                postMessageToPlayer(player, {
                                    method: 'setCurrentTime',
                                    value: startTime,
                                })
                            }
                            postMessageToPlayer(player, {
                                method: 'play',
                                value: 1,
                            })
                            break
                        case 'pause':
                            postMessageToPlayer(player, {
                                method: 'pause',
                                value: 1,
                            })
                            break
                    }
                } else if (slideType === 'youtube') {
                    switch (control) {
                        case 'play':
                            postMessageToPlayer(player, {
                                event: 'command',
                                func: 'mute',
                            })
                            postMessageToPlayer(player, {
                                event: 'command',
                                func: 'playVideo',
                            })
                            break
                        case 'pause':
                            postMessageToPlayer(player, {
                                event: 'command',
                                func: 'pauseVideo',
                            })
                            break
                    }
                } else if (slideType === 'video') {
                    video = currentSlide.children('video').get(0)
                    if (video != null) {
                        if (control === 'play') {
                            video.play()
                        } else {
                            video.pause()
                        }
                    }
                }
            }

            $gallery.on('init', function (slick) {
                slick = $(slick.currentTarget)
                setTimeout(function () {
                    playPauseVideo(slick, 'play')
                }, 1000)
            })

            $gallery.on('beforeChange', function (event, slick) {
                slick = $(slick.$slider)
                playPauseVideo(slick, 'pause')
            })

            $gallery.on('afterChange', function (event, slick) {
                slick = $(slick.$slider)
                playPauseVideo(slick, 'play')
            })

            $(document).on('click', '.bb-button-trigger-play-video', function (e) {
                const $button = $(e.currentTarget)
                const videoElement = document.getElementById($button.data('target'))

                videoElement.play()

                $button.closest('.bb-product-video').addClass('video-playing')

                videoElement.addEventListener('ended', () => {
                    $button.closest('.bb-product-video').removeClass('video-playing')
                    videoElement.currentTime = 0;
                    videoElement.pause();
                });
            })

            if ($gallery.length) {
                $gallery.map((index, item) => {
                    const $item = $(item)
                    if ($item.hasClass('slick-initialized')) {
                        $item.slick('unslick')
                    }

                    $item.slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                        dots: false,
                        infinite: false,
                        fade: true,
                        lazyLoad: 'ondemand',
                        asNavFor: '.bb-product-gallery-thumbnails',
                        rtl: this.isRtl(),
                    })
                })
            }

            if ($thumbnails.length) {
                let isVertical = $thumbnails.data('vertical') === 1

                if (window.innerWidth < 768) {
                    isVertical = false
                }

                $thumbnails.slick({
                    slidesToShow: 6,
                    slidesToScroll: 1,
                    asNavFor: '.bb-product-gallery-images',
                    focusOnSelect: true,
                    infinite: false,
                    rtl: this.isRtl() && ! isVertical,
                    vertical: isVertical,
                    verticalSwiping: isVertical,
                    prevArrow:
                        '<button class="slick-prev slick-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg></button>',
                    nextArrow:
                        '<button class="slick-next slick-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg></button>',
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 4,
                            },
                        },
                    ],
                })
            }

            this.initLightGallery($gallery)

            if (typeof Theme.lazyLoadInstance !== 'undefined') {
                Theme.lazyLoadInstance.update()
            }
        }

        const $quickViewGallery = $(document).find('.bb-quick-view-gallery-images')

        if ($quickViewGallery.length) {
            if ($quickViewGallery.hasClass('slick-initialized')) {
                $quickViewGallery.slick('unslick')
            }

            $quickViewGallery.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: false,
                arrows: true,
                adaptiveHeight: false,
                rtl: this.isRtl(),
            })
        }

        this.initLightGallery($quickViewGallery)
    }

    initPriceFilter() {
        if (typeof $.fn.slider === 'undefined') {
            throw new Error('jQuery UI slider is required for price filter')
        }

        const $priceFilter = $(document).find('.bb-product-price-filter')
        const $sliderRange = $priceFilter.find('.price-slider')
        const $rangeLabel = $priceFilter.find('.input-range-label')

        if ($priceFilter) {
            const $minPrice = $priceFilter.find('input[name="min_price"]')
            const $maxPrice = $priceFilter.find('input[name="max_price"]')

            // Get current values from form inputs (important for AJAX reinitialization)
            const currentMinPrice = parseInt($minPrice.val()) || $sliderRange.data('min')
            const currentMaxPrice = parseInt($maxPrice.val()) || $sliderRange.data('max')
            const isRtl = this.isRtl()

            // For RTL: Initialize slider with inverted range to visually reverse it
            const sliderMin = isRtl ? -$sliderRange.data('max') : $sliderRange.data('min')
            const sliderMax = isRtl ? -$sliderRange.data('min') : $sliderRange.data('max')
            const sliderValues = isRtl ? [-currentMaxPrice, -currentMinPrice] : [currentMinPrice, currentMaxPrice]

            $sliderRange.slider({
                range: true,
                min: sliderMin,
                max: sliderMax,
                values: sliderValues,
                slide: function (_, ui) {
                    // Convert UI values back to real values for RTL
                    const realMin = isRtl ? -ui.values[1] : ui.values[0]
                    const realMax = isRtl ? -ui.values[0] : ui.values[1]

                    $rangeLabel.find('.from').text(this.formatPrice(realMin))
                    $rangeLabel.find('.to').text(this.formatPrice(realMax))
                }.bind(this),
                change: function (_, ui) {
                    // Convert UI values back to real values for RTL
                    const realMin = isRtl ? -ui.values[1] : ui.values[0]
                    const realMax = isRtl ? -ui.values[0] : ui.values[1]

                    if (parseInt(currentMinPrice) !== realMin) {
                        $minPrice.val(realMin).trigger('change')
                    }

                    if (parseInt(currentMaxPrice) !== realMax) {
                        $maxPrice.val(realMax).trigger('change')
                    }
                }.bind(this),
            })

            // Initialize display values correctly
            const currentValues = $sliderRange.slider('values')
            const displayMin = isRtl ? -currentValues[1] : currentValues[0]
            const displayMax = isRtl ? -currentValues[0] : currentValues[1]

            $rangeLabel.find('.from').text(this.formatPrice(displayMin))
            $rangeLabel.find('.to').text(this.formatPrice(displayMax))
        }
    }

    formatPrice(price, numberAfterDot, x) {
        const currencies = window.currencies || {}

        if (!numberAfterDot) {
            numberAfterDot = currencies.number_after_dot !== undefined ? currencies.number_after_dot : 2
        }

        const regex = '\\d(?=(\\d{' + (x || 3) + '})+$)'
        let priceUnit = ''

        if (currencies.show_symbol_or_title) {
            priceUnit = currencies.symbol || currencies.title || ''
        }

        if (currencies.display_big_money) {
            let label = ''

            if (price >= 1000000 && price < 1000000000) {
                price = price / 1000000
                label = currencies.million
            } else if (price >= 1000000000) {
                price = price / 1000000000
                label = currencies.billion
            }

            priceUnit = label + (priceUnit ? ` ${priceUnit}` : '')
        }

        price = price.toFixed(Math.max(0, ~~numberAfterDot)).toString().split('.')

        price =
            price[0].toString().replace(new RegExp(regex, 'g'), `$&${currencies.thousands_separator}`) +
            (price[1] ? currencies.decimal_separator + price[1] : '')

        if (currencies.show_symbol_or_title) {
            price = currencies.is_prefix_symbol ? priceUnit + price : price + priceUnit
        }

        return price
    }

    #transformFormData = (formData) => {
        let data = []
        let groupedData = {}
        let seenParams = {}

        // Group array parameters and handle duplicates
        formData.forEach((item) => {
            if (!item.value) {
                return
            }

            if (item.name.includes('attributes[')) {
                if (item.value) {
                    data.push(item)
                }
            } else if (item.name.endsWith('[]')) { // Check if it's an array parameter (ends with [])
                const baseName = item.name.slice(0, -2)
                if (!groupedData[baseName]) {
                    groupedData[baseName] = new Set()
                }
                // Use a Set to automatically remove duplicates
                groupedData[baseName].add(item.value)
            } else {
                // For non-array parameters, only keep the first occurrence
                if (!seenParams[item.name]) {
                    seenParams[item.name] = true
                    data.push(item)
                }
            }
        })

        // Add grouped parameters as comma-separated values
        Object.keys(groupedData).forEach(key => {
            const values = Array.from(groupedData[key])
            if (values.length > 0) {
                data.push({
                    name: key,
                    value: values.join(',')
                })
            }
        })

        return data
    }

    highlightSearchKeywords = ($element, phrase) => {
        if (!phrase.trim()) {
            return
        }

        let keywords = phrase.trim().split(/\s+/)
        let regex = new RegExp(`(${keywords.join('|')})`, 'gi')

        $element.html($element.text().replace(regex, '<span class="bb-quick-search-highlight">$1</span>'))
    }

    #ajaxSearchProducts = (form, url) => {
        const button = form.find('button[type="submit"]')
        const input = form.find('input[name="q"]')
        const results = form.find('.bb-quick-search-results')

        if (!input.val()) {
            results.removeClass('show').html('')

            return
        }

        this.quickSearchAjax = $.ajax({
            type: 'GET',
            url: url || form.data('ajax-url'),
            data: form.serialize(),
            beforeSend: () => {
                button.addClass('btn-loading')

                if (!url) {
                    results.removeClass('show').html('')
                }

                if (this.quickSearchAjax !== null) {
                    this.quickSearchAjax.abort()
                }
            },
            success: ({ error, message, data }) => {
                if (error) {
                    Theme.showError(message)

                    return
                }

                results.addClass('show')

                if (url) {
                    results.find('.bb-quick-search-list').append($(data).find('.bb-quick-search-list').html())
                } else {
                    results.html(data)
                }

                let that = this

                let searchPhrase = input.val()
                results.find('.bb-quick-search-item-name').each(function () {
                    $(this).html($(this).text())

                    if (searchPhrase) {
                        that.highlightSearchKeywords($(this), searchPhrase)
                    }
                })


                if (typeof Theme.lazyLoadInstance !== 'undefined') {
                    Theme.lazyLoadInstance.update()
                }
            },
            complete: () => button.removeClass('btn-loading'),
        })
    }

    #ajaxFilterForm = (url, data, nextUrl) => {
        const form = $('.bb-product-form-filter')

        // If a direct URL is provided without form data, reset the tracking variables
        if (url && !data) {
            this.lastFilterFormData = null
            this.lastFilterFormAction = null
        }

        // If data is an array, convert it to a proper format for AJAX
        let ajaxData = data
        if (Array.isArray(data)) {
            // Convert to URLSearchParams to handle duplicates and arrays
            const params = new URLSearchParams()
            const paramsByName = {}
            const attributeArrays = {}

            // Get current URL parameters to check if search query has changed
            const currentUrlParams = new URLSearchParams(window.location.search)
            const currentSearchQuery = currentUrlParams.get('q') || ''
            let newSearchQuery = ''

            // Group by name to handle arrays and prevent duplicates
            data.forEach(item => {
                if (item.name && item.value) {
                    // Handle attribute arrays like attributes[color][]
                    if (item.name.includes('attributes[') && item.name.endsWith('[]')) {
                        if (!attributeArrays[item.name]) {
                            attributeArrays[item.name] = []
                        }
                        attributeArrays[item.name].push(item.value)
                    } else {
                        paramsByName[item.name] = item.value
                        // Track the new search query
                        if (item.name === 'q') {
                            newSearchQuery = item.value
                        }
                    }
                }
            })

            // If search query has changed, remove the page parameter
            if (newSearchQuery !== currentSearchQuery && paramsByName['page']) {
                delete paramsByName['page']
            }

            // Add regular parameters to URLSearchParams
            Object.keys(paramsByName).forEach(name => {
                params.set(name, paramsByName[name])
            })

            // Add attribute arrays to URLSearchParams
            Object.keys(attributeArrays).forEach(name => {
                attributeArrays[name].forEach(value => {
                    params.append(name, value)
                })
            })

            // Convert to object for jQuery AJAX while preserving arrays
            ajaxData = {}

            // Handle regular parameters
            Object.keys(paramsByName).forEach(name => {
                ajaxData[name] = paramsByName[name]
            })

            // Handle attribute arrays - convert to array format that jQuery understands
            Object.keys(attributeArrays).forEach(name => {
                ajaxData[name] = attributeArrays[name]
            })
        }

        this.filterAjax = $.ajax({
            url: url,
            type: 'GET',
            data: ajaxData,
            beforeSend: () => {
                document.dispatchEvent(
                    new CustomEvent('ecommerce.product-filter.before', {
                        detail: {
                            data: ajaxData,
                            element: form,
                        },
                    })
                )
            },
            success: (data) => {
                const { message, error } = data

                if (error) {
                    Theme.showError(message)
                    this.filterAjax = null
                    return
                }

                // Ensure the URL doesn't have duplicate parameters and handles attribute arrays
                let finalUrl = nextUrl || url
                if (finalUrl.includes('?')) {
                    const urlParts = finalUrl.split('?')
                    const baseUrl = urlParts[0]
                    const params = new URLSearchParams(urlParts[1])

                    // Handle both regular parameters and attribute arrays
                    const uniqueParams = new URLSearchParams()
                    const attributeArrays = {}

                    for (const [key, value] of params.entries()) {
                        // Handle attribute arrays like attributes[color][]
                        if (key.includes('attributes[') && key.endsWith('[]')) {
                            if (!attributeArrays[key]) {
                                attributeArrays[key] = []
                            }
                            attributeArrays[key].push(value)
                        } else if (!uniqueParams.has(key)) {
                            uniqueParams.set(key, value)
                        }
                    }

                    // Add attribute arrays to URLSearchParams
                    Object.keys(attributeArrays).forEach(key => {
                        attributeArrays[key].forEach(value => {
                            uniqueParams.append(key, value)
                        })
                    })

                    finalUrl = baseUrl + '?' + uniqueParams.toString()
                }

                window.history.pushState(data, null, finalUrl)

                document.dispatchEvent(
                    new CustomEvent('ecommerce.product-filter.success', {
                        detail: {
                            data,
                            element: form,
                        },
                    })
                )

                // Reset filterAjax after successful request
                this.filterAjax = null

                if ($('.bb-product-price-filter').length) {
                    EcommerceApp.initPriceFilter()
                }
            },
            error: (xhr) => {
                // Don't show error for aborted requests
                if (xhr.statusText !== 'abort') {
                    console.error('Filter request failed:', xhr)
                    Theme.handleError(xhr)
                }
            },
            complete: () => {
                // Always reset the filter timeout and AJAX request
                this.filterTimeout = null
                this.filterAjax = null
                if (typeof Theme.lazyLoadInstance !== 'undefined') {
                    Theme.lazyLoadInstance.update()
                }

                document.dispatchEvent(
                    new CustomEvent('ecommerce.product-filter.completed', {
                        detail: {
                            element: form,
                        },
                    })
                )
            },
        })
    }

    #initCategoriesDropdown = async () => {
        const makeRequest = (url, beforeCallback, successCallback) => {
            // Assuming url, beforeCallback, successCallback, and Theme.handleError are already defined

            // Call the beforeSend callback
            beforeCallback();

            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json', // Requesting JSON response
                    'Accept': 'application/json' // Requesting JSON response
                }
            })
                .then(response => {
                    // Check if the response is okay and parse it as JSON
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(({ error, data }) => {
                    if (error) {
                        return;
                    }

                    // Call the success callback with the data
                    successCallback(data);

                    // Dispatch a custom event after successfully fetching the data
                    document.dispatchEvent(
                        new CustomEvent('ecommerce.categories-dropdown.success', {
                            detail: {
                                data,
                            },
                        })
                    );
                })
                .catch(error => {
                    // Handle any errors that occur during the fetch
                    Theme.handleError(error);
                });
        }

        const initCategoriesDropdown = $(document).find('[data-bb-toggle="init-categories-dropdown"]')

        if (initCategoriesDropdown.length) {
            const url = initCategoriesDropdown.first().data('url')

            makeRequest(
                url,
                () => {},
                (data) => {
                    initCategoriesDropdown.each((index, element) => {
                        const currentTarget = $(element)
                        const target = $(currentTarget.data('bb-target'))

                        if (target.length) {
                            target.html(data.dropdown)
                        } else {
                            currentTarget.append(data.select)
                        }

                        if (typeof Theme.lazyLoadInstance !== 'undefined') {
                            Theme.lazyLoadInstance.update()
                        }
                    })
                }
            )
        }
    }

    productQuantityToggle = () => {
        const $container = $('[data-bb-toggle="product-quantity"]')

        $container.on('click', '[data-bb-toggle="product-quantity-toggle"]', function (e) {
            const $currentTarget = $(e.currentTarget)

            let $calculation = $currentTarget.data('value')

            if (!$calculation) {
                return
            }

            let $input = null

            if ($calculation === 'plus') {
                $input = $currentTarget.prev()
            } else if ($calculation === 'minus') {
                $input = $currentTarget.next()
            }

            if (!$input) {
                return
            }

            let $quantity = parseInt($input.val()) || 1

            $input.val($calculation === 'plus' ? $quantity + 1 : $quantity === 1 ? 1 : $quantity - 1)

            document.dispatchEvent(
                new CustomEvent('ecommerce.cart.quantity.change', {
                    detail: {
                        element: $currentTarget,
                        action: $calculation === '+' ? 'increase' : 'decrease',
                    },
                })
            )
        })
    }

    onChangeProductAttribute = () => {
        if (! window.onBeforeChangeSwatches || typeof window.onBeforeChangeSwatches !== 'function') {
            /**
             * @param {Array<Number>} data
             * @param {jQuery} element
             */
            window.onBeforeChangeSwatches = (data, element) => {
                const form = element.closest('form')

                if (data) {
                    form.find('button[type="submit"]').prop('disabled', true)
                    form.find('button[data-bb-toggle="add-to-cart"]').prop('disabled', true)
                }
            }
        }

        if (! window.onChangeSwatchesSuccess || typeof window.onChangeSwatchesSuccess !== 'function') {
            /**
             * @param {{data: Object, error: Boolean, message: String}} response
             * @param {jQuery} element
             */
            window.onChangeSwatchesSuccess = (response, element) => {
                if (!response) {
                    return
                }

                const $product = $('.bb-product-detail')
                const $form = element.closest('form')
                const $button = $form.find('button[type="submit"]')
                const $quantity = $form.find('input[name="qty"]')
                const $available = $product.find('.number-items-available')
                const $sku = $product.find('[data-bb-value="product-sku"]')

                const { error, data } = response

                if (error) {
                    $button.prop('disabled', true)
                    $quantity.prop('disabled', true)

                    $form.find('input[name="id"]').val('')

                    return
                }

                $button.prop('disabled', false)
                $quantity.prop('disabled', false)
                $form.find('input[name="id"]').val(data.id)

                $product.find('[data-bb-value="product-price"]').text(data.display_sale_price)

                if (data.sale_price !== data.price) {
                    $product.find('[data-bb-value="product-original-price"]').text(data.display_price).show()
                } else {
                    $product.find('[data-bb-value="product-original-price"]').hide()
                }

                if (data.sku) {
                    $sku.text(data.sku)
                    $sku.closest('div').show()
                } else {
                    $sku.closest('div').hide()
                }

                if (data.error_message) {
                    $button.prop('disabled', true)
                    $quantity.prop('disabled', true)

                    $available.html(`<span class='text-danger'>${data.error_message}</span>`).show()
                } else if (data.warning_message) {
                    $available.html(`<span class='text-warning fw-medium fs-6'>${data.warning_message}</span>`).show()
                } else if (data.success_message) {
                    $available.html(`<span class='text-success'>${data.success_message}</span>`).show()
                } else {
                    $available.html('').hide()
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

                const siteConfig = window.siteConfig || {}

                if (!data.image_with_sizes.origin.length) {
                    data.image_with_sizes.origin.push(siteConfig.img_placeholder)
                } else {
                    data.image_with_sizes.origin.forEach(function(item) {
                        imageHtml += `
                    <a href='${item}'>
                        <img src='${item}' alt='${data.name}'>
                    </a>
                `
                    })
                }

                if (!data.image_with_sizes.thumb.length) {
                    data.image_with_sizes.thumb.push(siteConfig.img_placeholder)
                } else {
                    data.image_with_sizes.thumb.forEach(function(item) {
                        thumbHtml += `
                    <div>
                        <img src='${item}' alt='${data.name}'>
                    </div>
                `
                    })
                }

                const $galleryImages = $product.find('.bb-product-gallery')
                const $existingGalleryImages = $galleryImages.find('.bb-product-gallery-images')
                const $existingThumbnails = $galleryImages.find('.bb-product-gallery-thumbnails')

                // Preserve existing video elements
                const existingVideoElements = $existingGalleryImages.find('.bb-product-video').clone()
                const existingVideoThumbnails = $existingThumbnails.find('.video-thumbnail').clone()

                let finalImageHtml = imageHtml
                let finalThumbHtml = thumbHtml

                // Add preserved videos back to the gallery
                if (existingVideoElements.length > 0) {
                    existingVideoElements.each(function() {
                        finalImageHtml += $(this)[0].outerHTML
                    })
                }

                if (existingVideoThumbnails.length > 0) {
                    existingVideoThumbnails.each(function() {
                        finalThumbHtml += `<div>${$(this)[0].outerHTML}</div>`
                    })
                }

                $galleryImages.find('.bb-product-gallery-thumbnails').slick('unslick').html(finalThumbHtml)

                const $quickViewGalleryImages = $(document).find('.bb-quick-view-gallery-images')

                if ($quickViewGalleryImages.length) {
                    $quickViewGalleryImages.slick('unslick').html(finalImageHtml)
                }

                $galleryImages.find('.bb-product-gallery-images').slick('unslick').html(finalImageHtml)

                if (typeof EcommerceApp !== 'undefined') {
                    EcommerceApp.initProductGallery()
                }
            }
        }
    }

    handleUpdateCart = (element) => {
        let form

        if (element) {
            form = $(element).closest('form')
        } else {
            form = $('form.cart-form')
        }

        $.ajax({
            type: 'POST',
            url: form.prop('action'),
            data: form.serialize(),
            success: ({ error, message, data }) => {
                if (error) {
                    Theme.showError(message)
                }

                this.ajaxLoadCart(data)
            },
            error: (error) => Theme.handleError(error),
        })
    }

    ajaxLoadCart = (data) => {
        if (!data) {
            return
        }

        const $cart = $('[data-bb-toggle="cart-content"]')

        if (data.count !== undefined) {
            $('[data-bb-value="cart-count"]').text(data.count)
        }

        if (data.total_price !== undefined) {
            $('[data-bb-value="cart-total-price"]').text(data.total_price)
        }

        if ($cart.length) {
            $cart.replaceWith(data.cart_content)
            this.productQuantityToggle()

            if (typeof Theme.lazyLoadInstance !== 'undefined') {
                Theme.lazyLoadInstance.update()
            }
        }
    }

    initClipboard() {
        $(document).on('click', '[data-ecommerce-clipboard]', async (e) => {
            e.preventDefault()

            const target = $(e.currentTarget)
            const copiedMessage = target.data('clipboard-message')
            const text = target.data('clipboard-text')

            if (!text) {
                return
            }

            let copied = false

            // Try modern clipboard API first
            if (navigator.clipboard && window.isSecureContext) {
                try {
                    await navigator.clipboard.writeText(text)
                    copied = true
                } catch (err) {
                    console.warn('Clipboard API failed, falling back to legacy method:', err)
                }
            }

            // Fallback for older browsers or non-secure contexts
            if (!copied) {
                const textArea = document.createElement('textarea')
                textArea.value = text
                textArea.style.position = 'fixed'
                textArea.style.left = '-999999px'
                textArea.style.top = '-999999px'
                document.body.appendChild(textArea)
                textArea.focus()
                textArea.select()

                try {
                    document.execCommand('copy')
                    copied = true
                } catch (err) {
                    console.error('Failed to copy text: ', err)
                }

                document.body.removeChild(textArea)
            }

            if (copied) {
                if (copiedMessage) {
                    if (typeof Theme !== 'undefined' && Theme.showSuccess) {
                        Theme.showSuccess(copiedMessage)
                    }
                }

                // Update button text temporarily
                const originalHtml = target.html()
                target.html('<i class="ti ti-check"></i> ' + (copiedMessage || 'Copied!'))

                setTimeout(() => {
                    target.html(originalHtml)
                }, 2000)
            } else {
                if (typeof Theme !== 'undefined' && Theme.showError) {
                    Theme.showError('Failed to copy to clipboard')
                }
            }
        })
    }
}

$(() => {
    window.EcommerceApp = new Ecommerce()

    EcommerceApp.productQuantityToggle()

    EcommerceApp.initProductGallery()

    EcommerceApp.onChangeProductAttribute()

    if ($('.bb-product-price-filter').length) {
        EcommerceApp.initPriceFilter()
    }

    document.addEventListener('ecommerce.quick-shop.completed', () => {
        EcommerceApp.productQuantityToggle()
    })

    document.addEventListener('ecommerce.cart.quantity.change', (e) => {
        const { element } = e.detail
        EcommerceApp.handleUpdateCart(element)
    })

    document.addEventListener('ecommerce.product-filter.before', () => {
        let $wrapper = $('[data-bb-toggle="product-list"]')
            .find('.bb-product-items-wrapper');

        if ($wrapper.length) {
            $wrapper.append('<div class="loading-spinner"></div>')
        }
    })

    document.addEventListener('ecommerce.product-filter.success', (e) => {
        const { data } = e.detail

        const $productItemsWrapper = $('.bb-product-items-wrapper')

        if ($productItemsWrapper.length) {
            $productItemsWrapper.html(data.data)
        }

        if (data.additional) {
            const $descriptionContainer = $('.bb-product-listing-page-description')

            const descriptionHtml = data.additional.product_listing_page_description_html

            if (descriptionHtml) {
                if ($descriptionContainer.length) {
                    $descriptionContainer.html(descriptionHtml)

                    document.dispatchEvent(new CustomEvent('shortcode.loaded', {
                        detail: {
                            name: null,
                            attributes: [],
                            html: descriptionHtml
                        }
                    }));

                    if (typeof Theme.lazyLoadInstance !== 'undefined') {
                        Theme.lazyLoadInstance.update()
                    }
                }
            } else {
                $descriptionContainer.html('')
            }

            const $defaultSidebar = $('.bb-shop-sidebar')

            let $sidebar = $('[data-bb-filter-sidebar]')

            if (! $sidebar.length) {
                $sidebar = $defaultSidebar
            }

            // Store the current active filter link before replacing the sidebar
            const activeFilterLinks = {};
            $('.bb-product-filter-link.active').each(function() {
                const filterGroup = $(this).closest('.bb-product-filter').data('filter-group');
                if (filterGroup) {
                    activeFilterLinks[filterGroup] = $(this).data('id');
                }
            });

            // Store the current checkbox states before replacing the sidebar
            const checkedCheckboxes = [];
            $sidebar.find('input[type="checkbox"]:checked').each(function() {
                const $checkbox = $(this);
                const name = $checkbox.attr('name');
                const value = $checkbox.val();
                const id = $checkbox.attr('id');

                checkedCheckboxes.push({
                    name: name,
                    value: value,
                    id: id
                });
            });

            // Replace the sidebar and get reference to the new one
            $sidebar.replaceWith(data.additional.filters_html)

            // Use setTimeout to ensure DOM is fully updated before restoring states
            setTimeout(() => {
                // Get reference to the new sidebar after replacement
                const $newSidebar = $('[data-bb-filter-sidebar]').length ? $('[data-bb-filter-sidebar]') : $('.bb-shop-sidebar')

                // Restore active state for filter links after sidebar is replaced
                Object.keys(activeFilterLinks).forEach(group => {
                    const id = activeFilterLinks[group];
                    $newSidebar.find(`.bb-product-filter[data-filter-group="${group}"] .bb-product-filter-link[data-id="${id}"]`).addClass('active');
                });

                // Restore checkbox states after sidebar is replaced
                checkedCheckboxes.forEach(checkbox => {
                    // Try multiple selectors to find the checkbox
                    let $targetCheckbox = null;

                    // First try by ID if available
                    if (checkbox.id) {
                        $targetCheckbox = $newSidebar.find(`#${checkbox.id}`);
                    }

                    // If not found by ID, try by name and value
                    if (!$targetCheckbox || !$targetCheckbox.length) {
                        $targetCheckbox = $newSidebar.find(`input[type="checkbox"][name="${checkbox.name}"][value="${checkbox.value}"]`);
                    }

                    // If still not found, try a more general approach
                    if (!$targetCheckbox || !$targetCheckbox.length) {
                        $targetCheckbox = $newSidebar.find(`input[type="checkbox"][value="${checkbox.value}"]`).filter(function() {
                            return $(this).attr('name') === checkbox.name;
                        });
                    }

                    if ($targetCheckbox && $targetCheckbox.length) {
                        $targetCheckbox.prop('checked', true);
                    }
                });
            }, 10);
        }

        if ($(document).find('.bb-product-price-filter').length) {
            EcommerceApp.initPriceFilter()
        }

        if ($productItemsWrapper.length) {
            $('html, body').animate({
                scrollTop: $productItemsWrapper.offset().top - 120,
            })
        }

        let $wrapper = $('[data-bb-toggle="product-list"]')
            .find('.bb-product-items-wrapper');

        if ($wrapper.length) {
            $wrapper.find('.loading-spinner').remove()
        }
    })

    document.addEventListener('ecommerce.product-filter.completed', () => {
        if (typeof Theme.lazyLoadInstance !== 'undefined') {
            Theme.lazyLoadInstance.update()
        }
    })
})
