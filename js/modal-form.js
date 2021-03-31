$(function() {
    // variables for the create new customer form
    var openCustomerForm = $('.customer-create');
    var closeCustomerForm = $('.close-form-customer-btn');
    var modalCustomer = $('.create-customer-form');

    // variables for the change to valued form
    var openValuedForm = $('.change-valued-btn');
    var closeValuedForm = $('.close-form-valued-btn');
    var modalValued = $('.change-valued-form');

    // onclick of this button, the create customer modal will fade in 
    openCustomerForm.on('click', function() {
        modalCustomer.fadeIn(300);
    });

    // onclick of this button, the create customer modal will fade out
    closeCustomerForm.on('click', function() {
        modalCustomer.fadeOut(300);
    });

    // onclick of this button, the change to valued modal will fade in 
    openValuedForm.on('click', function() {
        modalValued.fadeIn(300);
    });

    // onclick of this button, the change to valued modal will fade out
    closeValuedForm.on('click', function() {
        modalValued.fadeOut(300);
    });
});