$(function(){
    // set  submission of the form to false
    $(".search-field").on("submit", function(event) {
        event.preventDefault();
    });

    // search bar for customer list
    // on every release of a key, run this function
    $("#search-bar").on("keyup", function() {
        // lowercase the input on the search field
        var value = $(this).val().toLowerCase();

        // hide the irrelevant list items 
        $("#customer-list li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // search bar for customer list when changing status
    // on every release of a key, run this function
    $("#search-bar-valued").on("keyup", function() {
        // lowercase the input on the search field
        var value2 = $(this).val().toLowerCase();

        // hide the irrelevant list items 
        $(".valued-customer-list li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value2) > -1)
        });

    });
});