$(function() {
    var costs = $('.total-cost');
    var totalSpan = $('#pay-cost');
    var total = 0;

    for (var i = 0; i < costs.length; i++) {
        var floatVersion = parseFloat(costs.eq(i).text());
        total += floatVersion;
    }

    totalSpan.text('Total: £' +total.toFixed(2));
});