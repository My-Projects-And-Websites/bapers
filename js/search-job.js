$(function(){
    $("#search-bar").on("keyup", function() {
        var value = $(this).val().toLowerCase();

        $("#job-list li").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $(".search-field").on("submit", function(event) {
        event.preventDefault();
    });
});