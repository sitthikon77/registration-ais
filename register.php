<?php

include 'config/db.php';

session_start();

if (isset($_POST['submit'])) {

    $fname = $_POST['fname'];
    $fname = filter_var($fname, FILTER_SANITIZE_STRING);
    $lname = $_POST['lname'];
    $lname = filter_var($lname, FILTER_SANITIZE_STRING);
    $phone = $_POST['phone'];
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $workplace = $_POST['workplace'];
    $workplace = filter_var($workplace, FILTER_SANITIZE_STRING);

    $select = $conn->prepare("SELECT * FROM `users` WHERE phone = ?");
    $select->execute([$phone]);

    if ($select->rowCount() > 0) {
        $_SESSION['error'] = 'เบอร์นี้ได้ทำการสมัครสมาชิกแล้ว';
        header('refresh:2;index.php');
    } else {
        $insert = $conn->prepare("INSERT INTO `users`(fname, lname, phone, email, workplace) VALUES(?,?,?,?,?)");
        $insert->execute([$fname, $lname, $phone, $email, $workplace]);
        if ($insert) {
            $_SESSION['success'] = 'สมัครสมาชิกสำเร็จ!';
        }

        $phone = $_POST['phone'];
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);

        $select = $conn->prepare("SELECT * FROM `users` WHERE phone = ?");
        $select->execute([$phone]);
        $row = $select->fetch(PDO::FETCH_ASSOC);

        if ($select->rowCount() > 0) {

            if ($row['user_type'] == 'admin') {

                $_SESSION['admin_id'] = $row['id'];
                header('refresh:2;admin.php');
            } elseif ($row['user_type'] == 'user') {

                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_image'] = $row['image_person'];

                if ($row['image_person'] != '' && $row['image_atk'] != '' && $row['image_vaccine'] != '') {
                    header('refresh:2;qr-gen.php');
                } else {
                    header('refresh:2;info.php');
                }
            } else {
                $message[] = 'ไม่มีข้อมูลของท่านในระบบ!';
            }
        } else {
            $message[] = 'ข้อมูลไม่ถูกต้อง!';
        }
    }
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
    <link rel="stylesheet" href="css/style.css">
</head>

<body>



    <section class="wrapper-signup">
        <div class="container">
            <div class="col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 text-center">
                <!-- icon-mascot-ais -->
                <div class="logo">
                    <img src="img/mascot.png" class="img-fluid" width="20%" alt="logo">
                    <img src="img/logo.png" class="img-fluid" width="30%" alt="logo">
                </div>
                <form action="" method="post" enctype="multipart/form-data" class="form-phone bg-white shadow p-4 mt-3">
                    <h3 class="fs-4 text-dark mb-2">Sign Up To Officer</h3>
                    <h4 class="fs-6 text-dark mb-2">โปรดกรอกข้อมูลให้ถูกต้อง</h4>

                    <?php if (isset($_SESSION['success'])) { ?>
                        <div class="alert alert-success">
                            <?php
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php } ?>

                    <?php if (isset($_SESSION['error'])) { ?>
                        <div class="alert alert-danger">
                            <?php
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php } ?>

                    <div class="mb-3 row">

                        <div class="col-sm-12 text-start">
                            <label for="FName" class="col-form-label">ชื่อ</label>
                            <input type="text" name="fname" class="form-control-plaintext" id="FName" placeholder="โปรดระบุชื่อ" required>
                            <label for="LName" class="col-form-label">นามสกุล</label>
                            <input type="text" name="lname" class="form-control-plaintext" id="FName" placeholder="โปรดระบุนามสกุล" required>
                            <label for="Phone" class="col-form-label">เบอร์โทรศัพท์</label>
                            <input type="tel" name="phone" class="form-control-plaintext" id="FName" pattern="[0-9]{10}" placeholder="โปรดระบุเบอร์ Ex: 0123456789" required>
                            <label for="Email" class="col-form-label">อีเมล</label>
                            <input type="email" name="email" class="form-control-plaintext" id="FName" placeholder="โปรดระบุอีเมล Ex: t-stone@mail.com" required>
                            <label for="organization" class="col-form-label">องค์กร</label>
                            <input type="text" name="workplace" class="form-control-plaintext" id="FName" placeholder="โปรดระบุองค์กร" required>

                            <div class="mb-3  d-flex justify-content-between">
                                <button>Back</button>
                                <button type="submit" name="submit">Next</button>
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