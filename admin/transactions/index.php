<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';
require_once('../../database/functions.php');

// Initialize variables
$acc = null;
$user = null;

// $allBeneficiary = getAllBeneficiary($_SESSION['Account']['AccountNo']);

$error = 0;
$success = 0;
$error_message = "";
$success_message = "";
$amount = "";
if (isset($_SESSION['Account']['AccountNo'])) {
    $fromAcc = $_SESSION['Account']['AccountNo'];
}


if (isset($_POST['deposit'])) {
    $accno = $_SESSION['Account']['AccountNo'];
    $amount = $_POST['amount'];

    if ($amount <= 0) {
        echo "<script>alert('Error: Amount cannot be Zero or Negative')</script>";
    } else {
        $sql = "UPDATE Accounts SET Balance = Balance + $amount WHERE AccountNo = $accno";
        $_SESSION['Account']['Balance'] = $_SESSION['Account']['Balance'] + $amount;
        $result = mysqli_query($con, $sql);
        if ($result) {
            $sql = "INSERT INTO Transactions (FromAccountNo, ToAccountNo, Amount, TransactionType) VALUES (0, $accno, $amount, 'Deposited')";
            $transactionResult = mysqli_query($con, $sql);
            if ($transactionResult) {
                echo "<script>alert('Deposit Successful!')</script>";
            } else {
                echo "<script>alert('Error: Transaction log failed.')</script>";
            }
        } else {
            echo "<script>alert('Error: Deposit failed.')</script>";
        }
    }
}

if (isset($_POST['withdraw']) && isset($_POST['bal'])) {
    $amount = $_POST['amount'];
    $accnum = $_SESSION['Account']['AccountNo'];
    $bal = $_SESSION['Account']['Balance'];

    if ($amount <= 0) {
        echo "<script>alert('Error: Amount cannot be Zero or Negative')</script>";
    } else if ($amount > $bal) {
        echo "<script>alert('Error: Amount cannot be greater than Balance')</script>";
    } else {
        $sql = "UPDATE Accounts SET Balance = Balance - $amount WHERE AccountNo = $accnum";
        $result = mysqli_query($con, $sql);
        $_SESSION['Account']['Balance'] = $_SESSION['Account']['Balance'] - $amount;
        
        if ($result) {
            $sql = "INSERT INTO Transactions (FromAccountNo, ToAccountNo, Amount, TransactionType) VALUES ($accnum, 0, $amount, 'Withdrawn')";
            $transactionResult = mysqli_query($con, $sql);
            
            if ($transactionResult) {
                echo "<script>alert('Withdrawal Successful!')</script>";
            } else {
                echo "<script>alert('Error: Transaction log failed.')</script>";
            }
        } else {
            echo "<script>alert('Error: Withdrawal failed.')</script>";
        }
    }
}


//Transfer

