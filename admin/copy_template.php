<?php

    /**
     * *********************************************
     * manage members page
     * you can edit | delete | add members from here
     * *********************************************
     */

    ob_start();
    session_start();

    // check if there is session called username
    if (isset($_SESSION['username'])) {
        // page title
        $pageTitle = 'Members';
        include 'init.php';

        /** divide the page */
        // check if the link has GET request called do or not
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
        // check if do = manage, execute manage page code
        if($do == 'manage') {
        
        } elseif ($do == "add") { // check if else do = add, execute add page code

        } elseif($do == "insert") { // check if else do = insert, execute insert page code

        } elseif ($do == 'edit') { // check if else do = edit, execute edit page code

        } elseif ($do == 'update') { // check if else do = update, execute update page code

        } elseif ($do == "delete") { // check if else do = delete, execute delete page code
        
        } elseif ($do == "approve") { // check if else do = approve, execute approve page code
        
        }
    }
    // check if else no session called username, redirect to home page
    else {
        header('location: index.php');

        exit();
    }

    include $tps . 'footer.php';

    ob_end_flush();

?>