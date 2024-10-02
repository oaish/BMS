<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

if (!isset($_GET['id'])) {
    header('Location: /BMS/admin/dashboard.php');
    exit;
}
$_SESSION['token'] = rand(1000000000, 9999999999);
$id = $_GET['id'];
$sql = "SELECT * FROM Users WHERE UserID = $id";
$result = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM Accounts WHERE UserID = $id";
$result = mysqli_query($con, $sql);
$account = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM Transactions 
         WHERE FromAccountNo IN (SELECT AccountNo FROM Accounts WHERE UserID = $id) 
         OR ToAccountNo  IN (SELECT AccountNo FROM Accounts WHERE UserID = $id) 
         ORDER BY TransactionDate DESC";
$result = mysqli_query($con, $sql);
$transactions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $transactions[] = $row;
}

function format_number($number)
{
    return substr($number, 0, 4) . " - " . substr($number, 4, 4) . " - " . substr($number, 8, 4) . " - " . substr($number, 12, 4);
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
        <div class="user-page-div">
            <div class="user-details-base">
                <h3>User Details</h3>
                <hr>
                <div class="user-details-container">
                    <div class="account-details" style="margin-top: 10px;">
                        <div class="account-details-card">
                            <h5>User Name</h5>
                            <h4><?= $user['Username'] ?></h4>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Email ID</h5>
                            <h4><?= $user['Email'] ?></h4>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Date of Birth</h5>
                            <h4><?= $user['DOB']; ?></h4>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Status</h5>
                            <h4 style="text-transform: capitalize;">
                                <?= $user['Status']; ?>
                            </h4>
                        </div>
                    </div>
                    <div class="account-details">
                        <div class="account-details-card">
                            <h5>Account Number</h5>
                            <h4><?= $account['AccountNo'] ?></h4>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Account Type</h5>
                            <h4><?= $account['AccountType'] ?> Account</h4>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Card Number</h5>
                            <h4><?= format_number($account['CardNumber']) ?></h4>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Balance</h5>
                            <h4>&#8377; <?= number_format($account['Balance'], 2); ?>
                            </h4>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="action-btn delete" onclick="performAction('Delete', `<?= $user['UserID'] ?>`)">
                            <img src="/BMS/public/img/icons/delete_user.svg" alt=""> Delete
                        </button>
                        <button class="action-btn block" onclick="performAction('<?= $user['Status'] === 'active' ? 'Block' : 'Unblock' ?>', `<?= $user['UserID'] ?>`)">
                            <img src="/BMS/public/img/icons/user_banned.svg" alt=""> <?= $user['Status'] === 'active' ? "Block" : "Unblock" ?>
                        </button>
                    </div>
                </div>
            </div>
            <div class="user-transactions-base">
                <div class="dash-recent-transactions-div">
                    <div class="dash-card dash-recent">
                        <h3>Recent Transactions</h3>
                        <hr>
                        <div class="recent-transaction-header">
                            <div class="recent-transaction-card" style="text-align: center">ID</div>
                            <div class="recent-transaction-divider"></div>
                            <div class="recent-transaction-card">From</div>
                            <div class="recent-transaction-divider"></div>
                            <div class="recent-transaction-card">To</div>
                            <div class="recent-transaction-divider"></div>
                            <div class="recent-transaction-card">Amount</div>
                            <div class="recent-transaction-divider"></div>
                            <div class="recent-transaction-card">Type</div>
                            <div class="recent-transaction-divider"></div>
                            <div class="recent-transaction-card">Date</div>
                        </div>
                        <div class="dash-recent-transactions">
                            <?php if (empty($transactions)) { ?>
                                <div class="recent-transaction no-req">
                                    <div class="pay-req-acc">No Transactions</div>
                                </div>
                            <?php } ?>

                            <?php foreach ($transactions as $idx => $t) { ?>
                                <div class="recent-transaction">
                                    <div style="text-align: center"><?= $idx + 1 ?></div>
                                    <div class="recent-transaction-divider"></div>
                                    <div><?= $t['FromAccountNo'] == 0 ? "Cash" : $t['FromAccountNo'] ?></div>
                                    <div class="recent-transaction-divider"></div>
                                    <div><?= $t['ToAccountNo'] == 0 ? "Cash" : $t['ToAccountNo'] ?></div>
                                    <div class="recent-transaction-divider"></div>
                                    <div>&#8377; <?= number_format($t['Amount'], 2); ?></div>
                                    <div class="recent-transaction-divider"></div>
                                    <div><?= $t['TransactionType']; ?>
                                    </div>
                                    <div class="recent-transaction-divider"></div>
                                    <div><?= $t['TransactionDate'] ?></div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function performAction(action, id) {
        console.log(action, id);
        if (confirm(`Do you want to ${action} this user?`)) {
            window.location.href = `/BMS/admin/users/action.php?${action}=true&id=${id}&token=<?= $_SESSION['token'] ?>`;
        }
    }
</script>

<?php
include '../footer.php';
?>