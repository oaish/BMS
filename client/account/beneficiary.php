<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';
require_once('../../database/functions.php');

$MainAccID = $_SESSION['Acc']['AccountNo'];
$error = 0;
$success = 0;
$error_message = "";
$success_message = "";
$acc_no = "";
$ben_confirm_acc_no = "";
if (isset($_POST['acc_no'])) {
    $acc_no = $_POST['acc_no'];
    $ben_confirm_acc_no = $_POST['confirm_acc_no'];
    if ($acc_no == "" || $ben_confirm_acc_no == "") {
        $error = 1;
        $error_message = "Please enter acc no";
    } else if ($acc_no != $ben_confirm_acc_no) {
        $error = 1;
        $error_message = "Acc no and benfeciary acc no must match";
    } else if ($acc_no == $MainAccID) {
        $error = 1;
        $error_message = "You can not add yourself as beneficiary";
    }

    if ($error == 0) {
        $sql = "SELECT AccountID FROM Accounts WHERE AccountNo = '" . $acc_no . "'";
        $result = mysqli_query($con, $sql);
        if ($result && $row = mysqli_fetch_array($result)) {
            $sql = "SELECT BID FROM Beneficiary  WHERE AccountNo = '" . $acc_no . "' AND MainAccountNo = '" . $MainAccID . "'";
            $result = mysqli_query($con, $sql);
            if ($result && $row = mysqli_fetch_array($result)) {
                $error = 1;
                $error_message = "This account is already added as benefciary.";
            }

            if ($error == 0) {
                $sql = "INSERT INTO Beneficiary (MainAccountNo , AccountNo) VALUES ('" . $MainAccID . "','" . $acc_no . "')";
                $result = mysqli_query($con, $sql);
                if (mysqli_affected_rows($con) <= 0) {
                    $error = 1;
                    $error_message = "Something went wrong while inserting beneficiary.";
                } else {
                    $allBeneficiary = getAllBeneficiary($MainAccID);
                    $success = 1;
                    $success_message = "Beneficiary is successfully added";
                    $acc_no = "";
                    $ben_confirm_acc_no = "";
                }
            }
        } else {
            $error = 1;
            $error_message = "This account no does not exist.";
        }
    }
}
?>
<div class="content-wrapper">
    <div class="back-btn-div">
        <div class="back-btn" onclick="location.href='./'">
            <img src="../../public/img/icons/back.svg" alt="back"/>
        </div>
    </div>
    <hr/>

    <div class="beneficiary-container">
        <div class="content-main-req-div">
            <form method="POST">
                <?php
                if ($error == 1) {
                    ?>
                    <p style="color:red;margin-bottom:10px;">Error: <?= $error_message; ?></p>
                    <?php
                }
                if ($success == 1) {
                    ?>
                    <p style="color:green;margin-bottom:10px;">Success: <?= $success_message; ?></p>
                    <?php
                }
                ?>
                <div class="content-main-req">
                    <input name="acc_no" type="text" placeholder="Enter beneficiary acc no" class="request"
                           value="<?= $acc_no; ?>">
                    <input name="confirm_acc_no" type="text" placeholder="Confirm beneficiary acc no" class="credit"
                           value="<?= $ben_confirm_acc_no; ?>">
                    <div class="transfer-btn">
                        <button class="trans" type="submit">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
    include '../footer.php';
    ?>
