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
            color: #fff;
            text-shadow: 0 1px 3px rgba(0, 0, 0, .5);
        }
    </style>
</head>

<body>
    <?php
    include 'navbar.php';
    ?>
    <main>
        <div class="jumbotron">
            <div class="container">
                <h1 class="display-4">Welcome!</h1>
                < </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Best Selling Product</h5>
                                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in metus libero. Aenean at nisi eget magna bibendum placerat quis vel justo.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Recommended Product</h5>
                                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in metus libero. Aenean at nisi eget magna bibendum placerat quis vel justo.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Best Price</h5>
                                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus in metus libero. Aenean at nisi eget magna bibendum placerat quis vel justo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>
</body>

</html>