$(() => {
    const $reviewListContainer = $('.review-list-container')
    let imagesReviewBuffer = []

    const initLightGallery = (element) => {
        element.lightGallery({
            thumbnail: true,
        })
    }

    const getReviewList = (url, successCallback, additionalParams = {}) => {
        if (!url) {
            return
        }

        // Build URL with search and sort parameters
        const urlObj = new URL(url, window.location.origin)
        Object.keys(additionalParams).forEach(key => {
            if (additionalParams[key]) {
                urlObj.searchParams.set(key, additionalParams[key])
            }
        })

        $.ajax({
            url: urlObj.toString(),
            method: 'GET',
            beforeSend: () => {
                $reviewListContainer.append('<div class="loading-spinner"></div>')
            },
            success: ({ data, message }) => {
                $reviewListContainer.find('h4').text(message)
                $reviewListContainer.find('.review-list').html(data)

                if (typeof Theme.lazyLoadInstance !== 'undefined') {
                    Theme.lazyLoadInstance.update()
                }

                initLightGallery($reviewListContainer.find('.review-images'))

                if (successCallback) {
                    successCallback()
                }
            },
            complete: () => {
                $reviewListContainer.find('.loading-spinner').remove()
            },
        })
    }

    const getCurrentSearchParams = () => {
        return {
            search: $('.review-search-input').val() || '',
            sort_by: $('.review-sort-select').val() || 'newest',
            star: $('.review-star-filter').val() || ''
        }
    }

    // Update clear button visibility
    const updateClearButtonVisibility = () => {
        const hasSearch = $('.review-search-input').val().trim() !== ''
        const hasFilter = $('.review-star-filter').val() !== ''
        const hasSort = $('.review-sort-select').val() !== 'newest'

        if (hasSearch || hasFilter || hasSort) {
            $('.review-clear-btn').removeClass('d-none')
        } else {
            $('.review-clear-btn').addClass('d-none')
        }
    }

    const loadPreviewImage = function (input) {
        const $uploadText = $('.image-upload__text')
        const maxFiles = $(input).data('max-files')
        const filesAmount = input.files.length

        if (maxFiles) {
            if (filesAmount >= maxFiles) {
                $uploadText.closest('.image-upload__uploader-container').addClass('d-none')
            } else {
                $uploadText.closest('.image-upload__uploader-container').removeClass('d-none')
            }
            $uploadText.text(filesAmount + '/' + maxFiles)
        } else {
            $uploadText.text(filesAmount)
        }
        const viewerList = $('.image-viewer__list')
        const $template = $('#review-image-template').html()

        viewerList.find('.image-viewer__item').remove()

        if (filesAmount) {
            for (let i = filesAmount - 1; i >= 0; i--) {
                viewerList.prepend($template.replace('__id__', i))
            }
            for (let j = filesAmount - 1; j >= 0; j--) {
                let reader = new FileReader()
                reader.onload = function (event) {
                    viewerList
                        .find('.image-viewer__item[data-id=' + j + ']')
                        .find('img')
                        .attr('src', event.target.result)
                }
                reader.readAsDataURL(input.files[j])
            }
        }
    }

    const setImagesFormReview = function (input) {
        const dT = new ClipboardEvent('').clipboardData || new DataTransfer()

        for (let file of imagesReviewBuffer) {
            dT.items.add(file)
        }

        input.files = dT.files
        loadPreviewImage(input)
    }

    if ($reviewListContainer.length) {
        initLightGallery($('.review-images'))
        getReviewList($reviewListContainer.data('ajax-url'), null, getCurrentSearchParams())
    }

    $reviewListContainer.on('click', '.pagination a', (e) => {
        e.preventDefault()

        const url = $(e.currentTarget).prop('href')

        getReviewList(url, () => {
            $('html, body').animate({
                scrollTop: $reviewListContainer.offset().top - 130,
            })
        }, getCurrentSearchParams())
    })

    $(document).on('submit', '.product-review-container form', (e) => {
        e.preventDefault()
        e.stopPropagation()

        const $form = $(e.currentTarget)
        const $button = $form.find('button[type="submit"]')

        $.ajax({
            type: 'POST',
            cache: false,
            url: $form.prop('action'),
            data: new FormData($form[0]),
            contentType: false,
            processData: false,
            beforeSend: () => {
                $button.prop('disabled', true).addClass('loading')
            },
            success: ({ error, message }) => {
                if (!error) {
                    $form.find('select').val(0)
                    $form.find('textarea').val('')
                    $form.find('input[type=file]').val('')
                    $form.find('input.custom-field').val('')
                    imagesReviewBuffer = []

                    Theme.showSuccess(message)

                    getReviewList($reviewListContainer.data('ajax-url'), () => {
                        if (!$('.review-list').length) {
                            setTimeout(() => window.location.reload(), 1000)
                        }
                    }, getCurrentSearchParams())
                } else {
                    Theme.showError(message)
                }
            },
            error: (error) => {
                Theme.handleError(error, $form)
            },
            complete: () => {
                $button.prop('disabled', false).removeClass('loading')
            },
        })
    })

    $(document).on('change', '.product-review-container form input[type=file]', function (event) {
        event.preventDefault()

        const input = this
        const $input = $(input)
        const maxSize = $input.data('max-size')

        Object.keys(input.files).map(function (i) {
            if (maxSize && input.files[i].size / 1024 > maxSize) {
                const message = $input
                    .data('max-size-message')
                    .replace('__attribute__', input.files[i].name)
                    .replace('__max__', maxSize)
                Theme.showError(message)
            } else {
                imagesReviewBuffer.push(input.files[i])
            }
        })

        const filesAmount = imagesReviewBuffer.length
        const maxFiles = $input.data('max-files')
        if (maxFiles && filesAmount > maxFiles) {
            imagesReviewBuffer.splice(filesAmount - maxFiles - 1, filesAmount - maxFiles)
        }

        setImagesFormReview(input)
    })

    $(document).on('click', '.product-review-container form .image-viewer__icon-remove', function (event) {
        event.preventDefault()
        const $this = $(event.currentTarget)
        let id = $this.closest('.image-viewer__item').data('id')
        imagesReviewBuffer.splice(id, 1)

        let input = $('.product-review-container form input[type=file]')[0]
        setImagesFormReview(input)
    })

    $(document).on('click', '.delete-review-btn', function (e) {
        e.preventDefault()

        const $button = $(this)
        const reviewId = $button.data('review-id')
        const confirmMessage = $button.data('confirm-message')

        if (!confirm(confirmMessage)) {
            return
        }

        const deleteUrl = `/review/delete/${reviewId}`

        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: () => {
                $button.prop('disabled', true).addClass('loading')
            },
            success: ({ message }) => {
                Theme.showSuccess(message)

                // Reload the review list
                getReviewList($reviewListContainer.data('ajax-url'), () => {
                    if (!$('.review-list .review-item').length) {
                        setTimeout(() => window.location.reload(), 1000)
                    }
                }, getCurrentSearchParams())
            },
            error: (xhr) => {
                let message = 'An error occurred while deleting the review.'
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message
                }
                Theme.showError(message)
            },
            complete: () => {
                $button.prop('disabled', false).removeClass('loading')
            }
        })
    })

    // Search functionality
    let searchTimeout
    $(document).on('input', '[data-bb-toggle="review-search"]', function() {
        clearTimeout(searchTimeout)
        searchTimeout = setTimeout(() => {
            updateClearButtonVisibility()
            if ($reviewListContainer.length) {
                getReviewList($reviewListContainer.data('ajax-url'), null, getCurrentSearchParams())
            }
        }, 500) // Debounce search for 500ms
    })

    // Sort functionality
    $(document).on('change', '[data-bb-toggle="review-sort"]', function() {
        updateClearButtonVisibility()
        if ($reviewListContainer.length) {
            getReviewList($reviewListContainer.data('ajax-url'), null, getCurrentSearchParams())
        }
    })

    // Star filter functionality
    $(document).on('change', '[data-bb-toggle="review-star-filter"]', function() {
        updateClearButtonVisibility()
        if ($reviewListContainer.length) {
            getReviewList($reviewListContainer.data('ajax-url'), null, getCurrentSearchParams())
        }
    })

    // Star filter progress bar click functionality
    $(document).on('click', '[data-bb-toggle="review-star-filter-bar"]', function() {
        const star = $(this).data('star')
        $('.review-star-filter').val(star)
        if ($reviewListContainer.length) {
            getReviewList($reviewListContainer.data('ajax-url'), null, getCurrentSearchParams())
        }
    })

    // Handle keyboard navigation for progress bars
    $(document).on('keydown', '[data-bb-toggle="review-star-filter-bar"]', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault()
            $(this).click()
        }
    })

    // Toggle search box
    $(document).on('click', '[data-bb-toggle="review-search-toggle"]', function() {
        const $container = $('.review-search-container')
        const $button = $(this)

        if ($container.hasClass('d-none')) {
            // Hide other containers
            $('.review-filter-container, .review-sort-container').addClass('d-none')
            $('.review-control-buttons .btn').removeClass('active')

            // Show search container
            $container.removeClass('d-none')
            $button.addClass('active')
            $('.review-search-input').focus()
        } else {
            $container.addClass('d-none')
            $button.removeClass('active')
        }
        updateClearButtonVisibility()
    })

    // Toggle filter dropdown
    $(document).on('click', '[data-bb-toggle="review-filter-toggle"]', function() {
        const $container = $('.review-filter-container')
        const $button = $(this)

        if ($container.hasClass('d-none')) {
            // Hide other containers
            $('.review-search-container, .review-sort-container').addClass('d-none')
            $('.review-control-buttons .btn').removeClass('active')

            // Show filter container
            $container.removeClass('d-none')
            $button.addClass('active')
        } else {
            $container.addClass('d-none')
            $button.removeClass('active')
        }
        updateClearButtonVisibility()
    })

    // Toggle sort dropdown
    $(document).on('click', '[data-bb-toggle="review-sort-toggle"]', function() {
        const $container = $('.review-sort-container')
        const $button = $(this)

        if ($container.hasClass('d-none')) {
            // Hide other containers
            $('.review-search-container, .review-filter-container').addClass('d-none')
            $('.review-control-buttons .btn').removeClass('active')

            // Show sort container
            $container.removeClass('d-none')
            $button.addClass('active')
        } else {
            $container.addClass('d-none')
            $button.removeClass('active')
        }
        updateClearButtonVisibility()
    })

    // Clear filters functionality
    $(document).on('click', '[data-bb-toggle="review-clear-filters"]', function() {
        $('.review-search-input').val('')
        $('.review-star-filter').val('')
        $('.review-sort-select').val('newest')

        // Hide all containers and remove active states
        $('.review-search-container, .review-filter-container, .review-sort-container').addClass('d-none')
        $('.review-control-buttons .btn').removeClass('active')

        updateClearButtonVisibility()

        if ($reviewListContainer.length) {
            getReviewList($reviewListContainer.data('ajax-url'), null, getCurrentSearchParams())
        }
    })

    if (sessionStorage.reloadReviewsTab) {
        if ($('#product-detail-tabs a[href="#product-reviews"]').length) {
            new bootstrap.Tab($('#product-detail-tabs a[href="#product-reviews"]')[0]).show()
        }

        sessionStorage.reloadReviewsTab = false
    }
})
