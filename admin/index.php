<?php

    session_start();
    $pageTitle = 'Login';                                   // page title
    include 'init.php';
    if (isset($_SESSION['username'])) {
        header('location: dashboard.php');                  // redirect to dashboard.php
    }

    // Check if user coming from Http post request

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['pass'];
        $hashPass = sha1($password);

        // Check if user exists in database or not
        $stmt = $con->prepare("SELECT username, password, full_name, id
                                FROM users
                                WHERE username = ?
                                AND password = ?
                                AND is_admin = 1
                                LIMIT 1");
        $stmt->execute(array($username, $hashPass));
        $count = $stmt->rowCount();                         // number of rows in database
        $row = $stmt->fetch();                              // fetch the data
        // check if count > 0 >> user is exist
        if ($count > 0) {
            $_SESSION['username'] = $username;              // register session username
            $_SESSION['id'] = $row['id'];                   // register session id
            $_SESSION['full_name'] = $row['full_name'];     // register session full name
            header('location: dashboard.php');              // redirect to dashboard.php
            exit();                                         // exit
            // print_r($row);
        }
    }

?>

    <div class="container">
        <div class="row">
            <div class="col">
                <form class="login" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <h4 class="text-center">Login Form</h4>
                    <input class="form-control" type="text" name="username" placeholder="Username" autocapitalize="off" />
                    <input class="form-control myPass" type="password" name="pass" placeholder="Password" autocapitalize="new-password" />
                    <input class="btn btn-primary btn-block" type="submit" value="Login" />
                </form>
            </div>
        </div>
    </div>

<?php
    include $tps . 'footer.php';
?>