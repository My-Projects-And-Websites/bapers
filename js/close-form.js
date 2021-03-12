$(function() {
    var openForm = $('.customer-create');
    var closeForm = $('.close-form-btn');
    var modal = $('.create-customer-form');

    openForm.on('click', function() {
        modal.fadeIn(300);
    });

    closeForm.on('click', function() {
        modal.fadeOut(300);
    });
});