<?php

include 'config/db.php';
session_start();

$user_qr_id = $_SESSION['user_qr_id'];

if (!isset($user_qr_id)) {
    header('location:qr-admin.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- css-bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- css-style -->
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- font DB-Heavent-woff -->
    <link rel="stylesheet" href="css/DB-Heavent-woff/stylesheet.css">
    <style media="screen">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        #htmltoimage {
            width: 5cm;
            height: 3cm;
            margin: auto;
        }
    </style>

    <script type="text/javascript">
        function downloadimage() {
            var container = document.getElementById("htmltoimage");
            html2canvas(container, {
                allowTaint: true
            }).then(function(canvas) {

                var link = document.createElement("a");
                document.body.appendChild(link);
                link.download = "html_image.jpg";
                link.href = canvas.toDataURL();
                link.target = '_blank';
                link.click();

            });
        }
    </script>


</head>

<body onload="autoClick();" style="font-family: 'db_heaventregular'; text-align:center;">

    <?php
    $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_profile->execute([$user_qr_id]);
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
    ?>

    <div id="htmltoimage" class="container">
        <div class="row mb-2">
            <div class="col-6 text-center ">
                <span class="text-center" style="color: #333333; font-weight: 600; font-size: 21px;">TWZ <br> <?= $fetch_profile['fname']; ?> </span>
            </div>
            <div class="col-6 d-flex justify-content-center align-items-center">
                <img src="<?= $fetch_profile['image_qr']; ?>" class="img-fluid w-75" alt="">
            </div>
        </div>
        <div class="d-flex justify-content-center text-center">
            <img src="img/Asset-2.jpg" width="100%" alt="">
        </div>

    </div>
    <div>
        <button onclick="downloadimage()" class="clickbtn">Download</button>
    </div>

    <script src="js/html2canvas.js"></script>
    <script type="text/javascript"></script>
</body>

</html>