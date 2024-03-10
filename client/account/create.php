<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

function generateRandomNumber()
{
    $number = '';
    for ($i = 0; $i < 4; $i++) {
        $number .= sprintf("%04d", rand(0, 9999));
    }
    return $number;
}

function format_number($number)
{
    return substr($number, 0, 4) . " - " . substr($number, 4, 4) . " - " . substr($number, 8, 4) . " - " . substr($number, 12, 4);
}

$acc_no = rand(1000000000, 9999999999);
$card_no = generateRandomNumber();
$cvv = rand(100, 999);
$currentDate = date('m/y');
$expDate = date('m/y', strtotime('+5 years'));

if (count($_POST) > 0) {
    $sql = "INSERT INTO Accounts (UserID, AccountNo, CardNumber, CVV, ExpiryDate, AccountType) 
            VALUES (" . $_SESSION['Acc']['UserID'] . ",$acc_no, $card_no, $cvv, '$expDate', '" . $_POST['acc_type'] . "')";
    $result = mysqli_query($con, $sql);
    header('Location: ./');
    exit();
}
?>
    <div class="content-wrapper">
        <div class="back-btn-div">
            <div class="back-btn" onclick="location.href='./'">
                <img src="../../public/img/icons/back.svg" alt="back"/>
            </div>
        </div>
        <hr/>
        <form action="" method="post" class="account-container create">
            <h3 style="line-height: 30px">User Details</h3>
            <div class="new-acc-detail-grid">
                <div class="acc-detail-header">Name</div>
                <div class="acc-detail-body"><?= $_SESSION['Username']; ?></div>
                <div class="acc-detail-header">Email</div>
                <div class="acc-detail-body"><?= $_SESSION['Email']; ?></div>
                <div class="acc-detail-header">Date Of Birth</div>
                <div class="acc-detail-body"><?= $_SESSION['DOB']; ?></div>
                <div class="acc-detail-header">Phone Number</div>
                <div class="acc-detail-body"><?= $_SESSION['Phone']; ?></div>
            </div>
            <h3 style="margin-top: 20px;line-height: 30px">Account Details</h3>
            <div class="new-acc-detail-grid">
                <div class="acc-detail-header">Account Number</div>
                <div class="acc-detail-body"><?= $acc_no ?></div>
                <div class="acc-detail-header">Card Number</div>
                <div class="acc-detail-body"><?= format_number($card_no) ?></div>
                <div class="acc-detail-header">CVV</div>
                <div class="acc-detail-body"><?= $cvv ?></div>
                <div class="acc-detail-header">Expiry Date</div>
                <div class="acc-detail-body"><?= $expDate ?></div>
                <label for="select" class="acc-detail-header">Account Type</label>
                <div class="acc-detail-body select-control">
                    <select name="acc_type" class="new-acc-select" id="select">
                        <option value="Savings">Savings</option>
                        <option value="Current">Current</option>
                    </select>
                </div>
            </div>

            <div class="new-acc-btn-group">
                <button class="new-acc-btn" type="submit" name="btnsubmit" style="background-color: crimson">Create
                </button>
                <button class="new-acc-btn" type="button" onclick="location.href='./'">Back</button>
            </div>
        </form>
    </div>
<?php
include '../footer.php';
?>