<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

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

        .text-danger {
            color: red;
        }
    </style>
</head>

<body>
    <ul>
        <li><a class="active" href="index.php">Home</a></li>
        <li><a href="product_create.php">Create Product</a></li>
        <li><a href="product_read.php">Read All Product</a></li>
        <li><a href="customers_create.php">Create Customers</a></li>
        <li><a href="customer_read.php">Read All Customers</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Customers</h1>
        </div>

        <!-- PHP code to read records will be here -->
        <?php
        // include database connection
        include 'config/database.php';

        // delete message prompt will be here

        // select all data
        $query = "SELECT * FROM customers";
        $stmt = $con->prepare($query);
        $stmt->execute();

        // this is how to get the number of rows returned
        $num = $stmt->rowCount();

        // link to create record form
        echo "<a href='customers_create.php' class='btn btn-primary m-b-1em'>Create New Customer</a>";

        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>username</th>";
            echo "<th>Password</th>";
            echo "<th>fname</th>";
            echo "<th>lname</th>";
            echo "<th>gender</th>";
            echo "<th>dob</th>";
            echo "<th>status</th>";
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
                echo "<td>{$Password}</td>";
                echo "<td>{$fname}</td>";
                echo "<td>{$lname}</td>";
                echo "<td>{$gender}</td>";
                echo "<td>{$dob}</td>";
                echo "<td>{$status}</td>";

                echo "<td>";
                // read one record
                echo "<a href='customer_read_one.php?username={$username}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='update.php?username={$username}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_user(\"{$username}\");'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
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