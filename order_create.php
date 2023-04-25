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
                $order_no = htmlspecialchars(strip_tags($_POST['order_no']));
                $customer_name = htmlspecialchars(strip_tags($_POST['customer_name']));
                $product1 = htmlspecialchars(strip_tags($_POST['product1']));
                $product1_quantity = htmlspecialchars(strip_tags($_POST['product1_quantity']));
                $product2 = htmlspecialchars(strip_tags($_POST['product2']));
                $product2_quantity = htmlspecialchars(strip_tags($_POST['product2_quantity']));
                $product3 = htmlspecialchars(strip_tags($_POST['product3']));
                $product3_quantity = htmlspecialchars(strip_tags($_POST['product3_quantity']));

                //check if any field is empty
                if (empty($order_no)) {
                    $order_no_error = "Please enter order number";
                }

                if (empty($customer_name)) {
                    $customer_name_error = "Please enter customer name";
                }

                //check if there are any errors
                if (!isset($order_no_error) && !isset($customer_name_error)) {
                    // check if order_no already exists
                    $query = "SELECT COUNT(*) as count FROM orders WHERE order_no=:order_no";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(':order_no', $order_no);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($row['count'] > 0) {
                        // order_no already exists
                        echo "<div class='alert alert-danger'>The order number already exists. Please enter a unique order number.</div>";
                    } else {
                        // insert query
                        $query = "INSERT INTO orders SET order_no=:order_no, order_date=:order_date, customer_name=:customer_name, product1=:product1, product1_quantity=:product1_quantity, product2=:product2, product2_quantity=:product2_quantity, product3=:product3, product3_quantity=:product3_quantity";

                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        // bind the parameters
                        $stmt->bindParam(':order_no', $order_no);
                        $stmt->bindParam(':customer_name', $customer_name);
                        $stmt->bindParam(':product1', $product1);
                        $stmt->bindParam(':product1_quantity', $product1_quantity);
                        $stmt->bindParam(':product2', $product2);
                        $stmt->bindParam(':product2_quantity', $product2_quantity);
                        $stmt->bindParam(':product3', $product3);
                        $stmt->bindParam(':product3_quantity', $product3_quantity);

                        // specify when this record was inserted to the database
                        $order_date = date('Y-m-d H:i:s');
                        $stmt->bindParam(':order_date', $order_date);

                        // Execute the query
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Record was saved.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
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
                    <td>Order ID</td>
                    <td>
                        <input type="text" name="order_no" class="form-control" value="<?php echo isset($order_no) ? htmlspecialchars($order_no) : ''; ?>" />
                        <?php if (isset($order_no_error)) { ?><span class="text-danger"><?php echo $order_no_error; ?></span><?php } ?>
                    </td>
                </tr>
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
                <tr>
                    <td>Product 1</td>
                    <td>
                        <select name="product1" class="form-control">
                            <option value="">-- Select Product --</option>
                            <?php if ($product_names != null) {
                                foreach ($product_names as $name) { ?>
                                    <option value="<?php echo $name; ?>" <?php echo isset($product1) && $product1 == $name ? 'selected' : ''; ?>><?php echo $name; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </td>
                    <td><input type="number" name="product1_quantity" class="form-control" min="1" /></td>
                </tr>
                <tr>
                    <td>Product 2</td>
                    <td>
                        <select name="product2" class="form-control">
                            <option value="">-- Select Product --</option>
                            <?php if ($product_names != null) {
                                foreach ($product_names as $name) { ?>
                                    <option value="<?php echo $name; ?>" <?php echo isset($product2) && $product2 == $name ? 'selected' : ''; ?>><?php echo $name; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </td>
                    <td><input type="number" name="product2_quantity" class="form-control" min="1" /></td>
                </tr>
                <tr>
                    <td>Product 3</td>
                    <td>
                        <select name="product3" class="form-control">
                            <option value="">-- Select Product --</option>
                            <?php if ($product_names != null) {
                                foreach ($product_names as $name) { ?>
                                    <option value="<?php echo $name; ?>" <?php echo isset($product3) && $product3 == $name ? 'selected' : ''; ?>><?php echo $name; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </td>
                    <td><input type="number" name="product3_quantity" class="form-control" /> </td>
                </tr>
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