<?php
session_start();
if (strpos($_SERVER["REQUEST_URI"],"/BMS/client/dashboard.php") !== false || strpos($_SERVER["REQUEST_URI"],"/BMS/client/result.php") !== false) {
    include '../database/dbx.php';
} else {
    include '../../database/dbx.php';
}

if(!isset($_SESSION['UserID']) && !isset($_COOKIE['remember_user'])) {
    header("Location: /BMS/client/auth/login.php");
    exit;
}

if(isset($_COOKIE['remember_user']) && !isset($_SESSION['user_id'])) {
    $email = $_COOKIE['remember_user'];
    $sql = "SELECT * FROM Users JOIN Accounts ON Users.UserID = Accounts.UserID WHERE Email = '$email'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        foreach ($row as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }
}
