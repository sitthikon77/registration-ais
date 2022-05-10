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
  } else {
    $_SESSION['error'] = "อัพเดทข้อมูลผิดพลาด กรุณาตรวจสอบข้อมูลอีกครั้ง!";
    header("refresh:2;qr-admin.php");
  }
}

if (isset($_POST['data'])) {

  $date = date('d_m_Y_H_i_s');
  $fileName = "print_sticker/" . $date . mt_rand(00000, 99999) . ".png";

  //Get the base-64 string from data
  $filteredData = substr($_POST['data'], strpos($_POST['data'], ","));
  //Decode the string
  $unencodedData = base64_decode($filteredData);
  //Save the image
  file_put_contents($fileName, $unencodedData);

  echo json_encode($fileName);

  if (!empty($fileName)) {
    $update_file = $conn->prepare("UPDATE `users` SET sticker = ? WHERE id = ?");
    $update_file->execute([$fileName, $user_qr_id]);
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
  <link rel="stylesheet" href="css/style1.css">
  <!-- Font-awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

  <link rel="stylesheet" href="css/DB-Heavent-woff/stylesheet.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js%22%3E"></script>

  <style media="screen">
    #htmltoimage {
      width: 5cm;
      height: 3cm;
      margin: auto;
    }
  </style>
</head>

<body onload="autoClick();" style="font-family: 'db_heaventregular'; text-align:center;">

  <?php
  $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
  $select_profile->execute([$user_qr_id]);
  $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
  ?>

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
          <img src="img/logo1.png" class="img-fluid" width="25%" alt="logo">
        </div>
        <div class="form-phone bg-white shadow p-4">
          <form action="" method="post" enctype="multipart/form-data">
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
                  <input class="form-check-input" type="radio" name="recieve" value="รับแล้ว" id="flexRadioDefault1" required>
                  <label class="form-check-label" for="flexRadioDefault1">
                    รับแล้ว
                  </label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input" type="radio" name="recieve" value="ยังไม่ได้รับ" id="flexRadioDefault2" required>
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
                  <button type="submit" name="atk-recieve">บันทึกข้อมูล</button>
                </div>


              </div>
            </div>
          </form>
          <div id="htmltoimage" class="container">
            <div class="row mb-2">
              <div class="col-6 text-center ">
                <span class="text-center" style="font-weight: 600; font-size: 16px; font-family: 'db_heaventregular'; text-align:center;"><?= $fetch_profile['workplace']; ?> <br> <?= $fetch_profile['fname']; ?> <br> <?= $fetch_profile['phone']?> </span>
              </div>
              <div class="col-6 d-flex justify-content-center align-items-center">
                <img src="<?= $fetch_profile['image_qr']; ?>" class="img-fluid w-100" alt="">
              </div>
            </div>
            <div class="d-flex justify-content-center text-center">
              <img src="img/Asset-2.png" width="60%">
            </div>
          </div>
          <button onclick="downloadimage()" class="clickbtn">พิมพ์สติ๊กเกอร์</button>
        </div>
      </div>
    </div>

    <!-- JS-bootstrap -->
    <script>
      function downloadimage() {
        var container = document.getElementById("htmltoimage");

        html2canvas(container, {
          allowTaint: true,
          width: 188.976377,
          height: 113.385826,
          scale: 4
        }).then(function(canvas) {

          // var link = document.createElement("a");
          // document.body.appendChild(link);

          // link.download = Math.floor(Math.random() * 1000000) + "sticker.jpg";
          // link.href = canvas.toDataURL();
          // link.target = '_blank';
          // link.click();

          var dataImage = canvas.toDataURL("image/png")
          $.ajax({
            type: "POST",
            url: "qr-info.php",
            data: {
              data: dataImage
            }
          });
          alert("พิมพ์สติ๊กเกอร์สำเร็จ");                                            
        });
      }
    </script>

    <script src="js/html2canvas.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>