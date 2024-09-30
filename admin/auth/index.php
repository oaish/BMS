<?php
session_start();
require_once('../../database/dbx.php');

if (isset($_SESSION['AdminID'])) {
    header("Location: /BMS/admin/dashboard.php");
    exit();
}

$error = 0;
$success = 0;
$username = "";
$pass = "";
if (count($_POST) > 0) {
    $username = $_POST['Username'];
    $pass = $_POST['Password'];

    if ($username == '' || $pass == '') {
        echo "<script>alert('Please Enter Email and Password')</script>";
        $error = 1;
    }

    if ($error == 0) {
        $sql = "SELECT * FROM Staff WHERE Username = '$username' AND Password = '$pass'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            foreach ($row as $key => $value) {
                $_SESSION[$key] = $value;
            }

            header("Location: /BMS/admin/dashboard.php");
            exit();
        }
        else {
            echo "<script>alert('Invalid Email or Password')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank</title>
    <link rel="stylesheet" href="../../public/css/auth.css">
    <link rel="shortcut icon" href="/BMS/favicon.ico" type="image/x-icon">
</head>
<body style="background-color: #333">
<div class="main">
    <div class="container">
        <div class="forms">
            <div class="form login">
                <p class="heading" style="color: #CCC;border-bottom: 1px #116901 solid;"><span
                            class="title">Admin Login</span></p>
                <form action="" method="post">
                    <div class="input-field inputs" style="margin-top: 30px">
                        <label for="Username">Username</label>
                        <input type="text" name="Username" id="Username" class="form-control" value="<?= $username ?>"
                               placeholder="Enter your username" required="required">
                    </div>
                    <br>
                    <div class="input-field inputs">
                        <label for="Password">Password</label>
                        <input type="password" name="Password" id="Password"
                               class="form-control" placeholder="Enter your password" required="required">
                    </div>
                    <div class="input-field button login-btn">
                        <input type="submit" value="Login" name="btnsubmit">
                    </div>
                </form>
            </div>
            <br>
            <br>
        </div>
    </div>
</div>
<script src="../../public/js/script.js"></script>
</body>
</html>
