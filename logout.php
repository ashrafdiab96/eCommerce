<?php

    session_start();                    // start the session

    session_unset();                    // unset the data

    session_destroy();                  // destroy the session

    header('location: login.php');      // redirect to login page

    exit();                             // exit




?>