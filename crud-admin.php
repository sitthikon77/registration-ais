<?php

include 'config/db.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:logout.php');
}

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

  // Image Variable
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

  $select = $conn->prepare("SELECT * FROM `users` WHERE phone = ?");
  $select->execute([$phone]);

  if ($select->rowCount() > 0) {
    $_SESSION['error'] = 'เพิ่มสมาชิกไม่สำเร็จ!! โปรดตรวจสอบข้อมูลอีกครั้ง';
    header('refresh:3;');
  } else {
    $insert = $conn->prepare("INSERT INTO `users`(fname, lname, phone, email, workplace, image_person, image_atk, image_vaccine, user_status) VALUES(?,?,?,?,?,?,?,?,?)");
    $insert->execute([$fname, $lname, $phone, $email, $workplace, $new_image_person, $new_image_atk, $new_image_vaccine, 'Pending']);
    if ($insert) {
      move_uploaded_file($image_person_tmp_name, $image_person_folder);
      move_uploaded_file($image_atk_tmp_name, $image_atk_folder);
      move_uploaded_file($image_vaccine_tmp_name, $image_vaccine_folder);
      $_SESSION['success'] = 'เพิ่มสมาชิกสำเร็จ!';
      header('refresh:3;');
    }
  }
}

if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  $deletestmt = $conn->query("DELETE FROM users WHERE id = $delete_id");

  if ($deletestmt) {
    echo "<script>alert('ลบข้อมูลสำเร็จ!');</script>";
    $_SESSION['success'] = "ลบข้อมูลสำเร็จ!";
    header("refresh:1; url=crud-admin.php");
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

    /* POPUP */

    .box .img-box {
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;

    }

    .box .img-box.active {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1000;
    }

    /* .box .img-box img{
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
} */
    .box .img-box.active img {
      position: absolute;
      left: 30%;
      width: calc(50% - 100px);
      height: calc(100% - 100px);
    }

    .box .img-box h2 {
      opacity: 0;
      visibility: hidden;
      cursor: pointer;
    }

    .box .img-box.active h2 {
      opacity: 1;
      visibility: visible;
      text-align: center;
      color: red;
      font-size: 18px;
      font-weight: 800;
      margin-top: 15px;
      letter-spacing: 4px;
    }

    .box .img-box .content {
      position: absolute;
      bottom: 50px;
      right: 50px;
      left: 50px;
      opacity: 0;
      visibility: hidden;
      background: rgba(0, 0, 0, 0.8);
      padding: 20px;
      color: #ffffff;
      transform: translateY(100%);
    }

    .box .img-box.active .content {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
      transition: 0.5s;
    }
  </style>

  <!-- css-bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- css-style -->
  <link rel="stylesheet" href="css/style1.css">
  <!-- fontawesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body style="background-color : #FFF;">
  <!-- modal add user -->
  <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="FName" class="col-form-label">ชื่อ</label>
              <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="text" name="fname" class="form-control-plaintext" id="FName" placeholder="โปรดระบุชื่อ" required>
              <label for="LName" class="col-form-label">นามสกุล</label>
              <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="text" name="lname" class="form-control-plaintext" id="FName" placeholder="โปรดระบุนามสกุล" required>
              <label for="Phone" class="col-form-label">เบอร์โทรศัพท์</label>
              <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="tel" name="phone" class="form-control-plaintext" id="FName" pattern="[0-9]{10}" placeholder="โปรดระบุเบอร์ Format: 0123456789" required>
              <label for="Email" class="col-form-label">อีเมล</label>
              <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="email" name="email" class="form-control-plaintext" id="FName" placeholder="โปรดระบุอีเมล Ex: t-stone@mail.com" required>
              <label for="organization" class="col-form-label">องค์กร</label>
              <input style="border: solid 2px #222; border-radius:5px; padding:5px;" type="text" name="workplace" class="form-control-plaintext" id="FName" placeholder="โปรดระบุองค์กร" required>


              <!-- Image -->
              <label for="formFile" class="form-label" style="font-size: 0.8rem;">อัพโหลดรูปภาพของท่าน </label>
              <input class="form-control col-6" name="image_person" type="file" id="formFile" aria-label="file example" required>
              <label for="formFile" class="form-label mt-3" style="font-size: 0.8rem;">อัพโหลดผลตรวจ ATK</label>
              <input class="form-control" name="image_atk" type="file" id="formFile" aria-label="file example" required>
              <label for="formFile" class="form-label mt-3" style="font-size: 0.8rem;">อัพโหลดผลการฉีดวัคซีน</label>
              <input class="form-control" name="image_vaccine" type="file" id="formFile" aria-label="file example" required>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" name="submit" class="btn" style="background-color: #b2d233;">Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- admin section -->
  <section class="wrapper-admin">
    <div class="container-fluid mt-5">
      <div class="row">
        <div class="col-md-6">
          <h3>Admin CRUD System </h3>
        </div>
        <div class="col-md-6 d-flex justify-content-end mb-3">
          <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#userModal"><i class="fa-solid fa-user-plus"></i> Add User</button>
        </div>
        <div class="col-md-6 d-flex justify-content-between mb-3">
          <form action="" method="POST" enctype="multipart/form-data">
            <input class="form-check-input" type="radio" name="ver" value="All" id="flexRadioDefault1">
            <label class="form-check-label" for="flexRadioDefault1">All Status</label>&nbsp;&nbsp;&nbsp;
            <input class="form-check-input" type="radio" name="ver" value="Pass" id="flexRadioDefault1">
            <label class="form-check-label" for="flexRadioDefault1">Pass</label>&nbsp;&nbsp;&nbsp;
            <input class="form-check-input" type="radio" name="ver" value="Pending" id="flexRadioDefault2">
            <label class="form-check-label" for="flexRadioDefault2">Pending</label>&nbsp;&nbsp;&nbsp;
            <input class="form-check-input" type="radio" name="ver" value="Not pass" id="flexRadioDefault2">
            <label class="form-check-label" for="flexRadioDefault2">Not pass</label>
            <button type="submit" name="status-query" class="btn btn-primary">ค้นหา</button>
          </form>
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

        <table class="table table-dark">
          <thead>
            <tr class="table-dark" style="color:#b2d233;">
              <th scope="col">ID</th>
              <th scope="col">Fname</th>
              <th scope="col">Lname</th>
              <th scope="col">Phone</th>
              <th scope="col">Email</th>
              <th scope="col">Workplace</th>
              <th scope="col">img_person</th>
              <th scope="col">img_atk</th>
              <th scope="col">img_vaccine</th>
              <th scope="col">Status</th>
              <th scope="col">Comment</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt = $conn->query("SELECT * FROM users");
            $stmt->execute();
            $users = $stmt->fetchAll();

            if (isset($_POST['status-query'])) {
              if ($_POST['ver'] == 'All') {
                $stmt = $conn->query("SELECT * FROM users");
                $stmt->execute();
                $users = $stmt->fetchAll();
              } else if ($_POST['ver'] == 'Pass') {
                $stmt = $conn->query("SELECT * FROM users WHERE user_status = 'Pass'");
                $stmt->execute();
                $users = $stmt->fetchAll();
              } else if ($_POST['ver'] == 'Pending') {
                $stmt = $conn->query("SELECT * FROM users WHERE user_status = 'Pending'");
                $stmt->execute();
                $users = $stmt->fetchAll();
              } else if ($_POST['ver'] == 'Not pass') {
                $stmt = $conn->query("SELECT * FROM users WHERE user_status = 'Not pass'");
                $stmt->execute();
                $users = $stmt->fetchAll();
              }
            }

            if (!$users) {
              echo "<tr><td colspan='12' class='text-center'>No users found</td></tr>";
            } else {
              foreach ($users as $users) {

            ?>
                <tr>
                  <th scope="row"><?= $users['id']; ?></th>
                  <td><?= $users['fname']; ?></td>
                  <td><?= $users['lname']; ?></td>
                  <td><?= $users['phone']; ?></td>
                  <td><?= $users['email']; ?></td>
                  <td><?= $users['workplace']; ?></td>
                  <?php { ?>

                  <?php } ?>

                  <?php if ($users['image_person'] == '') { { ?>
                      <td>No image</td>
                    <?php }
                  }
                  if ($users['image_atk'] == '') { { ?>
                      <td>No image</td>
                    <?php }
                  }
                  if ($users['image_vaccine'] == '') { { ?>
                      <td>No image</td>
                    <?php }
                  } else { { ?>
                      <td width="100px">
                        <div class="box">
                          <div class="img-box">
                            <img width="100%" src="uploaded_person/<?= $users['image_person']; ?>" class="rounded">
                            <h2>Close</h2>
                          </div>
                        </div>
                      </td>
                      <td width="100px">
                        <div class="box">
                          <div class="img-box">
                            <img width="100%" src="uploaded_atk/<?= $users['image_atk']; ?>" class="rounded">
                            <h2>Close</h2>
                          </div>
                        </div>
                      </td>
                      <td width="100px">
                        <div class="box">
                          <div class="img-box">
                            <img width="100%" src="uploaded_vaccine/<?= $users['image_vaccine']; ?>" class="rounded">
                            <h2>Close</h2>
                          </div>
                        </div>
                      </td>
                  <?php }
                  } ?>

                  <?php if ($users['user_status'] == 'Pass') { { ?>
                      <td class="text-success"><?= $users['user_status']; ?></td>
                    <?php }
                  } else if ($users['user_status'] == 'Pending') { { ?>
                      <td class="text-warning"><?= $users['user_status']; ?></td>
                    <?php }
                  } else { { ?>
                      <td class="text-danger"><?= $users['user_status']; ?></td>
                  <?php }
                  } ?>

                  <td><?= $users['comment']; ?></td>
                  <td>
                    <a href="edit.php?id=<?= $users['id']; ?>" class="btn btn-warning">แก้ไข</a>
                    <a href="?delete=<?= $users['id']; ?>" class="btn btn-danger" onclick="return confirm('คุณต้องการที่จะลบข้อมูลใช่หรือไม่?')">ลบข้อมูล</a>
                  </td>
                </tr>
            <?php }
            } ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="container mt-3">
      <a href="admin.php">Back</a>
      <a href="logout.php">Logout</a>
    </div>
  </section>

  <!-- JS-bootstrap -->
  <script src="js/script.js"></script>
  <script src="js/popup.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <script>
    let imageBox = document.querySelectorAll('.img-box');
    imageBox.forEach(popup => popup.addEventListener('click', () => {
      popup.classList.toggle('active');
    }));
  </script>
</body>

</html>