<?php

include 'config/db.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:index.php');
};

if (isset($_POST['submit'])) {

    $image_person = pathinfo(basename($_FILES['image_person']['name']),PATHINFO_EXTENSION);
    $lassname_person = $image_person;
    $new_image_person = mt_rand(00000,99999).'.'.$lassname_person;
    $image_person_tmp_name = copy($_FILES['image_person']['tmp_name'], 'uploaded_person/' . $new_image_person);
    $image_person_size = $_FILES['image_person']['size'];
    $image_person_folder = 'uploaded_person/' . $new_image_person;

    $image_atk = pathinfo(basename($_FILES['image_atk']['name']),PATHINFO_EXTENSION);
    $lassname_atk = $image_atk;
    $new_image_atk = mt_rand(00000,99999).'.'.$lassname_atk;
    $image_atk_tmp_name = copy($_FILES['image_atk']['tmp_name'], 'uploaded_atk/' . $new_image_atk);
    $image_atk_size = $_FILES['image_atk']['size'];
    $image_atk_folder = 'uploaded_atk/' . $new_image_atk;

    $image_vaccine = pathinfo(basename($_FILES['image_vaccine']['name']),PATHINFO_EXTENSION);
    $lassname_vaccine = $image_vaccine;
    $new_image_vaccine = mt_rand(00000,99999).'.'.$lassname_vaccine;
    $image_vaccine_tmp_name = copy($_FILES['image_vaccine']['tmp_name'], 'uploaded_vaccine/' . $new_image_vaccine);
    $image_vaccine_size = $_FILES['image_vaccine']['size'];
    $image_vaccine_folder = 'uploaded_vaccine/' . $new_image_vaccine;

    

    if (!empty($image_person)) {

        // Person image upload
        if ($image_person_size > 3000000) {
            $message_person[] = 'ไฟล์รูปภาพใหญ่เกินไป';
        } else {
            $update_image_person = $conn->prepare("UPDATE `users` SET image_person = ? WHERE id = ?");
            $update_image_person->execute([$new_image_person, $user_id]);

            if ($update_image_person) {
                move_uploaded_file($image_person_tmp_name, $image_person_folder);
                $success_message_person[] = 'อัพโหลดรูปภาพสำเร็จ';
            }
        }
    }

    if (!empty($image_atk)){

        // ATK image upload
        if ($image_atk_size > 3000000) {
            $message_atk[] = 'ไฟล์รูปภาพใหญ่เกินไป';
        } else {
            $update_image_atk = $conn->prepare("UPDATE `users` SET image_atk = ? WHERE id = ?");
            $update_image_atk->execute([$new_image_atk, $user_id]);

            if ($update_image_atk) {
                move_uploaded_file($image_atk_tmp_name, $image_atk_folder);
                $success_message_atk[] = 'อัพโหลดรูปภาพสำเร็จ';
            }
        }
    }

    if(!empty($image_vaccine)){

        // Vaccine image upload
        if ($image_vaccine_size > 3000000) {
            $message_vaccine[] = 'ไฟล์รูปภาพใหญ่เกินไป';
        } else {
            $update_image_vaccine = $conn->prepare("UPDATE `users` SET image_vaccine = ? WHERE id = ?");
            $update_image_vaccine->execute([$new_image_vaccine, $user_id]);

            if ($update_image_vaccine) {
                move_uploaded_file($image_vaccine_tmp_name, $image_vaccine_folder);
                $success_message_vaccine[] = 'อัพโหลดรูปภาพสำเร็จ!';
            }
        }
    }

    $select = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select->execute([$user_id]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if ($row['image_person'] != '' && $row['image_atk'] != '' && $row['image_vaccine'] != ''){
        header('refresh:2;qr-gen.php');
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
    <!-- Font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>

<body>
    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
    <section class="wrapper-upload">

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
                    <img src="img/logo.png" class="img-fluid" width="30%" alt="logo">
                </div>
                <form action="" method="post" enctype="multipart/form-data" class="was-validated form-phone bg-white shadow p-4">
                    <h4 class="fs-6 text-dark">โปรดอัพโหลดข้อมูล Covid19 ของท่าน</h4>
                    <h4 style="font-size: 0.8rem; color: red;">ขนาดไฟล์ไม่เกิน 3 MB .jpg .png เท่านั้น</h4>
                    <div class="mb-3 row">
                        <div class="col-sm-12 text-start justify-content-center">
                            <div class="mb-3 mt-3">
                                <label for="formFile" class="form-label" style="font-size: 0.8rem;">อัพโหลดรูปภาพของท่าน </label>
                                <input class="form-control" type="file" name="image_person" id="formFile" aria-label="file example" accept="image/jpg, image/png, image/jpeg" required>

                                <?php
                                    if(isset($message_person)){
                                        foreach($message_person as $message_person){
                                            echo '
                                            <div class="alert alert-danger" role="alert">
                                                <span>'.$message_person.'</span>
                                            </div>
                                            ';
                                            unset($_SESSION['$message_person']);
                                        }
                                    }
                                ?>

                                <?php
                                    if(isset($success_message_person)){
                                        foreach($success_message_person as $success_message_person){
                                            echo '
                                            <div class="alert alert-success" role="alert">
                                                <span>'.$success_message_person.'</span>
                                            </div>
                                            ';
                                            unset($_SESSION['$success_message_person']);
                                        }
                                    }
                                ?> 

                                <label for="formFile" class="form-label mt-3" style="font-size: 0.8rem;">อัพโหลดผลตรวจ ATK</label>
                                <input class="form-control" type="file" name="image_atk" id="formFile" aria-label="file example" accept="image/jpg, image/png, image/jpeg" required>

                                <?php
                                    if(isset($message_atk)){
                                        foreach($message_atk as $message_atk){
                                            echo '
                                            <div class="alert alert-danger" role="alert">
                                                <span>'.$message_atk.'</span>
                                            </div>
                                            ';
                                            unset($_SESSION['$message_atk']);
                                        }
                                    }
                                ?>

                                <?php
                                    if(isset($success_message_atk)){
                                        foreach($success_message_atk as $success_message_atk){
                                            echo '
                                            <div class="alert alert-success" role="alert">
                                                <span>'.$success_message_atk.'</span>
                                            </div>
                                            ';
                                            unset($_SESSION['$success_message_atk']);
                                        }
                                    }
                                ?> 

                                <label for="formFile" class="form-label mt-3" style="font-size: 0.8rem;">อัพโหลดผลการฉีดวัคซีน</label>
                                <input class="form-control" type="file" name="image_vaccine" id="formFile" aria-label="file example" accept="image/jpg, image/png, image/jpeg" required>

                                <?php
                                    if(isset($message_vaccine)){
                                        foreach($message_vaccine as $message_vaccine){
                                            echo '
                                            <div class="alert alert-danger" role="alert">
                                                <span>'.$message_vaccine.'</span>
                                            </div>
                                            ';
                                            unset($_SESSION['$message_vaccine']);
                                        }
                                    }
                                ?>

                                <?php
                                    if(isset($success_message_vaccine)){
                                        foreach($success_message_vaccine as $success_message_vaccine){
                                            echo '
                                            <div class="alert alert-success" role="alert">
                                                <span>'.$success_message_vaccine.'</span>
                                            </div>
                                            ';
                                            unset($_SESSION['$success_message_vaccine']);
                                        }
                                    }
                                ?> 

                                <div class="invalid-feedback" style="font-size: x-small;">โปรดอัพโหลดข้อมูล</div>
                                <div class="mb-3 text-center">
                                    <button type="submit" name="submit">Upload</button>
                                </div>
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