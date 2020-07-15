<?php

    /**
     * *********************************************
     * manage comments page
     * you can edit | delete | approve comments from here
     * *********************************************
     */

    ob_start();
    session_start();

    // check if there is session called username
    if (isset($_SESSION['username'])) {
        // page title
        $pageTitle = 'comments';
        include 'init.php';
        include $tps . 'navbar.php';

        /** divide the page */
        // check if the link has GET request called do or not
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
        // check if do = manage, execute manage page code
        if($do == 'manage') {

            // select all comments
            // $stmt = $con->prepare("SELECT * FROM comments");
            $stmt = $con->prepare("SELECT comments.*, users.username AS userName, items.item_name AS itemName
                                    FROM comments
                                    INNER JOIN users ON users.id = comments.user_ID
                                    INNER JOIN items ON items.item_id = comments.item_ID
                                    ORDER BY comm_id DESC");
            // run the query
            $stmt->execute();
            // fetch all data
            $rows = $stmt->fetchAll();
            ?>
            <h1 class="text-center members_h1">Manage Comments</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <!-- table to show members data -->
                        <thead>
                            <td>ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>Username</td>
                            <td>Added date</td>
                            <td>Control</td>
                        </thead>
                        <?php
                            // Loop in database to get the data and show it in table
                            foreach ($rows as $row) {
                                echo "<tr>";
                                echo "<td>" . $row['comm_id'] . "</td>";
                                echo "<td>" . $row['comment'] . "</td>";
                                echo "<td>" . $row['itemName'] . "</td>";
                                echo "<td>" . $row['userName'] . "</td>";
                                echo "<td>" . $row['comm_date'] . "</td>";
                                echo "<td>
                                        <a href='comments.php?do=edit&comment_id= " . $row['comm_id'] . " ' class='btn btn-primary'><i class='fa fa-edit'></i> Edit</a>
                                        <a href='comments.php?do=delete&comment_id= " . $row['comm_id'] . " ' class='btn btn-danger confirmDelete'><i class='fas fa-times'></i> Delete</a>";
                                        if($row['comm_status'] == 0) {
                                            echo "<a href='comments.php?do=approve&comment_id= " . $row['comm_id'] . " ' class='btn btn-success approve'><i class='far fa-thumbs-up'></i> Approve</a>";
                                        }
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>
            </div>
        <?php

        } elseif ($do == 'edit') { // check if else do = edit, execute edit page code

            // check if get id and if it is numeric then get it's value
            $commID = isset($_GET['comment_id']) && is_numeric($_GET['comment_id']) ? intval($_GET['comment_id']) : 0;
            // select all data
            $stmt = $con->prepare("SELECT * FROM comments WHERE comm_id = ? LIMIT 1");
            // execute query
            $stmt->execute(array($commID));
            // row count
            $count = $stmt->rowCount();
            // fetch the data
            $row = $stmt->fetch();
            // if there is ID, show the form
            if ($count > 0) { ?>
                <h1 class="text-center members_h1">Edit Comment</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=update" method="POST">
                        <!-- User ID -->
                        <input type="hidden" name="comment_id" value="<?php echo $commID; ?>" />
                        <!-- Start Comment -->
                        <div class="form-group d-flex align-items-center">
                            <label class="col-sm-2 control-label">Comment</label>
                            <div class="col-sm-9 d-inline-block">
                                <textarea style="resize:none" rows="5" class="form-control" name="comment"><?php echo $row['comment']; ?></textarea>
                            </div>
                        </div>
                        <!-- End Comment -->
                        <!-- Start Submit Button -->
                        <div class="form-group">
                            <div class="offset-sm-2 col-sm-9 d-inline-block">
                                <input type="submit" value="Save Updates" class="btn btn-primary" />
                            </div>
                        </div>
                        <!-- End Submit Button -->
                    </form>
                </div>
            <?php
            } else { // if there is no such id, show error
                echo "<div class='container'>";
                $errorMesg = "<div class='alert alert-danger'>There Is No Such ID</div>";
                redirectHome($errorMesg, 'back', 4);
                echo "</div>";
            }

        } elseif ($do == 'update') { // check if else do = update, execute update page code

            echo "<h1 class='text-center'>Update Comment</h1>";
            echo "<div class='container mt-5'>";
            // check if data come in POST request
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // get the variable from hte form
                $commID = $_POST['comment_id'];
                $comment = $_POST['comment'];

                // update this data in database
                $stmt = $con->prepare('UPDATE comments SET comment = ? WHERE comm_id = ?');
                // run the query
                $stmt->execute(array($comment, $commID));

                // print success message
                $successMesg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record updated</div>";
                redirectHome($successMesg, "members.php", 2);

            } else { // check if the data not come in POST request
                $errorMesg = "<div class='alert alert-danger'>You Can't Browse This Page Direct</div>";
                redirectHome($errorMesg, "index.php", 2);
            }
            echo "</div>";

        } elseif ($do == "delete") { // check if else do = delete, execute delete page code

            echo "<h1 class='text-center'>Delete Comment</h1>";
            // check if the id isset and is number, get it's value
            $commID = isset($_GET['comment_id']) && is_numeric($_GET['comment_id']) ? intval($_GET['comment_id']) : 0;
            // select the user
            $stmt = $con->prepare("SELECT * FROM comments WHERE comm_id = ? LIMIT 1");
            // execute the query
            $stmt->execute(array($commID));
            // row count
            $count = $stmt->rowCount();
            // check if the is user of this id delete it
            if ($count > 0) {
                $stmt = $con->prepare("DELETE FROM comments WHERE comm_id = ?");
                $stmt->execute(array($commID));
                // print success message
                echo "<div class='container mt-5'>";
                $theMesg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record deleted</div>";
                redirectHome($theMesg, 'back', 2);
                echo "</h1>";
            } else { // if there is no such id, show error
                echo "<div class='container'>";
                $errorMesg = "<div class='alert alert-danger'>There Is No Such ID</div>";
                redirectHome($errorMesg, 'back', 2);
                echo "</div>";
            }
        } elseif ($do == "approve") { // check if else do = approve, execute approve page code

            echo "<h1 class='text-center'>Approve Comment</h1>";
            // check if the id isset and is number, get it's value
            $commID = isset($_GET['comment_id']) && is_numeric($_GET['comment_id']) ? intval($_GET['comment_id']) : 0;
            // select the user
            $stmt = $con->prepare("SELECT * FROM comments WHERE comm_id = ? LIMIT 1");
            // execute the query
            $stmt->execute(array($commID));
            // row count
            $count = $stmt->rowCount();
            // check if the is user of this id delete it
            if ($count > 0) {
                $stmt = $con->prepare("UPDATE comments SET comm_status = 1 WHERE comm_id = ?");
                $stmt->execute(array($commID));
                // print success message
                echo "<div class='container mt-5'>";
                $theMesg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Approved</div>";
                redirectHome($theMesg, 'back', 2);
                echo "</h1>";
            } else { // if there is no such id, show error
                echo "<div class='container'>";
                $errorMesg = "<div class='alert alert-danger'>There Is No Such ID</div>";
                redirectHome($errorMesg, 'back', 2);
                echo "</div>";
            }

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