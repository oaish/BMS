<?php
if(!isset($_SESSION['UserID']) && !isset($_COOKIE['remember_user'])) {
    header("Location: /BMS/client/auth/login.php");
    exit;
} else {
    header("Location: /BMS/client/dashboard.php");
    exit;
}