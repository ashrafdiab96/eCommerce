// alert("555");
$(function() {
    'use strict';

    // Change between login and signup page
    $(".login-page h1 span").click(function() {
        $(this).addClass("active").siblings().removeClass("active");
        $(".login-page form").hide();
        $('.' + $(this).data("class")).fadeIn();
    });

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

    // put asterisk after required fields
    $("input").each(function() {
        if ($(this).attr("required") === "required") {
            $(this).after("<span class='asterisk'>*</span>");
        }
    });

    // using jQuery to show written fields on real time
    $(".live").keyup(function() {
        $($(this).data("class")).text($(this).val());
    });

});