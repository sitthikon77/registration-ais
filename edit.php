<?php

include 'config/db.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:logout.php');
}

if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $workplace = $_POST['workplace'];
    $verify = $_POST['ver'];
    $comment = $_POST['comment'];
    $image_person = $_FILES['image_person'];
    $image_atk = $_FILES['image_atk'];
    $image_vaccine = $_FILES['image_vaccine'];

    $image_person2 = $_POST['image_person2'];
    $image_atk2 = $_POST['image_atk2'];
    $image_vaccine2 = $_POST['image_vaccine2'];

    $upload_person = $_FILES['image_person']['name'];
    $upload_atk = $_FILES['image_atk']['name'];
    $upload_vaccine = $_FILES['image_vaccine']['name'];

    if ($upload_person != '' && $upload_atk != '' && $upload_vaccine != '') {
        $image_person = pathinfo(basename($_FILES['image_person']['name']), PATHINFO_EXTENSION);
        $lassname_person = $image_person;
        $new_image_person = mt_rand(00000, 99999) . '.' . $lassname_person;
        $image_person_tmp_name = copy($_FILES['image_person']['tmp_name'], 'uploaded_person/' . $new_image_person);
        $image_person_size = $_FILES['image_person']['size'];
        $image_person_folder = 'uploaded_person/' . $new_image_person;

        $image_atk = pathinfo(basename($_FILES['image_atk']['name']), PATHINFO_EXTENSION);
        $lassname_atk = $image_atk;
        $new_image_atk = mt_rand(00000, 99999) . '.' . $lassname_atk;
        $image_atk_tmp_name = copy($_FILES['image_atk']['tmp_name'], 'uploaded_atk/' . $new_image_atk);
        $image_atk_size = $_FILES['image_atk']['size'];
        $image_atk_folder = 'uploaded_atk/' . $new_image_atk;

        $image_vaccine = pathinfo(basename($_FILES['image_vaccine']['name']), PATHINFO_EXTENSION);
        $lassname_vaccine = $image_vaccine;
        $new_image_vaccine = mt_rand(00000, 99999) . '.' . $lassname_vaccine;
        $image_vaccine_tmp_name = copy($_FILES['image_vaccine']['tmp_name'], 'uploaded_vaccine/' . $new_image_vaccine);
        $image_vaccine_size = $_FILES['image_vaccine']['size'];
        $image_vaccine_folder = 'uploaded_vaccine/' . $new_image_vaccine;

        if (!empty($image_person && $image_atk && $image_vaccine)) {
            if ($image_person_size < 3000000 && $image_atk_size < 3000000 && $image_vaccine_size < 3000000) {
                move_uploaded_file($image_person_tmp_name, $image_person_folder);
                move_uploaded_file($image_atk_tmp_name, $image_atk_folder);
                move_uploaded_file($image_vaccine_tmp_name, $image_vaccine_folder);
            } else {
                $message_image = 'ไฟล์รูปภาพใหญ่เกินไป';
            }
        }
    } else if ($upload_person != '') {

        $image_person = pathinfo(basename($_FILES['image_person']['name']), PATHINFO_EXTENSION);
        $lassname_person = $image_person;
        $new_image_person = mt_rand(00000, 99999) . '.' . $lassname_person;
        $image_person_tmp_name = copy($_FILES['image_person']['tmp_name'], 'uploaded_person/' . $new_image_person);
        $image_person_size = $_FILES['image_person']['size'];
        $image_person_folder = 'uploaded_person/' . $new_image_person;

        if (!empty($image_person)) {
            if ($image_person_size < 3000000) {
                move_uploaded_file($image_person_tmp_name, $image_person_folder);
                $new_image_atk = $image_atk2;
                $new_image_vaccine = $image_vaccine2;
            } else {
                $message_image = 'ไฟล์รูปภาพใหญ่เกินไป';
            }
        }
    } else if ($upload_atk != '') {

        $image_atk = pathinfo(basename($_FILES['image_atk']['name']), PATHINFO_EXTENSION);
        $lassname_atk = $image_atk;
        $new_image_atk = mt_rand(00000, 99999) . '.' . $lassname_atk;
        $image_atk_tmp_name = copy($_FILES['image_atk']['tmp_name'], 'uploaded_atk/' . $new_image_atk);
        $image_atk_size = $_FILES['image_atk']['size'];
        $image_atk_folder = 'uploaded_atk/' . $new_image_atk;

        if (!empty($image_atk)) {
            if ($image_atk_size < 3000000) {
                move_uploaded_file($image_atk_tmp_name, $image_atk_folder);
                $new_image_person = $image_person2;
                $new_image_vaccine = $image_vaccine2;
            } else {
                $message_image = 'ไฟล์รูปภาพใหญ่เกินไป';
            }
        }
    } else if ($upload_vaccine != '') {

        $image_vaccine = pathinfo(basename($_FILES['image_vaccine']['name']), PATHINFO_EXTENSION);
        $lassname_vaccine = $image_vaccine;
        $new_image_vaccine = mt_rand(00000, 99999) . '.' . $lassname_vaccine;
        $image_vaccine_tmp_name = copy($_FILES['image_vaccine']['tmp_name'], 'uploaded_vaccine/' . $new_image_vaccine);
        $image_vaccine_size = $_FILES['image_vaccine']['size'];
        $image_vaccine_folder = 'uploaded_vaccine/' . $new_image_vaccine;

        if (!empty($image_vaccine)) {
            if ($image_vaccine_size < 3000000) {
                move_uploaded_file($image_vaccine_tmp_name, $image_vaccine_folder);
                $new_image_person = $image_person2;
                $new_image_atk = $image_atk2;
            } else {
                $message_image = 'ไฟล์รูปภาพใหญ่เกินไป';
            }
        }
    } else {
        $new_image_person = $image_person2;
        $new_image_atk = $image_atk2;
        $new_image_vaccine = $image_vaccine2;
    }

    $sql = $conn->prepare("UPDATE users SET fname = :fname, lname = :lname, phone = :phone, email = :email, workplace = :workplace, user_status = :user_status, comment = :comment, image_person = :image_person, image_atk = :image_atk, image_vaccine = :image_vaccine WHERE id = :id");
    $sql->bindParam(":id", $id);
    $sql->bindParam(":fname", $fname);
    $sql->bindParam(":lname", $lname);
    $sql->bindParam(":phone", $phone);
    $sql->bindParam(":email", $email);
    $sql->bindParam(":workplace", $workplace);
    $sql->bindParam(":user_status", $verify);
    $sql->bindParam(":comment", $comment);
    $sql->bindParam(":image_person", $new_image_person);
    $sql->bindParam(":image_atk", $new_image_atk);
    $sql->bindParam(":image_vaccine", $new_image_vaccine);
    $sql->execute();

    if ($sql) {
        $_SESSION['success'] = 'แก้ไขข้อมูลสำเร็จ!';
        header('location: crud-admin.php');
    } else {
        $_SESSION['error'] = 'แก้ไขข้อมูลไม่สำเร็จ!! โปรดตรวจสอบข้อมูลอีกครั้ง';
        header('location: crud-admin.php');
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

    <style>
        .wrapper-admin {
            background-color: #fff;
        }

        .wrapper-admin a {
            background-color: #010101;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
        }

        .wrapper-admin a:hover {
            background-color: #b2d233;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
            color: #000;
        }

        #container {
            max-width: 550px;
        }
    </style>

    <!-- css-bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- css-style -->
    <link rel="stylesheet" href="css/style1.css">
    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body style="background-color: #b2d233;">
    <!-- admin section -->
    <section class="wrapper-admin" style="background-color: #b2d233;">
        <div id="container" class="container mt-5">
            <h1>แก้ไขข้อมูล</h1>

            <form action="" method="post" enctype="multipart/form-data" style="border: solid 5px #222; border-radius:15px; padding:5px; background-color:#fff;">

                <?php
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $stmt = $conn->query("SELECT * FROM users WHERE id = $id");
                    $stmt->execute();
                    $data = $stmt->fetch();
                }
                ?>

                <div class="mb-3">
                    <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="text" readonly name="id" value="<?= $data['id']; ?>" class="form-control-plaintext" id="FName" placeholder="โปรดระบุชื่อ" required>
                    <label for="FName" class="col-form-label">ชื่อ</label>
                    <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="text" name="fname" value="<?= $data['fname']; ?>" class="form-control-plaintext" id="FName" placeholder="โปรดระบุชื่อ" required>

                    <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="hidden" name="image_person2" value="<?= $data['image_person']; ?>" class="form-control-plaintext" id="FName" placeholder="โปรดระบุชื่อ" required>
                    <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="hidden" name="image_atk2" value="<?= $data['image_atk']; ?>" class="form-control-plaintext" id="FName" placeholder="โปรดระบุชื่อ" required>
                    <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="hidden" name="image_vaccine2" value="<?= $data['image_vaccine']; ?>" class="form-control-plaintext" id="FName" placeholder="โปรดระบุชื่อ" required>

                    <label for="LName" class="col-form-label">นามสกุล</label>
                    <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="text" name="lname" value="<?= $data['lname']; ?>" class="form-control-plaintext" id="FName" placeholder="โปรดระบุนามสกุล" required>

                    <label for="Phone" class="col-form-label">เบอร์โทรศัพท์</label>
                    <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="tel" name="phone" value="<?= $data['phone']; ?>" class="form-control-plaintext" id="FName" pattern="[0-9]{10}" placeholder="โปรดระบุเบอร์ Format: 0123456789" required>

                    <label for="Email" class="col-form-label">อีเมล</label>
                    <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="email" name="email" value="<?= $data['email']; ?>" class="form-control-plaintext" id="FName" placeholder="โปรดระบุอีเมล Ex: t-stone@mail.com" required>

                    <label for="organization" class="col-form-label">หน่วยงาน</label>
                    <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="text" name="workplace" value="<?= $data['workplace']; ?>" class="form-control-plaintext" id="FName" placeholder="โปรดระบุหน่วยงาน" required><br>

                    <div class="d-flex justify-content-center">
                        <input class="form-check-input" type="radio" name="ver" value="Pass" id="flexRadioDefault1" required>
                        <label class="form-check-label" for="flexRadioDefault1">ผ่านการตรวจสอบ</label>&nbsp;&nbsp;&nbsp;
                        <input class="form-check-input" type="radio" name="ver" value="Not pass" id="flexRadioDefault2" required>
                        <label class="form-check-label" for="flexRadioDefault2">ไม่ผ่านการตรวจสอบ</label><br><br>
                    </div>

                    <label for="organization" class="col-form-label">เหตุผล</label>
                    <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="text" name="comment" value="<?= $data['comment']; ?>" class="form-control-plaintext" id="FName" placeholder="โปรดระบุเหตุผล" required><br>


                    <!-- Image -->
                    <label for="formFile" class="form-label" style="font-size: 0.8rem;">อัพโหลดรูปภาพของท่าน </label>
                    <input class="form-control col-6" name="image_person" type="file" id="formFile" aria-label="file example">
                    <img width="100%" src="uploaded_person/<?= $data['image_person']; ?>" class="rounded">

                    <label for="formFile" class="form-label mt-3" style="font-size: 0.8rem;">อัพโหลดผลตรวจ ATK</label>
                    <input class="form-control" name="image_atk" type="file" id="formFile" aria-label="file example">
                    <img width="100%" src="uploaded_atk/<?= $data['image_atk']; ?>" class="rounded">

                    <label for="formFile" class="form-label mt-3" style="font-size: 0.8rem;">อัพโหลดผลการฉีดวัคซีน</label>
                    <input class="form-control" name="image_vaccine" type="file" id="formFile" aria-label="file example">
                    <img width="100%" src="uploaded_vaccine/<?= $data['image_vaccine']; ?>" class="rounded">

                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary" href="crud-admin.php">Back</a>
                    <button type="submit" name="update" class="btn" style="background-color: #b2d233;">แก้ไขข้อมูล</button>
                </div>
            </form>

        </div>

        <div class="container mt-3">
            <a href="logout.php">Logout</a>
        </div>
    </section>

    <!-- JS-bootstrap -->
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>