if (isset($_POST['toAcc'])) {
    $toAcc = $_POST['toAcc'];
    $amount = $_POST['amount'];
    $ttype = $_POST['transactiontype'];

    if ($error == 0) {
        if ($toAcc == "") {
            $error = 1;
            $error_message = "Please select destination account";
            echo "<script>alert('Please select destination account.')</script>";
        } else if ($amount == "") {
            $error = 1;
            $error_message = "Please enter amount";
            echo "<script>alert('Please enter amount.')</script>";
        } else if (!is_numeric($amount)) {
            $error = 1;
            $error_message = "Please enter valid amount";
            echo "<script>alert('Please enter valid amount.')</script>";
        } else if ($amount > $_SESSION['Account']['Balance']) {
            $error = 1;
            $error_message = "Insufficient Balance";
            echo "<script>alert('Insufficient Balance.')</script>";
        }
        else if($ttype == "")
        {
            $error = 1;
            $error_message = "Plz Select Transaction Type.";
            echo "<script>alert('Plz Select Transaction Type.')</script>";
        }
    }

    // echo  "<script>alert('{$toAccuserdetail['Status']}')</script>";

    if ($error == 0) {

        $sql = "SELECT * FROM Accounts WHERE AccountNo = $toAcc";
        $result = mysqli_query($con, $sql);
        $accdetail = mysqli_fetch_assoc($result);
        if ($accdetail) {
            $sql = "SELECT * FROM Users WHERE UserID= '{$accdetail['UserID']}'";
            $result = mysqli_query($con, $sql);
            $toAccuserdetail = mysqli_fetch_assoc($result);
        }
        // echo  "<script>alert('{$toAccuserdetail['Status']}')</script>";
        if($toAccuserdetail['Status'] === "active")
        {
            $sql = "UPDATE Accounts SET Balance = Balance - " . $amount . " WHERE AccountNo = '" . $fromAcc . "'";
            $result = mysqli_query($con, $sql);
            $_SESSION['Account']['Balance'] = $_SESSION['Account']['Balance'] - $amount;
    
            $sql = "UPDATE Accounts SET Balance = Balance + " . $amount . " WHERE AccountNo = '" . $toAcc . "'";
            $result = mysqli_query($con, $sql);
    
            $sql = "INSERT INTO Transactions (FromAccountNo, ToAccountNo, Amount, TransactionType) VALUES ('" . $fromAcc . "','" . $toAcc . "','" . $amount . "', '" . $ttype . "')";
            $result = mysqli_query($con, $sql);
    
            echo "<script>alert('Transfered Successfully!!!.')</script>";
        }
        else{
            echo "<script>alert('Can\\'t Able To Transfer Money!!!\\nReceiver Account Inactive')</script>";
        }

    }

}

if (isset($_POST['find'])) {
    $accno = $_POST['accno'];
    $sql = "SELECT * FROM Accounts WHERE AccountNo = $accno";
    $result = mysqli_query($con, $sql);
    $acc = mysqli_fetch_assoc($result);

    if ($acc) {
        $_SESSION['Account'] = $acc;
        $sql = "SELECT * FROM Users WHERE UserID= '{$acc['UserID']}'";
        $result = mysqli_query($con, $sql);
        $_SESSION['User'] = mysqli_fetch_assoc($result);
        $user = $_SESSION['User'];
    } else {
        $acc = null;
    }
} else if (isset($_SESSION['User'] ) && isset($_SESSION['Account'])) {
    $user = $_SESSION['User'];
    $acc = $_SESSION['Account'];
    // echo "<script>alert('" . json_encode($_SESSION['Account']) . json_encode($_SESSION['User'])."')</script>";
}


?>

