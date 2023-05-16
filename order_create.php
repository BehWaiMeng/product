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
        exit;
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
        $customer_name = "";
        $product_ids = [];
        $quantities = [];
        $customer_name_error = $product_error = $quantity_error = "";

        // Get all customer names
        $query = "SELECT username FROM customers";
        try {
            $stmt = $con->prepare($query);
            $stmt->execute();
            $customer_names = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }

        // Get all product IDs and names
        $query = "SELECT id, name FROM products";
        try {
            $stmt = $con->prepare($query);
            $stmt->execute();
            $products = $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }

        // Generate product options HTML
        $productOptionsHTML = '<option value="">-- Select Product --</option>';
        foreach ($products as $product) {
            $productOptionsHTML .= '<option value="' . $product['id'] . '">' . $product['name'] . '</option>';
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
                $stmt->bindParam(":customer_name", $customer_name);

                try {
                    $con->beginTransaction();
                    $stmt->execute();
                    $order_id = $con->lastInsertId();

                    // Insert order details data into the order_details table
                    $query = "INSERT INTO order_details (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(":order_id", $order_id);

                    foreach ($product_ids as $index => $product_id) {
                        if (!empty($product_id) && !empty($quantities[$index])) {
                            $stmt->bindParam(":product_id", $product_id);
                            $stmt->bindParam(":quantity", $quantities[$index]);
                            $stmt->execute();
                        }
                    }

                    $con->commit();
                    // Set a session variable to show a success message
                    $_SESSION["success"] = "Order successfully created.";

                    header("Location: order_read.php");
                } catch (PDOException $e) {
                    $con->rollBack();
                    echo "Error: " . $e->getMessage();
                }
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <select name="customer_name" class="form-control">
                    <option value="">-- Select Username --</option>
                    <?php foreach ($customer_names as $name) : ?>
                        <option value="<?php echo $name; ?>" <?php if ($customer_name == $name) echo "selected"; ?>>
                            <?php echo $name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="error"><?php echo $customer_name_error; ?></span>
            </div>
            <br>

            <div class="products-container">
                <div class="row product-row">
                    <div class="col-6 mb-2">
                        <select name="product[]" class="form-control">
                            <?php echo $productOptionsHTML; ?>
                        </select>
                    </div>
                    <div class="col-5 mb-2">
                        <input type="number" name="quantity[]" class="form-control" min="1" />
                    </div>
                    <div class="col-1 mb-2">
                        <button type="button" class="btn btn-danger remove-product">Remove</button>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <button type="button" class="btn btn-primary add-product">Add Product</button>
            </div>
            <span class="error"><?php echo $product_error; ?></span>
            <span class="error"><?php echo $quantity_error; ?></span>
            <br>

            <input type="submit" class="btn btn-primary" value="Submit">
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-Ek1M5aJFw3zkf2VJM5anAHIbZ9MlZJ3gZGdr2w+3e3yl4+BXyC6SfVEo0X6Mmqz7" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('.add-product').on('click', function() {
                let productRow = $('.product-row').first().clone();
                productRow.find('input').val('');
                productRow.find('select').val('');
                $('.products-container').append(productRow);
            });

            $('.products-container').on('click', '.remove-product', function() {
                if ($('.product-row').length > 1) {
                    $(this).closest('.product-row').remove();
                }
            });
        });
    </script>
</body>

</html>