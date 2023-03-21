<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <style>
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
        }

        li {
            float: left;
        }

        li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        li a:hover {
            background-color: #111;
        }
    </style>
</head>

<body>
    <!--navbar-->
    <ul>
        <li><a class="active" href="http://localhost/PROJECT/homepage.php">Home</a></li>
        <li><a href="http://localhost/PROJECT/product_create.php">Create Product</a></li>
        <li><a href="http://localhost/PROJECT/customers_create.php">Create Customers</a></li>
        <li><a href="http://localhost/PROJECT/contact.php">Contact
    </ul>




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
                $username = htmlspecialchars(strip_tags($_POST['username']));
                $password = htmlspecialchars(strip_tags($_POST['password']));
                $fname = htmlspecialchars(strip_tags($_POST['fname']));
                $lname = htmlspecialchars(strip_tags($_POST['lname']));
                $gender = htmlspecialchars(strip_tags($_POST['gender']));
                $dob = htmlspecialchars(strip_tags($_POST['dob']));
                $registry = htmlspecialchars(strip_tags($_POST['registry']));
                $status = htmlspecialchars(strip_tags($_POST['status']));


                //check if any field is empty
                if (empty($username)) {
                    $username_error = "Please enter username";
                }
                if (empty($password)) {
                    $password_error = "Please enter password";
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
                if (empty($registry)) {
                    $registry_error = "Please enter registry";
                }
                if (empty($status)) {
                    $status_error = "Please select status";
                }



                //check if there are any errors
                if (!isset($username_error) && !isset($password_error) && !isset($fname_error) && !isset($lname_error) && !isset($gender_error) && !isset($dob_error) && !isset($registry_error) && !isset($status_error)) {




                    // insert query
                    $query = "INSERT INTO customers SET username=:username, password=:password, fname=:fname, lname=:lname, gender=:gender, dob=:dob , registry=:registry, status=:status";

                    // prepare query for execution
                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':fname', $fname);
                    $stmt->bindParam(':lname', $lname);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':dob', $dob);
                    $stmt->bindParam(':registry', $registry);
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
                    <td><input type='varchar' username='username' class='form-control' /></td>
                </tr>
                <tr>
                    <td>password</td>
                    <td><input type='varchar' password='password' class='form-control' value="<?php echo isset($username) ? htmlspecialchars($username) : ""; ?>" /></td>
                </tr>
                <tr>
                    <td>fname</td>
                    <td><input type='text' name='fname' class='form-control' /></td>
                </tr>
                <tr>
                    <td>lname</td>
                    <td><input type='text' name='lname' class='form-control' /></td>
                </tr>
                <tr>
                    <td>gender</td>
                    <td><input type='text' name='gender' class='form-control' /></td>
                </tr>
                <tr>
                    <td>dob</td>
                    <td><input type='date' name='dob' class='form-control' /></td>
                </tr>
                <tr>
                    <td>registry</td>
                    <td><input type='datetime' name='registry' class='form-control' /></td>
                </tr>
                <tr>
                    <td>status</td>
                    <td><input type='text' name='status' class='form-control' /></td>
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