<?php

    /**
     ************************************************
     ** manage members page
     ** you can edit | delete | add members from here
     ************************************************
    **/

    ob_start();     // Output Buffering Start
    session_start();

    // check if there is session called username
    if (isset($_SESSION['user'])) {
        // page title
        $pageTitle = 'Members';
        include 'init.php';

        /** divide the page */
        // check if the link has GET request called do or not
        $do = isset($_GET['do']) ? $_GET['do'] : 'edit';

        if ($do == 'edit') { // check if else do = edit, execute edit page code
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
                <div class="create-ad info mb-3">
                    <div class="container">
                        <div class="card border-primary mt-3">
                            <div class="card-header info-head">
                                <span>Edit Item</span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <dov class="col-md-8">
                                        <form class="form-horizontal main-form" action="?do=update" method="POST" enctype="multipart/form-data">
                                            <!-- User ID -->
                                            <input type="hidden" name="item_id" value="<?php echo $itemID; ?>" />
                                            <!-- Start Name -->
                                            <!-- Start Name -->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Item Name</label>
                                                <div class="col-sm-9 d-inline-block">
                                                    <input type="text" name="name" value="<?php echo $row['item_name'] ?>" class="form-control live input-required" data-class=".live-name" pattern=".{2,}" title="Name must be more than 2 characters" required = "required" />
                                                </div>
                                            </div>
                                            <!-- End Name -->
                                            <!-- Start Desc -->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">description</label>
                                                <div class="col-sm-9 d-inline-block">
                                                    <input type="text" name="desc" value="<?php echo $row['item_desc'] ?>" class="form-control live" data-class=".live-desc" />
                                                </div>
                                            </div>
                                            <!-- End Desc -->
                                            <!-- Start Price -->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Price</label>
                                                <div class="col-sm-9 d-inline-block">
                                                    <input type="text" name="price" value="<?php echo $row['item_price'] ?>" class="form-control live input-required" data-class=".live-price" required="required" />
                                                </div>
                                            </div>
                                            <!-- End Price -->
                                            <!-- Start Country -->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Country Of Item</label>
                                                <div class="col-sm-9 d-inline-block">
                                                    <input type="text" name="country" value="<?php echo $row['item_country'] ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <!-- End Country -->
                                            <!-- Start Status -->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Item Status</label>
                                                <div class="col-sm-9 d-inline-block">
                                                    <select class="form-control input-required" name="status">
                                                        <option value="0" <?php if ($row['item_status'] == 0) { echo "selected"; } ?>></option>
                                                        <option value="1" <?php if ($row['item_status'] == 1) { echo "selected"; } ?>>New</option>
                                                        <option value="2" <?php if ($row['item_status'] == 2) { echo "selected"; } ?>>Like New</option>
                                                        <option value="3" <?php if ($row['item_status'] == 3) { echo "selected"; } ?>>Used</option>
                                                        <option value="4" <?php if ($row['item_status'] == 4) { echo "selected"; } ?>>Old</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- End Status -->
                                            <!-- Start Categories -->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Category</label>
                                                <div class="col-sm-9 d-inline-block">
                                                    <select class="form-control input-required" name="categories">
                                                        <option value="0"></option>
                                                        <?php
                                                            $stmt = $con->prepare("SELECT * FROM categories");
                                                            $stmt->execute();
                                                            $cats = $stmt->fetchAll();
                                                            foreach ($cats as $cat) {
                                                                echo "<option value='".$cat['cat_id']."'";
                                                                if ($row['category_id'] == $cat['cat_id']) { echo "selected"; };
                                                                echo ">".$cat['cat_name']."</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- End Categories -->
                                            <!-- Start Submit Button -->
                                            <div class="form-group">
                                                <div class="offset-sm-2 col-sm-9 d-inline-block">
                                                    <input type="submit" value="Edit Item" class="btn btn-primary" />
                                                    <?php echo "<a href='?do=editImage&item_id=".$row['item_id']."' class='btn btn-secondary text-white'>Edit Image</a>" ?>
                                                </div>
                                            </div>
                                            <!-- End Submit Button -->
                                        </form>
                                    </dov>
                                    <div class="col-md-4">
                                        <div class='img-thumbnail item-box live-preview'>
                                            <span class='price-tag live-price'>0</span>
                                            <div class='text-center'>
                                                <img class='img-fluid w-50' src='./avatar.jpg' />
                                            </div>
                                            <div class='caption ml-3'>
                                                <h3 class="live-name">Name</h3>
                                                <p class="live-desc">Description</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Start Looping on Form Errors -->
                        <div class="errors mt-3">
                            <?php
                                if (! empty($formErrors)) {
                                    foreach ($formErrors as $error) {
                                        echo "<div class='alert alert-danger'>". $error ."</div>";
                                    }
                                }
                            ?>
                        </div>
                        <!-- End Looping on Form Errors -->
                    </div>
                </div>
            <?php }

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
                if ($catID == 0) {
                    $formErrors[] = "<div class='alert alert-danger'>You must choose the <strong>Category</strong></div>";
                }

                // loop into Errors array and echo it
                foreach($formErrors as $error) {
                    echo $error;
                }

                if (empty($formErrors)) {
                    // update this data in database
                    $stmt = $con->prepare('UPDATE items SET item_name = ?, item_desc = ?, item_price = ?, item_country = ?,
                                            item_status = ?, category_id = ? WHERE item_id = ?');
                    // run the query
                    $stmt->execute(array($name, $desc, $price, $country, $status, $catID, $itemID));

                    // print success message
                    echo "<div class='alert alert-success'>" . $stmt->rowCount() . " Record updated</div>";
                }

            } else { // check if the data not come in POST request
                echo "<div class='alert alert-danger'>You Can't Browse This Page Direct</div>";
            }
            echo "</div>";

        } elseif ($do == "delete") { // check if else do = delete, execute delete page code
            echo "<div class='container text-center'>";
                    echo "<h1>Delete Item</h1>";
            echo "</div>";
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
            if ($count > 0) {
                $stmt = $con->prepare("DELETE FROM items WHERE item_id = ?");
                $stmt->execute(array($itemID));
                // print success message
                echo "<div class='container mt-5'>";
                    echo "<div class='alert alert-success'>" . $stmt->rowCount() . " Record deleted</div>";
                echo "</div>";
            } else { // if there is no such id, show error
                echo "<div class='container'>";
                    echo "<div class='alert alert-danger'>There Is No Such ID</div>";
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
            echo "<h1 class='text-center'>Update Member</h1>";
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
                    move_uploaded_file($imgTmp, "admin/uploads\itemsImages\\" . $image);
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

        } elseif ($do == 'editProfile') {
            // check if get id and if it is numeric then get it's value
            $userID = isset($_GET['user_id']) && is_numeric($_GET['user_id']) ? intval($_GET['user_id']) : 0;
            // select all data
            $stmt = $con->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            // execute query
            $stmt->execute(array($userID));
            // row count
            $count = $stmt->rowCount();
            // fetch the data
            $row = $stmt->fetch();
            if ($count > 0) { ?>
                <h1 class="text-center">Edit My Personal Data</h1>
                <div class="container">
                <form class="form-horizontal" action="?do=insertProfile" method="POST" enctype="multipart/form-data">
                        <!-- User ID -->
                        <input type="hidden" name="user_id" value="<?php echo $userID; ?>" />
                        <!-- Start Username -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-9 d-inline-block">
                                <input type="text" name="username" class="form-control input-required" value="<?php echo $row['username']; ?>" autocomplete="off" required = "required" />
                            </div>
                        </div>
                        <!-- End Username -->
                        <!-- Start Password -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-9 d-inline-block">
                            <input type="hidden" name="old_password" value="<?php echo $row['password']; ?>" />
                                <input type="password" name="new_password" class="form-control" autocomplete="new-password" />
                            </div>
                        </div>
                        <!-- End Password -->
                        <!-- Start Email -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-9 d-inline-block">
                                <input type="email" name="email" class="form-control input-required" value="<?php echo $row['email']; ?>" autocomplete="off" required = "required" />
                            </div>
                        </div>
                        <!-- End Email -->
                        <!-- Start Full Name -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Full Name</label>
                            <div class="col-sm-9 d-inline-block">
                                <input type="text" name="full_name" class="form-control input-required" value="<?php echo $row['full_name']; ?>" autocomplete="off" required = "required" />
                            </div>
                        </div>
                        <!-- End Full Name -->
                        <!-- Start Submit Button -->
                        <div class="form-group">
                            <div class="offset-sm-2 col-sm-9 d-inline-block">
                                <input type="submit" value="Save Updates" class="btn btn-primary" />
                                <?php echo "<a href='?do=editProfileImage&user_id=".$row['id']."' class='btn btn-secondary'>Edit Image</a>"; ?>
                            </div>
                        </div>
                        <!-- End Submit Button -->
                    </form>
                </div>
            <?php }
        } elseif ($do == 'insertProfile') {
            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container mt-5'>";
            // check if data come in POST request
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // get the variable from hte form
                $id = $_POST['user_id'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $fullName = $_POST['full_name'];

                // password trick
                $pass = empty($_POST['new_password']) ? $_POST['old_password'] : sha1($_POST['new_password']);

                // form validation
                $formErrors = array();
                if (empty($username)) {
                    $formErrors[] = "<div class='alert alert-danger'>Username can't be <strong>Empty</strong></div>";
                }
                if (strlen($username) < 3) {
                    $formErrors[] = "<div class='alert alert-danger'>Username can't be less than <strong>3 character</strong></div>";
                }
                if (strlen($username) > 20) {
                    $formErrors[] = "<div class='alert alert-danger'>Username can't be more than <strong>20 character</strong></div>";
                }
                if (empty($email)) {
                    $formErrors[] = "<div class='alert alert-danger'>Email can't be <strong>Empty</strong></div>";
                }
                if (empty($fullName)) {
                    $formErrors[] = "<div class='alert alert-danger'>Name can't be <strong>Empty</strong></div>";
                }

                // loop into Errors array and echo it
                foreach($formErrors as $error) {
                    echo $error;
                }

                // check if there are no errors, update the data
                if (empty($formErrors)) {
                    // check if user is exist in database or not
                    $stmt = $con->prepare("SELECT * FROM users WHERE username = ? AND id != ?");
                    $stmt->execute(array($username, $id));
                    $check = $stmt->rowCount();
                    if ($check === 1) {
                        $theMesg = "<div class='alert alert-danger'>This username is already exist in database</div>";
                        redirectHome($theMesg, 'back', 2);
                    } else { // edit the user data and add it to database
                        // update this data in database
                        $stmt = $con->prepare('UPDATE users
                                                SET username = ?, password = ?, email = ?, full_name = ?
                                                WHERE id = ?');
                        // run the query
                        $stmt->execute(array($username, $pass, $email, $fullName, $id));

                        // print success message
                        echo "<div class='alert alert-success'>" . $stmt->rowCount() . " Record updated</div>";
                    }
                }
            } else { // check if the data not come in POST request
                echo "<div class='alert alert-danger'>You Can't Browse This Page Direct</div>";
            }
            echo "</div>";

        } elseif ($do == 'editProfileImage') {
            // check if get id and if it is numeric then get it's value
            $userID = isset($_GET['user_id']) && is_numeric($_GET['user_id']) ? intval($_GET['user_id']) : 0;
            // select all data
            $stmt = $con->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            // execute query
            $stmt->execute(array($userID));
            // row count
            $count = $stmt->rowCount();
            // fetch the data
            $row = $stmt->fetch();
            // if there is ID, show the form
            if ($count > 0) { ?>
                <h1 class="text-center members_h1">Edit Image</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=insertProfileImage" method="POST" enctype="multipart/form-data">
                        <!-- User ID -->
                        <input type="hidden" name="user_id" value="<?php echo $userID; ?>" />
                        <!-- Start User Image -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Your Image</label>
                            <div class="col-sm-9 d-inline-block">
                                <input type="file" name="image" class="form-control input-required" />
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
        } elseif ($do == 'insertProfileImage') {
            echo "<h1 class='text-center'>Update Member</h1>";
            echo "<div class='container mt-5'>";
            // check if data come in POST request
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // get the variable from hte form
                $id = $_POST['user_id'];
                // upload image
                $imgName = $_FILES['image']['name'];
                $imgSize = $_FILES['image']['size'];
                $imgTmp = $_FILES['image']['tmp_name'];
                $imgType = $_FILES['image']['type'];

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
                    move_uploaded_file($imgTmp, "admin\uploads\usersImages\\" . $image);
                    // update this data in database
                    $stmt = $con->prepare('UPDATE users SET image = ? WHERE id = ?');
                    // run the query
                    $stmt->execute(array($image, $id));

                    // print success message
                    $successMesg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record updated</div>";
                    redirectHome($successMesg, "members.php", 200);
                }
            } else { // check if the data not come in POST request
                $errorMesg = "<div class='alert alert-danger'>You Can't Browse This Page Direct</div>";
                redirectHome($errorMesg, "index.php", 5);
            }
            echo "</div>";
        }

        include $tps . 'footer.php';
    }
    // check if else no session called username, redirect to home page
    else {
        header('location: index.php');

        exit();
    }

    ob_end_flush();     // Output Buffering End

?>