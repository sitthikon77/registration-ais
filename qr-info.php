<?php

include 'config/db.php';
session_start();

$user_qr_id = $_SESSION['user_qr_id'];

if (!isset($user_qr_id)) {
  header('location:qr-admin.php');
}

if (isset($_POST['atk-recieve'])) {
  $atkrecieve = $_POST['recieve'];

  $update_recieve = $conn->prepare("UPDATE `users` SET atk_recieve = ? WHERE id = ?");
  $update_recieve->execute([$atkrecieve, $user_qr_id]);

  if ($update_recieve) {
    $_SESSION['success'] = "อัพเดทข้อมูลสำเร็จ!";
    header("refresh:2;print.php");
  } else {
    $_SESSION['error'] = "อัพเดทข้อมูลผิดพลาด กรุณาตรวจสอบข้อมูลอีกครั้ง!";
    header("refresh:2;qr-admin.php");
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
    <a href="qr-admin.php">Back</a>
  </div>
  <section class="wrapper-qr">

    <?php
    $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_profile->execute([$user_qr_id]);
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
    ?>

    <div class="container">
      <div class="col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 text-center">
        <!-- icon-mascot-ais -->
        <div class="logo">
          <img src="img/ask_aunjai.png" class="img-fluid" width="30%" alt="logo">
          <img src="img/logo.png" class="img-fluid" width="25%" alt="logo">
        </div>
        <form action="" class="form-phone bg-white shadow p-4" method="post" enctype="multipart/form-data">
          <h4 class="fs-5 text-dark">ข้อมูลผู้ร่วมงาน</h4>
          <div class="mb-3 row">
            <div class="col-sm-12 text-start justify-content-center">
              <h5 class="fs-6">ชื่อ</h5>
              <input type="text" name="fname" readonly class="form-control mb-3" id="" value="<?= $fetch_profile['fname']; ?>">
              <h5 class="fs-6 text-primary">ผลการตรวจหลักฐานข้อมูล Covid19</h5>
              <input type="text" readonly class="form-control mb-3" id="" value="<?= $fetch_profile['user_status']; ?>">
              <input type="text" readonly class="form-control mb-3" id="" value="<?= $fetch_profile['atk_recieve']; ?>">
              <h5 class="fs-6 mb-3">กรุณาระบุการรับที่ตรวจ ATK ของเจ้าหน้าที่</h5>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="recieve" value="รับแล้ว" id="flexRadioDefault1">
                <label class="form-check-label" for="flexRadioDefault1">
                  รับแล้ว
                </label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="radio" name="recieve" value="ยังไม่ได้รับ" id="flexRadioDefault2">
                <label class="form-check-label" for="flexRadioDefault2">
                  ยังไม่ได้รับ
                </label>
              </div>

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

              <div class="mb-3  text-center">
                <button type="submit" name="atk-recieve">พิมพ์สติกเกอร์</button>
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