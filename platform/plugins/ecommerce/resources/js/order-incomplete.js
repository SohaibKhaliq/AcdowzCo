$(() => {
    $(document)
        .on('click', '.btn-update-order', (event) => {
            event.preventDefault()
            let _self = $(event.currentTarget)

            _self.addClass('button-loading')

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('form').prop('action'),
                data: _self.closest('form').serialize(),
                success: (res) => {
                    if (!res.error) {
                        Botble.showSuccess(res.message)
                    } else {
                        Botble.showError(res.message)
                    }
                    _self.removeClass('button-loading')
                },
                error: (res) => {
                    Botble.handleError(res)
                    _self.removeClass('button-loading')
                },
            })
        })
        .on('click', '.btn-trigger-send-order-recover-modal', (event) => {
            event.preventDefault()
            $('#confirm-send-recover-email-button').data('action', $(event.currentTarget).data('action'))
            $('#send-order-recover-email-modal').modal('show')
        })
        .on('click', '#confirm-send-recover-email-button', (event) => {
            event.preventDefault()
            let _self = $(event.currentTarget)

            _self.addClass('button-loading')

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.data('action'),
                success: (res) => {
                    if (!res.error) {
                        Botble.showSuccess(res.message)
                    } else {
                        Botble.showError(res.message)
                    }
                    _self.removeClass('button-loading')
                    $('#send-order-recover-email-modal').modal('hide')
                },
                error: (res) => {
                    Botble.handleError(res)
                    _self.removeClass('button-loading')
                },
            })
        })
        .on('click', '[data-bb-toggle="confirm-mark-as-completed-button"]', (event) => {
            event.preventDefault()

            const $currentTarget = $(event.currentTarget)
            const $form = $currentTarget.closest('form')

            $httpClient
                .make()
                .withButtonLoading($currentTarget)
                .post($form.prop('action'), $form.serialize())
                .then(({ data }) => {
                    if (data.error) {
                        Botble.showError(data.message)
                        return
                    }

                    $('#mark-order-as-completed-modal').modal('hide')

                    Botble.showSuccess(data.message)

                    if (data.data.next_url) {
                        setTimeout(() => (window.location.href = data.data.next_url), 2000)
                    }
                })
        })
})
