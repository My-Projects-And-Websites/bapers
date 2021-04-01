function togglePaym(id) {
    // open payment details on click of button
    // variables in the payment container
    var paymContainer = $('.jobs-payment-details-' + id);
    var discPlan = $('.discount-plan-text-' + id);
    var inputDisable = $('.discount-rate-' + id);

    // if the discount plan says unavailable, disable discount rate input
    if (discPlan.text() == "Unavailable") {
        inputDisable.css("display", 'none');
    }

    // payment details will slide down if their display is set to block, if display is set to none, slide up
    paymContainer.slideToggle(200);
}

function changePaymType(id) {
    // payment details
    var selectPaymType = $('.payment-type-cash-card-' + id);
    var cardName = $('.card-name-' + id); // cardholder name
    var cardNum = $('.card-num-' + id); // card number
    var cardExp = $('.card-exp-' + id); // card expiry date
    var cardCVV = $('.card-type-' + id); // card CVV

    // if the value of select tag is set to cash, hide the vard details input
    if (selectPaymType.val() == "Cash") {
        cardName.slideUp(200);
        cardNum.slideUp(200);
        cardExp.slideUp(200);
        cardCVV.slideUp(200);

        // reset their input so it does not get posted
        cardName.val('');
        cardNum.val('');
        cardExp.val('');
        cardCVV.val('');
    }
    else {
        // if it is card, reveal input fields
        cardName.slideDown(200);
        cardNum.slideDown(200);
        cardExp.slideDown(200);
        cardCVV.slideDown(200);

        // enable the input fields
        cardName.prop("disabled", false);
        cardNum.prop("disabled", false);
        cardExp.prop("disabled", false);
        cardCVV.prop("disabled", false);
    }
}