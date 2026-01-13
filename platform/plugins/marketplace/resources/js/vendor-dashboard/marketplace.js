;(function($) {
    'use strict'

    function handleToggleDrawer() {
        $('.ps-drawer-toggle').on('click', function() {
            $('.ps-drawer--mobile').addClass('active')
            $('.ps-site-overlay').addClass('active')
        })

        $('.ps-drawer__close').on('click', function() {
            $('.ps-drawer--mobile').removeClass('active')
            $('.ps-site-overlay').removeClass('active')
        })

        $('body').on('click', function(e) {
            if ($(e.target).siblings('.ps-drawer--mobile').hasClass('active')) {
                $('.ps-drawer--mobile').removeClass('active')
                $('.ps-site-overlay').removeClass('active')
            }
        })
    }

    function handleMenuToggle() {
        // Handle menu toggle for 2-level menus
        $('.menu a.submenu-toggle').on('click', function(e) {
            e.preventDefault()

            const $this = $(this)
            const targetId = $this.attr('data-bs-target')
            const $submenu = $(targetId)
            const isExpanded = $this.attr('aria-expanded') === 'true'

            // Toggle the submenu
            if (isExpanded) {
                $submenu.removeClass('show')
                $this.attr('aria-expanded', 'false')
            } else {
                $submenu.addClass('show')
                $this.attr('aria-expanded', 'true')
            }
        })

        // Handle submenu item clicks
        $('.menu-submenu a').on('click', function() {
            // Remove active class from all menu items
            $('.menu a').removeClass('active')
            $('.menu-submenu a').removeClass('active')

            // Add active class to clicked item
            $(this).addClass('active')

            // Add active class to parent menu item
            $(this).closest('.has-children').find('> a').addClass('active')
        })
    }

    function tabs() {
        $('.ps-tab-list  li > a ').on('click', function(e) {
            e.preventDefault()
            const target = $(this).attr('href')
            $(this).closest('li').siblings('li').removeClass('active')
            $(this).closest('li').addClass('active')
            $(target).addClass('active')
            $(target).siblings('.ps-tab').removeClass('active')
        })
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader()
            reader.onload = function(e) {
                $(input).closest('.image-box').find('.preview_image').prop('src', e.target.result)
            }

            reader.readAsDataURL(input.files[0])
        }
    }

    $(function() {
        tabs()
        handleToggleDrawer()
        handleMenuToggle()

        $('.custom-select-image').on('click', function(event) {
            event.preventDefault()
            $(this).closest('.image-box').find('.image_input').trigger('click')
        })

        $('.image_input').on('change', function() {
            readURL(this)
        })

        $(document).on('click', '.btn_remove_image', (event) => {
            event.preventDefault()
            let $img = $(event.currentTarget).closest('.image-box').find('.preview-image-wrapper .preview_image')
            $img.attr('src', $img.data('default-image'))
            $(event.currentTarget).closest('.image-box').find('.image-data').val('')
        })

        if (window.noticeMessages && window.noticeMessages.length) {
            noticeMessages.map((x) => {
                Botble.showNotice(x.type, x.message, '')
            })
        }
    })
})(jQuery)
