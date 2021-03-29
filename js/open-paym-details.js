function togglePaym(id) {
    var paymContainer = $('.jobs-payment-details-' + id);
    var discPlan = $('.discount-plan-text-' + id);
    var inputDisable = $('.discount-rate-input-' + id);

    if (discPlan.text() == "Unavailable") {
        inputDisable.prop("disabled", true);
    }

    console.log(discPlan.text());
    paymContainer.slideToggle(200);
}

function changePaymType(id) {
    var selectPaymType = $('.payment-type-cash-card-' + id);
    var cardName = $('.card-name-' + id);
    var cardNum = $('.card-num-' + id);
    var cardExp = $('.card-exp-' + id);
    var cardCVV = $('.card-type-' + id);

    if (selectPaymType.val() == "Cash") {
        cardName.slideUp(200);
        cardNum.slideUp(200);
        cardExp.slideUp(200);
        cardCVV.slideUp(200);

        cardName.val('');
        cardNum.val('');
        cardExp.val('');
        cardCVV.val('');
    }
    else {
        cardName.slideDown(200);
        cardNum.slideDown(200);
        cardExp.slideDown(200);
        cardCVV.slideDown(200);

        cardName.prop("disabled", false);
        cardNum.prop("disabled", false);
        cardExp.prop("disabled", false);
        cardCVV.prop("disabled", false);
    }

    console.log(selectPaymType);
}