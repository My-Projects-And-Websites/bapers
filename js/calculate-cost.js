$(function() {
    // declaare variables to get the elements from the page
    var costs = $('.total-cost');
    var totalSpan = $('#pay-cost');
    // this is the price which will store the total
    var total = 0;

    // parse the text as float and add it to total
    for (var i = 0; i < costs.length; i++) {
        var floatVersion = parseFloat(costs.eq(i).text());
        total += floatVersion;
    }

    // append the text to the designated element
    totalSpan.text('Total: £' +total.toFixed(2));
});