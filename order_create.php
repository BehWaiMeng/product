<!DOCTYPE HTML>
<html>

<head>
    <title>Create Order</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION["user"])) {
        $_SESSION["warning"] = "You must be logged in to access this page.";
        header("Location: login.php");
        exit; // Terminate script execution after redirect
    }
    include 'navbar.php';
    ?>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Order</h1>
        </div>

        <?php
        // include database connection
        include 'config/database.php';

        // Initialize variables
        $customer_name = $product_ids = $quantities = "";
        $customer_name_error = $product_error = $quantity_error = "";

        // Get all customer names
        $query = "SELECT username FROM customers";
        try {
            $stmt = $con->prepare($query);
            $stmt->execute();
            $customer_names = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit; // Terminate script execution on error
        }

        // Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $customer_name = $_POST['customer_name'];
            $product_ids = $_POST['product'];
            $quantities = $_POST['quantity'];

            // Check if customer name is empty
            if (empty($customer_name)) {
                $customer_name_error = "Username is required";
            }

            // Check if products are selected
            $empty_product = array_filter($product_ids, 'strlen');
            if (empty($empty_product)) {
                $product_error = "At least one product is required";
            }

            // Check if quantities are entered
            $empty_quantity = array_filter($quantities, 'strlen');
            if (empty($empty_quantity)) {
                $quantity_error = "Quantity is required for each selected product";
            }

            if (empty($customer_name_error) && empty($product_error) && empty($quantity_error)) {
                // Insert order data into the orders table
                $query = "INSERT INTO orders (customer_name, order_date) VALUES (:customer_name, NOW())";

                $stmt = $con->prepare($query);
                $stmt->bindParam(':customer_name', $customer_name);

                if ($stmt->execute()) {
                    $order_id = $con->lastInsertId();

                    // Insert order details into the order_details table
                    $item_count = count($product_ids);

                    for ($i = 0; $i < $item_count; $i++) {
                        if (!empty($product_ids[$i]) && !empty($quantities[$i])) {
                            // Get the price for the current product
                            $query_price = "SELECT price FROM products WHERE id = :product_id";
                            $stmt_price = $con->prepare($query_price);
                            $stmt_price->bindParam(':product_id', $product_ids[$i]);
                            $stmt_price->execute();
                            $price = $stmt_price->fetchColumn();
                            // Calculate the total price for the current product
                            $total_price = $price * $quantities[$i];
                            // Insert the order details
                            $query = "INSERT INTO order_details (order_id, product_id, quantity, per_price, total_price, order_date) VALUES (:order_id, :product_id, :quantity, :per_price, :total_price, NOW())";
                            $stmt = $con->prepare($query);
                            $stmt->bindParam(':order_id', $order_id);
                            $stmt->bindParam(':product_id', $product_ids[$i]);
                            $stmt->bindParam(':quantity', $quantities[$i]);
                            $stmt->bindParam(':per_price', $price);
                            $stmt->bindParam(':total_price', $total_price);
                            $stmt->execute();
                        }
                    }
                    echo "<div class='alert alert-success'>Record was saved successfully.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Failed to save record. Please try again.</div>";
                }
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="row">
                <div class="col-12 mb-2">
                    <label class="order-form-label">Username</label>
                </div>
                <div class="col-12 mb-2">
                    <select name="customer_name" class="form-control">
                        <option value="">-- Select Customer --</option>
                        <?php
                        if (!empty($customer_names)) {
                            foreach ($customer_names as $username) {
                                $selected = ($username == $customer_name) ? 'selected' : '';
                                echo "<option value='$username' $selected>$username</option>";
                            }
                        }
                        ?>
                    </select>
                    <span class="error"><?php echo $customer_name_error; ?></span>
                </div>
            </div>

            <div class="pRow">
                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label class="order-form-label">Product</label>
                            <select class="form-select" name="product[]" aria-label="form-select-lg example">
                                <option value="" selected>Choose your product</option>
                                <?php
                                $query = "SELECT * FROM products";
                                $stmtproduct = $con->prepare($query);
                                $stmtproduct->execute();

                                while ($product_row = $stmtproduct->fetch(PDO::FETCH_ASSOC)) {
                                    extract($product_row);
                                    $selected = (in_array($id, $product_ids)) ? 'selected' : '';
                                    echo "<option value='$id' $selected>$name</option>";
                                }
                                ?>
                            </select>
                            <span class="error"><?php echo $product_error; ?></span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group">
                            <label class="order-form-label">Quantity</label>
                            <input type="number" name="quantity[]" class="form-control" min="1" value="<?php echo $quantities[$i]; ?>" />
                            <span class="error"><?php echo $quantity_error; ?></span>
                        </div>
                    </div>
                    <div class="col-1 align-self-end">

                        <div class="form-group">
                            <button type="button" class="btn btn-danger remove-product">Remove</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3">
                <button type="button" class="btn btn-primary add-product">Add Product</button>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <input type='submit' value='Save' class='btn btn-primary' />
                    <a href='order_read.php' class='btn btn-danger'>Back to read orders</a>
                </div>
            </div>
        </form>
    </div> <!-- end .container -->

    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add-product')) {
                var productRow = document.querySelector('.pRow');
                var clone = productRow.cloneNode(true);
                productRow.parentNode.insertBefore(clone, productRow.nextSibling);
            }
            if (event.target.matches('.remove-product')) {
                var productRow = event.target.closest('.pRow');
                if (document.querySelectorAll('.pRow').length > 1) {
                    productRow.remove();
                }
            }
        }, false);
    </script>
</body>

</html>