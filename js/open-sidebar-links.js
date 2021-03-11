$(function() {
    var openJobBtn = $('.open-job-collapsed-bar');
    var openCustomerBtn = $('.open-customer-collapsed-bar');
    
    var jobLinks = $('.job-links');
    var customerLinks = $('.customer-links');

    openJobBtn.on('click', function() {
        jobLinks.slideToggle(200);
    });

    openCustomerBtn.on('click', function() {
        customerLinks.slideToggle(200);
    });
});