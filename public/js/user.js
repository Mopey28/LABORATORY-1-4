$(document).ready(function() {
    $('.product-actions .btn').on('click', function() {
        var productId = $(this).data('product-id');
        $('#addToCartModal' + productId).modal('show');
    });
});
