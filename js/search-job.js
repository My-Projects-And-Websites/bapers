$(function(){
    // search bar for job list
    // on every release of a key, run this function
    $("#search-bar").on("keyup", function() {
        // lowercase the input on the search field
        var value = $(this).val().toLowerCase();

        // hide the irrelevant list items 
        $("#job-list li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // remove submission of the form
    $(".search-field").on("submit", function(event) {
        event.preventDefault();
    });
});