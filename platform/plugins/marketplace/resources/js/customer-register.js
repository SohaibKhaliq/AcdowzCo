$(() => {
    $(document).on('click', 'input[name=is_vendor]', (e) => {
        const currentTarget = $(e.currentTarget)

        if (currentTarget.val() == 1) {
            $('[data-bb-toggle="vendor-info"]').slideDown()
        } else {
            $('[data-bb-toggle="vendor-info"]').slideUp()
            currentTarget.closest('form').find('button[type=submit]').prop('disabled', false)
        }
    })

    $('form.js-base-form input[name="shop_url"]').on('change', (e) => {
        const currentTarget = $(e.currentTarget)
        const form = currentTarget.closest('form')
        const url = currentTarget.val()

        if (!url) {
            return
        }

        const slug = form.find('[data-slug-value]')

        $.ajax({
            url: currentTarget.data('url'),
            type: 'POST',
            data: { url },
            beforeSend: () => {
                currentTarget.prop('disabled', true)
                form.find('button[type=submit]').prop('disabled', true)
            },
            success: ({ error, message, data }) => {
                if (error) {
                    currentTarget.addClass('is-invalid').removeClass('is-valid')
                    $('.shop-url-status').removeClass('text-success').addClass('text-danger').text(message)
                } else {
                    currentTarget.removeClass('is-invalid').addClass('is-valid')
                    $('.shop-url-status').removeClass('text-danger').addClass('text-success').text(message)
                    form.find('button[type=submit]').prop('disabled', false)
                }

                if (data?.slug) {
                    slug.html(
                        `${slug.data('base-url')}/<strong>${data.slug.substring(0, 100).toLowerCase()}</strong>`,
                    )
                }
            },
            complete: () => currentTarget.prop('disabled', false),
        })
    })

    if ($('.become-vendor-form').length) {
        const certificateDropzone = new Dropzone('#certificate-dropzone', {
            url: '#',
            autoProcessQueue: false,
            paramName: 'certificate_file',
            maxFiles: 1,
            acceptedFiles: '.pdf,.jpg,.jpeg,.png,.webp',
            addRemoveLinks: true,
            dictDefaultMessage: $('#certificate-dropzone').data('placeholder'),
            maxfilesexceeded: function(file) {
                this.removeFile(file)
            },
        })

        const governmentIdDropzone = new Dropzone('#government-id-dropzone', {
            url: '#',
            autoProcessQueue: false,
            paramName: 'government_id_file',
            maxFiles: 1,
            acceptedFiles: '.pdf,.jpg,.jpeg,.png,.webp',
            addRemoveLinks: true,
            dictDefaultMessage: $('#government-id-dropzone').data('placeholder'),
            maxfilesexceeded: function(file) {
                this.removeFile(file)
            },
        })

        $('form.become-vendor-form').on('submit', function(e) {
            e.preventDefault()

            const form = $(e.currentTarget)
            const formData = new FormData(form.get(0))

            if (certificateDropzone.getQueuedFiles().length > 0) {
                formData.append('certificate_file', certificateDropzone.getQueuedFiles()[0])
            }

            if (governmentIdDropzone.getQueuedFiles().length > 0) {
                formData.append('government_id_file', governmentIdDropzone.getQueuedFiles()[0])
            }

            $.ajax({
                url: form.prop('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function({ data }) {
                    if (data?.redirect_url) {
                        window.location.href = data.redirect_url
                    }
                },
                error: function(response) {
                    const { errors } = response.responseJSON

                    form.find('input').removeClass('is-invalid').removeClass('is-valid')
                    form.find('.invalid-feedback').remove()

                    if (errors) {
                        Object.keys(errors).forEach((key) => {
                            const input = form.find(`input[name="${key}"]`)
                            const error = errors[key]

                            if (['certificate_file', 'government_id_file'].includes(key)) {
                                const wrapper = form.find(`[data-field-name="${key}"]`)
                                wrapper.find('.invalid-feedback').remove()
                                wrapper.append(`<div class="invalid-feedback" style="display: block">${error}</div>`)
                            } else {
                                input.addClass('is-invalid').removeClass('is-valid')
                                if (!input.is(':checkbox')) {
                                    input.parent().append(`<div class="invalid-feedback">${error}</div>`)
                                }
                            }
                        })
                    }
                },
            })
        })
    }
})
