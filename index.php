<?php

    ob_start();
    session_start();
    // page title
    $pageTitle = 'Home';
    include 'init.php';

    $items = getAll("items", "item_id", "WHERE item_approved = 1");
    echo "<div class='container my-3'>";
        echo "<div class='row'>";
            foreach ($items as $item) {
                echo "<div class='col-sm-6 col-md-4'>";
                    echo "<div class='img-thumbnail item-box p-1 mb-2'>";
                        echo "<span class='price-tag'>".$item['item_price']."</span>";
                        echo "<div class='text-center'>";
                            if (! empty($item['item_image'])) {
                                echo "<div class='item-img text-center'>";
                                    echo "<img class='img-fluid' src='admin/uploads/itemsImages/".$item['item_image']."' /> ";
                                echo "</div>";
                            }
                            if (empty($item['item_image'])) {
                                echo "<div class='item-img text-center'>";
                                    echo "<img class='img-fluid' src='avatar.jpg' /> ";
                                echo "</div>";
                            }
                        echo "</div>";
                        echo "<div class='caption ml-3'>";
                            echo '<h3><a href="item.php?item_id= '. $item['item_id'].' ">' . $item['item_name'] . '</a></h3>';
                            echo "<p>" . $item['item_desc'] . "</p>";
                            echo "<div class='date'>" . $item['item_date'] . "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            }
        echo "</div>";
    echo "</div>";

    include $tps . 'footer.php';
    ob_end_flush();

?>