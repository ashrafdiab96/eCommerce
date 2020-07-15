<?php

    /**
    ** getData function v1.0
    ** function to get the data from specific table
    ** this function accept parameters
    ** $field -> field which wants to choose
    ** $table -> table which choose from
    ** $where -> condition to filtering the data
    ** $order -> column which order by
    ** $type -> type of ordering DESC or ASC
    ** return array of data
    */

    function getData ($field, $table, $where = NULL, $order, $type = 'ASC') {
        global $con;
        $stmt = $con->prepare("SELECT $field FROM $table $where ORDER BY $order $type");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }

    /**
    ** title function v1.0
    ** this function echo the page title if the page
    ** has variable $pageTitle and ech default title for other pages
    */

    function getTitle() {
        global $pageTitle;
        if (isset($pageTitle)) {
            echo $pageTitle;
        } else {
            echo 'Default';
        }
    }

    /**
    ** redirectHome function v2.0
    ** function to redirect to home page
    ** this function accept parameters
    ** $theMesg -> print the message [ Error | Success | .... ]
    ** $url -> the url wanted to redirected
    ** $seconds -> seconds before redirecting
    */

    function redirectHome($theMesg, $url = null, $seconds = 3) {
        if ($url === null) {
            $url = 'index.php';
        } elseif ($url == 'back') {
            if (isset($_SERVER['HTTP_REFERER']) && ! empty($_SERVER['HTTP_REFERER'])) {
                $url = $_SERVER['HTTP_REFERER'];
            } else {
                $url = 'index.php';
            }
        }
        echo "<div class='container mt-5'>";
        echo $theMesg;
        echo "<div class='alert alert-info'>You Will Be Redirected After $seconds Seconds </div>";
        echo "</div>";
        header("refresh:$seconds;url=$url");
        exit();
    }

    /**
    ** checkItems function v1.0
    ** function to check items in database
    ** this function accept parameters
    ** $select -> the item to select [Ex: username, id ......]
    ** $from -> the table to select [Ex: users, categories .....]
    ** $value -> the value of select [Ex: ashraf, electronic]
    */

    function checkItem($select, $from, $value) {
        global $con;
        $stmt = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
        $stmt->execute(array($value));
        $count = $stmt->rowCount();
        return $count;
    }

    /**
    ** countItems function v1.0
    ** function to count the number of items
    ** this function accept parameters
    ** $item -> item to count
    ** $table -> the table to choose from
    */

    function countItems($item, $table) {
        global $con;
        $stmt = $con->prepare("SELECT COUNT($item) FROM $table");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
    ** latestItems function v1.0
    ** function to get the latest items from database [users, categories ....]
    ** this function accept parameters
    ** $select -> field to select
    ** $table -> table to select from
    ** $limit -> number of records to get
    ** $order -> to order by it
    */

    function latestItems($select, $table, $order, $limit) {
        global $con;
        $stmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }


?>