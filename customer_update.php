<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!-- custom css -->
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>

    <?php
    include 'navbar.php';
    ?>
</head>

<body>
    <?php



    echo '<div class="container">
    <div class="page-header">
        <h1>Update Customer</h1>
    </div>';

    $username2 = isset($_GET['username']) ? $_GET['username'] : die('ERROR: Record Username not found.');

    include 'config/database.php';

    try {
        $query = "SELECT * FROM customers WHERE username = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $username2);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $username = $row['username'];
        $fname = $row['fname'];
        $lname = $row['lname'];
        $gender = $row['gender'];
        $dob = $row['dob'];
        $status = $row['status'];
        $password = $row['Password'];  // Get current password from the database
    } catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }

    if ($_POST) {

        try {
            $query = "UPDATE customers
            SET fname=:fname, lname=:lname, gender=:gender, password=:password, dob=:dob, status=:status
            WHERE username = :username";
            $stmt = $con->prepare($query);

            $fname = isset($_POST['fname']) ? htmlspecialchars(strip_tags($_POST['fname'])) : '';
            $lname = isset($_POST['lname']) ? htmlspecialchars(strip_tags($_POST['lname'])) : '';
            $gender = isset($_POST['gender']) ? htmlspecialchars(strip_tags($_POST['gender'])) : '';
            $old_password = isset($_POST['old_password']) ? htmlspecialchars(strip_tags($_POST['old_password'])) : '';
            $new_password = isset($_POST['new_password']) ? htmlspecialchars(strip_tags($_POST['new_password'])) : '';
            $confirm_password = isset($_POST['confirm_password']) ? htmlspecialchars(strip_tags($_POST['confirm_password'])) : '';
            $dob = isset($_POST['dob']) ? htmlspecialchars(strip_tags($_POST['dob'])) : '';
            $status = isset($_POST['status']) ? htmlspecialchars(strip_tags($_POST['status'])) : '';

            if (empty($fname)) {
                $fname_error = "Please enter First name";
            }
            if (empty($lname)) {
                $lname_error = "Please enter Last name";
            }
            if (empty($gender)) {
                $gender_error = "Please enter Gender";
            }

            if (empty($dob)) {
                $dob_error = "Please enter Date Of Birth";
            }
            if (empty($status)) {
                $status_error = "Please select Status";
            }

            if (!empty($old_password) || !empty($new_password) || !empty($confirm_password)) {

                if (empty($old_password)) {
                    $old_password_error = "Please type in the old password";
                }
                if (empty($new_password)) {
                    $new_password_error = "Please type in the new password";
                }
                if (empty($confirm_password)) {
                    $confirm_password_error = "Please type in the confirm password";
                }

                // Verify old password
                $old_password = md5($old_password);
                if ($old_password != $password) {
                    $old_password_error = "Incorrect old password";
                }

                if ($old_password ==  md5($new_password)) {
                    $new_password_error = "The new password and new password cannot be the same";
                }

                // Check if new_password and confirm_password match
                if ($new_password !== $confirm_password) {
                    $confirm_password_error = "New password and confirm password do not match";
                }
            }

            //check if there are any errors
            if (!isset($fname_error) && !isset($lname_error) && !isset($gender_error) && !isset($old_password_error) && !isset($new_password_error) && !isset($confirm_password_error) && !isset($dob_error) && !isset($status_error)) {

                try {
                    $query = "UPDATE customers
                            SET fname=:fname, lname=:lname, gender=:gender, password=:password, dob=:dob, status=:status
                            WHERE username = :username";
                    $stmt = $con->prepare($query);

                    $hashed_password = md5($new_password);

                    $stmt->bindParam(':fname', $fname);
                    $stmt->bindParam(':lname', $lname);
                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':dob', $dob);
                    $stmt->bindParam(':status', $status);
                    $stmt->bindParam(':username', $username);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
            } else {
                echo "<div class='alert alert-danger'>Please fill up all the empty place.</div>";
            }
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
    }


    ?>



    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?username={$username2}"); ?>" method="post">
        <table class='table table-hover table-responsive table-bordered'>
            <!-- Add this inside the table element where you have other input fields -->
            <tr>
                <td>Username</td>
                <td><input type='text' name='username' value="<?php echo htmlspecialchars($username, ENT_QUOTES); ?>" class='form-control' readonly /></td>
            </tr>

            <tr>
                <td>First Name</td>
                <td><input type='text' name='fname' value="<?php echo htmlspecialchars($fname, ENT_QUOTES);  ?>" class='form-control' /></td>

                <?php if (isset($fname_error)) { ?><span class="text-danger"><?php echo $fname_error; ?></span><?php } ?>

            </tr>
            <tr>
                <td>Last Name</td>
                <td><textarea name='lname' class='form-control'><?php echo htmlspecialchars($lname, ENT_QUOTES);  ?></textarea>
                    <?php if (isset($lname_error)) { ?><span class="text-danger"><?php echo $lname_error; ?></span><?php } ?>
                </td>
            </tr>
            <tr>
                <td>gender</td>
                <td>
                    <input type="radio" name="gender" value="Male" <?php if (isset($gender) && $gender == "Male") echo "checked"; ?>> Male
                    <input type="radio" name="gender" value="Female" <?php if (isset($gender) && $gender == "Female") echo "checked"; ?>> Female
                    <?php if (isset($gender_error)) { ?><span class="text-danger"><?php echo $gender_error; ?></span><?php } ?>
                </td>
            </tr>
            <tr>
                <td>Old Password</td>
                <td>
                    <input type='password' name='old_password' class='form-control' />
                    <?php if (isset($old_password_error)) { ?><span class="text-danger"><?php echo $old_password_error; ?></span><?php } ?>
                </td>
            </tr>
            <tr>
                <td>New Password</td>
                <td>
                    <input type='password' name='new_password' class='form-control' />
                    <?php if (isset($new_password_error)) { ?><span class="text-danger"><?php echo $new_password_error; ?></span><?php } ?>
                </td>
            </tr>
            <tr>
                <td>Confirm Password</td>
                <td>
                    <input type='password' name='confirm_password' class='form-control' />
                    <?php if (isset($confirm_password_error)) { ?><span class="text-danger"><?php echo $confirm_password_error; ?></span><?php } ?>
                </td>
            </tr>


            <tr>
                <td>date of birth</td>
                <td><input type='date' name='dob' class='form-control' value="<?php echo isset($dob) ? htmlspecialchars($dob) : ''; ?>" />
                    <?php if (isset($dob_error)) { ?><span class="text-danger"><?php echo $dob_error; ?></span><?php } ?></<td>

            </tr>
            <tr>
                <td>status</td>
                <td>
                    <input type="radio" name="status" value="Active" <?php if (isset($status) && $status == "Active") echo "checked"; ?>> Active
                    <input type="radio" name="status" value="Inactive" <?php if (isset($status) && $status == "Inactive") echo "checked"; ?>> Inactive
                    <?php if (isset($status_error)) { ?><span class="text-danger"><?php echo $status_error; ?></span><?php } ?>


                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Save Changes' class='btn btn-primary' />
                    <a href='customer_read.php' class='btn btn-danger'>Back to read customers</a>
                </td>
            </tr>
        </table>
    </form>
    </div>
    <!-- end .container -->
</body>

</html>