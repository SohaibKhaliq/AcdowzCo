<style>
    .input-password-toggle {
        position: absolute;
        right: 0;
        top: 0;
        cursor: pointer;
        padding: 10px 15px;
        z-index: 9;
    }

    input[data-bb-password]:valid, input[data-bb-password].is-valid {
        background-image: unset;
    }

    body[dir="rtl"] .input-password-toggle {
        right: unset;
        left: 0;
    }
</style>

<script>
    (function() {
        if (window.bbPasswordToggleInitialized) {
            return;
        }

        window.bbPasswordToggleInitialized = true;

        function initPasswordToggles() {
            document.querySelectorAll('[data-bb-toggle-password]').forEach(function (button) {
                if (button.dataset.initialized === 'true') {
                    return;
                }

                button.dataset.initialized = 'true';

                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const inputGroup = this.closest('.input-group');
                    const passwordField = inputGroup ? inputGroup.querySelector('[data-bb-password]') : null;

                    if (! passwordField) {
                        console.warn('Password field not found for toggle button');
                        return;
                    }

                    if (passwordField.getAttribute('type') === 'password') {
                        passwordField.setAttribute('type', 'text');
                        this.innerHTML = `{!! BaseHelper::renderIcon('ti ti-eye-off') !!}`;
                    } else {
                        passwordField.setAttribute('type', 'password');
                        this.innerHTML = `{!! BaseHelper::renderIcon('ti ti-eye') !!}`;
                    }
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPasswordToggles);
        } else {
            initPasswordToggles();
        }
    })();
</script>
