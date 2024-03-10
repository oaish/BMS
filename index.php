<?php
if(!isset($_SESSION['UserID']) && !isset($_COOKIE['remember_user'])) {
    header("Location: http://localhost/BMS/client/auth/login.php");
    exit;
} else {
    header("Location: http://localhost/BMS/client/dashboard.php");
    exit;
}