<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
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
            <h1>Create Order</h1>
        </div>

        <?php
        // include database connection
        include 'config/database.php';

        // Get all product names
        $query = "SELECT name FROM products";
        try {
            $stmt = $con->prepare($query);
            $stmt->execute();
            $product_names = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        // Get all customer names
        $query = "SELECT username FROM customers";
        try {
            $stmt = $con->prepare($query);
            $stmt->execute();
            $customer_names = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        if ($_POST) {
            try {
                // posted values
                $customer_name = htmlspecialchars(strip_tags($_POST['customer_name']));
                $products = $_POST['product'];
                $quantities = $_POST['quantity'];

                if (empty($customer_name)) {
                    $customer_name_error = "Please enter customer name";
                }

                if (!isset($customer_name_error)) {
                    // Insert the order into the database
                    $query = "INSERT INTO orders SET order_date=:order_date, customer_name=:customer_name";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(':customer_name', $customer_name);

                    $order_date = date('Y-m-d H:i:s');
                    $stmt->bindParam(':order_date', $order_date);

                    if ($stmt->execute()) {
                        $order_id = $con->lastInsertId();

                        // Insert the order details into the database
                        $query = "INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity";
                        $stmt = $con->prepare($query);
                        $stmt->bindParam(':order_id', $order_id);
                        $stmt->bindParam(':product', $product);
                        $stmt->bindParam(':quantity', $quantity);

                        foreach ($products as $index => $product) {
                            if (!empty($product) && !empty($quantities[$index])) {
                                $quantity = htmlspecialchars(strip_tags($quantities[$index]));
                                $stmt->execute();
                            }
                        }

                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Please fill up all the required fields.</div>";
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <table class='table table-hover table-responsive table-bordered'>

                <tr>
                    <td>Select Customer</td>
                    <td>
                        <select name="customer_name" class="form-control">
                            <option value="">-- Select Customer --</option>
                            <?php if ($customer_names != null) {
                                foreach ($customer_names as $username) { ?>
                                    <option value="<?php echo $username; ?>" <?php echo isset($customer_name) && $customer_name == $username ? 'selected' : ''; ?>><?php echo $username; ?></option>
                            <?php }
                            } ?>
                        </select>
                        <?php if (isset($customer_name_error)) { ?><span class="text-danger"><?php echo $customer_name_error; ?></span><?php } ?>
                    </td>
                </tr>

                <?php for ($i = 1; $i <= 3; $i++) { ?>
                    <tr>
                        <td>Product <?php echo $i; ?></td>
                        <td>
                            <select name="product[]" class="form-control">
                                <option value="">-- Select Product --</option>
                                <?php if ($product_names != null) {
                                    foreach ($product_names as $name) { ?>
                                        <option value="<?php echo $name; ?>"><?php echo $name; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </td>
                        <td><input type="number" name="quantity[]" class="form-control" min="1" /></td>
                    </tr>
                <?php } ?>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='order_read.php' class='btn btn-danger'>Back to read orders</a>
                    </td>
                </tr>
            </table>
        </form>

    </div> <!-- end .container -->
</body>

</html>