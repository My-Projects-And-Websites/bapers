$(function() {
    var paymentDash = $('.payments-to-process');

    if (paymentDash.attr('id') != "Receptionist") {
        paymentDash.css('grid-column', '3 / 5');
    }
});