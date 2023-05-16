<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <?php include 'navbar.php';
    ?>
</head>

<body>
    <?php
    // Check if user is logged in
    session_start();
    if (!isset($_SESSION["user"])) {
        $_SESSION["warning"] = "You must be logged in to access this page.";
        header("Location: login.php");
        exit();
    }

    ?>

    <div class="container">
        <div class="page-header">
            <h1>Create Customers</h1>
        </div>
        <?php
        // Define error variables
        $usernameError = "";
        $passwordError = "";
        $confirmPasswordError = "";
        $fnameError = "";
        $lnameError = "";
        $genderError = "";
        $dobError = "";
        $statusError = "";

        if ($_POST) {
            // Include database connection
            include 'config/database.php';

            try {
                // Get form input values
                $username = htmlspecialchars(strip_tags($_POST['username'] ?? ''));
                $password = htmlspecialchars(strip_tags($_POST['password'] ?? ''));
                $confirmpassword = htmlspecialchars(strip_tags($_POST['confirmpassword'] ?? ''));
                $fname = htmlspecialchars(strip_tags($_POST['fname'] ?? ''));
                $lname = htmlspecialchars(strip_tags($_POST['lname'] ?? ''));
                $gender = $_POST['gender'] ?? '';
                $dob = htmlspecialchars(strip_tags($_POST['dob'] ?? ''));
                $status = $_POST['status'] ?? '';

                // Validate form input
                if (empty($username)) {
                    $usernameError = "Please enter a username.";
                } elseif (strlen($username) < 6) {
                    $usernameError = "The username must be at least 6 characters.";
                } elseif (strpos($username, ' ') !== false) {
                    $usernameError = "The username cannot contain spaces.";
                }

                if (empty($password)) {
                    $passwordError = "Please enter a password.";
                } elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", $password) || preg_match('/\s/', $password)) {
                    $passwordError = "The password must contain at least 6 characters with at least 1 number and 1 letter, and should not contain spaces.";
                } elseif (empty($confirmpassword)) {
                    $confirmPasswordError = "Please enter the confirm password.";
                } elseif ($password !== $confirmpassword) {
                    $confirmPasswordError = "The password and confirm password do not match.";
                }

                if (empty($fname)) {
                    $fnameError = "Please enter the first name.";
                }

                if (empty($lname)) {
                    $lnameError = "Please enter the last name.";
                }

                if (empty($gender)) {
                    $genderError = "Please select a gender.";
                } elseif ($gender !== 'Male' && $gender !== 'Female') {
                    $genderError = "Invalid gender value.";
                }

                if (empty($dob)) {
                    $dobError = "Please enter the date of birth.";
                } elseif (strtotime($dob) > time()) {
                    $dobError = "Date of birth cannot be in the future.";
                }

                if (empty($status)) {
                    $statusError = "Please select a status.";
                }

                // If there are no errors, proceed with database insert
                if (
                    empty($usernameError) && empty($passwordError) &&
                    empty($confirmPasswordError) && empty($fnameError) &&
                    empty($lnameError) && empty($genderError) &&
                    empty($dobError) && empty($statusError)
                ) {
                    // Hash the password
                    $hashedPassword = md5($password);

                    // Prepare insert query
                    $query = "INSERT INTO customers (username, Password, fname, lname, gender, dob, status, created) 
                    VALUES (:username, :password, :fname, :lname, :gender, :dob, :status, :created)";

                    $stmt = $con->prepare($query);

                    // Bind parameters
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->bindParam(':fname', $fname);
                    $stmt->bindParam(':lname', $lname);
                    $stmt->bindParam(':gender', $gender);

                    $stmt->bindParam(':dob', $dob);
                    $stmt->bindParam(':status', $status);

                    // Specify when this record was created
                    $created = date('Y-m-d H:i:s');
                    $stmt->bindParam(':created', $created);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td>
                        <input type='text' name='username' class='form-control' value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" />
                        <div class="text-danger"><?php echo $usernameError; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td>
                        <input type='password' name='password' class='form-control' value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>" />
                        <div class="text-danger"><?php echo $passwordError; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td>
                        <input type='password' name='confirmpassword' class='form-control' value="<?php echo isset($confirmpassword) ? htmlspecialchars($confirmpassword) : ''; ?>" />
                        <div class="text-danger"><?php echo $confirmPasswordError; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td>
                        <input type='text' name='fname' class='form-control' value="<?php echo isset($fname) ? htmlspecialchars($fname) : ''; ?>" />
                        <div class="text-danger"><?php echo $fnameError; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td>
                        <input type='text' name='lname' class='form-control' value="<?php echo isset($lname) ? htmlspecialchars($lname) : ''; ?>" />
                        <div class="text-danger"><?php echo $lnameError; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <input type="radio" name="gender" value="Male" <?php if (isset($gender) && $gender == "Male") echo "checked"; ?>> Male
                        <input type="radio" name="gender" value="Female" <?php if (isset($gender) && $gender == "Female") echo "checked"; ?>> Female
                        <div class="text-danger"><?php echo $genderError; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td>
                        <input type='date' name='dob' class='form-control' value="<?php echo isset($dob) ? htmlspecialchars($dob) : ''; ?>" />
                        <div class="text-danger"><?php echo $dobError; ?></div>
                    </td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <input type="radio" name="status" value="Active" <?php if (isset($status) && $status == "Active") echo "checked"; ?>> Active
                        <input type="radio" name="status" value="Inactive" <?php if (isset($status) && $status == "Inactive") echo "checked"; ?>> Inactive
                        <div class="text-danger"><?php echo $statusError; ?></div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to read customer</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>