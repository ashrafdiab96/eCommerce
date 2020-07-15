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
    if (isset($_SESSION['username'])) {
        $pageTitle = 'Members';                        // page title
        include 'init.php';
        include $tps . 'navbar.php';

        /** divide the page */
        // check if the link has GET request called do or not
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
        // check if do = manage, execute manage page code
        if($do == 'manage') {
            // query to select non approved members
            $query = '';
            if (isset($_GET['page']) && $_GET['page'] == 'pending') {
                $query = 'AND reg_status = 0';
            }
            // select all users except admins
            $stmt = $con->prepare("SELECT * FROM users WHERE is_admin != 1 $query ORDER BY id");
            // run the query
            $stmt->execute();
            // assign to variable
            $rows = $stmt->fetchAll();
            ?>
            <h1 class="text-center members_h1">Manage Page</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <!-- table to show members data -->
                        <thead>
                            <td>ID</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Registered Date</td>
                            <td>Control</td>
                        </thead>
                        <?php
                            // Loop in database to get the data and show it in table
                            foreach ($rows as $row) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['full_name'] . "</td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>
                                        <a href='members.php?do=edit&user_id= " . $row['id'] . " ' class='btn btn-primary'><i class='fa fa-edit'></i> Edit</a>
                                        <a href='members.php?do=delete&user_id= " . $row['id'] . " ' class='btn btn-danger confirmDelete'><i class='fas fa-times'></i> Delete</a>";
                                        if($row['reg_status'] == 0) {
                                            echo "<a href='members.php?do=approve&user_id= " . $row['id'] . " ' class='btn btn-success approve'><i class='far fa-thumbs-up'></i> Approve</a>";
                                        }
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div>
                <!-- Button to add new users -->
                <div class="text-center mt-3">
                    <a href='members.php?do=add' class="btn btn-dark add-user-btn mb-4"><i class="fa fa-plus"></i> Add New Member</a>
                </div>
            </div>
        <?php
        } elseif ($do == "add") { // check if else do = add, execute add page code ?>
            <h1 class="text-center members_h1">Add New Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=insert" method="POST" enctype="multipart/form-data">
                    <!-- Start Username -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="username" class="form-control input-required" autocomplete="off" required = "required" />
                            <span class="asterisk">*</span>
                        </div>
                    </div>
                    <!-- End Username -->
                    <!-- Start Password -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="password" name="password" class="form-control pass-inp input-required" autocomplete="new-password" required = "required" />
                            <i class="show-pass fa fa-eye fa-2x"></i>
                            <span class="asterisk">*</span>
                        </div>
                    </div>
                    <!-- End Password -->
                    <!-- Start Email -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="email" name="email" class="form-control input-required" autocomplete="off" required = "required" />
                            <span class="asterisk">*</span>
                        </div>
                    </div>
                    <!-- End Email -->
                    <!-- Start Full Name -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="name" class="form-control input-required" autocomplete="off" required = "required" />
                            <span class="asterisk">*</span>
                        </div>
                    </div>
                    <!-- End Full Name -->
                    <!-- Start User Image -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Your Photo</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="file" name="userImg" class="form-control input-required" required = "required" />
                            <span class="asterisk">*</span>
                        </div>
                    </div>
                    <!-- End User Image -->
                    <!-- Start Submit Button -->
                    <div class="form-group">
                        <div class="offset-sm-2 col-sm-9 d-inline-block">
                            <input type="submit" value="Add Member" class="btn btn-primary" />
                        </div>
                    </div>
                    <!-- End Submit Button -->
                </form>
            </div>
        <?php
        } elseif($do == "insert") { // check if else do = insert, execute insert page code
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo "<h1 class='text-center'>Insert New User</h1>";
                echo "<div class='container'>";
                // get the variables from the form
                $username = $_POST['username'];
                $password = $_POST['password'];
                $hashPass = sha1($_POST['password']);
                $email = $_POST['email'];
                $name = $_POST['name'];

                // upload image
                $imgName = $_FILES['userImg']['name'];
                $imgSize = $_FILES['userImg']['size'];
                $imgTmp = $_FILES['userImg']['tmp_name'];
                $imgType = $_FILES['userImg']['type'];

                // list of allow type to upload
                $imgExtensions = array("jpeg", "jpg", "png", "gif");

                // get allowed extension
                $tmp = explode('.', $imgName);
                $extension = strtolower(end($tmp));


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
                if (empty($password)) {
                    $formErrors[] = "<div class='alert alert-danger'>Password can't be <strong>Empty</strong></div>";
                }
                if (strlen($password) < 8) {
                    $formErrors[] = "<div class='alert alert-danger'>Password can't be less than <strong>8 character</strong></div>";
                }
                if (empty($email)) {
                    $formErrors[] = "<div class='alert alert-danger'>Email can't be <strong>Empty</strong></div>";
                }
                if (empty($name)) {
                    $formErrors[] = "<div class='alert alert-danger'>Name can't be <strong>Empty</strong></div>";
                }
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

                // if there are no errors, insert data in database to add new member
                if(empty($formErrors)) {
                    $image = rand(0, 100000) . '_' . $imgName;
                    move_uploaded_file($imgTmp, "uploads\usersImages\\" . $image);
                    // check if user is exist in database or not
                    $check = checkItem("username", "users", $username);
                    if ($check === 1) {
                        $theMesg = "<div class='alert alert-danger'>This username is already exist in database</div>";
                        redirectHome($theMesg, 'back', 4);
                    } else { // edit the new user to database
                            // insert the data
                            $stmt = $con->prepare("INSERT INTO users
                                                    (username, password, email, full_name, image, reg_status, date)
                                                    VALUES
                                                    (:user, :pass, :email, :name, :img, 1, now())");
                            // run the query
                            $stmt->execute(array(
                            'user'  => $username,
                            'pass'  => $hashPass,
                            'email' => $email,
                            'name'  => $name,
                            'img'   => $image
                        ));
                        // print success message
                        $theMesg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";
                        redirectHome($theMesg, "members.php", 2);
                    }
                }
                echo "</div>";
            } else { // check if the data not come in POST request
                $errorMesg = "<div class='alert alert-danger'>You Can't Browse This Page Direct</div>";
                redirectHome($errorMesg, "index.php", 5);
            }

        } elseif ($do == 'edit') { // check if else do = edit, execute edit page code
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
                <h1 class="text-center members_h1">Edit Member</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=update" method="POST" enctype="multipart/form-data">
                        <!-- User ID -->
                        <input type="hidden" name="user_id" value="<?php echo $userID; ?>" />
                        <!-- Start Username -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-9 d-inline-block">
                                <input type="text" name="username" class="form-control input-required" value="<?php echo $row['username']; ?>" autocomplete="off" required = "required" />
                                <span class="asterisk">*</span>
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
                                <span class="asterisk">*</span>
                            </div>
                        </div>
                        <!-- End Email -->
                        <!-- Start Full Name -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Full Name</label>
                            <div class="col-sm-9 d-inline-block">
                                <input type="text" name="full_name" class="form-control input-required" value="<?php echo $row['full_name']; ?>" autocomplete="off" required = "required" />
                                <span class="asterisk">*</span>
                            </div>
                        </div>
                        <!-- End Full Name -->
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
                        $successMesg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record updated</div>";
                        redirectHome($successMesg, "members.php", 2);
                    }
                }
            } else { // check if the data not come in POST request
                $errorMesg = "<div class='alert alert-danger'>You Can't Browse This Page Direct</div>";
                redirectHome($errorMesg, "index.php", 5);
            }
            echo "</div>";
        } elseif ($do == "delete") { // check if else do = delete, execute delete page code
            echo "<h1 class='text-center'>Delete Member</h1>";
            // check if the id isset and is number, get it's value
            $userID = isset($_GET['user_id']) && is_numeric($_GET['user_id']) ? intval($_GET['user_id']) : 0;
            // select the user
            $stmt = $con->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            // execute the query
            $stmt->execute(array($userID));
            // row count
            $count = $stmt->rowCount();
            // check if the is user of this id delete it
            if ($count > 0) {
                $stmt = $con->prepare("DELETE FROM users WHERE id = :userId");
                $stmt->bindParam(":userId", $userID);
                $stmt->execute();
                // print success message
                echo "<div class='container mt-5'>";
                $theMesg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record deleted</div>";
                redirectHome($theMesg, 'back', 4);
                echo "</div>";
            } else { // if there is no such id, show error
                echo "<div class='container'>";
                $errorMesg = "<div class='alert alert-danger'>There Is No Such ID</div>";
                redirectHome($errorMesg, 'back', 4);
                echo "</div>";
            }
        } elseif ($do == 'approve') { // check if else do = approve, execute approve page code
            echo "<h1 class='text-center'>Approve Member</h1>";
            // check if the id isset and is number, get it's value
            $userID = isset($_GET['user_id']) && is_numeric($_GET['user_id']) ? intval($_GET['user_id']) : 0;
            // select the user
            $stmt = $con->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            // execute the query
            $stmt->execute(array($userID));
            // row count
            $count = $stmt->rowCount();
            // check if the is user of this id delete it
            if ($count > 0) {
                $stmt = $con->prepare("UPDATE users SET reg_status = 1 WHERE id = :userId");
                $stmt->bindParam(":userId", $userID);
                $stmt->execute();
                // print success message
                echo "<div class='container mt-5'>";
                $theMesg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Approved</div>";
                redirectHome($theMesg, 'back', 4);
                echo "</h1>";
            } else { // if there is no such id, show error
                echo "<div class='container'>";
                $errorMesg = "<div class='alert alert-danger'>There Is No Such ID</div>";
                redirectHome($errorMesg, 'back', 4);
                echo "</div>";
            }
        } elseif ($do == 'editImage') {
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
                    <form class="form-horizontal" action="?do=insertImage" method="POST" enctype="multipart/form-data">
                        <!-- User ID -->
                        <input type="hidden" name="user_id" value="<?php echo $userID; ?>" />
                        <!-- Start User Image -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Your Image</label>
                            <div class="col-sm-9 d-inline-block">
                                <input type="file" name="image" class="form-control input-required" required = "required" />
                                <span class="asterisk">*</span>
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
        } elseif ($do == "insertImage") {
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
                    move_uploaded_file($imgTmp, "uploads\usersImages\\" . $image);
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