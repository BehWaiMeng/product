<!DOCTYPE HTML>
<html>

<head>
    <title>Create Order</title>
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
    <div class="container-fluid mt-5 p-5 mb-4">
        <div class="page-header text-center mb-4">
            <h1>Create Order</h1>
        </div>

        <?php
        // include database connection
        include 'config/database.php';

        // Get all customer names
        $query = "SELECT username FROM customers";
        try {
            $stmt = $con->prepare($query);
            $stmt->execute();
            $customer_names = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        // Your PHP code to handle form submission

        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="row">
                <div class="col-12 mb-2 ">
                    <label class="order-form-label">Username</label>
                </div>

                <div class="col-12 mb-2">
                    <select name="customer_name" class="form-control">
                        <option value="">-- Select Customer --</option>
                        <?php if ($customer_names != null) {
                            foreach ($customer_names as $username) { ?>
                                <option value="<?php echo $username; ?>"><?php echo $username; ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>

            <div class="pRow">
                <div class="row">
                    <div class="col-8 mb-2 ">
                        <label class="order-form-label">Product</label>
                    </div>

                    <div class="col-4 mb-2">
                        <label class="order-form-label">Quantity</label>
                    </div>

                    <div class="col-8 ">
                        <select class="form-select mb-3" name="product[]" aria-label="form-select-lg example">
                            <option value="" selected>Choose your product </option>
                            <?php
                            $query = "SELECT * FROM products";
                            $stmtproduct = $con->prepare($query);
                            $stmtproduct->execute();

                            while ($product_row = $stmtproduct->fetch(PDO::FETCH_ASSOC)) {
                                extract($product_row);
                                echo "<option value=$id>$name</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-4 ">
                        <input type="number" name="quantity[]" class="form-control" min="1" />
                    </div>
                </div>
            </div>
            <div class="col-8 mt-3">
                <input type="button" value="Add More" class="add_one btn btn-warning" />
                <input type="button" value="Delete" class="delete_one btn btn-warning" />
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
            if (event.target.matches('.add_one')) {
                var element = document.querySelector('.pRow');
                var clone = element.cloneNode(true);
                element.after(clone);
            }
            if (event.target.matches('.delete_one')) {
                var total = document.querySelectorAll('.pRow').length;
                if (total > 1) {
                    var element = document.querySelector('.pRow:last-child');
                    element.remove();
                }
            }
        }, false);
    </script>
</body>

</html>