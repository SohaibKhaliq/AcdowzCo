(function($) {
    'use strict';

    $(document).ready(function() {
        // Select all functionality
        $('#select-all').on('change', function() {
            $('.product-checkbox').prop('checked', $(this).is(':checked'));
            toggleBulkActions();
        });

        $('.product-checkbox').on('change', toggleBulkActions);

        function toggleBulkActions() {
            const checked = $('.product-checkbox:checked').length;
            $('#bulk-delete-btn').prop('disabled', checked === 0);
        }

        // Approve product
        $('.btn-approve-product').on('click', function() {
            const id = $(this).data('id');
            const $button = $(this);
            
            if (confirm('Are you sure you want to approve this product?')) {
                $.ajax({
                    url: '/admin/marketplace/product-oversight/' + id + '/approve',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $button.prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.error) {
                            alert(response.message);
                            $button.prop('disabled', false);
                        } else {
                            window.location.reload();
                        }
                    },
                    error: function() {
                        alert('Error approving product');
                        $button.prop('disabled', false);
                    }
                });
            }
        });

        // Reject product
        $('.btn-reject-product').on('click', function() {
            const id = $(this).data('id');
            const $button = $(this);
            
            if (confirm('Are you sure you want to reject this product?')) {
                $.ajax({
                    url: '/admin/marketplace/product-oversight/' + id + '/reject',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $button.prop('disabled', true);
                    },
                    success: function(response) {
                        window.location.reload();
                    },
                    error: function() {
                        alert('Error rejecting product');
                        $button.prop('disabled', false);
                    }
                });
            }
        });

        // Bulk delete
        $('#bulk-delete-btn').on('click', function() {
            const ids = $('.product-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length === 0) {
                alert('Please select products to delete');
                return;
            }

            if (confirm('Are you sure you want to delete ' + ids.length + ' selected products? This cannot be undone.')) {
                $.ajax({
                    url: '/admin/marketplace/product-oversight/bulk-delete',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        ids: ids
                    },
                    success: function(response) {
                        window.location.reload();
                    },
                    error: function() {
                        alert('Error deleting products');
                    }
                });
            }
        });
    });

})(jQuery);
