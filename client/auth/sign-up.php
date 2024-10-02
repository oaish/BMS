<?php
session_start();

if (isset($_SESSION['UserID'])) {
    header("Location: http://localhost/BMS/client/dashboard.php");
    exit();
}

require_once('../../database/dbx.php');

function validate_email($email) {
    // Validate email format
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateName($name) {
    // Name should only contain letters and spaces, between 2 and 50 characters
    if (preg_match("/^[a-zA-Z ]{2,50}$/", $name)) {
        return true;
    } else {
        return false;
    }
}

function validatePhoneNumber($phone) {
    // Remove any spaces, dashes, or parentheses
    $cleanedPhone = preg_replace("/[^0-9]/", '', $phone);

    // Check if the number is 10-15 digits long
    if (preg_match("/^[0-9]{10,15}$/", $cleanedPhone)) {
        return true;
    } else {
        return false;
    }
}

function validate_password($password) {
    // Password validation: minimum 8 characters, at least one letter and one number
    if (strlen($password) < 8) {
        return false;
    }
    if (!preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        return false;
    }
    return true;
}

$error_msg = '';
$error = 0;
$success = 0;
$name = "";
$email = "";
$password = "";
$contact = "";
$dob = "";
$acc_type = "";
$bank_code = "";

function generateRandomNumber()
{
    $number = '';
    for ($i = 0; $i < 4; $i++) {
        $number .= sprintf("%04d", rand(0, 9999));
    }
    return $number;
}

if (count($_POST) > 0) {
    $name = $_POST['name'];
    $email = $_POST['emailid'];
    $password = $_POST['password'];
    $contact = $_POST['contact'];
    $acc_type = $_POST['acc-type'];
    $dob = $_POST['dob'];
    
    $dobObject = DateTime::createFromFormat('Y-m-d', $dob);
    $currentDate = new DateTime();

    if ($name == '' || $email == '' || $password == '' || $contact == '' || $acc_type == '') {
        $error_msg = 'Please fill mandatory fields';
        $error = 1;
    }
    elseif(!validateName($name)) {
        echo "<script> alert('Invalid Name!'); </script>";
    }
    elseif (!validatePhoneNumber($contact)) {
        echo "<script> alert('Invalid Phone Number!'); </script>";
    }
    elseif(!validate_email($email)) {
    echo "<script> alert('Invalid email format!'); </script>";
    }
    elseif(!validate_password($password)) {
       echo "<script>alert('Password must be at least 8 characters long and contain at least one letter and one number!'); </script>"; 
    }
    elseif ($dobObject > $currentDate) {
        echo "<script>alert('Invalid Date Of Birth!'); </script>"; 
    }
    else{
        $sql = "SELECT * FROM Users WHERE email = '$email'";
    $exists = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($exists);
    if (gettype($row) == 'array') {
        $error_msg = 'Email already exists';
        $error = 1;
    }

    if ($error == 0) {
        $sql = "INSERT INTO Users (username, email, password, phone, dob)
        VALUES ('$name', '$email', '$password', $contact, '$dob')";
        $result = mysqli_query($con, $sql);

        if ($result) {
            $sql = "SELECT MAX(UserID) AS maxUserID FROM Users";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $maxUserID = $row['maxUserID'];
            $acc_no = rand(1000000000, 9999999999);
            $card_no = generateRandomNumber();
            $cvv = rand(100, 999);
            $currentDate = date('m/y');
            $expDate = date('m/y', strtotime('+5 years'));
            $sql = "INSERT INTO Accounts (AccountNo, UserID, AccountType, CardNumber, CVV, ExpiryDate) VALUES ('$acc_no', $maxUserID, '$acc_type', '$card_no', $cvv, '$expDate')";
            $rez = mysqli_query($con, $sql);
            header("Location: login.php");
        } else {
            echo json_encode(['error' => 'Insertion failed']);
        }
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
                <p class="heading"
                   style="color: #ccc; padding-bottom: 10px;"><span
                            class="title">Sign-up</span></p>
                <form action="" method="post">
                    <?php
                    if ($error == 1) {
                        ?>
                        <script>alert('<?= $error_msg ?>')</script>
                        <?php
                    }
                    ?>
                    <br>
                    <div class="input-field inputs">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control"
                               placeholder="Enter your name" value="<?= $name; ?>">
                    </div>
                    <br>
                    <div class="input-field inputs">
                        <label for="email">Email</label>
                        <input type="text" name="emailid" id="emailid" class="form-control"
                               placeholder="Enter your email" required="required" value="<?= $email; ?>">
                    </div>
                    <br>
                    <div class="input-field inputs">
                        <label for="password">Password</label>
                        <input type="password" name="password" value="<?= $password; ?>" id="password"
                               class="form-control" placeholder="Enter your password" required="required">
                    </div>
                    <br>
                    <div class="input-field inputs">
                        <label for="contact">Phone Number</label>
                        <input type="number" name="contact" id="contact" class="form-control"
                               placeholder="Enter your phone number" value="<?= $contact; ?>" required="required"
                               maxlength="10" minlength="10">
                    </div>
                    <br>

                    <div class="input-field inputs">
                        <label for="dob">Date of Birth</label>
                        <input type="date" name="dob" id="dob" value="<?= $dob; ?>" class="form-control" placeholder="" required="required">
                    </div>
                    <br>

                    <div class="input-field inputs">
                        <label for="account-type">Account Type</label>
                        <select name="acc-type" id="account-type" class="select-control">
                            <option value="">Select Account Type</option>
                            <option value="Savings" <?= ($acc_type == 'Savings') ? 'selected' : ''?>>Savings</option>
                            <option value="Current" <?= ($acc_type == 'Current') ? 'selected' : ''?>>Current</option>
                        </select>
                    </div>

                    <div class="input-field button login-btn" style="margin-bottom: 0">
                        <input type="submit" value="Sign-up" name="btnsubmit" style="margin-top: 0"/>
                    </div>
                </form>
            </div>
            <br>
            <br>
        </div>
    </div>
</div>
</body>
</html>