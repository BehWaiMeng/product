<!DOCTYPE HTML>
<html>

<head>
    <title>Update Customer</title>
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
            <h1>Update Customer</h1>
        </div>

        <?php
        // Check if the form was submitted
        if ($_POST) {
            // Include database connection
            include 'config/database.php';

            // Prepare update query
            $query = "UPDATE customers SET fname=:fname, lname=:lname, gender=:gender, dob=:dob, status=:status, modified=:modified WHERE username=:username";
            $stmt = $con->prepare($query);

            // Posted values
            $username = htmlspecialchars(strip_tags($_POST['username']));
            $fname = htmlspecialchars(strip_tags($_POST['fname']));
            $lname = htmlspecialchars(strip_tags($_POST['lname']));
            $gender = htmlspecialchars(strip_tags($_POST['gender']));
            $dob = htmlspecialchars(strip_tags($_POST['dob']));
            $status = htmlspecialchars(strip_tags($_POST['status']));

            // Get the current timestamp for the modified column
            $modified = date('Y-m-d H:i:s');

            // Bind the parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':dob', $dob);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':modified', $modified);

            // Execute the query
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Customer details were updated.</div>";
                header("Location: {$_SERVER['PHP_SELF']}?username=" . urlencode($username));
                exit();
            } else {
                echo "<div class='alert alert-danger'>Unable to update customer details.</div>";
            }
        }
        ?>

        <?php
        // Get passed parameter value, in this case, the record ID
        $username2 = isset($_GET['username']) ? $_GET['username'] : die('ERROR: Record Username not found.');

        // Include database connection
        include 'config/database.php';

        // Read current record's data
        try {
            $query = "SELECT * FROM customers WHERE username = ?";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $username2);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row !== false) {
                extract($row);
            } else {
                die('ERROR: Could not find customer with the given username.');
            }
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username2, ENT_QUOTES); ?>">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="fname" value="<?php echo htmlspecialchars($fname, ENT_QUOTES); ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="lname" value="<?php echo htmlspecialchars($lname, ENT_QUOTES); ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="gender" class="form-control">
                    <option value="Male" <?php echo ($gender == "Male") ? "selected" : ""; ?>>Male</option>
                    <option value="Female" <?php echo ($gender == "Female") ? "selected" : ""; ?>>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" value="<?php echo htmlspecialchars($dob, ENT_QUOTES); ?>" class="form-control">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Active" <?php echo ($status == "Active") ? "selected" : ""; ?>>Active</option>
                    <option value="Inactive" <?php echo ($status == "Inactive") ? "selected" : ""; ?>>Inactive</option>
                </select>
            </div>

            <input type="submit" class="btn btn-primary" value="Update">
            <a href="customer_read.php" class="btn btn-danger">Cancel</a>
        </form>

        <!-- Change password button -->
        <div class="mt-4">

            <a href="change_password.php?username=<?php echo urlencode($username2); ?>" class="btn btn-secondary">Change Password</a>
        </div>


        <?php
        if (isset($_POST['change_password'])) {
            $new_password = htmlspecialchars(strip_tags($_POST['new_password']));
            $confirm_password = htmlspecialchars(strip_tags($_POST['confirm_password']));

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
        }
        ?>
    </div>
</body>

</html>