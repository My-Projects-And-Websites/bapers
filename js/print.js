var elemToPrint = $('#print-this');
var printBtn = $('.print-btn');
var backBtn = $('.back-btn');

printBtn.on('click', function() {
    printBtn.css('display', 'none');
    backBtn.css('display', 'none');
    window.print();
});

printBtn.on('focusout', function() {
    printBtn.fadeIn(200);
    backBtn.fadeIn(200);
});