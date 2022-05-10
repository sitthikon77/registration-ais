<?php 

    include 'config/db.php';
    session_start();

    $user_id = $_SESSION['user_id'];

    if(!isset($user_id)){
        header('location:index.php');
    }

    // QR code generate
    require_once 'phpqrcode/qrlib.php';

    $file = 'images/' . uniqid().".png";

    if (!empty($file)) {

        $update_file = $conn->prepare("UPDATE `users` SET image_qr = ? WHERE id = ?");
        $update_file->execute([$file, $user_id]);
        // if ($update_file) {
        //     move_uploaded_file($file, $path);
        // }
    }

    // Output
    $select_status = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_status->execute([$user_id]);
    $fetch_status = $select_status->fetch(PDO::FETCH_ASSOC);

    if($select_status->rowCount() > 0) {
        if($fetch_status['phone']){
            $phone_qr = $fetch_status['phone'];
        }else{
            $phone_qr = "ไม่มีข้อมูลผู้ใช้";
        }
    }

    

    // png($text, $file, EXX_LEVEL, Pixel_Size, Frame_size)
    QRcode::png($phone_qr, $file, 'L', 7, 2);

    // echo "<img src='".$file."'>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR code gen | Covid19 Certificate upload system</title>
    <!-- css-bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- css-style -->
    <link rel="stylesheet" href="css/style1.css">
    <!-- Font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
     
    <link rel="stylesheet" href="css/DB-Heavent-woff/stylesheet.css">
</head>
<body style="font-family: 'db_heaventregular'; font-size: 1.5rem;">
    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
    <section class="wrapper-qr">

    <?php
      $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
      $select_profile->execute([$user_id]);
      $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
    ?>

        <div class="container">
            <div class="col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 text-center">
                 <!-- icon-mascot-ais -->
                 <div class="logo">
                    <img src="img/mascot.png" class="img-fluid" width="25%" alt="logo">
                    <img src="img/logo1.png" class="img-fluid" width="30%" alt="logo">
                </div>
                <form action="" class="form-phone bg-white shadow p-4 fw-bold">
                    <h3 class="fs-4 text-dark mb-1 fw-bold">Your QR Code</h3>
                    <h4 class="fs-6 text-dark fw-bold">โปรดเก็บ QR Code ของท่าน เพื่อใช้ยืนยันข้อมูล</h4>
                    <div class="mb-3 row">                     
                        <div class="col-sm-12 text-start justify-content-center">
                            <label for="FName" class="col-form-label">ยินดีต้อนรับ</label>
                            <h3 class="fw-bold fs-4"><?= $fetch_profile['fname']; ?></h3>
                            <div class="d-flex justify-content-center">
                                <?php echo "<img src='".$file."'>";?>
                            </div>
                        </div>                      
                    </div>
                </form>
            </div>
        </div>
     <!-- JS-bootstrap -->
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>