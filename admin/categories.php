<?php

    /**
     ***************************************************
     ** manage categories page
     ** you can edit | delete | add categories from here
     ***************************************************
    **/

    ob_start();
    session_start();

    // check if there is session called username
    if (isset($_SESSION['username'])) {
        // page title
        $pageTitle = 'Categories';
        include 'init.php';
        include $tps . 'navbar.php';

        /** divide the page */
        // check if the link has GET request called do or not
        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';
        // check if do = manage, execute manage page code
        if($do == 'manage') {

            // dynamic sorting
            $sort = 'ASC';
            $sort_array = array('ASC', 'DESC');

            // check if there is GET request called sort and has value of sort_array
            if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
                $sort = $_GET['sort'];
            }

            // select all from categories table
            $stmt = $con->prepare("SELECT * FROM categories WHERE cat_parent = 0 ORDER BY cat_ordering $sort");
            $stmt->execute();
            $cats = $stmt->fetchAll(); ?>

            <h1 class="text-center my-3">Manage Categories</h1>
            <div class="container">
                <div class="card categories my-4">
                    <div class="card-header">
                        <i class="fas fa-layer-group"></i> Categories
                        <span class="sorting fa-pull-right">
                            <span class="mr-3"><i class="fa fa-sort"></i> Order Type: [</span>
                            <a href="?sort=ASC" class="mr-2 <?php if ($sort == 'ASC') {echo 'active'; } ?>">Ascending</a>
                            <a href="?sort=DESC" class="mr-2 <?php if ($sort == 'DESC') {echo 'active'; } ?>">| Descending ]</a>
                            <span class='mr-2'><i class="fa fa-eye"></i> View: [</span>
                            <span class='mr-2 view active' data-view="full">Full</span>
                            <span class='mr-2 view' data-view="classic">| Classic ]</span>
                        </span>
                    </div>
                    <div class="card-body">
                        <?php
                            foreach ($cats as $cat) {
                                echo "<div class='cat'>";
                                    echo "<div class='hidden-btn'>";
                                        echo "<a href='categories.php?do=edit&category_id=" . $cat['cat_id'] . "' class='btn btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                        echo "<a href='categories.php?do=delete&category_id=" . $cat['cat_id'] . "' class='btn btn-danger confirmDelete'><i class='fas fa-times'></i> Delete</a>";
                                    echo "</div>";
                                    echo "<h3>" . $cat['cat_name'] . "</h3>";
                                    echo "<div class='full-view'>";
                                        echo "<p>";
                                            if ( $cat['cat_desc'] == '') {
                                                echo "No Description";
                                            } else {
                                                echo  $cat['cat_desc'];
                                            }
                                        echo "</p>";
                                        if ($cat['cat_visibility'] == 1) {
                                            echo "<span class='visible'>Hidden</span> ";
                                        }
                                        if ($cat['allow_comments'] == 1) {
                                            echo "<span class='comments'>Comment disabled</span> ";
                                        }
                                        if ($cat['allow_ads'] == 1) {
                                            echo "<span class='ads'>Advertises disabled</span> ";
                                        }
                                        // get child categories
                                        $childCats = getData("*", "categories", "WHERE cat_parent = {$cat['cat_id']}", "cat_id");
                                        if (! empty ($childCats)) {
                                            echo "<h5 class='child-head'>Child Categories</h5>";
                                            echo "<ul class='list-unstyled child-cat'>";
                                                foreach ($childCats as $c) {
                                                    echo "<li>
                                                            <a href='categories.php?do=edit&category_id=" . $c['cat_id'] . "'>" . $c['cat_name'] . "</a>
                                                            <a href='categories.php?do=delete&category_id=" . $c['cat_id'] . "' class='show-delete confirmDelete'>Delete</a>
                                                        </li>";
                                                }
                                            echo "</ul>";
                                        }
                                    echo "</div>";
                                echo "</div>";
                                echo "<hr>";
                            }
                        ?>
                    </div>
                </div>
                <div class="text-center">
                    <a href="categories.php?do=add" class="btn btn-dark mb-4 add-cat">Add Category</a>
                </div>
            </div>

        <?php
        } elseif ($do == "add") { // check if else do = add, execute add page code?>
            <h1 class="text-center members_h1">Add New Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=insert" method="POST">
                    <!-- Start Name -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category Name</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="name" class="form-control input-required" required = "required" />
                            <span class="asterisk">*</span>
                        </div>
                    </div>
                    <!-- End Name -->
                    <!-- Start Description -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="desc" class="form-control" />
                        </div>
                    </div>
                    <!-- End Description -->
                    <!-- Start parent select -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Parent</label>
                        <div class="col-sm-9 d-inline-block">
                            <select name="parent" class="form-control">
                                <option value="0">None</option>
                                <?php
                                    $allCats = getData("*", "categories", "WHERE cat_parent = 0", "cat_id");
                                    foreach ($allCats as $cat) {
                                        echo "<option value='".$cat['cat_id']."'>".$cat['cat_name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End parent select -->
                    <!-- Start Ordering -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="ordering" class="form-control" />
                        </div>
                    </div>
                    <!-- End Ordering -->
                    <!-- Start Visibility -->
                    <div class="form-group d-flex align-items-center">
                        <label class="col-sm-2 control-label">Visibility</label>
                        <div class="col-sm-9 d-inline-block">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1" />
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Visibility -->
                    <!-- Start Comments -->
                    <div class="form-group d-flex align-items-center">
                        <label class="col-sm-2 control-label">Comments</label>
                        <div class="col-sm-9 d-inline-block">
                            <div>
                                <input id="com-yes" type="radio" name="comments" value="0" checked />
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="comments" value="1" />
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Comments -->
                    <!-- Start Ads -->
                    <div class="form-group d-flex align-items-center">
                        <label class="col-sm-2 control-label">Ads</label>
                        <div class="col-sm-9 d-inline-block">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1" />
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Ads -->
                    <!-- Start Submit Button -->
                    <div class="form-group">
                        <div class="offset-sm-2 col-sm-9 d-inline-block">
                            <input type="submit" value="Add Category" class="btn btn-primary" />
                        </div>
                    </div>
                    <!-- End Submit Button -->
                </form>
            </div>
        <?php
        } elseif($do == "insert") { // check if else do = insert, execute insert page code
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo "<h1 class='text-center'>Insert Category</h1>";
                echo "<div class='container'>";

                // get the data from the form
                $catName = $_POST['name'];
                $catDesc = $_POST['desc'];
                $parent = $_POST['parent'];
                $catOrder = $_POST['ordering'];
                $catVisible = $_POST['visibility'];
                $catComment = $_POST['comments'];
                $catAds = $_POST['ads'];

                // form validation and check errors
                $formErrors = array();
                if (empty($catName)) {
                    $formErrors[] = "<div class='alert alert-danger'>Name can't be <strong>Empty</strong></div>";
                }
                if (strlen($catName) < 3) {
                    $formErrors[] = "<div class='alert alert-danger'>Name can't be less than <strong>3 character</strong></div>";
                }
                if (strlen($catDesc) > 250) {
                    $formErrors[] = "<div class='alert alert-danger'>Description can't be more than <strong>250 character</strong></div>";
                }

                // loop into errors array and print the error
                foreach ($formErrors as $error) {
                    echo $error;
                }

                // insert the category if no errors in validation
                if (empty($formErrors)) {
                    // check if the category name is exist in database or not
                    $check = checkItem("cat_name", "categories", $catName);
                    if ($check === 1) {
                        $theMesg = "<div class='alert alert-danger'>This Category is already exist in database</div>";
                        redirectHome($theMesg, 'back', 2);
                    } else { // insert the new category
                        $stmt = $con->prepare("INSERT INTO categories
                                                (cat_name, cat_desc, cat_parent, cat_ordering, cat_visibility, allow_comments, allow_ads)
                                                VALUES
                                                (:name, :desc, :parent, :order, :visible, :comment, :ads)");
                        // run the query
                        $stmt->execute(array(
                            "name"      => $catName,
                            "desc"      => $catDesc,
                            "parent"    => $parent,
                            "order"     => $catOrder,
                            "visible"   => $catVisible,
                            "comment"   => $catComment,
                            "ads"       => $catAds
                        ));
                        // print success message
                        $theMesg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";
                        redirectHome($theMesg, "categories.php", 2);
                    }
                }
                echo "</div>";
            } else { // if the request method not POST
                $errorMesg = "<div class='alert alert-danger'>You Can't Browse This Page Directly</div>";
                redirectHome($errorMesg, "index.php", 2);
            }

        } elseif ($do == 'edit') { // check if else do = edit, execute edit page code

            // check if there is id and if it is number get it's value
            $catID = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? intval($_GET['category_id']) : 0;
            // select category data based on it's id
            $stmt = $con->prepare("SELECT * FROM categories WHERE cat_id = ? LIMIT 1");
            // run the query
            $stmt->execute(array($catID));
            // fetch the data
            $row = $stmt->fetch();
            // get the rows count
            $count = $stmt->rowCount();
            // check if there is ID, show the form
            if ($count > 0) {?>
                <h1 class="text-center members_h1">Edit Category</h1>
                <div class="container">
                <form class="form-horizontal" action="?do=update" method="POST">
                    <!-- Category ID -->
                    <input type="hidden" name="category_id" value="<?php echo $catID; ?>" />
                    <!-- Start Name -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category Name</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="name" class="form-control input-required" value="<?php echo $row['cat_name']; ?>" required = "required" />
                            <span class="asterisk">*</span>
                        </div>
                    </div>
                    <!-- End Name -->
                    <!-- Start Description -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="desc" class="form-control" value="<?php echo $row['cat_desc']; ?>" />
                        </div>
                    </div>
                    <!-- End Description -->
                    <!-- Start Ordering -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-9 d-inline-block">
                            <input type="text" name="ordering" class="form-control" value="<?php echo $row['cat_ordering']; ?>" />
                        </div>
                    </div>
                    <!-- End Ordering -->
                    <!-- Start parent select -->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Parent</label>
                        <div class="col-sm-9 d-inline-block">
                            <select name="parent" class="form-control">
                                <option value="0">None</option>
                                <?php
                                    $allCats = getData("*", "categories", "WHERE cat_parent = 0", "cat_id");
                                    foreach ($allCats as $cat) {
                                        echo "<option value='".$cat['cat_id']."'";
                                        if ($row['cat_parent'] == $cat['cat_id']) { echo 'selected'; }
                                        echo ">".$cat['cat_name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End parent select -->
                    <!-- Start Visibility -->
                    <div class="form-group d-flex align-items-center">
                        <label class="col-sm-2 control-label">Visibility</label>
                        <div class="col-sm-9 d-inline-block">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($row['cat_visibility'] == 0) { echo 'checked'; } ?> />
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($row['cat_visibility'] == 1) { echo 'checked'; } ?> />
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Visibility -->
                    <!-- Start Comments -->
                    <div class="form-group d-flex align-items-center">
                        <label class="col-sm-2 control-label">Comments</label>
                        <div class="col-sm-9 d-inline-block">
                            <div>
                                <input id="com-yes" type="radio" name="comments" value="0" <?php if ($row['allow_comments'] == 0) { echo 'checked'; } ?> />
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="comments" value="1" <?php if ($row['allow_comments'] == 1) { echo 'checked'; } ?> />
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Comments -->
                    <!-- Start Ads -->
                    <div class="form-group d-flex align-items-center">
                        <label class="col-sm-2 control-label">Ads</label>
                        <div class="col-sm-9 d-inline-block">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($row['allow_ads'] == 0) { echo 'checked'; } ?> />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1" <?php if ($row['allow_ads'] == 1) { echo 'checked'; } ?> />
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- End Ads -->
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
                redirectHome($errorMesg, 'back', 2);
                echo "</div>";
            }

        } elseif ($do == 'update') { // check if else do = update, execute update page code

            echo "<h1 class='text-center'>Update Category</h1>";
            echo "<div class='container'>";
            // check if the request is POST or not
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // get the data from the form
                $catID = $_POST['category_id'];
                $catName = $_POST['name'];
                $catDesc = $_POST['desc'];
                $catOrder = $_POST['ordering'];
                $parent = $_POST['parent'];
                $catVisible = $_POST['visibility'];
                $catComments= $_POST['comments'];
                $catAds = $_POST['ads'];

                // check if cat name exists in database, to provide user to edit it to name exists in database
                $stmt = $con->prepare("SELECT * FROM categories WHERE cat_name = ? AND cat_id != ?");
                $stmt->execute(array($catName, $catID));
                $check = $stmt->rowCount();
                if ($check == 1) {
                    $theMesg = "<div class='alert alert-danger'>This name is already exist in database</div>";
                    redirectHome($theMesg, 'back', 2);
                } else { // edit the category data
                    // update the data
                    $stmt = $con->prepare("UPDATE categories
                                            SET cat_name = ?, cat_desc = ?, cat_ordering = ?, cat_parent = ?,
                                            cat_visibility = ?, allow_comments = ?, allow_ads = ?
                                            WHERE cat_id = ?");
                    // run the query
                    $stmt->execute(array($catName, $catDesc, $catOrder, $parent, $catVisible, $catComments, $catAds, $catID));
                    // print success message
                    $successMesg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record updated</div>";
                    redirectHome($successMesg, "categories.php", 2);
                }

                echo "</div>";

            } else { // check if the data not come in POST request
                $errorMesg = "<div class='alert alert-danger'>You Can't Browse This Page Direct</div>";
                redirectHome($errorMesg, "index.php", 5);
            }
        } elseif ($do == "delete") { // check if else do = delete, execute delete page code
            // check if there is id and if it is number get it's value
            $catID = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? intval($_GET['category_id']) : 0;
            // select the category
            $stmt = $con->prepare("SELECT * FROM categories WHERE cat_id = ? LIMIT 1");
            // execute the query
            $stmt->execute(array($catID));
            // row count
            $count = $stmt->rowCount();
            // check if the is category of this id delete it
            if ($count > 0) {
                $stmt = $con->prepare("DELETE FROM categories WHERE cat_id = ?");
                $stmt->execute(array($catID));
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
        }

        include $tps . 'footer.php';
    }

    // check if else no session called username, redirect to home page
    else {
        header('location: index.php');

        exit();
    }

    ob_end_flush();

?>