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
    //check if it login or not
    session_start();
    if (!isset($_SESSION["user"])) {
        $_SESSION["warning"] = "You must be logged in to access this page.";
        header("Location: login.php");
    }

    include 'navbar.php'; ?>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Customers</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php



        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $order_no = isset($_GET['order_no']) ? $_GET['order_no'] : die('ERROR: Record order_no not found.');


        //include database connection
        include 'config/database.php';



        // read current record's data
        try {
            // prepare select query
            $query = "SELECT * FROM orders WHERE order_no = ?";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $order_no);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($row == 0) {
                //die('ERROR: Record not found.');
            }
            // values to fill up our form
            $order_no = $row['order_no'];

            $order_date = $row['order_date'];
            $customer_name = $row['customer_name'];
            $gender = $row['gender'];
            $dob = $row['dob'];
            $status = $row['status'];
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
                <td>order_no</td>
                <td><?php echo htmlspecialchars($order_no, ENT_QUOTES);  ?></td>
            </tr>

            <tr>
                <td>order_date</td>
                <td><?php echo htmlspecialchars($order_date, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>customer_name</td>
                <td><?php echo htmlspecialchars($customer_name, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?php echo htmlspecialchars($gender, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Date of birth</td>
                <td><?php echo htmlspecialchars($dob, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>status</td>
                <td><?php echo htmlspecialchars($status, ENT_QUOTES);  ?></td>
            </tr>



            <tr>
                <td></td>
                <td>
                    <a href='customer_read.php' class='btn btn-danger'>Back to read products</a>
                </td>
            </tr>
        </table>



    </div> <!-- end .container -->

</body>

</html>