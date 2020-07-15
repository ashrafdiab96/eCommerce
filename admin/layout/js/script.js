// alert("555");
$(function() {
    'use strict';

    // Hide Placeholder On Form Touch
    $('[placeholder]').focus(function() {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function() {
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    // show password
    var passField = $('.pass-inp');
    $(".show-pass").hover(function() {
        passField.attr('type', 'text')
    }, function() {
        passField.attr('type', 'password')
    });

    // delete confirmation message
    $(".confirmDelete").click(function() {
        return confirm('Are You Sure You Want To Delete This ?');
    });

    // dashboard page, show and hide users and items
    $(".latest .toggle-info").click(function() {
        $(this).toggleClass("selected").parent().next(".card-body").fadeToggle(100)
    });

    // categories page show and hide categories data
    $(".cat h3").click(function() {
        $(this).next(".cat .full-view").fadeToggle(300);
    });

    // categories page, sort the cqtegories
    $(".categories .sorting .view").click(function() {
        $(this).addClass("active").siblings(".view").removeClass("active");
        if ($(this).data("view") === "full") {
            $(".categories .cat .full-view").fadeIn(200);
        } else {
            $(".categories .cat .full-view").fadeOut(200);
        }
    });

});