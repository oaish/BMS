<?php
session_start();
include "../../database/dbx.php";
$email = "";

if (!isset($_SESSION['reset_email'])) {
    header("Location: /BMS/client/auth/forgot_password.php");
    exit();
}

if (isset($_POST['submit'])) {
    $sql = "UPDATE Users SET Password = '" . $_POST['newpass'] . "' WHERE Email = '" . $_GET['email'] . "'";
    $result = mysqli_query($con, $sql);
    if ($result) {
        header("Location: /BMS/client/auth/login.php");
        exit();
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
                <p class="heading" style="padding-bottom: 15px;color: #ccc; border-bottom: 1px #116901 solid;"><span
                            class="title">Reset Password</span></p>
                <form action="" method="post" name="resetForm">
                    <div class="input-field inputs" style="margin-top: 30px">
                        <label for="otp">New Password</label>
                        <input type="password" name="newpass" id="newpass"
                               class="form-control" placeholder="Enter your new password" required="required">
                    </div>
                    <div class="input-field inputs" style="margin-top: 30px">
                        <label for="otp">Confirm Password</label>
                        <input type="password" name="confirm" id="confirm"
                               class="form-control" placeholder="Confirm your password" required="required">
                    </div>
                    <div class="input-field button login-btn" style="margin-bottom: 0">
                        <input type="submit" value="Reset" name="submit">
                    </div>
                </form>
            </div>
            <br>
            <br>
        </div>
    </div>
</div>
<script src="../../public/js/script.js"></script>
<script>
    document.resetForm.onsubmit = function (e) {
        if (document.resetForm.newpass.value === "" || document.resetForm.confirm.value === "") {
            e.preventDefault();
            alert('All fields are required');
        }

        if (document.resetForm.newpass.value !== document.resetForm.confirm.value) {
            e.preventDefault();
            alert('Passwords do not match');
        }
    }
</script>
</body>
</html>
