$(function(){
    // search bar for payment list
    // on every release of a key, run this function
    $("#search-bar").on("keyup", function() {
        // lowercase the input on the search field
        var value = $(this).val().toLowerCase();

        // hide the irrelevant list items 
        $("#payment-list li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // set  submission of the form to false
    $(".search-field").on("submit", function(event) {
        event.preventDefault();
    });
});