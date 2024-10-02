<?php
session_start();

if (isset($_SESSION['UserID'])) {
    header("Location: /BMS/client/dashboard.php");
    exit();
}

require_once('../../database/dbx.php');
$error = 0;
$success = 0;
$email = "";
$pass = "";
if (count($_POST) > 0) {
    $email = $_POST['emailid'];
    $pass = $_POST['loginpassword'];

    if ($email == '' || $pass == '') {
        echo "<script>alert('Please Enter Email and Password')</script>";
        $error = 1;
    }

    if ($error == 0) {
        $sql = "SELECT * FROM Users WHERE Email = '$email' AND Password = '$pass'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            foreach ($row as $key => $value) {
                $_SESSION[$key] = $value;
            }

            if(isset($_POST['remember'])) {
                $cookie_name = "remember_user";
                $cookie_value = $email;
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
            }
            header("Location: /BMS/client/dashboard.php");
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
                            class="title">Login</span></p>
                <form action="" method="post">
                    <div class="input-field inputs" style="margin-top: 30px">
                        <label for="emailid">Email</label>
                        <input type="text" name="emailid" id="emailid" class="form-control" value="<?= $email ?>"
                               placeholder="Enter your email" required="required">
                    </div>
                    <br>
                    <div class="input-field inputs">
                        <label for="loginpassword">Password</label>
                        <input type="password" name="loginpassword" id="loginpassword"
                               class="form-control" placeholder="Enter your password" required="required">
                    </div>
                    <div class="checkbox-text form-check  ">
                        <div class="checkbox-content form-check-input" style="margin-top: 42px;">
                            <input type="checkbox" name="remember" id="logCheck">
                            <label for="logCheck" class="text">Remember me</label>
                        </div>
                        <div class="forgot">
                            <a href="forgot_password.php" class="text">Forgot password?</a>
                        </div>
                    </div>
                    <div class="input-field button login-btn">
                        <input type="submit" value="Login" name="btnsubmit">
                    </div>
                </form>
                <div class="login-signup">
                <span class="text" style="color: #ccc; font-size: 20px;">Not a member?
                    <a href="/BMS/client/auth/sign-up.php" class="text signup-link"
                       style=" font-size: 20px;"><b>Signup Now</b></a>
                </span>
                </div>
                <div class="login-signup" style=" margin-top: 10px">
                <span class="text" style="color: #ccc; font-size: 20px;"> For Admin login 
                    <a href="/BMS/admin/auth/" class="text signup-link"
                       style=" font-size: 20px;"><b>click here</b></a>
                </span>
                </div>
            </div>
            <br>
            <br>
        </div>
    </div>
</div>
<script src="../../public/js/script.js"></script>
</body>
</html>