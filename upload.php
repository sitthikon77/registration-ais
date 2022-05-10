<?php

include 'config/db.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:index.php');
};

if (isset($_POST['submit'])) {

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

    if (!empty($image_atk)) {

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

    if (!empty($image_vaccine)) {

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

    if ($row['image_person'] != '' && $row['image_atk'] != '' && $row['image_vaccine'] != '') {
        header('refresh:2;qr-gen.php');
    }

    $updatestatus = $conn->prepare("UPDATE `users` SET user_status = ?, comment = ? WHERE id = ?");
    $updatestatus->execute(['Pending','', $user_id]);

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload | Covid19 Certificate upload system</title>
    <!-- css-bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- css-style -->
    <link rel="stylesheet" href="css/style1.css">
    <!-- Font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/DB-Heavent-woff/stylesheet.css">
</head>

<body style="font-family: 'db_heaventregular'; font-size: 1.5rem; background-color: #b2d233;">
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
                    <img src="img/logo1.png" class="img-fluid" width="30%" alt="logo">
                </div>
                <form action="" method="post" enctype="multipart/form-data" class="was-validated form-phone bg-white shadow p-4">
                    <h4 class="fs-4 fw-bold text-dark">โปรดอัพโหลดข้อมูล Covid19 ของท่าน</h4>
                    <h4 class="fs-5 fw-bold text-red" style="font-size: 0.8rem; color: red;">ขนาดไฟล์ไม่เกิน 3 MB .jpg .png เท่านั้น</h4>
                    <div class="mb-3 row">
                        <div class="col-sm-12 text-start justify-content-center">
                            <div class="mb-3 mt-3">
                                <label for="formFile" class="form-label fs-6 fw-bold" style="font-size: 0.8rem;">อัพโหลดรูปภาพของท่าน ตัวอย่าง <a type="button" id="myInput" class="fw-bolder mt-2" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#myModal0">คลิก</a> </label>
                                <input class="form-control" type="file" name="image_person" id="formFile" aria-label="file example" accept="image/jpg, image/png, image/jpeg" required>

                                <?php
                                if (isset($message_person)) {
                                    foreach ($message_person as $message_person) {
                                        echo '
                                            <div class="alert alert-danger" role="alert">
                                                <span>' . $message_person . '</span>
                                            </div>
                                            ';
                                        unset($_SESSION['$message_person']);
                                    }
                                }
                                ?>

                                <?php
                                if (isset($success_message_person)) {
                                    foreach ($success_message_person as $success_message_person) {
                                        echo '
                                            <div class="alert alert-success" role="alert">
                                                <span>' . $success_message_person . '</span>
                                            </div>
                                            ';
                                        unset($_SESSION['$success_message_person']);
                                    }
                                }
                                ?>

                                <label for="formFile" class="form-label fs-6 fw-bold" style="font-size: 0.8rem;">อัพโหลดรูปภาพ ATK ตัวอย่าง <a type="button" id="myInput" class="fw-bolder mt-2" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#myModal1">คลิก</a> </label>
                                <input class="form-control" type="file" name="image_atk" id="formFile" aria-label="file example" accept="image/jpg, image/png, image/jpeg" required>

                                <?php
                                if (isset($message_atk)) {
                                    foreach ($message_atk as $message_atk) {
                                        echo '
                                            <div class="alert alert-danger" role="alert">
                                                <span>' . $message_atk . '</span>
                                            </div>
                                            ';
                                        unset($_SESSION['$message_atk']);
                                    }
                                }
                                ?>

                                <?php
                                if (isset($success_message_atk)) {
                                    foreach ($success_message_atk as $success_message_atk) {
                                        echo '
                                            <div class="alert alert-success" role="alert">
                                                <span>' . $success_message_atk . '</span>
                                            </div>
                                            ';
                                        unset($_SESSION['$success_message_atk']);
                                    }
                                }
                                ?>

                                <label for="formFile" class="form-label fs-6 fw-bold" style="font-size: 0.8rem;">อัพโหลดรูปภาพประวัติการฉีดวัคซีน ตัวอย่าง <a type="button" id="myInput" class="fw-bolder mt-2" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#myModal2">คลิก</a> </label>
                                <input class="form-control" type="file" name="image_vaccine" id="formFile" aria-label="file example" accept="image/jpg, image/png, image/jpeg" required>

                                <?php
                                if (isset($message_vaccine)) {
                                    foreach ($message_vaccine as $message_vaccine) {
                                        echo '
                                            <div class="alert alert-danger" role="alert">
                                                <span>' . $message_vaccine . '</span>
                                            </div>
                                            ';
                                        unset($_SESSION['$message_vaccine']);
                                    }
                                }
                                ?>

                                <?php
                                if (isset($success_message_vaccine)) {
                                    foreach ($success_message_vaccine as $success_message_vaccine) {
                                        echo '
                                            <div class="alert alert-success" role="alert">
                                                <span>' . $success_message_vaccine . '</span>
                                            </div>
                                            ';
                                        unset($_SESSION['$success_message_vaccine']);
                                    }
                                }
                                ?>

                                <div class="fs-5 fw-bold invalid-feedback" style="font-size: x-small;">โปรดอัพโหลดข้อมูล</div>
                                <div class="mb-3 text-center">
                                    <button class="fs-5 fw-bold" type="submit" name="submit">Upload</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- photo -->
    <div id="myModal0" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ">ตัวอย่างภาพถ่ายเซลฟี่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="img/photo.svg" alt="" class="img-fluid">
                    <span class="text-danger fs-2 text-center fw-bold">*ห้ามใช้ภาพการ์ตูน</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ATK -->
    <div id="myModal1" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ">ตัวอย่างภาพผลตรวจ ATK</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="img/atk.svg" alt="" class="img-fluid">
                    <span class="text-danger fs-5 text-center fw-bold">รูปถ่ายผลตรวจ ATK (ไม่เกิน 72 ชั่วโมง) พร้อมบัตรประจำตัวประชาชน</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>

    <!-- vaccine -->
    <div id="myModal2" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title ">ตัวอย่างภาพหลักฐานการฉีดวัคซีนโควิด19</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="img/vaccine.svg" alt="" class="img-fluid">
                    <span class="text-danger fs-6 text-center fw-bold">สามารถบันทึกหน้าจอเอกสาร(ให้เห็นข้อมูลส่วนตัวและคิวอาร์โค้ด) ได้ที่แอปพลิเคชันหมอพร้อม</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">ยืนยัน</button>
                </div>
            </div>
        </div>
    </div>
    <!-- JS-bootstrap -->
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>