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
</head>

<body>
    <?php

    include 'navbar.php'; ?>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Update Customer</h1>
        </div>
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $username2 = isset($_GET['username']) ? $_GET['username'] : die('ERROR: Record Username not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT * FROM customers WHERE username = ?";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $username2);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $username = $row['username'];
            $fname = $row['fname'];
            $lname = $row['lname'];
            $gender = $row['gender'];
            $dob = $row['dob'];
            $status = $row['status'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <?php
        // check if form was submitted
        if ($_POST) {
            try {
                // write update query
                $query = "UPDATE customers
                  SET fname=:fname, lname=:lname, gender=:gender, dob=:dob, status=:status
                  WHERE username = :username";

                // prepare query for excecution
                $stmt = $con->prepare($query);
                // posted values
                $fname = htmlspecialchars(strip_tags($_POST['fname']));
                $lname = htmlspecialchars(strip_tags($_POST['lname']));
                $gender = htmlspecialchars(strip_tags($_POST['gender']));
                $dob = htmlspecialchars(strip_tags($_POST['dob']));
                $status = htmlspecialchars(strip_tags($_POST['status']));

                // bind the parameters
                $stmt->bindParam(':fname', $fname);
                $stmt->bindParam(':lname', $lname);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':dob', $dob);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':username', $username);
                // Execute the query
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was updated.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?> <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?username={$username2}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='fname' value="<?php echo htmlspecialchars($fname, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><textarea name='lname' class='form-control'><?php echo htmlspecialchars($lname, ENT_QUOTES);  ?></textarea></td>
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
                        <a href='index.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <!-- end .container -->
</body>

</html>