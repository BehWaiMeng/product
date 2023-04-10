<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Products by Category - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>

    <?php include 'navbar.php'; ?>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Products by Category</h1>
        </div>

        <!-- PHP read products by category will be here -->
        <?php
        // get passed parameter value, in this case, the category_id
        $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : die('ERROR: Record category_id not found.');

        //include database connection
        include 'config/database.php';

        // read products by category_id
        try {
            $query = "SELECT * FROM products WHERE category_id = ?";
            $stmt = $con->prepare($query);

            // bind the category_id
            $stmt->bindParam(1, $category_id);

            // execute the query
            $stmt->execute();

            // display products in a table
            echo "<table class='table table-hover table-responsive table-bordered'>";
            echo "<tr>";
            echo "<th>Product ID</th>";
            echo "<th>Product Name</th>";
            echo "<th>Description</th>";
            echo "<th>Price</th>";
            echo "<th>Category ID</th>";
            echo "</tr>";

            // loop through the products and display them
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$description}</td>";
                echo "<td>{$price}</td>";
                echo "<td>{$category_id}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <a href='index.php' class='btn btn-danger'>Back to read categories</a>

    </div> <!-- end .container -->

</body>

</html>