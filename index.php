<?php

include 'config/db.php';

session_start();

if (isset($_POST['submit'])) {

    $phone = $_POST['phone'];
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);

    $select = $conn->prepare("SELECT * FROM `users` WHERE phone = ?");
    $select->execute([$phone]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if ($select->rowCount() > 0) {

        if ($row['user_type'] == 'admin') {

            $_SESSION['admin_id'] = $row['id'];
            $success_message[] = 'กำลังเข้าสู่ระบบ...';
            header('refresh:2;admin.php');
        } elseif ($row['user_type'] == 'user') {

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_image'] = $row['image_person'];
            $success_message[] = 'กำลังเข้าสู่ระบบ...';

            if ($row['user_status'] != 'Not pass') {
                header('refresh:2;qr-gen.php');
            } else {
                header('refresh:2;info.php');
            }
        } else {
            $message[] = 'no user found!';
        }
    } else {
        $message[] = 'ข้อมูลไม่ถูกต้อง!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer | Covid19 Certificate upload system</title>
    <!-- css-bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- css-style -->
    <link rel="stylesheet" href="css/style1.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js%22%3E"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/DB-Heavent-woff/stylesheet.css">
</head>

<body style="font-family: 'db_heaventregular'; font-size: 1.5rem; background-color: #b2d233;">
    <section class="wrapper-index">
        <div class="new-account-link">
        </div>
        <div class="container">
            <div class="col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 text-center">
                <!-- icon-mascot-ais -->
                <div class="logo">
                    <img src="img/mascot.png" class="img-fluid" width="45%" alt="logo">
                    <img src="img/logo1.png" class="img-fluid" width="50%" alt="logo">
                </div>
                <!-- form-container--->
                <form action="" method="post" enctype="multipart/form-data" class="form-phone bg-white shadow p-4 mt-3">
                    <h3 class="fs-3 text-dark mb-4 fw-bold">ใส่หมายเลขโทรศัพท์ 10 หลัก </h3>
                    <?php
                    if (isset($message)) {
                        foreach ($message as $message) {
                            echo '
                                <div class="alert alert-danger" role="alert">
                                    <span>' . $message . '</span>
                                </div>
                                ';
                            unset($_SESSION['$message']);
                        }
                    }
                    ?>

                    <?php
                    if (isset($success_message)) {
                        foreach ($success_message as $success_message) {
                            echo '
                                <div class="alert alert-success" role="alert">
                                    <span>' . $success_message . '</span>
                                </div>
                                ';
                            unset($_SESSION['$success_message']);
                        }
                    }
                    ?>
                    <div class="form-floating mb-3">
                        <input type="tel" class="p-2 number fs-4 fw-bold" id="phone" name="phone" placeholder="ตัวอย่าง : 0945495479" pattern="[0-9]{10}" required>
                        <h5 class="text fs-5 fw-bold"></h5>
                        <button type="submit" name="submit" class="check fw-bold">เข้าสู่ระบบ</button>
                    </div>
                </form>
                <div class="d-flex justify-content-end mt-3">

                    <a class="fs-5 fw-bold" style="text-decoration: none; color: grey;" href="register.php">New Account</a>
                </div>
                <a type="button" id="myInput" class="fw-bolder mt-2 fs-3" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#myModal">
                    คู่มือการลงทะเบียน
                </a>
            </div>
        </div>
    </section>

    <!-- Tutorial -->

    <div id="myModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-3 fw-bold ">คู่มือการลงทะเบียน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="img/popup-test.svg" alt="" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark fs-5 fw-light" data-bs-dismiss="modal">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <!-- <script type="text/javascript">
        $(window).on('load', function() {
            $('#myModal').modal('show');
        });
    </script> -->

    <script type="text/javascript">
        $(document).ready(function() {
            if (sessionStorage.getItem('#myModal') !== 'true') {
                $('#myModal').modal('show');
                sessionStorage.setItem('#myModal', 'true');
            }
        });
    </script>

    <!-- JS-bootstrap -->
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>