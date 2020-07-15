<?php

    // $dsn = 'mysql:host=sql206.epizy.com;dbname=epiz_26231501_shop';
    // $user = 'epiz_26231501';
    // $pass = '1l5iOdzHBp6u';

    $dsn = 'mysql:host=localhost;dbname=shop';
    $user = 'root';
    $pass = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );

    try {
        $con = new PDO($dsn, $user, $pass, $option);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    catch(PDOException $e) {
        echo 'Failed to connect' . $e->getMessage();
    }

?>