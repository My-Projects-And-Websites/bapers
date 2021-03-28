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
    var cardNum = $('.card-num-' + id);
    var cardExp = $('.exp-date-' + id);
    var cardCVV = $('.card-cvv-' + id);

    if (selectPaymType.val() == "Cash") {
        cardNum.prop("disabled", true);
        cardExp.prop("disabled", true);
        cardCVV.prop("disabled", true);
    }
    else {
        cardNum.prop("disabled", false);
        cardExp.prop("disabled", false);
        cardCVV.prop("disabled", false);
    }

    console.log(selectPaymType);
}