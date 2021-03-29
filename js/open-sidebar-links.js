$(function() {
    var openJobBtn = $('.open-job-collapsed-bar');
    var jobLinks = $('.job-links');

    openJobBtn.on('click', function() {
        jobLinks.slideToggle(200);
    });
});