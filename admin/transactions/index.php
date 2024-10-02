<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

// Initialize variables
$acc = null;
$user = null;

if (isset($_POST['find'])) {
    $accno = $_POST['accno'];
    $sql = "SELECT * FROM Accounts WHERE AccountNo = $accno";
    $result = mysqli_query($con, $sql);
    $acc = mysqli_fetch_assoc($result);

    if ($acc) {
        $_SESSION['Acc']['accnumber'] = $acc["AccountNo"];
        $sql = "SELECT * FROM Users WHERE UserID= '{$acc['UserID']}'";
        $result = mysqli_query($con, $sql);
        $_SESSION['User'] = mysqli_fetch_assoc($result);
        $user = $_SESSION['User'];
    } else {
        $acc = null;
    }
} else if (isset($_SESSION['User'])) {
    $user = $_SESSION['User'];
}

if (isset($_POST['deposit']) && isset($_POST['accnum'])) {
    $accno = $_POST['accnum'];
    $amount = $_POST['amount'];

    if ($amount <= 0) {
        echo "<script>alert('Error: Amount cannot be Zero or Negative')</script>";
    } else {
        $sql = "UPDATE Accounts SET Balance = Balance + $amount WHERE AccountNo = $accno";
        $result = mysqli_query($con, $sql);

        if ($result) {
            $sql = "INSERT INTO Transactions (FromAccountNo, ToAccountNo, Amount, TransactionType) VALUES (0, $accno, $amount, 'Deposited')";
            $transactionResult = mysqli_query($con, $sql);
            if ($transactionResult) {
                echo "<script>alert('Deposit Successful!')</script>";
                 $sql = "SELECT * FROM Accounts WHERE AccountNo = $accno";
                $result = mysqli_query($con, $sql);
                $acc = mysqli_fetch_assoc($result);
                
                if ($acc) {
                    $sql = "SELECT * FROM Users WHERE UserID= '{$acc['UserID']}'";
                    $result = mysqli_query($con, $sql);
                    $user = mysqli_fetch_assoc($result);
                    $_SESSION['Acc']['accnumber'] = $acc["AccountNo"];
                    $_SESSION['User'] = $user; 
                }
            } else {
                echo "<script>alert('Error: Transaction log failed.')</script>";
            }

            
        } else {
            echo "<script>alert('Error: Deposit failed.')</script>";
        }
    }
}

if (isset($_POST['withdraw']) && isset($_POST['accnum']) && isset($_POST['bal'])) {
    $amount = $_POST['amount'];
    $accnum = $_POST['accnum'];
    $bal = $_POST['bal'];

    if ($amount <= 0) {
        echo "<script>alert('Error: Amount cannot be Zero or Negative')</script>";
    } else if ($amount > $bal) {
        echo "<script>alert('Error: Amount cannot be greater than Balance')</script>";
    } else {
        $sql = "UPDATE Accounts SET Balance = Balance - $amount WHERE AccountNo = $accnum";
        $result = mysqli_query($con, $sql);
        
        if ($result) {
            $sql = "INSERT INTO Transactions (FromAccountNo, ToAccountNo, Amount, TransactionType) VALUES ($accnum, 0, $amount, 'Withdrawn')";
            $transactionResult = mysqli_query($con, $sql);
            
            if ($transactionResult) {
                echo "<script>alert('Withdrawal Successful!')</script>";
                $sql = "SELECT * FROM Accounts WHERE AccountNo = $accnum";
                $result = mysqli_query($con, $sql);
                $acc = mysqli_fetch_assoc($result);
                
                if ($acc) {
                    $sql = "SELECT * FROM Users WHERE UserID= '{$acc['UserID']}'";
                    $result = mysqli_query($con, $sql);
                    $user = mysqli_fetch_assoc($result);
                    $_SESSION['Acc']['accnumber'] = $acc["AccountNo"];
                    $_SESSION['User'] = $user; 
                }
            } else {
                echo "<script>alert('Error: Transaction log failed.')</script>";
            }
        } else {
            echo "<script>alert('Error: Withdrawal failed.')</script>";
        }
    }
}


function refetch($accno,$con)
{
    $sql = "SELECT * FROM Accounts WHERE AccountNo = $accno";
    $result = mysqli_query($con, $sql);
    $acc = mysqli_fetch_assoc($result);
    
    if ($acc) {
        $sql = "SELECT * FROM Users WHERE UserID= '{$acc['UserID']}'";
        $result = mysqli_query($con, $sql);
        $user = mysqli_fetch_assoc($result);
        $_SESSION['Acc']['accnumber'] = $acc["AccountNo"];
        $_SESSION['User'] = $user; 
    }
}

?>

<div class="content-wrapper">
    <div class="back-btn-div">
        <div class="back-btn" onclick="location.href='../dashboard.php'">
            <img src="../../public/img/icons/back.svg" alt="back" />
        </div>
    </div>
    <hr />
    <div class="account-container">
        <div class="accountno">
            <form action="" method="post">
                <p id="">Enter Account Number: </p>
                <input type="number" name="accno" id="accno" value='{<? if(isset($acc)){ $acc["AccountNo"];} ?>}' required>
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
                            <div style="text-align: center"><?= $user['UserID'] ?></div>
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
                        <button type="submit" name="deposit" value="Deposit">Deposit</button>
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
                        <button type="submit" name="withdraw" value="Withdraw">Withdraw</button>
                    </form>
                </div>
            </div>
            <div class="transfer">
                <h3>Transfer</h3>
                <hr>
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
