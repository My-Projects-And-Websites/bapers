function toggleJobDetails(id) {
    // this is used for the processing jobs
    // job details will toggle onclick of the arrow down button
    var jobDetailsContainer = $('#job-details-container-' + id);
    jobDetailsContainer.slideToggle(200);
}