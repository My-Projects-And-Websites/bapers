$(function() {
    // sidebar button, show links to accept jobs and process jobs
    var openJobBtn = $('.open-job-collapsed-bar');
    var jobLinks = $('.job-links');

    openJobBtn.on('click', function() {
        jobLinks.slideToggle(200);
    });
});