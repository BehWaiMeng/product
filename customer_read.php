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
    session_start();
    if (!isset($_SESSION["user"])) {
        $_SESSION["warning"] = "You must be logged in to access this page.";
        header("Location: login.php");
    }

    include 'navbar.php'; ?>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Customers</h1>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href='customers_create.php' class='btn btn-primary m-b-1em'>Create New Customer</a>
            </div>
            <form class="d-flex justify-content-end" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                <div class="input-group">
                    <input class="form-control" type="text" name="search" placeholder="Search Customer Name" aria-label="Search" style="max-width: 300px;">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </div>
            </form>
        </div>

        <!-- PHP code to read records will be here -->
        <?php
        // include database connection
        include 'config/database.php';

        // delete message prompt will be here

        // select all data
        $query = "SELECT * FROM customers";

        // check if search parameter is present in the URL
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search_term = htmlspecialchars(strip_tags($_GET['search']));
            $query .= " WHERE username LIKE '%" . $search_term . "%'";
        }

        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get number of rows returned
        $num = $stmt->rowCount();

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>username</th>";
            echo "<th>gender</th>";
            echo "<th>dateofbirth</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$username}</td>";
                echo "<td>{$gender}</td>";
                echo "<td>{$dob}</td>";

                // ...
                echo "<td>";
                // read one record
                echo "<a href='customer_read_one.php?username={$username}' class='btn btn-info me-3'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='customer_update.php?username={$username}' class='btn btn-primary me-3'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='customer_delete.php' onclick='delete_user(\"{$username}\");'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                // ...

                echo "</tr>";
            }

            // end table
            echo "</table>";
        }
        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>

    </div> <!-- end