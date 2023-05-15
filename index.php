<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <title>Home Page</title>
    <style>
        /* Add some custom styles here */
        body {
            background-color: #f7f7f7;
        }

        .jumbotron {
            color: #000000;
            /* This will make the text color black */
            text-shadow: 0 1px 3px rgba(0, 0, 0, .5);
            background-color: #ffffff;
        }
    </style>

</head>

<body>
    <?php
    include 'navbar.php';

    // Replace with your database credentials and make sure to establish a connection
    include 'config/database.php';

    // Fetch total customers
    $query = "SELECT COUNT(*) as total_customers FROM customers";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $total_customers = $stmt->fetch(PDO::FETCH_ASSOC)['total_customers'];

    // Fetch total products
    $query = "SELECT COUNT(*) as total_products FROM products";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

    // Fetch total orders
    $query = "SELECT COUNT(*) as total_orders FROM orders";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $total_orders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];
    // Fetch latest order
    $query = "SELECT orders.order_id, customers.username, products.name as product_name, order_details.quantity FROM orders JOIN customers ON orders.customer_name = customers.username JOIN order_details ON orders.order_id = order_details.order_id JOIN products ON order_details.product_id = products.id ORDER BY orders.order_id DESC LIMIT 1";

    $stmt = $con->prepare($query);
    $stmt->execute();
    $latest_order = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch highest purchased amount
    $query = "SELECT orders.order_id, customers.username, products.name as product_name, order_details.quantity FROM orders JOIN customers ON orders.customer_name = customers.username JOIN order_details ON orders.order_id = order_details.order_id JOIN products ON order_details.product_id = products.id ORDER BY (order_details.quantity * order_details.per_price) DESC LIMIT 1";


    $stmt = $con->prepare($query);
    $stmt->execute();
    $highest_purchase_order = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch top 5 selling products
    $query = "SELECT products.name, SUM(order_details.quantity) as total_sold FROM order_details JOIN products ON order_details.product_id = products.id GROUP BY products.id ORDER BY total_sold DESC LIMIT 5";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $top_selling_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch 3 products never purchased
    $query = "SELECT name FROM products LEFT JOIN order_details ON products.id = order_details.product_id WHERE order_details.product_id IS NULL LIMIT 3";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $unpurchased_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $con = null;
    ?>

    <main>
        <div class="jumbotron">
            <div class="container">
                <h1 class="display-4">Welcome!</h1>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Customers</h5>
                            <p class="card-text"><?php echo $total_customers; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Products</h5>
                            <p class="card-text"><?php echo $total_products; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Total Orders</h5>
                            <p class="card-text"><?php echo $total_orders; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Latest Order</h5>
                            <p class="card-text">Order ID: <?php echo $latest_order['order_id']; ?> - Customer: <?php echo $latest_order['username']; ?> - Product: <?php echo $latest_order['product_name']; ?> - Quantity: <?php echo $latest_order['quantity']; ?></p>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Highest Purchase Amount</h5>
                            <p class="card-text">Order ID: <?php echo $highest_purchase_order['order_id']; ?> - Customer: <?php echo $highest_purchase_order['username']; ?> - Product: <?php echo $highest_purchase_order['product_name']; ?> - Quantity: <?php echo $highest_purchase_order['quantity']; ?></p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Top 5 Selling Products</h5>

                            <?php foreach ($top_selling_products as $product) : ?>
                                <li><?php echo $product['name']; ?> - <?php echo $product['total_sold']; ?> sold</li>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Products Never Purchased</h5>

                            <?php
                            $count = count($unpurchased_products);
                            foreach ($unpurchased_products as $index => $product) :
                            ?>
                                <li>
                                    <?php echo $product['name']; ?>
                                    <?php if ($index < $count - 1) : ?>

                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>