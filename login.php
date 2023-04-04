<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #17a2b8;
        }

        .login-form {
            width: 340px;
            margin: 50px auto;
            font-size: 15px;
            background-color: #fff;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.2);
        }

        .login-form h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-control,
        .btn {
            min-height: 38px;
            border-radius: 2px;
        }

        .btn {
            font-size: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php
    // include database connection
    include 'config/database.php';

    // Initialize the error message variable
    $msg = '';

    if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query to check if the username and password match in the database and status is active
        $query = "SELECT * FROM customers WHERE username=:username  AND Password=:password AND status='active'";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $num_rows = $stmt->rowCount();

        if ($num_rows == 1) {
            // Redirect to index.php
            header("Location: index.php");
            exit;
        } else {
            $msg = "Username or password is incorrect or account is inactive.";
        }
    } else {
        $msg = "Please fill up the empty space.";
    }

    ?>

    <div class="login-form">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary btn-block">Log in</button>
            <?php if (!empty($msg)) { ?>
                <div class="alert alert-danger mt-3"><?php echo $msg; ?></div>
            <?php } ?>
        </form>
    </div>

</body>

</html>