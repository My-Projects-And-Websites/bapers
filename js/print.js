// variables for printing reports
var elemToPrint = $('#print-this');
var printBtn = $('.print-btn');
var backBtn = $('.back-btn');

// open print dialog and hide buttons so it does not get captured on print
printBtn.on('click', function() {
    printBtn.css('display', 'none');
    backBtn.css('display', 'none');
    window.print();
});

// if print dialog is removed, bring back buttons to display
printBtn.on('focusout', function() {
    printBtn.fadeIn(200);
    backBtn.fadeIn(200);
});