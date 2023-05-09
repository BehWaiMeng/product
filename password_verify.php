<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Include database connection
    include 'config/database.php';

    // Get the submitted password and user's current password from session
    $submitted_password = $_POST['password'];
    $username = $_SESSION['user'];

    // Fetch the user's password from the database
    $query = "SELECT password FROM customers WHERE username = :username";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $current_password = $result['password'];

    // Verify the submitted password against the user's password
    if (password_verify($submitted_password, $current_password)) {
        // If the passwords match, redirect to the Update Customer page
        header("Location: update_customer.php");
    } else {
        // If the passwords don't match, set an error message
        $_SESSION["error"] = "Incorrect password. Please try again.";
    }
}

?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Verify Password</title>
    <!-- Add your CSS and JavaScript links here -->
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>Verify Password</h1>
        </div>

        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <form action="password_verify.php" method="POST">
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
        </form>
    </div>
</body>

</html>