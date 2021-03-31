$(function() {

    // onclick of this button, close the payment alert modal
    $('#close-payment-alert').on('click', function() {
        // fade out animation in 200 milliseconds
        $('.payment-alert').fadeOut(200);
    });
})
