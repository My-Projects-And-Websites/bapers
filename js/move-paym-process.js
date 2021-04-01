$(function() {
    // this is mainly done for styling, if role is not receptionist place the payments to process on the right side
    var paymentDash = $('.payments-to-process');

    // if the id attribute of this element is not receptionist
    if (paymentDash.attr('id') != "Receptionist") {
        paymentDash.css('grid-column', '3 / 5');
    }
});