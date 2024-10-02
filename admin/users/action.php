<?php
include '../session_check.php';

$id = $_GET['id'];
$get_token = $_GET['token'];
$session_token = $_SESSION['token'];

if (strnatcasecmp($session_token, $get_token) != 0) {
    header("Location: /BMS/admin/dashboard.php");
    exit;
}

if (isset($_GET['Delete'])) {
    $sql = "DELETE FROM Accounts WHERE UserID = $id";
    $result = mysqli_query($con, $sql);
    $sql = "DELETE FROM Users WHERE UserID = $id";
    $result = mysqli_query($con, $sql);
    header("Location: /BMS/admin/users/");
    exit;
}

if (isset($_GET['Block'])) {
    $sql = "UPDATE Users SET Status = 'inactive' WHERE UserID = $id";
    $result = mysqli_query($con, $sql);
    header("Location: /BMS/admin/users/user.php?id=$id");
    exit;
}

if (isset($_GET['Unblock'])) {
    $sql = "UPDATE Users SET Status = 'active' WHERE UserID = $id";
    $result = mysqli_query($con, $sql);
    header("Location: /BMS/admin/users/user.php?id=$id");
    exit;
}