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



    include 'navbar.php'; ?>



    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Order</h1>
        </div>

        <?php

        // include database connection
        include 'config/database.php';

        // Get all categories
        $query = "SELECT * FROM categories";
        try {
            $stmt = $con->prepare($query);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        if ($_POST) {


            try {
                // posted values
                $order_no = htmlspecialchars(strip_tags($_POST['order_no']));
                $order_date = htmlspecialchars(strip_tags($_POST['order_date']));
                $customer_name = htmlspecialchars(strip_tags($_POST['customer_name']));



                //check if any field is empty
                if (empty($order_no)) {
                    $order_no_error = "Please enter order number";
                }
                if (empty($order_date)) {
                    $order_date_error = "Please enter product description";
                }
                if (empty($customer_name)) {
                    $customer_name_error = "Please enter customer name";
                }




                //check if there are any errors
                if (!isset($order_no_error) && !isset($description_error) && !isset($price_error) && !isset($promotion_price_error) && !isset($manufacture_date_error) && !isset($expired_date_error) && !isset($expired_date_error) && !isset($category_error)) {




                    // insert query
                    $query = "INSERT INTO products SET name=:name, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date , category_id=:category_id, created=:created";


                    // prepare query for execution
                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':promotion_price', $promotion_price);
                    $stmt->bindParam(':manufacture_date', $manufacture_date);
                    $stmt->bindParam(':expired_date', $expired_date);
                    $stmt->bindParam(':category_id', $category_id);
                    // specify when this record was inserted to the database
                    $created = date('Y-m-d H:i:s');
                    $stmt->bindParam(':created', $created);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Please fill up all the empty place.</div>";
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
                    <td>Name</td>
                    <td><input type="varchar" name="name" class="form-control" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" />
                        <?php if (isset($name_error)) { ?><span class="text-danger"><?php echo $name_error; ?></span><?php } ?></<td>
                </tr>

                <tr>
                    <td>Description</td>
                    <td><textarea name="description" class="form-control"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                        <?php if (isset($description_error)) { ?><span class="text-danger"><?php echo $description_error; ?></span><?php } ?></<td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type="double" name='price' class='form-control' value="<?php echo isset($price) ? htmlspecialchars($price) : ''; ?>" />
                        <?php if (isset($price_error)) { ?><span class="text-danger"><?php echo $price_error; ?></span><?php } ?></<td>
                </tr>
                <tr>
                    <td>promotion_price</td>
                    <td><input type='double' name='promotion_price' class='form-control' value="<?php echo isset($promotion_price) ? htmlspecialchars($promotion_price) : ''; ?>" />
                        <?php if (isset($promotion_price_error)) { ?><span class="text-danger"><?php echo $promotion_price_error; ?></span><?php } ?></<td>
                </tr>
                <tr>
                    <td>manufacture_date</td>
                    <td><input type='date' name='manufacture_date' class='form-control' value="<?php echo isset($manufacture_date) ? htmlspecialchars($manufacture_date) : ''; ?>" />
                        <?php if (isset($manufacture_date_error)) { ?><span class="text-danger"><?php echo $manufacture_date_error; ?></span><?php } ?></<td>
                </tr>
                <tr>
                    <td>expired_date</td>
                    <td><input type='date' name='expired_date' class='form-control' value="<?php echo isset($expired_date) ? htmlspecialchars($expired_date) : ''; ?>" />
                        <?php if (isset($expired_date_error)) { ?><span class="text-danger"><?php echo $expired_date_error; ?></span><?php } ?></<td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>
                        <select id="category_id" name="category_id" class="form-control">

                            <?php
                            if (!empty($categories)) {
                                foreach ($categories as $category) {
                                    $selected = isset($category_name) && $category_name == $category['id'] ? 'selected' : '';
                                    echo "<option value='{$category['category_id']}' {$selected}>{$category['category_name']}</option>";
                                }
                            }
                            ?>
                        </select>
                        <?php if (isset($category_error)) { ?><span class="text-danger"><?php echo $category_error; ?></span><?php } ?>
                    </td>
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