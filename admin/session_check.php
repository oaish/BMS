<?php
session_start();
if (strpos($_SERVER["REQUEST_URI"],"dashboard.php") !== false || strpos($_SERVER["REQUEST_URI"],"result.php") !== false) {
    include '../database/dbx.php';
} else {
    include '../../database/dbx.php';
}

if(!isset($_SESSION['AdminID'])) {
    header("Location: /BMS/admin/auth/");
    exit;
}
