<?php

include 'config/db.php';

session_start();

if (isset($_POST['submit'])) {

    $phone = $_POST['phone'];
    $phone = filter_var($phone, FILTER_SANITIZE_STRING);

    $select = $conn->prepare("SELECT * FROM `users` WHERE phone = ?");
    $select->execute([$phone]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if($select->rowCount() > 0){

        if($row['user_type'] == 'admin'){

            $_SESSION['admin_id'] = $row['id'];
            $success_message[] = 'กำลังเข้าสู่ระบบ...';
            header('refresh:2;admin.php');

        }elseif($row['user_type'] == 'user'){

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_image'] = $row['image_person'];
            $success_message[] = 'กำลังเข้าสู่ระบบ...';

            if($row['image_person'] != '' && $row['image_atk'] != '' && $row['image_vaccine'] != ''){
                header('refresh:2;qr-gen.php');
            }else{
                header('refresh:2;info.php');
            }

        }else{
            $message[] = 'no user found!';
        }

    }else{
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
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>
    <section class="wrapper-index">
        <div class="new-account-link">
            <a href="register.php">New Account</a>
        </div>
        <div class="container">
            <div class="col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 text-center">
                <!-- icon-mascot-ais -->
                <div class="logo">
                    <img src="img/mascot.png" class="img-fluid" width="45%" alt="logo">
                    <img src="img/logo.png" class="img-fluid" width="50%" alt="logo">
                </div>
                <!-- form-container--->
                <form action="" method="post" enctype="multipart/form-data" class="form-phone bg-white shadow p-4 mt-3">
                    <h3 class="fs-4 text-dark mb-2">Sign In To Officer </h3>
                    <?php
                        if(isset($message)){
                            foreach($message as $message){
                                echo '
                                <div class="alert alert-danger" role="alert">
                                    <span>'.$message.'</span>
                                </div>
                                ';
                                unset($_SESSION['$message']);
                            }
                        }
                    ?>

                    <?php
                        if(isset($success_message)){
                            foreach($success_message as $success_message){
                                echo '
                                <div class="alert alert-success" role="alert">
                                    <span>'.$success_message.'</span>
                                </div>
                                ';
                                unset($_SESSION['$success_message']);
                            }
                        }
                    ?> 
                    <div class="form-floating mb-3">
                        <input type="tel" class="form-control number" id="phone" name="phone" placeholder="094-549-5479" pattern="[0-9]{10}" required>
                        <label for="phone">Phone</label>
                        <p class="mb-2"><span style="color: red;">*</span>ตัวอย่าง : 0945495479</p>
                        <h5 class="text"></h5>
                        <button type="submit" name="submit" class="check">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- JS-bootstrap -->
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>