<?php

include 'config/db.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>info | Covid19 Certificate upload system</title>
    <!-- css-bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- css-style -->
    <link rel="stylesheet" href="css/style1.css">
    <link rel="stylesheet" href="css/DB-Heavent-woff/stylesheet.css">
</head>

<body style="font-family: 'db_heaventregular'; font-size: 1.5rem;">
    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
    <section class="wrapper-info">
        <?php
        $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_profile->execute([$user_id]);
        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>

        <div class="container">
            <div class="col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 text-center">
                <!-- icon-mascot-ais -->
                <div class="logo">
                    <img src="img/mascot.png" class="img-fluid" width="20%" alt="logo">
                    <img src="img/logo1.png" class="img-fluid" width="30%" alt="logo">
                </div>
                <form action="" class="form-phone bg-white shadow p-4 mt-3">
                    <h4 class="fs-4 fw-bold text-dark mb-2">โปรดตรวจสอบข้อมูลส่วนตัวของท่าน</h4>
                    <div class="mb-3 row">

                        <div class="col-sm-12 text-start">
                            <label for="FName" class="col-form-label">ชื่อ</label>
                            <h4 class="fs-5"><?= $fetch_profile['fname']; ?></h4>
                            <label for="FName" class="col-form-label">นามสกุล</label>
                            <h4 class="fs-5"><?= $fetch_profile['lname']; ?></h4>
                            <label for="FName" class="col-form-label">เบอร์โทรศัพท์</label>
                            <h4 class="fs-5"><?= $fetch_profile['phone']; ?></h4>
                            <label for="FName" class="col-form-label">อีเมล</label>
                            <h4 class="fs-5"><?= $fetch_profile['email']; ?></h4>
                            <label for="FName" class="col-form-label">หน่วยงาน</label>
                            <h4 class="fs-5"><?= $fetch_profile['workplace']; ?></h4>

                            <?php if ($fetch_profile['user_status'] == 'Not pass') { { ?>
                                    <div class="text-center">
                                        <h3 for="FName" class="text-danger fw-bold">สถานะไม่ผ่านเนื่องจาก</h3>
                                        <h4 class="fs-5 text-danger fw-bold"><?= $fetch_profile['comment']; ?></h4>
                                        <h4 class="text-warning fw-bold">กรุณากดยืนยันเพื่ออัพโหลดรูปภาพใหม่อีกครั้ง</h4>
                                    </div>
                            <?php }
                            } ?>

                            <div class="mb-3 text-center">
                                <p class="fs-6 fw-light">กรณีข้อมูลของท่านไม่ถูกต้อง กรุณาติดต่อเจ้าหน้าที่</p>
                                <a href="upload.php" class="fs-3 fw-bold">ยืนยัน</a>
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