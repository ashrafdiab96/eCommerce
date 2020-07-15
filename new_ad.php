<?php
    ob_start();
    session_start();
    // page title
    $pageTitle = "Create New Item";
    include 'init.php';
    if (isset($_SESSION['user'])) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formErrors = array();
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc = filter_var($_POST['desc'], FILTER_SANITIZE_STRING);
            $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $categories = filter_var($_POST['categories'], FILTER_SANITIZE_NUMBER_INT);

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

            if (empty($name)) {
                $formErrors[] = "Item Name Can't Be Empty";
            }
            if (strlen($name) < 2) {
                $formErrors[] = "Item Name Must Be More Than 2 Characters";
            }
            if (strlen($name) > 50) {
                $formErrors[] = "Item Name Must Be Less Than 50 Characters";
            }
            if (strlen($desc) > 250) {
                $formErrors[] = "Item Description Must Be Less Than 250 Characters";
            }
            if (empty($price)) {
                $formErrors[] = "Price Can't Be Empty";
            }
            if (empty($status)) {
                $formErrors[] = "Status Can't Be Empty";
            }
            if (empty($categories)) {
                $formErrors[] = "Category Can't Be Empty";
            }
            if (! empty($imgName) && ! in_array($extension, $imgExtensions)) {
                $formErrors[] = "<div class='alert alert-danger'>This Extension <strong>Isn't Allow</strong></div>";
            }
            if ($imgSize > 4194304) {
                $formErrors[] = "<div class='alert alert-danger'>Image Can't Larger Than <strong>4 MB</strong></div>";
            }

            if (empty ($formErrors)) {
                $image = rand(0, 100000) . '_' . $imgName;
                move_uploaded_file($imgTmp, "admin/uploads\itemsImages\\" . $image);
                $stmt = $con->prepare("INSERT INTO items
                                        (item_name, item_desc, item_price, item_date, item_country, item_image, item_status, category_id, user_id)
                                        VALUES
                                        (:name, :desc, :price, now(), :country, :img, :status, :catID, :user_id)");
                $stmt->execute(array(
                    'name'      => $name,
                    'desc'      => $desc,
                    'price'     => $price,
                    'country'   => $country,
                    'img'       => $image,
                    'status'    => $status,
                    'catID'     => $categories,
                    'user_id'   => $_SESSION['userID']
                ));
                // print success message
                if ($stmt) {
                    echo "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";
                }
            }
        }
?>

<div class="create-ad info mb-3">
    <div class="container">
        <div class="card border-primary mt-3">
            <div class="card-header info-head">
                <span>Create New Ad</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <dov class="col-md-8">
                        <form class="form-horizontal main-form" action="?do=insert" method="POST" enctype="multipart/form-data">
                            <!-- Start Name -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Item Name</label>
                                <div class="col-sm-9 d-inline-block">
                                    <input type="text" name="name" class="form-control live input-required" data-class=".live-name" pattern=".{2,}" title="Name must be more than 2 characters" required = "required" />
                                </div>
                            </div>
                            <!-- End Name -->
                            <!-- Start Desc -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">description</label>
                                <div class="col-sm-9 d-inline-block">
                                    <input type="text" name="desc" class="form-control live" data-class=".live-desc" />
                                </div>
                            </div>
                            <!-- End Desc -->
                            <!-- Start Price -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-9 d-inline-block">
                                    <input type="text" name="price" class="form-control live input-required" data-class=".live-price" required="required" />
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
                                                echo "<option value='".$cat['cat_id']."'>".$cat['cat_name']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- End Categories -->
                            <!-- Start Item Image -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Item Image</label>
                                <div class="col-sm-9 d-inline-block">
                                    <input type="file" name="itemImg" class="form-control input-required" required = "required" />
                                    <span class="asterisk">*</span>
                                </div>
                            </div>
                            <!-- End Item Image -->
                            <!-- Start Submit Button -->
                            <div class="form-group">
                                <div class="offset-sm-2 col-sm-9 d-inline-block">
                                    <input type="submit" value="Add Item" class="btn btn-primary" />
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


<?php
    } else {
        header("location:login.php");
    }

    include $tps . 'footer.php';
    ob_end_flush();
?>