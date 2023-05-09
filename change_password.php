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

        <!-- Add the same structure for displaying messages as in the second snippet -->
        <?php
        // Include database connection
        include 'config/database.php';

        // Add a place for delete message prompt (similar to the second snippet)
        ?>

        <?php
        // Rest of the code remains the same
        ?>

        <form action="change_password.php" method="POST">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Old Password</td>
                    <td><input type="password" name="old_password" class="form-control" required></td>
                </tr>
                <tr>
                    <td>New Password</td>
                    <td><input type="password" name="new_password" class="form-control" required></td>
                </tr>
                <tr>
                    <td>Confirm New Password</td>
                    <td><input type="password" name="confirm_password" class="form-control" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" class="btn btn-primary" value="Change Password" name="change_password">
                    </td>
                </tr>
            </table>
        </form>
    </div>

</body>

</html>