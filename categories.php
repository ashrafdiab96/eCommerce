<?php
    session_start();

    // page title
    $pageTitle = 'Categories';
    include 'init.php';
?>

<div class="container">
    <h1 class="text-center mt-3"></h1>
    <div class="row">
        <?php
            $items = getItems("category_id", $_GET['page_id']);
            if (! empty($items)) {
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
            } else {
                echo "<div class='col text-center mt-5'>";
                    echo "<div class='img-thumbnail p-5'>";
                        echo "<h4>No Items To Show</h4>";
                    echo "</div>";
                echo "</div>";
            }
        ?>
    </div>
</div>



<?php include $tps . 'footer.php'; ?>
