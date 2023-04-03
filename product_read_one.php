<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style>
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
        }

        li {
            float: left;
        }

        li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        li a:hover {
            background-color: #111;
        }

        .text-danger {
            color: red;
        }
    </style>
</head>

<body>
    <!--navbar-->
    <ul>
        <li><a class="active" href="http://localhost/PROJECT/index.php">Home</a></li>
        <li><a href="http://localhost/PROJECT/product_create.php">Create Product</a></li>
        <li><a href="http://localhost/PROJECT/product_read.php">Read All Product</a></li>
        <li><a href="http://localhost/PROJECT/product_read_one.php?id=1">Read One Product</a></li>
        <li><a href="http://localhost/PROJECT/customers_create.php">Create Customers</a></li>
        <li><a href="http://localhost/PROJECT/customer_read.php">Read All Customers</a></li>
        <li><a href="http://localhost/PROJECT/customer_read_one.php">Read One Customers</a></li>
        <li><a href="http://localhost/PROJECT/contact.php">Contact</a></li>
    </ul>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Product</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT id, name, description, price, promotion_price, manufacture_date, expired_date FROM products WHERE id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $name = $row['name'];
            $description = $row['description'];
            $price = $row['price'];
            $promotion_price = $row['promotion_price'];
            $manufacture_date = $row['manufacture_date'];
            $expired_date = $row['expired_date'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>



        <!-- HTML read one record table will be here -->
        <!--we have our html table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Name</td>
                <td><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Price</td>
                <td><?php echo htmlspecialchars($price, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>promotion_price</td>
                <td><?php echo htmlspecialchars($promotion_price, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>manufacture_date</td>
                <td><?php echo htmlspecialchars($manufacture_date, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>expired_date</td>
                <td><?php echo htmlspecialchars($expired_date, ENT_QUOTES);  ?></td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <a href='index.php' class='btn btn-danger'>Back to read products</a>
                </td>
            </tr>
        </table>



    </div> <!-- end .container -->

</body>

</html>