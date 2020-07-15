<?php

    include 'connect.php';                  // database connection file

    // routes

    $tps = 'includes/templates/';           // templates directory
    $lang = 'includes/languages/';          // languages directory
    $func = 'includes/functions/';          // functions directory
    $css = 'layout/css/';                   // css directory
    $js = 'layout/js/';                     // js directory

    // include the important files

    include $lang . 'english.php';          // english file
    include $func . 'functions.php';        // functions file
    // include navbar in only pages not have $noNavbar variable
    // if(! isset($noNavbar)) {
    //     // include $tps . 'navbar.php';        // navbar file
    // }
    include $tps . 'header.php';            // header file

?>