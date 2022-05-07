<?php

include 'config/db.php';

session_start();
session_unset();
session_destroy();

$message[] = 'กำลังออกจากระบบ...';
header('refresh:1;index.php');

?>