<script>
    'use strict';

    window.addEventListener('load', function() {
        $(document).on('submit', '.bb-contact-store-form', function (e) {
            e.preventDefault();

            var $form = $(e.currentTarget);
            var $button = $form.find('button[type="submit"]');

            var loadingClass = $button.data('bb-loading') || 'btn-loading';

            $.ajax({
                url: $form.prop('action'),
                method: $form.prop('method'),
                data: $form.serialize(),
                beforeSend: function () {
                    $button.prop('disabled', true).addClass(loadingClass);
                },
                success: function (response) {
                    $form[0].reset();

                    if (typeof Theme !== 'undefined') {
                        if (response.error) {
                            Theme.showError(response.message);
                        } else {
                            Theme.showSuccess(response.message);
                        }
                    }
                },
                error: function (response) {
                    if (typeof Theme !== 'undefined') {
                        Theme.handleError(response);
                    }
                },
                complete: function () {
                    if (typeof refreshRecaptcha !== 'undefined') {
                        refreshRecaptcha();
                    }

                    $button.prop('disabled', false).removeClass(loadingClass);
                }
            });
        });
    });
</script>
