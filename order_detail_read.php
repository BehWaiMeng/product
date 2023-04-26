<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Orders - PHP CRUD Tutorial</title>
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
            <h1>Read Orders</h1>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href='order_create.php' class='btn btn-primary m-b-1em'>Create New Order</a>
            </div>
            <form class="d-flex justify-content-end" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                <div class="input-group">
                    <input class="form-control" type="text" name="search" placeholder="Search order ID" aria-label="Search" style="max-width: 300px;">
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
        $query = "SELECT order_details_id, order_id, product_id, quantity FROM order details";

        // check if search parameter is present in the URL
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search_term = htmlspecialchars(strip_tags($_GET['search']));
            $query .= " WHERE order_detail_id LIKE '%" . $search_term . "%'";
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
            echo "<th>Order details id</th>";
            echo "<th>Order id</th>";
            echo "<th>product id </th>";
            echo "<th> quantity </th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['order_no'] to just $order_no only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$order_details_id}</td>";
                echo "<td>{$order_id}</td>";
                echo "<td>{$product_id}</td>";
                echo "<td>{$quantity}</td>";
                echo "<td>";

                // read one record
                echo "<a href='order_detail_read_one.php?order_no={$order_no}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use these links on the next part of this post
                echo "<a href='order_update.php?order_no={$order_no}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use these links on the next part of this post
                echo "<a href='#' onclick='delete_order({$order_no});'  class='btn btn-danger'>Delete</a>";
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

    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->
</body>

</html>