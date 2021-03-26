$(function(){
    $(".search-field").on("submit", function(event) {
        event.preventDefault();
    });

    $("#search-bar").on("keyup", function() {
        var value = $(this).val().toLowerCase();

        $("#customer-list li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#search-bar-valued").on("keyup", function() {
        var value2 = $(this).val().toLowerCase();

        $(".valued-customer-list li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value2) > -1)
        });

    });
});