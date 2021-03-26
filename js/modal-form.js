$(function() {
    var openCustomerForm = $('.customer-create');
    var closeCustomerForm = $('.close-form-customer-btn');
    var modalCustomer = $('.create-customer-form');

    var openValuedForm = $('.change-valued-btn');
    var closeValuedForm = $('.close-form-valued-btn');
    var modalValued = $('.change-valued-form');

    openCustomerForm.on('click', function() {
        modalCustomer.fadeIn(300);
    });

    closeCustomerForm.on('click', function() {
        modalCustomer.fadeOut(300);
    });

    openValuedForm.on('click', function() {
        modalValued.fadeIn(300);
    });

    closeValuedForm.on('click', function() {
        modalValued.fadeOut(300);
    });
});