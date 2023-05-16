<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!-- custom css -->
    <?php

    //check if it login or not
    session_start();
    if (!isset($_SESSION["user"])) {
        $_SESSION["warning"] = "You must be logged in to access this page.";
        header("Location: login.php");
    }
    include 'navbar.php';
    ?>

</head>

<body>


    <div class="container">
        <div class="page-header">
            <h1>Update Category</h1>
        </div>

        <?php
        // database connection will be here
        include 'config/database.php';

        // get passed parameter value, in this case, the record ID
        $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: Record ID not found.');

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT category_id, category_name, description FROM categories WHERE category_id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $category_id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $category_name = $row['category_name'];
            $description = $row['description'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <!-- check if form was submitted -->
        <?php
        // ...

        if ($_POST) {
            try {
                // write update query
                $query = "UPDATE categories SET category_name=:category_name, description=:description WHERE category_id = :category_id";

                // prepare query for execution
                $stmt = $con->prepare($query);

                // posted values
                $category_id = htmlspecialchars(strip_tags($_POST['category_id']));
                $category_name = htmlspecialchars(strip_tags($_POST['category_name']));
                $description = htmlspecialchars(strip_tags($_POST['description']));

                // Check if category name already exists
                $checkQuery = "SELECT category_id FROM categories WHERE category_name = :category_name AND category_id != :category_id";
                $checkStmt = $con->prepare($checkQuery);
                $checkStmt->bindParam(':category_name', $category_name);
                $checkStmt->bindParam(':category_id', $category_id);
                $checkStmt->execute();

                if ($checkStmt->rowCount() > 0) {
                    echo "<div class='alert alert-danger'>Category name already exists.</div>";
                } else {
                    // bind the parameters
                    $stmt->bindParam(':category_name', $category_name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':category_id', $category_id);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                }
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>


        <!-- Moved HTML form outside of the if block -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?category_id={$category_id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>category_name</td>
                    <td><input type='text' name='category_name' value="<?php echo htmlspecialchars($category_name, ENT_QUOTES);  ?>" class='form-control' required /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control' required><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='hidden' name='category_id' value="<?php echo htmlspecialchars($category_id, ENT_QUOTES);  ?>" />
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='category_read.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <!-- end .container -->

</body>

</html>