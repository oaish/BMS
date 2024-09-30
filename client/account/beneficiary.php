<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';
require_once('../../database/functions.php');

$MainAccID = $_SESSION['Acc']['AccountNo'];
$allBeneficiary = getAllBeneficiary($MainAccID);

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
        //validation
        // check if acc no exist
        $sql = "SELECT AccountID FROM Accounts WHERE AccountNo = '" . $acc_no . "'";
        $result = mysqli_query($con, $sql);
        if ($result && $row = mysqli_fetch_array($result)) {
            //check whether this Account ID already added as beneficiary
            $sql = "SELECT BID FROM Beneficiary  WHERE AccountNo = '" . $acc_no . "' AND MainAccountNo = '" . $MainAccID . "'";
            $result = mysqli_query($con, $sql);
            if ($result && $row = mysqli_fetch_array($result)) {
                $error = 1;
                $error_message = "This account is already added as benefciary.";
            }

            if ($error == 0) {
                //insert into beneficiary table
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
        <div class="request-content">
            <div class="beneficiary-list">
                <p style="color:#fff; font-size: 25px; margin-bottom: 10px;">Beneficiary List</p>
                <table style="color:#fff; width: 100%;">
                    <tr style="text-align: left; background-color: white; color: #333;">
                        <th>Sr No</th>
                        <th>Name</th>
                        <th>Acc No</th>
                    </tr>
                    <?php if (empty($allBeneficiary)) { ?>
                        <tr>
                            <td colspan="3">No Beneficiaries </td>
                        </tr>
                    <?php } ?>

                    <?php
                    $sr = 1;
                    foreach ($allBeneficiary as $key => $val) {
                        $sql = "SELECT u.Username FROM Accounts acc
                                INNER JOIN Users u
                                ON u.UserID = acc.UserID
                                WHERE acc.AccountNo = '" . $val . "'";
                        $result = mysqli_query($con, $sql);
                        if ($result && $row = mysqli_fetch_array($result)) {
                            $username = $row['Username'];
                            $acc_no = $val;
                        }
                        ?>
                        <tr>
                            <td><?= $sr; ?></td>
                            <td><?= $username; ?></td>
                            <td><?= $acc_no; ?></td>
                        </tr>
                        <?php
                        $sr++;
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <?php
    include '../footer.php';
    ?>
