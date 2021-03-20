var elemToPrint = $('#print-this');
var printBtn = $('.print-btn');

printBtn.on('click', function() {
    printBtn.css('display', 'none');
    window.print();
});

printBtn.on('focusout', function() {
    printBtn.fadeIn(200);
});