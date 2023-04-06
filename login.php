<?php
// Initialize the error message variables
$username_error = '';
$password_error = '';
$msg = '';

// Check if the form was submitted
if (isset($_POST['login'])) {
    // Get the username and password from the form
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if username is empty
    if (empty($username)) {
        $username_error = "Please enter username";
    }

    // Check if password is empty
    if (empty($password)) {
        $password_error = "Please enter password";
    }

    // If there are no errors, try to login
    if (empty($username_error) && empty($password_error)) {
        // Hash the password using md5()
        $password_hashed = md5($password);

        // Database configuration
        $host = 'localhost';
        $username = 'behwaimeng';
        $password = '5201314beh@';
        $dbname = 'behwaimeng';

        // Create a database connection
        $con = mysqli_connect($localhost, $username, $password, $dbname);

        // Check connection
        if (mysqli_connect_errno()) {
            die("Failed to connect to MySQL: " . mysqli_connect_error());
        }

        // Query to check if the username and password match in the database
        $query = "SELECT * FROM customers WHERE username=? AND password=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $password_hashed);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $num_rows = mysqli_stmt_num_rows($stmt);

        // Check if username and password match in the database
        if ($num_rows == 1) {
            // Login successful, redirect to home page or do something else
            header("Location: index.php");
            exit();
        } else {
            // Display an error message
            $msg = "Wrong username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Form</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2 class="text-center">Login</h2>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form action="" method="post">
                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" class="form-control" name="username" required>
                        <?php if (!empty($username_error)) echo '<p class="text-danger">' . $username_error . '</p>'; ?>
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" class="form-control" name="password" required>
                        <?php if (!empty($password_error)) echo '<p class="text-danger">' . $password_error . '</p>'; ?>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="login" value="Login">
                    </div>
                    <?php if (!empty($msg)) echo '<p class="text-danger">' . $msg . '</p>'; ?>
                </form>
            </div>
        </div>
    </div>
    <!-- jQuery and Bootstrap JS -->