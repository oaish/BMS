<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

$id = 0;
if (isset($_GET['id']) && isset($_GET['token'])) {
    $id = $_GET['id'];
    $get_token = $_GET['token'];
    $session_token = $_SESSION['token'];

    if ($id === "1") {
        exit;
    } 

    if (strnatcasecmp($session_token, $get_token) == 0) {
        $sql = "DELETE FROM Staff WHERE AdminID = $id";
        $result = mysqli_query($con, $sql);
    }
}
header('Location: /BMS/admin/staffs');
?>
