<?php
    ob_start();
    session_start();

    // page title
    $pageTitle = 'Show Item';
    include 'init.php';
    // check if get id and if it is numeric then get it's value
    $itemID = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? intval($_GET['item_id']) : 0;
    // select all data
    $stmt = $con->prepare("SELECT items.*, categories.cat_name AS catName, users.username AS userName
                            FROM items
                            INNER JOIN categories
                            ON categories.cat_id = items.category_id
                            INNER JOIN users
                            ON users.id = items.user_id
                            WHERE item_id = ? LIMIT 1");
    // execute query
    $stmt->execute(array($itemID));
    // row count
    $count = $stmt->rowCount();
    // check if there is id
    if ($count > 0) {
        // fetch the data
        $row = $stmt->fetch();
?>

        <div class="container my-3">
            <!-- Start Item Data -->
            <div class="row">
                <div class="col-md-3">
                    <!-- <img class='img-fluid w-100 img-thumbnail' src='./avatar.jpg' /> -->
                    <?php
                        if (! empty($row['item_image'])) {
                            echo "<div class='item-img text-center'>";
                                echo "<img class='img-fluid' src='admin/uploads/itemsImages/".$row['item_image']."' /> ";
                            echo "</div>";
                        }
                        if (empty($row['item_image'])) {
                            echo "<div class='item-img text-center'>";
                                echo "<img class='img-fluid' src='avatar.jpg' /> ";
                            echo "</div>";
                        }
                    ?>
                </div>
                <div class="col-md-9 item-info">
                    <h2><?php echo $row['item_name']; ?></h2>
                    <p><?php echo $row['item_desc']; ?></p>
                    <ul class="list-unstyled">
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>Added Date : </span><?php echo $row['item_date']; ?>
                        </li>
                        <li>
                            <i class="fa fa-money-check-alt fa-fw"></i>
                            <span>Price : </span><?php echo $row['item_price']; ?>
                        </li>
                        <li>
                        <i class="fa fa-building fa-fw"></i>
                            <span>Made In : </span><?php echo $row['item_country']; ?>
                        </li>
                        <li>
                            <i class="fa fa-tags fa-fw"></i>
                            <span>Category : </span><a href="categories.php?page_id=<?php echo $row['category_id']; ?>"><?php echo $row['catName']; ?></a>
                        </li>
                        <li>
                            <i class="fa fa-user fa-fw"></i>
                            <?php
                                $stmt = $con->prepare("SELECT items.user_id AS userID FROM items
                                                        INNER JOIN users
                                                        ON users.id = items.user_id
                                                        WHERE item_id = ?");
                                $stmt->execute(array($itemID));
                                $uid = $stmt->fetch();
                            ?>
                            <?php
                                echo "<span>Member : </span><a href='userProfile.php?user_id=".$uid['userID']."'>";
                                echo $row['userName'];
                                echo "</a>";
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- End Item Data -->
            <hr>
            <?php if (isset ($_SESSION['user'])) { ?>
            <!-- Start Comments -->
            <div class="row">
                <div class="offset-md-3 w-100">
                    <div class="add-comment">
                        <h3>Add Your Comment</h3>
                        <form action="<?php echo $_SERVER['PHP_SELF'].'?item_id='.$row['item_id']; ?>" method="POST">
                            <textarea name="comment" class="form-control" rows="5" style="resize:none;" required="required"></textarea>
                            <input class="btn btn-info mt-2" type="submit" value="Add Comment" />
                        </form>
                        <?php
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                                $userID = $_SESSION['userID'];
                                $itemID = $row['item_id'];
                                if (! empty ($comment)) {
                                    $stmt = $con->prepare("INSERT INTO comments
                                                            (comment, comm_status, comm_date, item_ID, user_ID)
                                                            VALUES
                                                            (:comm, 0, now(), :itemID, :userID)");
                                    $stmt->execute(array(
                                        'comm'      => $comment,
                                        'itemID'    => $itemID,
                                        'userID'    => $userID
                                    ));
                                    if ($stmt) {
                                        echo "<div class='alert alert-success mt-2'>Comment Added</div>";
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <!-- End Comments -->
            <?php } else {
                echo "<div class='text-center'>";
                    echo "<a href='login.php'>Login or Register</a> to Add Comment";
                echo "</div>";
            } ?>
            <hr>
            <!-- Start Show Comments -->
            <?php
                $stmt = $con->prepare("SELECT comments.*, users.username AS member
                                        FROM comments
                                        INNER JOIN users
                                        ON users.id = comments.user_ID
                                        WHERE item_ID = ? AND comm_status = 1
                                        ORDER BY comm_id DESC");
                $stmt->execute(array($itemID));
                $comments = $stmt->fetchAll();
                if (! empty($comments)) {
                    foreach ($comments as $comment) {?>
                        <div class="comment-box">
                            <div class='row'>
                                <div class='col-md-3 text-center'>
                                    <a href='#'><img class='img-fluid rounded-circle img-thumbnail' src='./avatar.jpg' /></a>
                                    <a href='#'><?php echo $comment['member']; ?></a>
                                </div>
                                <div class='col-md-9'>
                                    <p class="lead"><?php echo $comment['comment'] . "<br>"; ?></p>
                                    <?php echo $comment['comm_date'] . "<br>"; ?>
                                </div>
                            </div>
                        </div>
                        <hr>
                    <?php }
                } else {
                    echo "<div class='col text-center'>";
                            echo "<h5>No Comments To Show</h5>";
                    echo "</div>";
                }
            ?>
            <!-- End Show Comments -->
        </div>

<?php
    } else {
        echo "<div class='container mt-3'>";
            echo "<div class='alert alert-danger'>There is no such id</div>";
        echo "</div>";
    }

    include $tps . 'footer.php';
    ob_end_flush();
?>