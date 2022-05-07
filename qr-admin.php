<?php

include 'config/db.php';
session_start();

if (isset($_POST['check'])) {

    if (!isset($_POST['phone'])) {
        $_SESSION['error'] = "กรุณาสแกน QR code!";
    } else {

        $phone = $_POST['phone'];
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);

        $select = $conn->prepare("SELECT * FROM `users` WHERE phone = ?");
        $select->execute([$phone]);
        $row = $select->fetch(PDO::FETCH_ASSOC);

        if ($select->rowCount() > 0) {

            $_SESSION['user_qr_id'] = $row['id'];
            header('location:qr-info.php');
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
    <title>QR code scanner | Covid19 Certificate upload system</title>

    <!-- css-bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- css-style -->
    <link rel="stylesheet" href="css/style.css">
    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="js/html5-qrcode.min.js"></script>

</head>

<body>
    <div class="logout">
        <a href="admin.php">Back</a>
    </div>
    <section class="wrapper-qr-scanner">

        <div class="container">
            <div class="col-sm-8 offset-sm-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 text-center">
                <!-- icon-mascot-ais -->
                <div class="logo">
                    <img src="img/ask_aunjai.png" class="img-fluid" width="30%" alt="logo">
                    <img src="img/logo.png" class="img-fluid" width="25%" alt="logo">
                </div>
                <!-- form-container--->
                <div class="form-phone bg-white shadow p-4 mt-3">
                    <h3 class="fs-2 text-dark mb-2"> QR Code scanner</h3>
                    <div class="row">
                        <div class="col-12">
                            <div style="width:100%;" id="reader"></div>
                        </div>
                        <div class="col-12" style="padding:30px;">
                            <h4>SCAN RESULT</h4>
                            <form action="" id="qr-submit" method="post" enctype="multipart/form-data">
                                <div id="result"><input type="text" name="phone"></div>
                                <button id="subhere" type="submit" name="check">ตรวจผล</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- qr-scanner -->
    <script type="text/javascript">
        function onScanSuccess(qrCodeMessage) {
            document.getElementById('result').innerHTML = '<input required name="phone" class="result" value=' + qrCodeMessage + '>';
            document.getElementById('subhere').click();
        }

        function onScanError(errorMessage) {
            //handle scan error
        }
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: 250
            });
        html5QrcodeScanner.render(onScanSuccess, onScanError);
    </script>

    <!-- JS-bootstrap -->
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- Js-qr-scanner -->
    <script src="js/html5-qrcode.min.js"></script>
</body>

</html>