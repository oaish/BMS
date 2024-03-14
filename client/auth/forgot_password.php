<?php
session_start();
$email = "";
if (isset($_POST['email'])) {
    $headers = "From: no-reply@bms.com\r\n";
    $headers .= "Reply-To: oaishqazi@outlook.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $sub = "BMS | OTP Verification";
    $otp = rand(100000, 999999);
    $msg = "Your OTP is $otp";
    $email = $_POST['email'];
    if (mail($email, $sub, $msg, $headers)) {
        $_SESSION["otp"] = $otp;
        $_SESSION["reset_email"] = $email;
    } else {
        $error = error_get_last();
    }
}

if (isset($_POST['otp'])) {
    if ($_POST['otp'] == $_SESSION["otp"]) {
        header("Location: /BMS/client/auth/reset_password.php?email=".$_SESSION['reset_email']."&otp=".$_SESSION['otp']);
        exit();
    } else {
        echo "<script>alert('Invalid OTP')</script>";
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
                <p class="heading" style="padding-bottom: 15px;color: #ccc;border-bottom: 1px #116901 solid;"><span
                            class="title">Forgot Password</span></p>
                <form action="" method="post">
                    <?php if (!isset($_POST['submit'])) { ?>
                        <div class="input-field inputs" style="margin-top: 30px">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" class="form-control"
                                   placeholder="Enter your email address" required="required">
                        </div>
                    <?php } ?>
                    <?php if (isset($_POST['submit'])) { ?>
                        <div class="input-field inputs" style="margin-top: 30px">
                            <label for="otp">One - Time Password</label>
                            <input type="password" name="otp" id="otp"
                                   class="form-control" placeholder="Enter your OTP" required="required">
                        </div>
                    <?php } ?>
                    <div class="input-field button login-btn" style="margin-bottom: 0">
                        <input type="submit" value="<?= isset($_POST['submit']) ? 'Submit' : 'Next' ?>" name="submit">
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
