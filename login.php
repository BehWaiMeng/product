<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <style>
        body {
            background-color: #f8f9fa;
        }

        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #343a40;
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
            background-color: #495057;
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding-top: 5rem;
            padding-bottom: 5rem;
        }

        .login-form {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 500px;
            width: 100%;
        }

        .page-header {
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION["user"])) {
        header("Location: index.php");
    }

    ?>

    <div class="container login-container">
        <div class="login-form">
            <div class="page-header">
                <h1>Login</h1>
            </div>

            <?php
            // ...

            if ($_POST) {
                // include database connection
                include 'config/database.php';

                try {
                    // posted values
                    $username = htmlspecialchars(strip_tags($_POST['username']));
                    $password = htmlspecialchars(strip_tags($_POST['password']));

                    // check if any field is empty
                    if (empty($username)) {
                        $username_error = "Please enter a username.";
                    }
                    if (empty($password)) {
                        $password_error = "Please enter a password.";
                    }

                    if (!isset($username_error) && !isset($password_error)) {
                        // check if the username exists
                        $query = "SELECT * FROM customers WHERE username = :username";
                        $stmt = $con->prepare($query);
                        $stmt->bindParam(':username', $username);
                        $stmt->execute();
                        $num = $stmt->rowCount();

                        if ($num == 1) {
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $hashed_password = md5($password);

                            // check if the account status is active
                            if ($row['status'] == 'Active') {
                                // check if the password is correct
                                if ($row['Password'] == $hashed_password) {
                                    // if one row is returned, login was successful
                                    echo "<div class='alert alert-success'>Login successful.</div>";
                                    $_SESSION["user"] = $username;
                                    header("Location: index.php");
                                    exit;
                                } else {
                                    // if the password is incorrect
                                    echo "<div class='alert alert-danger'>Incorrect password. Please try again.</div>";
                                }
                            } else {
                                // if the account status is inactive
                                echo "<div class='alert alert-danger'>Your account is inactive. Please contact the administrator.</div>";
                            }
                        } else {
                            // if zero rows are returned, the username doesn't exist
                            echo "<div class='alert alert-danger'>Username not found. Please check your username.</div>";
                        }
                    }
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
            }

            // ...


            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
                    <?php if (isset($username_error)) { ?><span class="text-danger"><?php echo $username_error; ?></span><?php } ?>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <?php if (isset($password_error)) { ?><span class="text-danger"><?php echo $password_error; ?></span><?php } ?>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>