<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';
require_once('../../database/functions.php');

$allBeneficiary = getAllBeneficiary($_SESSION['Acc']['AccountNo']);

$error = 0;
$success = 0;
$error_message = "";
$success_message = "";
$amount = "";

if (count($_POST) > 0) {
    $fromAcc = $_POST['fromAcc'];
    $toAcc = $_POST['toAcc'];
    $amount = $_POST['amount'];
    if ($fromAcc == "") {
        $error = 1;
        $error_message = "Please select source account";
    }

    if ($error == 0) {
        if ($toAcc == "") {
            $error = 1;
            $error_message = "Please select destination account";
        }
    }

    if ($error == 0) {
        if ($amount == "") {
            $error = 1;
            $error_message = "Please enter amount";
        }
    }

    if ($error == 0) {
        if (!is_numeric($amount)) {
            $error = 1;
            $error_message = "Please enter valid amount";
        }
    }

    if ($error == 0) {
        $sql = "INSERT INTO PayRequests (FromAccountNo, ToAccountNo, Amount) VALUES ('" . $fromAcc . "','" . $toAcc . "','" . $amount . "')";
        $result = mysqli_query($con, $sql);

        header('Location: /BMS/client/transactions/');
        exit();
    }

}

?>


<div class="content-wrapper">
    <div class="back-btn-div">
        <div class="back-btn" onclick="location.href='/BMS/client/transactions/'">
            <img src="../../public/img/icons/back.svg" alt="back"/>
        </div>
    </div>
    <hr/>
    <div class="transfer-content">
        <div class="content-main">
            <div class="content-main-form">
                <h2>Pay Request</h2>
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
                <form action="" method="post">
                    <input type="text" class="debit" value="<?= $_SESSION['Acc']['AccountNo']; ?>" name="fromAcc"
                           readonly>
                    <select name="toAcc" id="" class="debit">
                        <option value="">Select Account Holder</option>
                        <?php
                        foreach ($allBeneficiary as $acc) {
                            $sql = "SELECT u.Username,acc.AccountNo FROM Accounts acc
                                    INNER JOIN Users u
                                    ON u.UserID = acc.UserID
                                    WHERE acc.AccountNo = '" . $acc . "'";
                            $result = mysqli_query($con, $sql);
                            if ($result && $myrow = mysqli_fetch_array($result)) {
                                $username = $myrow['Username'];
                                $acc_no = $myrow['AccountNo'];
                                $full_acc = $acc_no . " (" . $username . ")";
                            }
                            ?>
                            <option value="<?= $acc_no; ?>"><?= $full_acc; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input type="text" placeholder="Amount" class="amount" name="amount" value="<?= $amount; ?>">
                    <div class="transfer-btn">
                        <button class="trans" name="trans-btn">Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php
include '../footer.php';
?>

<style>
    .content-main-form h2 {
        color: #ccc;
        margin-bottom: 20px;
    }

    .content-main {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .content-main-form {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -70%);
    }

    .content-main-form form {
        display: grid;
        gap: 20px;
        grid-template-rows: 1fr 1fr;
        grid-template-columns: 1fr 1fr;
    }

    .debit {
        font-size: 19px;
        height: 40px;
        border-radius: 10px;
        padding: 6px;
    }

    .amount {
        font-size: 19px;
        height: 40px;
        border-radius: 10px;
        padding: 6px;
    }

    .transfer-btn {
        width: 100%;
    }

    .trans {
        font-size: 19px;
        height: 40px;
        border-radius: 10px;
        padding: 6px;
        width: 40%;
        text-align: center;
    }
</style>