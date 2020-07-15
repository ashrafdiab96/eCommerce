<?php

    /**
     * *********************************************
     * manage items page
     * you can edit | delete | add items from here
     * *********************************************
     */

    ob_start();
    session_start();

    // check if there is session called username
    if (isset($_SESSION['username'])) {
        // page title
        $pageTitle = 'Items';
        include 'init.php';
        include $tps . 'navbar.php';

        /** divide the page */
        // check if the link has GET request called do or not
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
        // check if do = manage, execute manage page code
        if($do == 'manage') {

            // query to select non approved items
            $query = '';
            if (isset($_GET['page']) && $_GET['page'] == 'pending') {
                $query = 'AND reg_status = 0';
            }
            // select all items
            $stmt = $con->prepare("SELECT items.*, categories.cat_name AS catName, users.username AS memberName
                                    FROM items
                                    INNER JOIN categories ON categories.cat_id = items.category_id
                                    INNER JOIN users ON users.id = items.user_id
                                    ORDER BY item_id DESC");
            // run the query
            $stmt->execute();
            // assign to variable
            $rows = $stmt->fetchAll();
            ?>
            <h1 class="text-center members_h1">Manage Items</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <!-- table to show items data -->
                        <thead>
                            <td>ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Country</td>
                            <td>Category</td>
                            <td>Member</td>
                            <td>Adding Date</td>
                            <td>Control</td>
                        </thead>
                        <?php
                            // Loop in database to get the data and show it in table
                            foreach ($rows as $row) {
                                echo "<tr>";
                                echo "<td>" . $row['item_id'] . "</td>";
                                echo "<td>" . $row['item_name'] . "</td>";
                                echo "<td>" . $row['item_desc'] . "</td>";
                                echo "<td>" . $row['item_price'] . "</td>";
                                echo "<td>" . $row['item_country'] . "</td>";
                                echo "<td>" . $row['catName'] . "</td>";
                                echo "<td>" . $row['memberName'] . "</td>";
                                echo "<td>" . $row['item_date'] . "</td>";
                                echo "<td>
                                        <a href='items.php?do=edit&item_id= " . $row['item_id'] . " ' class='btn btn-primary m-1 w-100'><i class='fa fa-edit'></i> Edit</a><br>
                                        <a href='items.php?do=delete&item_id= " . $row['item_id'] . " ' class='btn btn-danger m-1 w-100 confirmDelete'><i class='fas fa-times'></i> Delete</a><br>";
                                        if($row['item_approved'] == 0) {
                                            echo "<a href='items.php?do=approve&item_id= " . $row['item_id'] . " ' class='btn btn-success m-1 w-100 approve'><i class='far fa-thumbs-up'></i> Approve</a>";
                                        }
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>
                <!-- Button to add new users -->
                <div class="text-center mt-3">
                    <a href='items.php?do=add' class="btn btn-dark add-user-btn mb-4"><i class="fa fa-plus"></i> Add New Item</a>
                </div>
            </div>
        <?php

        } elseif ($do == "add") { // check if else do = add, execute add page code ?>

            <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=insert" method="POST">
                    <!-- Start Name -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Name</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="name" class="form-control input-required" required = "required" />
                            <span class="asterisk">*</span>
                        </div>
                    </div>
                    <!-- End Name -->
                    <!-- Start Desc -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">description</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="desc" class="form-control" />
                        </div>
                    </div>
                    <!-- End Desc -->
                    <!-- Start Price -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="price" class="form-control input-required" />
                            <span class="asterisk">*</span>
                        </div>
                    </div>
                    <!-- End Price -->
                    <!-- Start Country -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Country Of Item</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="country" class="form-control" />
                        </div>
                    </div>
                    <!-- End Country -->
                    <!-- Start Status -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Status</label>
                        <div class="col-sm-9 d-inline-block">
                            <select class="form-control input-required" name="status">
                                <option value="0">   </option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status -->
                    <!-- Start Members -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-9 d-inline-block">
                            <select class="form-control input-required" name="members">
                                <option value="0"></option>
                                <?php
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach ($users as $user) {
                                        echo "<option value='".$user['id']."'>".$user['username']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Members -->
                    <!-- Start Categories -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-9 d-inline-block">
                            <select class="form-control input-required" name="categories">
                                <option value="0"></option>
                                <?php
                                    $cats = getData("*", "categories", "WHERE cat_parent = 0", "cat_id");
                                    foreach ($cats as $cat) {
                                        echo "<option value='".$cat['cat_id']."'";
                                        if ($row['category_id'] == $cat['cat_id']) {echo 'selected';}
                                        echo ">" . $cat['cat_name'];
                                        echo "</option>";
                                        $childCats = getData("*", "categories", "WHERE cat_parent = {$cat['cat_id']}", "cat_id");
                                        foreach ($childCats as $child) {
                                            echo "<option value=".$child['cat_id'].">&nbsp;&nbsp;&nbsp;- ".$child['cat_name']."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categories -->
                    <!-- Start Submit Button -->
                    <div class="form-group">
                        <div class="offset-sm-2 col-sm-9 d-inline-block">
                            <input type="submit" value="Add Item" class="btn btn-primary" />
                        </div>
                    </div>
                    <!-- End Submit Button -->
                </form>
            </div>
        <?php

        } elseif($do == "insert") { // check if else do = insert, execute insert page code

            // check if the data come by POST request or not
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo "<h1 class='text-center'>Insert New User</h1>";
                echo "<div class='container'>";
                // get the variables from the form
                $name = $_POST['name'];
                $desc = $_POST['desc'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $status = $_POST['status'];
                $userID = $_POST['members'];
                $catID = $_POST['categories'];

                // form validation
                $formErrors = array();
                if (empty($name)) {
                    $formErrors[] = "<div class='alert alert-danger'>Name can't be <strong>Empty</strong></div>";
                }
                if (empty($desc)) {
                    $formErrors[] = "<div class='alert alert-danger'>Description can't be <strong>Empty</strong></div>";
                }
                if (empty($price)) {
                    $formErrors[] = "<div class='alert alert-danger'>Price can't be <strong>Empty</strong></div>";
                }
                if (empty($country)) {
                    $formErrors[] = "<div class='alert alert-danger'>Country can't be <strong>Empty</strong></div>";
                }
                if ($status == 0) {
                    $formErrors[] = "<div class='alert alert-danger'>You must choose the <strong>Status</strong></div>";
                }
                if ($userID == 0) {
                    $formErrors[] = "<div class='alert alert-danger'>You must choose the <strong>Member</strong></div>";
                }
                if ($catID == 0) {
                    $formErrors[] = "<div class='alert alert-danger'>You must choose the <strong>Category</strong></div>";
                }

                // loop into Errors array and echo it
                foreach($formErrors as $error) {
                    echo $error;
                }

                // if there are no errors, insert data in database to add new member
                if(empty($formErrors)) {
                    // insert the data
                    $stmt = $con->prepare("INSERT INTO items
                                        (item_name, item_desc, item_price, item_date, item_country, item_status, category_id, user_id)
                                        VALUES
                                        (:name, :desc, :price, now(), :country, :status, :catID, :userID)");
                    // run the query
                    $stmt->execute(array(
                    'name'      => $name,
                    'desc'      => $desc,
                    'price'     => $price,
                    'country'   => $country,
                    'status'    => $status,
                    'catID'     => $catID,
                    'userID'    => $userID
                ));
                // print success message
                $theMesg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";
                redirectHome($theMesg, "items.php", 2);
                }
                echo "</div>";
            } else { // check if the data not come in POST request
                $errorMesg = "<div class='alert alert-danger'>You Can't Browse This Page Direct</div>";
                redirectHome($errorMesg, "index.php", 5);
            }

        } elseif ($do == 'edit') { // check if else do = edit, execute edit page code
            // check if get id and if it is numeric then get it's value
            $itemID = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? intval($_GET['item_id']) : 0;
            // select all data
            $stmt = $con->prepare("SELECT * FROM items WHERE item_id = ? LIMIT 1");
            // execute query
            $stmt->execute(array($itemID));
            // row count
            $count = $stmt->rowCount();
            // fetch the data
            $row = $stmt->fetch();
            // if there is ID, show the form
            if ($count > 0) { ?>
                <h1 class="text-center members_h1">Edit Member</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=update" method="POST">
                        <!-- User ID -->
                        <input type="hidden" name="item_id" value="<?php echo $itemID; ?>" />
                        <!-- Start Name -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Item Name</label>
                            <div class="col-sm-9 d-inline-block">
                                <input type="text" name="name" class="form-control input-required" value="<?php echo $row['item_name']; ?>" required = "required" />
                                <span class="asterisk">*</span>
                            </div>
                        </div>
                        <!-- End Name -->
                        <!-- Start Desc -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="desc" value="<?php echo $row['item_desc']; ?>" class="form-control" />
                        </div>
                    </div>
                    <!-- End Desc -->
                    <!-- Start Price -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="price" value="<?php echo $row['item_price']; ?>" class="form-control input-required" />
                            <span class="asterisk">*</span>
                        </div>
                    </div>
                    <!-- End Price -->
                    <!-- Start Country -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Country Of Item</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="country" value="<?php echo $row['item_country']; ?>" class="form-control" />
                        </div>
                    </div>
                    <!-- End Country -->
                    <!-- Start Status -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Item Status</label>
                        <div class="col-sm-9 d-inline-block">
                            <select class="form-control input-required" name="status">
                                <option value="0"></option>
                                <option value="1" <?php if($row['item_status'] == 1) {echo 'selected';}  ?>>New</option>
                                <option value="2" <?php if($row['item_status'] == 2) {echo 'selected';}  ?>>Like New</option>
                                <option value="3" <?php if($row['item_status'] == 3) {echo 'selected';}  ?>>Used</option>
                                <option value="4" <?php if($row['item_status'] == 4) {echo 'selected';}  ?>>Old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status -->
                    <!-- Start Members -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-9 d-inline-block">
                            <select class="form-control input-required" name="members">
                                <option value="0"></option>
                                <?php
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach ($users as $user) {
                                        echo "<option value='".$user['id']."'";
                                        if ($row['user_id'] == $user['id']) {echo 'selected';}
                                        echo ">" . $user['username'];
                                        echo "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Members -->
                    <!-- Start Categories -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-9 d-inline-block">
                            <select class="form-control input-required" name="categories">
                                <option value="0"></option>
                                <?php
                                    $cats = getData("*", "categories", "WHERE cat_parent = 0", "cat_id");
                                    foreach ($cats as $cat) {
                                        echo "<option value='".$cat['cat_id']."'";
                                        if ($row['category_id'] == $cat['cat_id']) {echo 'selected';}
                                        echo ">" . $cat['cat_name'];
                                        echo "</option>";
                                        $childCats = getData("*", "categories", "WHERE cat_parent = {$cat['cat_id']}", "cat_id");
                                        foreach ($childCats as $child) {
                                            echo "<option value=".$child['cat_id'].">&nbsp;&nbsp;&nbsp;- ".$child['cat_name']."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categories -->
                        <!-- Start Submit Button -->
                        <div class="form-group">
                            <div class="offset-sm-2 col-sm-9 d-inline-block">
                                <input type="submit" value="Save Updates" class="btn btn-primary" />
                                <?php echo "<a href='?do=editImage&item_id=".$row['item_id']."' class='btn btn-secondary text-white'>Edit Image</a>" ?>
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

            echo "<h1 class='text-center'>Update Item</h1>";
            echo "<div class='container mt-5'>";
            // check if data come in POST request
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // get the variables from the form
                $itemID = $_POST['item_id'];
                $name = $_POST['name'];
                $desc = $_POST['desc'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $status = $_POST['status'];
                $userID = $_POST['members'];
                $catID = $_POST['categories'];

                // form validation
                $formErrors = array();
                if (empty($name)) {
                    $formErrors[] = "<div class='alert alert-danger'>Name can't be <strong>Empty</strong></div>";
                }
                if (empty($desc)) {
                    $formErrors[] = "<div class='alert alert-danger'>Description can't be <strong>Empty</strong></div>";
                }
                if (empty($price)) {
                    $formErrors[] = "<div class='alert alert-danger'>Price can't be <strong>Empty</strong></div>";
                }
                if (empty($country)) {
                    $formErrors[] = "<div class='alert alert-danger'>Country can't be <strong>Empty</strong></div>";
                }
                if ($status == 0) {
                    $formErrors[] = "<div class='alert alert-danger'>You must choose the <strong>Status</strong></div>";
                }
                if ($userID == 0) {
                    $formErrors[] = "<div class='alert alert-danger'>You must choose the <strong>Member</strong></div>";
                }
                if ($catID == 0) {
                    $formErrors[] = "<div class='alert alert-danger'>You must choose the <strong>Category</strong></div>";
                }

                // loop into Errors array and echo it
                foreach($formErrors as $error) {
                    echo $error;
                }

                // check if there are no errors, update the data
                if (empty($formErrors)) {
                    // update this data in database
                    $stmt = $con->prepare('UPDATE items SET item_name = ?, item_desc = ?, item_price = ?, item_country = ?,
                                            item_status = ?, user_id = ?, category_id = ? WHERE item_id = ?');
                    // run the query
                    $stmt->execute(array($name, $desc, $price, $country, $status, $userID, $catID, $itemID));

                    // print success message
                    $successMesg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record updated</div>";
                    redirectHome($successMesg, "members.php", 2);
                }
            } else { // check if the data not come in POST request
                $errorMesg = "<div class='alert alert-danger'>You Can't Browse This Page Direct</div>";
                redirectHome($errorMesg, "index.php", 2);
            }
            echo "</div>";

        } elseif ($do == "delete") { // check if else do = delete, execute delete page code

            echo "<h1 class='text-center'>Delete Member</h1>";
            // check if the id isset and is number, get it's value
            $itemID = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? intval($_GET['item_id']) : 0;
            // select the item
            $stmt = $con->prepare("SELECT * FROM items WHERE item_id = ? LIMIT 1");
            // execute the query
            $stmt->execute(array($itemID));
            // row count
            $count = $stmt->rowCount();
            // check if the is item of this id delete it
            if ($count > 0) {
                $stmt = $con->prepare("DELETE FROM items WHERE item_id = ?");
                $stmt->execute(array($itemID));
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

            echo "<h1 class='text-center'>Approve Item</h1>";
            // check if the id isset and is number, get it's value
            $itemID = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? intval($_GET['item_id']) : 0;
            // select the item
            $stmt = $con->prepare("SELECT * FROM items WHERE item_id = ? LIMIT 1");
            // execute the query
            $stmt->execute(array($itemID));
            // row count
            $count = $stmt->rowCount();
            // check if the is item of this id delete it
            if ($count > 0) {
                $stmt = $con->prepare("UPDATE items SET item_approved = 1 WHERE item_id = ?");
                $stmt->execute(array($itemID));
                // print success message
                echo "<div class='container mt-5'>";
                $theMesg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Approved</div>";
                redirectHome($theMesg, 'back', 2);
                echo "</h1>";
            } else { // if there is no such id, show error
                echo "<div class='container'>";
                $errorMesg = "<div class='alert alert-danger'>There Is No Such ID</div>";
                redirectHome($errorMesg, 'index.php', 2);
                echo "</div>";
            }

        } elseif ($do == 'editImage') { // check if else do = editImage, editImage update page code
            // check if get id and if it is numeric then get it's value
            $itemID = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? intval($_GET['item_id']) : 0;
            // select all data
            $stmt = $con->prepare("SELECT * FROM items WHERE item_id = ? LIMIT 1");
            // execute query
            $stmt->execute(array($itemID));
            // row count
            $count = $stmt->rowCount();
            // fetch the data
            $row = $stmt->fetch();
            // if there is ID, show the form
            if ($count > 0) { ?>
                <h1 class="text-center members_h1">Edit Image</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=insertImage" method="POST" enctype="multipart/form-data">
                        <!-- Item ID -->
                        <input type="hidden" name="item_id" value="<?php echo $itemID; ?>" />
                        <!-- Start User Image -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Your Image</label>
                            <div class="col-sm-9 d-inline-block">
                                <input type="file" name="itemImg" class="form-control input-required" />
                            </div>
                        </div>
                        <!-- End User Image -->
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
            }

        } elseif ($do == "insertImage") { // check if else do = insertImage, execute insertImage page code
            echo "<h1 class='text-center'>Update Image</h1>";
            echo "<div class='container mt-5'>";
            // check if data come in POST request
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // get the variable from hte form
                $id = $_POST['item_id'];
                // upload image
                $imgName = $_FILES['itemImg']['name'];
                $imgSize = $_FILES['itemImg']['size'];
                $imgTmp = $_FILES['itemImg']['tmp_name'];
                $imgType = $_FILES['itemImg']['type'];

                // list of allow type to upload
                $imgExtensions = array("jpeg", "jpg", "png", "gif");

                // get allowed extension
                $tmp = explode('.', $imgName);
                $extension = strtolower(end($tmp));

                // form validation
                $formErrors = array();
                if (! empty($imgName) && ! in_array($extension, $imgExtensions)) {
                    $formErrors[] = "<div class='alert alert-danger'>This Extension <strong>Isn't Allow</strong></div>";
                }
                if ($imgSize > 4194304) {
                    $formErrors[] = "<div class='alert alert-danger'>Image Can't Larger Than <strong>4 MB</strong></div>";
                }

                // loop into Errors array and echo it
                foreach($formErrors as $error) {
                    echo $error;
                }

                // check if there are no errors, update the data
                if (empty($formErrors)) {
                    $image = rand(0, 100000) . '_' . $imgName;
                    move_uploaded_file($imgTmp, "uploads\itemsImages\\" . $image);
                    // update this data in database
                    $stmt = $con->prepare('UPDATE items SET item_image = ? WHERE item_id = ?');
                    // run the query
                    $stmt->execute(array($image, $id));

                    // print success message
                    echo "<div class='alert alert-success'>" . $stmt->rowCount() . " Record updated</div>";
                }
            } else { // check if the data not come in POST request
                echo "<div class='alert alert-danger'>You Can't Browse This Page Direct</div>";
            }
            echo "</div>";

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