<div class="content-wrapper">
    <div class="back-btn-div">
        
    </div>
    <hr />
    <div class="account-container">
        <div class="accountno">
            <form action="" method="post">
                <p id="">Enter Account Number: </p>
                <input type="number" name="accno" id="accno" required value="<? $_SESSION['Account']['AccountNo'] ?>"/>
                <button type="submit" name="find" id="find">Search</button>
            </form>
        </div>
        <div class="user-accounts-div">
            <div class="dash-card dash-recent">
                <h3>Account Details</h3>
                <hr>
                <div class="user-account-header">
                    <div class="user-account-card" onclick="setOrder('UserID')" style="text-align: center">ID</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder('Username')">Username</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder('Email')">Email</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder('Phone')">Phone</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder('DOB')">Account Type</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder('Status')">Balance</div>
                </div>
                <div class="user-accounts">
                    <?php if (empty($acc)) { ?>
                        <div class="user-account no-req">
                            <div class="pay-req-acc">No Account</div>
                        </div>
                    <?php } ?>

                    <?php if (!empty($acc)) { ?>
                        <div class="user-account">
                            <div style="text-align: center"><?= $user['UserID']  ?></div>
                            <div class="user-account-divider"></div>
                            <div><?= $user['Username'] ?></div>
                            <div class="user-account-divider"></div>
                            <div><?= $user['Email'] ?></div>
                            <div class="user-account-divider"></div>
                            <div><?= $user['Phone'] ?></div>
                            <div class="user-account-divider"></div>
                            <div><?= $acc["AccountType"] ?></div>
                            <div class="user-account-divider"></div>
                            <div><?= $acc["Balance"] ?></div>
                        </div>
                        
                        <?php if ($user["Status"] == "active") { ?>
                            <div class="buttons">
                                <button class="btn" type="button" id="deposit-btn" value="Deposit" onclick="submitForm('deposit')">Deposit</button>
                                <button class="btn" type="button" id="withdraw-btn" value="Withdraw" onclick="submitForm('withdraw')">Withdraw</button>
                                <button class="btn" type="button" id="transfer-btn" value="Transfer" onclick="submitForm('transfer')">Transfer</button>
                            </div>
                        <?php } else { ?>
                                <p class="inactive">This Account Is Inactive</p>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php if (!empty($acc)) { ?>
        <div class="money-transfer">
            <div class="deposit">
                <h3>Deposit</h3>
                <hr>
                <div class="deposit-container">
                    <form action="" method="post" class="deposit-form">
                        <label class="deposit-label">
                            Deposit Amount:
                            <input type="number" name="amount" placeholder="Enter Amount" required>
                            <input type="hidden" name="accnum" value="<?= $acc['AccountNo'] ?>">
                        </label>
                        <br>
                        <button type="submit" class="btn" name="deposit" id="deposit" value="Deposit">Deposit</button>
                    </form>
                </div>
            </div>
            <div class="withdraw">
                <h3>Withdraw</h3>
                <hr>
                <div class="deposit-container">
                    <form action="" method="post" class="deposit-form">
                        <label class="deposit-label">
                            Withdraw Amount:
                            <input type="number" name="amount" placeholder="Enter Amount">
                            <input type="hidden" name="accnum" value="<?= $acc['AccountNo'] ?>">
                            <input type="hidden" name="bal" value="<?= $acc['Balance'] ?>">
                        </label>
                        <br>
                        <button type="submit" class="btn" name="withdraw" id="withdraw" value="Withdraw">Withdraw</button>
                    </form>
                </div>
            </div>
            <div class="transfer">
                <h3>Transfer</h3>
                <hr>
                <div class="transfer-content">
                    <div class="content-main">
                        <div class="content-main-form">
                            
                            <form action="" method="post">

                                <div>

                                    <input type="text" class="debit" value="<?= $fromAcc ?>" name="fromAcc"
                                        readonly>
    
                                    <?php if (isset($_GET['accNo'])) { ?>
                                        <input type="text" class="debit" value="<?= $_GET['accNo'] ?>" name="toAcc" readonly>
                                    <?php } else { ?>
                                        <select name="toAcc" id="toAcc" class="debit">
                                            <option value="">Select Account Holder</option>
                                            <?php
                                            foreach ($allBeneficiary as $bacc) {
                                                $sql = "SELECT u.Username,acc.AccountNo FROM Accounts acc
                                                    INNER JOIN Users u
                                                    ON u.UserID = acc.UserID
                                                    WHERE acc.AccountNo = '" . $bacc . "'";
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
                                    <?php } ?>
                                </div>
                                <div>
                                    <input type="text" placeholder="Amount" class="amount" name="amount"
                                    value="<?= isset($_GET['amt']) ? $_GET['amt'] : $amount; ?>">
                                    <select name="transactiontype" id="ttype" class="debit">
                                        <option value="">Select Transaction Type</option>
                                        <option value="NEFT">NEFT</option>
                                        <option value="RTGS">RTGS</option>
                                    </select>
                                </div>
                                <div class="transfer-btn">
                                    <button class="trans btn" id="transfer" name="<?= isset($_GET['accNo']) ? 'pay-req-btn' : 'trans-btn' ?>">
                                        Transfer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<script>
    function submitForm(action) {
        var btn = document.getElementById(action + '-btn');
        document.querySelector('.money-transfer').style.display = "block";
        if (btn.value == "Deposit") {
            document.querySelector('.deposit').style.display = "block";
            document.querySelector('.withdraw').style.display = "none";
            document.querySelector('.transfer').style.display = "none"; 
        } else if (btn.value == "Withdraw") {
            document.querySelector('.withdraw').style.display = "block";    
            document.querySelector('.deposit').style.display = "none";
            document.querySelector('.transfer').style.display = "none"; 
        } else if (btn.value == "Transfer") {
            document.querySelector('.transfer').style.display = "block";  
            document.querySelector('.deposit').style.display = "none";
            document.querySelector('.withdraw').style.display = "none";  
        }
    }
</script>

<?php
include '../footer.php';
?>
