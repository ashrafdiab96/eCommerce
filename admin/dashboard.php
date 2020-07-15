<?php

    ob_start();     // Output Buffering Start
    session_start();
    if (isset($_SESSION['username'])) {
        // page title
        $pageTitle = 'Dashboard';
        include 'init.php';
        include $tps . 'navbar.php';
        // number of users to selected
        $latestUsers = 5;
        // select latest users from database
        $lastUsers = latestItems("*", "users", "id", $latestUsers);

        // number of items to selected
        $ItemsLimit = 5;
        // select latest items from database
        $lastItems = latestItems("*", "items", "item_id", $ItemsLimit);

        /** Start Dashboard Page */
?>

        <!-- Start General Design -->
        <div class="home-stats mt-2">
            <div class="container text-center">
                <h1>Dashboard</h1>
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="stat st-members">
                            Total Members
                            <span><a href="members.php"><?php echo countItems("id", "users"); ?></a></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-pending">
                            Pending Members
                            <span><a href="members.php?do=manage&page=pending">
                                <?php echo checkItem("reg_status", "users", 0); ?>
                            </a></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-items">
                            Total Items
                            <span><a href="items.php"><?php echo countItems("item_id", "items"); ?></a></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st-comments">
                            Total Comments
                            <span><a href="comments.php"><?php echo countItems("comm_id", "comments"); ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End General Design -->

        <!-- Start Latest Section -->
        <div class="latest">
            <!-- Start The Container -->
            <div class="container">
                <!-- Start The Row -->
                <div class="row mb-4">
                    <!-- Start Latest Regesterd users -->
                    <div class="col-md-6">
                        <!-- Start The Card -->
                        <div class="card">
                            <!-- Start The Card Header -->
                            <div class="card-header">
                                <?php  ?>
                                <i class="fa fa-users"></i> Latest <?php echo $latestUsers; ?> Register Users
                                <span class="toggle-info fa-pull-right mt-1">
                                    <i class="fa fa-minus"></i>
                                </span>
                            </div>
                            <!-- End The Card Header -->

                            <!-- Start The Card Body -->
                            <div class="card-body">
                                <ul class="list-unstyled users-list">
                                    <?php
                                        if (! empty($lastUsers)) {
                                            foreach ($lastUsers as $user) {
                                                echo '<li>';
                                                echo $user['username'] ;
                                                // approve button
                                                if($user['reg_status'] == 0) {
                                                    echo "<a href='members.php?do=approve&user_id= " . $user['id'] . " '>";
                                                    echo "<span class='btn btn-success fa-pull-right'>";
                                                    echo "<i class='far fa-thumbs-up'></i> Approve";
                                                    echo "</span>";
                                                    echo "</a>";
                                                }
                                                // edit button
                                                echo '<a href="members.php?do=edit&user_id=' . $user['id'] . '">';
                                                echo '<span class="btn btn-info mx-1 fa-pull-right">';
                                                echo '<i class="fa fa-edit"></i> Edit';
                                                echo '</span>';
                                                echo '</a>';
                                                // delete button
                                                echo '<a href="members.php?do=delete&user_id=' . $user['id'] . '">';
                                                echo '<span class="btn btn-danger confirmDelete fa-pull-right">';
                                                echo '<i class="fas fa-times"></i> Delete';
                                                echo '</span>';
                                                echo '</a>';
                                                echo '</li>';
                                            }
                                        } else {
                                            echo "There is no users to show";
                                        }
                                    ?>
                                </ul>
                            </div>
                            <!-- End The Card Body -->
                        </div>
                        <!-- End The Card -->
                    </div>
                    <!-- End Latest Regesterd users -->

                    <!-- Start Latest Added Items -->
                    <div class="col-md-6">
                        <!-- Start The Card -->
                        <div class="card">
                            <!-- Start The Card Header -->
                            <div class="card-header">
                                <i class="fa fa-tag"></i> Latest <?php echo $ItemsLimit; ?> Added Items
                                <span class="toggle-info fa-pull-right mt-1">
                                    <i class="fa fa-minus"></i>
                                </span>
                            </div>
                            <!-- End The Card Body -->

                            <!-- Start The Card Body -->
                            <div class="card-body">
                                <ul class="list-unstyled users-list">
                                    <?php
                                        if (! empty($lastItems)) {
                                            foreach ($lastItems as $item) {
                                                echo '<li>';
                                                echo $item['item_name'] ;
                                                // approve button
                                                if($item['item_approved'] == 0) {
                                                    echo "<a href='items.php?do=approve&item_id= " . $item['item_id'] . " '>";
                                                    echo "<span class='btn btn-success fa-pull-right'>";
                                                    echo "<i class='far fa-thumbs-up'></i> Approve";
                                                    echo "</span>";
                                                    echo "</a>";
                                                }
                                                // edit button
                                                echo '<a href="items.php?do=edit&item_id=' . $item['item_id'] . '">';
                                                echo '<span class="btn btn-info mx-1 fa-pull-right">';
                                                echo '<i class="fa fa-edit"></i> Edit';
                                                echo '</span>';
                                                echo '</a>';
                                                // delete button
                                                echo '<a href="items.php?do=delete&item_id=' . $item['item_id'] . '">';
                                                echo '<span class="btn btn-danger confirmDelete fa-pull-right">';
                                                echo '<i class="fas fa-times"></i> Delete';
                                                echo '</span>';
                                                echo '</a>';
                                                echo '</li>';
                                            }
                                        } else {
                                            echo "There is no items to show";
                                        }
                                    ?>
                                </ul>
                            </div>
                            <!-- End The Card Body -->
                        </div>
                        <!-- End The Card -->
                    </div>
                    <!-- End Latest Added Items -->
                </div>
                <!-- End The Row -->
            </div>
            <!-- End The Container -->
        </div>
        <!-- End Latest Section -->

        <?php
        /** End Dashboard Page */

        include $tps . 'footer.php';
        // echo 'Welcome ' . $_SESSION['full_name'];
    } else {
        header('location: index.php');
        exit();
    }
    ob_end_flush();     // Output Buffering End

?>