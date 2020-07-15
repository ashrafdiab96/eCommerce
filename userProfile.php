<?php
    ob_start();
    session_start();
    // page title
    $pageTitle = 'Show Profile';
    include 'init.php';

    $userID = isset($_GET['user_id']) && is_numeric($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    $getUser = $con->prepare("SELECT * FROM users WHERE id = ?");
    $getUser->execute(array($userID));
    $info = $getUser->fetch();
?>

        <div class="info">
            <div class="container">
                <div class="row">
                    <div class="col mt-3 d-flex justify-content-center">
                        <?php
                            if (! empty($info['image'])) {
                                echo "<div class='user-prof-img text-center'>";
                                    echo "<img class='img-fluid rounded-circle img-thumbnail' src='admin/uploads/usersImages/".$info['image']."' /> ";
                                echo "</div>";
                            }
                            if (empty($info['image'])) {
                                echo "<div class='user-prof-img text-center'>";
                                    echo "<img class='img-fluid rounded-circle img-thumbnail' src='avatar.jpg' /> ";
                                echo "</div>";
                            }
                        ?>
                    </div>
                </div>
                <div class="card border-primary mt-3">
                    <div class="card-header info-head">
                        <span>My Info</span>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <ul class="list-unstyled">
                                        <li>
                                            <i class="fa fa-users fa-fw"></i>
                                            <span>Name : </span><?php echo $info['full_name']; ?>
                                        </li>
                                        <li>
                                            <i class="fa fa-unlock-alt fa-fw"></i>
                                            <span>Username : </span><?php echo $info['username']; ?>
                                        </li>
                                        <li>
                                            <i class="fa fa-envelope fa-fw"></i>
                                            <span>Email : </span><?php echo $info['email']; ?>
                                        </li>
                                        <li>
                                            <i class="fa fa-calendar fa-fw"></i>
                                            <span>Register Date : </span><?php echo $info['date']; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="my-items" class="my-ads">
            <div class="container">
                <div class="card border-primary mt-3">
                    <div class="card-header info-head">
                        <span>My Ads</span>
                    </div>
                    <div class="card-body">
                        <?php
                            $items = getItems("user_id", $info['id'], 1);
                            if (! empty($items)) {
                                echo "<div class='container'>";
                                    echo "<div class='row'>";
                                        foreach ($items as $item) {
                                            echo "<div class='col-sm-6 col-md-4'>";
                                                echo "<div class='img-thumbnail item-box p-1 mb-2'>";
                                                    if ($item['item_approved'] == 0) {
                                                        echo "<span class='approve-status'>Not Approved</span>";
                                                    }
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
                                                        echo "<h3><a href='item.php?item_id=".$item['item_id']."'>" . $item['item_name'] . "</a></h3>";
                                                        echo "<p>" . $item['item_desc'] . "</p>";
                                                        echo "<div class='date'>" . $item['item_date'] . "</div>";
                                                    echo "</div>";
                                                echo "</div>";
                                            echo "</div>";
                                        }
                                    echo "</div>";
                                echo "</div>";
                            } else {
                                echo "<div class='col text-center'>";
                                    echo "<h4>No Items To Show</h4>";
                                echo "</div>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>


<?php

    include $tps . 'footer.php';
    ob_end_flush();
?>x