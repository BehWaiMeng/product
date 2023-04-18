<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">




</head>

<body>
    <!--navbar-->
    <?php
    //check if it login or not
    session_start();
    if (!isset($_SESSION["user"])) {
        $_SESSION["warning"] = "You must be logged in to access this page.";
        header("Location: login.php");
    }
    include 'navbar.php'; ?>


    <!-- Latest compiled and minified Bootstrap CSS (Apply your Bootstrap here -->
    </head>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Customers</h1>
        </div>
        <?php
        if ($_POST) {
            // include database connection
            include 'config/database.php';
            try {
                // posted values
                $username = htmlspecialchars(strip_tags($_POST['username'] ?? ''));
                $Password = htmlspecialchars(strip_tags($_POST['Password'] ?? ''));
                $confirmpassword = htmlspecialchars(strip_tags($_POST['confirmpassword'] ?? ''));
                $fname = htmlspecialchars(strip_tags($_POST['fname'] ?? ''));
                $lname = htmlspecialchars(strip_tags($_POST['lname'] ?? ''));
                if (isset($_POST['gender'])) $gender = $_POST['gender'];
                $dob = htmlspecialchars(strip_tags($_POST['dob'] ?? ''));
                if (isset($_POST['status'])) $status = $_POST['status'];

                // check if any field is empty
                if (empty($username)) {
                    $username_error = "Please enter username";
                } elseif (strlen($username) < 6) {
                    $username_error = "The username must be at least 6 characters";
                } elseif (strpos($username, ' ') !== false) {
                    $username_error = "The username cannot contain spaces";
                }
                if (empty($Password)) {
                    $password_error = "Please enter Password";
                } elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", $Password) || preg_match('/\s/', $Password)) {
                    $password_error = "The password must contain at least 6 characters with at least 1 number and 1 alphabet and should not contain spaces";
                } elseif (empty($confirmpassword)) {
                    $confirmpassword_error = "Please enter confirm password";
                } elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", $confirmpassword) || preg_match('/\s/', $confirmpassword)) {
                    $confirmpassword_error = "The confirm password must contain at least 6 characters with at least 1 number and 1 alphabet and should not contain spaces";
                } elseif ($Password != $confirmpassword) {
                    $confirmpassword_error = "The password does not match.";
                } else {
                    $Password = md5($Password);
                }





                if (empty($fname)) {
                    $fname_error = "Please enter fname";
                }
                if (empty($lname)) {
                    $lname_error = "Please enter lname";
                }
                if (empty($gender)) {
                    $gender_error = "Please enter gender";
                }
                if (empty($dob)) {
                    $dob_error = "Please enter dob";
                }

                if (empty($status)) {
                    $status_error = "Please select status";
                }



                //check if there are any errors
                if (!isset($username_error) && !isset($password_error) && !isset($confirmpassword_error) && !isset($fname_error) && !isset($lname_error) && !isset($gender_error) && !isset($dob_error) && !isset($status_error)) {




                    // insert query
                    $query = "INSERT INTO customers SET username=:username, Password=:Password, fname=:fname, lname=:lname, gender=:gender, dob=:dob, status=:status, created=:created";

                    // prepare query for execution
                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':Password', $Password);
                    $stmt->bindParam(':fname', $fname);
                    $stmt->bindParam(':lname', $lname);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':dob', $dob);
                    $stmt->bindParam(':status', $status);

                    // specify when this record was inserted to the database
                    $created = date('Y-m-d H:i:s');
                    $stmt->bindParam(':created', $created);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Please fill up all the empty place.</div>";
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>



        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>username</td>
                    <td><input type='varchar' name='username' class='form-control' value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" />
                        <?php if (isset($username_error)) { ?><span class="text-danger"><?php echo $username_error; ?></span><?php } ?></<td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='password' name='Password' class='form-control' value="<?php echo isset($Password) ? htmlspecialchars($Password) : ''; ?>" />
                        <?php if (isset($password_error)) { ?><span class="text-danger"><?php echo $password_error; ?></span><?php } ?></<td>
                </tr>
                <tr>
                    <td>confirmpassword</td>
                    <td><input type='password' name='confirmpassword' class='form-control' value="<?php echo isset($confirmpassword) ? htmlspecialchars($confirmpassword) : ''; ?>" />
                        <?php if (isset($confirmpassword_error)) { ?><span class="text-danger"><?php echo $confirmpassword_error; ?></span><?php } ?></<td>
                </tr>
                <tr>
                    <td>fname</td>
                    <td><input type='text' name='fname' class='form-control' value="<?php echo isset($fname) ? htmlspecialchars($fname) : ''; ?>" />
                        <?php if (isset($fname_error)) { ?><span class="text-danger"><?php echo $fname_error; ?></span><?php } ?></<td>
                </tr>
                <tr>
                    <td>lname</td>
                    <td><input type='text' name='lname' class='form-control' value="<?php echo isset($lname) ? htmlspecialchars($lname) : ''; ?>" />
                        <?php if (isset($lname_error)) { ?><span class="text-danger"><?php echo $lname_error; ?></span><?php } ?></<td>
                </tr>
                <tr>
                    <td>gender</td>
                    <td>
                        <input type="radio" name="gender" value="male" <?php if (isset($gender) && $gender == "male") echo "checked"; ?>> Male
                        <input type="radio" name="gender" value="female" <?php if (isset($gender) && $gender == "female") echo "checked"; ?>> Female
                        <?php if (isset($gender_error)) { ?><span class="text-danger"><?php echo $gender_error; ?></span><?php } ?>
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
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='index.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->









</body>

</html>