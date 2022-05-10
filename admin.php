<?php

    include 'config/db.php';
    session_start();

    $admin_id = $_SESSION['admin_id'];

    if(!isset($admin_id)){
        header('location:logout.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Covid19 Certificate upload system</title>

    <style>
        .wrapper-admin a{
            
            background-color: #010101;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
        }

        .wrapper-admin a:hover{
            background-color: #b2d233;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            color: #000;
        }        
    </style>

    <!-- css-bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- css-style -->
    <link rel="stylesheet" href="css/style1.css">
    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <section class="wrapper-admin">
        <div class="container">
            <div class="col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 text-center">
                <!-- icon-mascot-ais -->
                <div class="logo">
                    <img src="img/ask_aunjai.png" class="img-fluid" width="70%" alt="logo">
                    <img src="img/logo1.png" class="img-fluid" width="25%" alt="logo">
                </div>
                <!-- form-container--->
                <div action="" class="form-phone bg-white shadow p-4 mt-3">
                    <h3 class="fs-2 text-dark mb-2"> Admin Menu</h3>
                    <div class="mt-3 d-grid">
                        <a class="mb-3" href="qr-admin.php"><i class="fa-solid fa-qrcode"></i> QR Code Scanner</a>
                        <a class="mb-3"  href="crud-admin.php"><i class="fa-solid fa-user"></i> CRUD Admin</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

     <!-- JS-bootstrap -->
     <script src="js/script.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>