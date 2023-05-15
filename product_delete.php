<!DOCTYPE HTML>
<html>

<head>
    <title>Delete Products</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <?php
    // Include database connection
    include 'config/database.php';

    try {
        // Get the record username
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record id not found.');

        // Delete query
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            // Redirect to the Read id page and
            // tell the user the record was deleted
            header('Location: index.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    } catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
    ?>
</body>

</html>