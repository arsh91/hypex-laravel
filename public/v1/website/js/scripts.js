$(document).ready(function() {
    $('.dropdown-menu li a').click(function(event) {
        var option = $(event.target).text();
        $(event.target).parents('.btn-group').find('.dropdown-toggle').html(option+' <span class="pull-right"><i class="fas fa-chevron-down"></i></span>');
    });
});​



