$(() => {
    if ($.fn.datepicker) {
        const $datePicker = $('#date_of_birth')

        let options = {
            format: 'yyyy-mm-dd',
            orientation: 'bottom',
        }

        const language = $datePicker.data('locale')

        if (language) {
            options.language = language
        }

        const dateFormat = $datePicker.data('date-format')

        if (dateFormat) {
            options.format = dateFormat
        }

        $datePicker.datepicker(options)
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
})
