<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">



</head>

<body>
    <!--navbar-->
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
            <h1>Read All Categories</h1>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href='category_create.php' class='btn btn-primary m-b-1em'>Create New Category</a>
            </div>
            <form class="d-flex justify-content-end" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                <div class="input-group">
                    <input class="form-control" type="text" name="search" placeholder="Search Category Name" aria-label="Search" style="max-width: 300px;">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </div>
            </form>
        </div>
        <!-- PHP code to read records will be here -->
        <?php
        // include database connection
        include 'config/database.php';



        // get total number of customers
        $query = "SELECT COUNT(*) as total FROM categories";
        $stmt = $con->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_category = $row['total'];

        echo "<h5>Total category: " . $total_category . "</h5>";

        // delete message prompt will be here



        // Select all data
        $query = "SELECT * FROM categories";

        // Check if search parameter is present in the URL
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search_term = htmlspecialchars(strip_tags($_GET['search']));
            $query .= " WHERE category_name LIKE ?";
        }

        $stmt = $con->prepare($query);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search_term = "%{$search_term}%";
            $stmt->bindParam(1, $search_term);
        }

        $stmt->execute();

        // This is how to get number of rows returned
        $num = $stmt->rowCount();

        // Check if more than 0 record found
        if ($num > 0) {
            // Your code to handle the case when more than 0 record found
        } else {
            // Your code to handle the case when no records found
        }






        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>Category name</th>";
            echo "<th>Category id</th>";
            echo "<th>Description</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$category_name}</td>";
                echo "<td>{$category_id}</td>";
                echo "<td>{$description}</td>";


                echo "<td>";
                // read one record
                echo "<a href='category_read_one.php?category_id={$category_id}' class='btn btn-info me-2'>Read</a>";


                // we will use this links on next part of this post
                echo "<a href='category_update.php?category_id={$category_id}' class='btn btn-primary me-2'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_user({$category_id});'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }



            // end table
            echo "</table>";
        }
        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>


    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->
    <script>
        function delete_user(category_id) {
            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the ID to delete.php and execute the delete query
                window.location = 'category_delete.php?category_id=' + category_id;
            }
        }
    </script>



</body>

</html>