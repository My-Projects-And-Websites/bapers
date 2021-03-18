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
});