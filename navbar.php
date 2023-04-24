<!DOCTYPE html>
<html>

<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        ul {
            list-style-type: none;
            background-color: #333;
            display: flex;
            align-items: center;
            height: 50px;
        }

        li {
            position: relative;
        }

        li a,
        .dropbtn {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        li a:hover,
        .dropbtn:hover {
            background-color: #111;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            left: 0;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s ease;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        li:hover .dropdown-content {
            display: block;
        }

        /* Style the dropdown button */
        .dropbtn {
            background-color: #333;
            border: none;
            cursor: pointer;
        }

        /* On hover, display the dropdown content */
        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Add a blue background color to dropdown links */
        .dropdown-content a {
            background-color: #f1f1f1;
            color: #333;
        }

        /* Add a grey background color to dropdown button on hover */
        .dropdown:hover .dropbtn {
            background-color: #111;
        }
    </style>
</head>

<body>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li class="dropdown">
            <a class="dropbtn">Products</a>
            <div class="dropdown-content">
                <a href="product_create.php">Create Product</a>
                <a href="product_read.php">Read Products</a>
            </div>
        </li>
        <li class="dropdown">
            <a class="dropbtn">Customers</a>
            <div class="dropdown-content">
                <a href="customers_create.php">Create Customers</a>
                <a href="customer_read.php">Read Customers</a>
            </div>
        </li>
        <li class="dropdown">
            <a class="dropbtn">Categories</a>
            <div class="dropdown-content">
                <a href="category_create.php">Create Category</a>
                <a href="category_read.php">Read Categories</a>
            </div>
        </li>
        <li class="dropdown">
            <a class="dropbtn">Orders</a>
            <div class="dropdown-content">
                <a href="order_create.php">Create Order</a>
                <a href="order_read.php">Read Order</a>
            </div>
        </li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>

</html