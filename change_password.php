<!DOCTYPE HTML>
<html>

<head>
    <title>Change Password</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION["user"])) {
        $_SESSION["warning"] = "You must be logged in to access this page.";
        header("Location: login.php");
    }

    include 'navbar.php';
    ?>

    <div class="container">
        <div class="page-header">
            <h1>Change Password</h1>
        </div>

        <?php
        // Include database connection
        include 'config/database.php';

        if (isset($_POST['change_password'])) {
            $old_password = htmlspecialchars(strip_tags($_POST['old_password']));
            $new_password = htmlspecialchars(strip_tags($_POST['new_password']));
            $confirm_password = htmlspecialchars(strip_tags($_POST['confirm_password']));

            // Fetch the user's password from the database
            $username = $_SESSION['user'];
            $query = "SELECT password FROM customers WHERE username = :username";
            $stmt = $con->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $current_password = $result['password'];

            // Verify the submitted old password against the user's password
            if (password_verify($old_password, $current_password)) {
                if ($new_password === $confirm_password) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Update the password
                    $query = "UPDATE customers SET password=:password WHERE username=:username";
                    $stmt = $con->prepare($query);

                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->bindParam(':username', $username);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Password was changed successfully.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to change the password.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>New password and confirmation do not match.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Incorrect old password. Please try again.</div>";
            }
        }
        ?>

        <form action="change_password.php" method="POST">
            <div class="form-group">
                <label>Old Password</label>
                <input type="password" name="old_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <input type="submit" class="btn btn-primary" value="ChangePassword" name="change_password">
        </form>
    </div>

</body>

</html>