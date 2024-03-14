<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

if (isset($_POST['withdraw'])) {
    $amount = $_POST['amount'];
    $acc = $_SESSION['Acc']['AccountNo'];

    if ($amount <= 0) {
        echo "<script>alert('Error: Amount cannot be Zero or Negative')</script>";
    } else if ($amount > $_SESSION['Acc']['Balance']) {
        echo "<script>alert('Error: Amount cannot be greater than Balance')</script>";
    } else {
        $sql = "UPDATE Accounts SET Balance = Balance - $amount WHERE AccountNo = $acc";
        $result = mysqli_query($con, $sql);
        $sql = "INSERT INTO Transactions (FromAccountNo, ToAccountNo, Amount, TransactionType) VALUES ($acc, 0, $amount, 'Withdrawn')";
        $result = mysqli_query($con, $sql);
        if ($result) {
            echo "<script>alert('Withdrawal Successful!')</script>";
        }
    }
}
?>
<style>
    .deposit-container {
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .deposit-label {
        color: #ccc;
        font-size: 1.2rem;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .deposit-label input {
        padding: 10px;
    }

    .deposit-form {
        display: flex;
        gap: 10px;
        flex-direction: column;
    }

    .deposit-form button {
        padding: 5px;
    }
</style>

<div class="content-wrapper">
    <div class="back-btn-div">
        <div class="back-btn" onclick="location.href='./'">
            <img src="../../public/img/icons/back.svg" alt="back"/>
        </div>
    </div>
    <hr/>
    <div class="deposit-container">
        <form action="" method="post" class="deposit-form">
            <label class="deposit-label">
                Withdraw Amount:
                <input type="number" name="amount" placeholder="Enter Amount">
            </label>
            <br>
            <button type="submit" name="withdraw" value="Withdraw">Withdraw</button>
        </form>
    </div>
</div>
<?php
include '../footer.php';
?>
