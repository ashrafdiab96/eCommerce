<?php
    ob_start();
    session_start();
    $pageTitle = 'Login';
    include 'init.php';

    if (isset($_SESSION['user'])) {
        // redirect to home
        header('location: index.php');
    }

    // Check if user coming from Http post request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // check if this data for login form
        if (isset($_POST['login'])) {
            $username = $_POST['user'];
            $password = $_POST['pass'];
            $hashPass = sha1($password);

            // Check if user exists in database or not
            $stmt = $con->prepare("SELECT username, password, full_name, id
                                    FROM users
                                    WHERE username = ?
                                    AND password = ?
                                    LIMIT 1");
            $stmt->execute(array($username, $hashPass));
            $count = $stmt->rowCount();                         // number of rows in database
            $row = $stmt->fetch();                              // fetch the data
            // check if count > 0 >> user is exist
            if ($count > 0) {
                $_SESSION['user'] = $username;                  // register session username
                $_SESSION['userID'] = $row['id'];               // register session id
                $_SESSION['full_name'] = $row['full_name'];     // register session full name
                $_SESSION['img'] = $row['image'];               // register session image
                header('location: index.php');                  // redirect to home
                exit();                                         // exit
            } else {
                echo "<div class='container mt-3'>";
                echo "<div class='alert alert-danger'>The username or password not correct</div>";
                echo "</div>";
            }
        } else {    // else, this data by signup form

            $fullName = $_POST['full_name'];
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['pass'];
            $hashPass = sha1($password);
            // array to carry the validation errors
            $formErrors = array();

            // get full name and validate it
            if (isset($_POST['full_name'])) {
                $fullName = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
                if (empty($fullName)) {
                    $formErrors[] = "The Name Can't Be Empty";
                }
                if (strlen($fullName) < 2) {
                    $formErrors[] = "The Name Can't Be Less Than 2 Characters";
                }
                if (strlen($fullName) > 20) {
                    $formErrors[] = "The Name Can't Be More Than 20 Characters";
                }
            }
            // get email and validate it
            if (isset($_POST['email'])) {
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                if (empty($email)) {
                    $formErrors[] = "The Email Can't Be Empty";
                }
                if (filter_var($email, FILTER_SANITIZE_EMAIL) != true) {
                    $formErrors[] = "Invalid Email";
                }
            }

            // get username and validate it
            if (isset($_POST['username'])) {
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                if (empty($username)) {
                    $formErrors[] = "The Username Can't Be Empty";
                }
                if (strlen($username) < 2) {
                    $formErrors[] = "The Username Must Be More Than 2 Characters";
                }
                if (strlen($username) > 50) {
                    $formErrors[] = "The Username Must Be Less Than 50 Characters";
                }
            }

            // get password and validate it
            if (isset($_POST['pass']) && isset($_POST['confirm-pass'])) {
                $password = sha1($_POST['pass']);
                $confirmPass = sha1($_POST['confirm-pass']);
                if (empty($password)) {
                    $formErrors[] = "The Password Can't Be Empty";
                }
                if (strlen($password) < 2) {
                    $formErrors[] = "The Password Must Be More Than 8 Characters";
                }
                if ($password !== $confirmPass) {
                    $formErrors[] = "Sorry, The Password Doesn't Match";
                }
            }

            // check if no errors, insert the user
            if (empty($formErrors)) {
                // check if the user is exist in database
                $check = checkItem("username", "users", $username);
                if ($check == 1) {
                    $formErrors[] = 'Sorry This username is exist in database';
                } else {
                    $stmt = $con->prepare("INSERT INTO users (full_name, email, username, password, reg_status, date)
                                            VALUES (:name, :mail, :user, :pass, 0, now())");
                    $stmt->execute(array(
                        'name'  => $fullName,
                        'mail'  => $email,
                        'user'  => $username,
                        'pass'  => $hashPass
                    ));
                    // print success message
                    $successMsg = "You are signed up successfully";
                }
            }
        }
    }
?>

<!-- Start Login Form -->
<div class="container h-75 d-flex justify-content-center align-items-center login-page">
    <h1 class="text-center mt-5"><span class="login active" data-class="login-form">Login</span>
    | <span class="signup" data-class="signup-form">Signup</span>
    </h1>
    <form class="form-group w-100 mt-3 login-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="input-container">
            <input type="text" class="form-control mb-2" name="user" placeholder="Username" required="required">
        </div>
        <div class="input-container">
            <input type="password" class="form-control" name="pass" placeholder="Password" required="required">
        </div>
        <div class="input-container">
            <input type="submit" class="btn btn-info w-100 mt-2" name="login" value="Login">
        </div>
    </form>
    <!-- End Login Form -->


    <!-- Start Signup Form -->
    <form class="form-group w-100 mt-3 signup-form" data-class="signup-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="input-container">
            <input type="text" class="form-control mb-2" name="full_name" placeholder="Full Name" pattern=".{2,20}" title="Username must between 2 and 20 characters" required="required">
        </div>
        <div class="input-container">
            <input type="email" class="form-control mb-2" name="email" placeholder="Email" required="required">
        </div>
        <div class="input-container">
            <input type="text" class="form-control mb-2" name="username" placeholder="Username" required="required">
        </div>
        <div class="input-container">
            <input type="password" class="form-control mb-2" name="pass" placeholder="Password" pattern=".{8,}" title="Password must More Than 8 characters" required="required">
        </div>
        <div class="input-container">
            <input type="password" class="form-control mb-2" name="confirm-pass" placeholder="Confirm Password" pattern=".{8,}" title="Password must More Than 8 characters" required="required">
        </div>
        <div class="input-container">
            <input type="submit" class="btn btn-success w-100" name="signup" value="Signup">
        </div>
    </form>
</div>
<!-- End Signup Form -->


<!-- Start Errors Show -->
<div class='the-errors'>
    <?php
        if (! empty($formErrors)) {
            echo "<div class='container'>";
            foreach ($formErrors as $error) {
                echo "<div class='msg'>" . $error . "</div>";
            }
            echo "</div>";
        }
        if (isset($successMsg)) {
            echo "<div class='msg success'>". $successMsg ."</div>";
        }
    ?>
</div>

<?php
    include $tps . 'footer.php';
    ob_end_flush();
?>