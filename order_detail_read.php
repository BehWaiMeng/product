<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Order Details - PHP CRUD Tutorial</title>
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

    include 'navbar.php';
    ?>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Order details</h1>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href='order_create.php' class='btn btn-primary m-b-1em'>Create New Order</a>
            </div>
        </div>

        <?php
        // include database connection
        include 'config/database.php';

        // delete message prompt will be here

        // check if order_id is present in the URL
        if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
            $order_id = htmlspecialchars(strip_tags($_GET['order_id']));

            // select all data
            $query = "SELECT od.order_details_id, od.order_id, od.product_id, od.quantity, p.price, o.customer_name, o.order_date FROM `order_details` od INNER JOIN `products` p ON od.product_id = p.id INNER JOIN `orders` o ON od.order_id = o.order_id WHERE od.order_id = :order_id";

            $stmt = $con->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();

            // this is how to get the number of rows returned
            $num = $stmt->rowCount();

            // check if more than 0 record found
            if ($num > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // extract row
                extract($row);

                // Display order date and customer name
                echo "<div class='row mb-3'>";
                echo "<div class='col-6'><strong>Order Date:</strong> {$order_date}</div>";
                echo "<div class='col-6'><strong>Customer Name:</strong> {$customer_name}</div>";
                echo "</div>";

                // Start table
                echo "<table class='table table-hover table-responsive table-bordered'>";
                echo "<tr>";
                echo "<th>Order ID</th>";
                echo "<th>Product ID</th>";
                echo "<th>Quantity</th>";
                echo "<th>Per price</th>"; // Add price column
                echo "<th>Total Price</th>"; // Add total price column
                echo "<th>Action</th>";
                echo "</tr>";

                $total = 0; // Declare a variable to store the total price for all products

                //Retrieve our table contents
                do {
                    // extract row
                    extract($row);

                    // Calculate total price for each product
                    $total_price = $quantity * $price;

                    // Add the total price of the current product to the total
                    $total += $total_price;

                    // Creating new table row per record
                    echo "<tr>";
                    echo "<td>{$order_id}</td>";
                    echo "<td>{$product_id}</td>";
                    echo "<td>{$quantity}</td>";
                    echo "<td class='text-end'>" . number_format($price, 2) . "</td>";
                    echo "<td class='text-end'>" . number_format($total_price, 2) . "</td>";
                    echo "<td>";
                    echo "<a href='order_read.php' class='btn btn-primary'>Back to read order</a>";
                    echo "</td>";
                    echo "</tr>";
                } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));

                // Display the total price for all products
                echo "<tr>";
                echo "<td colspan='4' class='text-end'><strong>Total Price:</strong></td>";
                echo "<td class='text-end'>" . number_format($total, 2) . "</td>";
                echo "<td></td>";
                echo "</tr>";

                // End table
                echo "</table>";
            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>No order ID provided.</div>";
        }
        ?>

    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->
</body>

</html>