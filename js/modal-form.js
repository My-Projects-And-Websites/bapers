$(function() {
    var openCustomerForm = $('.customer-create');
    var closeCustomerForm = $('.close-form-customer-btn');
    var modalCustomer = $('.create-customer-form');

    var openJobForm = $('.job-assign');
    var closeJobForm = $('.close-form-job-btn');
    var modalJob = $('.assign-job-form');

    openCustomerForm.on('click', function() {
        modalCustomer.fadeIn(300);
    });

    closeCustomerForm.on('click', function() {
        modalCustomer.fadeOut(300);
    });
});