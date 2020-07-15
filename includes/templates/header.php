<!doctype html>
<html lang="en">
  <head>
    <title><?php getTitle(); ?></title>

    <!-- Required meta tags -->
    <meta charset="utf-8">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@800&display=swap">
    <link rel="stylesheet" href="<?php echo $css; ?>all.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>fontawesome.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>style.css">
  </head>
  <body>
    <?php
        if (isset($_SESSION['user'])) {
            // admin/uploads/usersImages/
            ?>
            <nav class="navbar user-nav navbar-expand-sm navbar-light bg-light">
                <?php
                    $stmt = $con->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt->execute(array ($_SESSION['userID']));
                    $data = $stmt->fetchAll();
                    foreach ($data as $d) {
                        $img = $d['image'];
                    }
                ?>
                <div class="container">
                    <div class="collapse upper-links navbar-collapse" id="collapsibleNavId">
                        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                            <li><span class="btn btn-warning"><a class="text-white" href='new_ad.php'>New Item</a></span></li>
                        </ul>
                        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['full_name']; ?></a>
                                <div class="dropdown-menu" aria-labelledby="dropdownId">
                                    <a class="dropdown-item" href="profile.php">My Profile</a>
                                    <a class="dropdown-item" href="profile.php#my-items">My Items</a>
                                    <a class="dropdown-item" href="#">Settings</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="logout.php">Logout</a>
                                </div>
                            </li>
                            <li class="nav-item ml-2">
                                <a href="profile.php">
                                    <?php
                                        if (! empty($img)) {
                                            echo "<img class='img-fluid rounded-circle' src='admin/uploads/usersImages/".$img."' /> ";
                                        }
                                        if (empty($img)) {
                                            echo "<img class='img-fluid rounded-circle' src='avatar.jpg' /> ";
                                        }
                                    ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        <?php
        } else {
            ?>
            <nav class="navbar navbar-expand-sm navbar-light bg-light">
                <div class="container">
                    <div class="collapse navbar-collapse" id="collapsibleNavId">
                        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                            <li class="nav-item"><a class="nav-link" href="login.php">Login | Signup</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        <?php } ?>

    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><?php echo lang('HOME'); ?></a>
            <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId"
              aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    <?php
                    $cats = getData("*", "categories", "WHERE cat_parent = 0", "cat_id");
                    foreach ($cats as $cat) {
                        echo "<li class='nav-item'>
                                <a class='nav-link' href='categories.php?page_id=".$cat['cat_id'].'&page_name='. str_replace(' ', '-', $cat['cat_name']) ."'>
                                ". $cat['cat_name'] .
                            "</a></li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
