<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

if (!isset($_GET['id'])) {
    header('Location: /BMS/admin/dashboard.php');
    exit;
}
$id = $_GET['id'];
$sql = "SELECT * FROM Users WHERE UserID = $id";
$result = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM Accounts WHERE UserID = $id";
$result = mysqli_query($con, $sql);
$accounts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $accounts[] = $row;
}

$sql = "SELECT * FROM Transactions 
         WHERE FromAccountNo IN (SELECT AccountID FROM Accounts WHERE UserID = $id) 
         OR ToAccountNo  IN (SELECT AccountID FROM Accounts WHERE UserID = $id) 
         ORDER BY TransactionDate DESC";
$result = mysqli_query($con, $sql);
$transactions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $transactions[] = $row;
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
            </div>
            <div class="user-accounts-base">
                <h3>Bank Accounts</h3>
                <hr>
                <div class="user-accounts-container">
                    <?php foreach ($accounts as $acc) { ?>
                        <div class="uaccount-card">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="30" height="30">
                                    <path d="M24 4.0957031C22.795674 4.0957031 21.592813 4.4874214 20.597656 5.2714844L6.5722656 16.322266 A 1.50015 1.50015 0 0 0 7.5 19L40.5 19 A 1.50015 1.50015 0 0 0 41.427734 16.322266L27.404297 5.2714844C26.40914 4.4874214 25.204326 4.0957031 24 4.0957031 z M 24 7.0878906C24.545174 7.0878906 25.090032 7.2689692 25.546875 7.6289062L36.173828 16L11.826172 16L22.453125 7.6289062C22.909968 7.2689692 23.454826 7.0878906 24 7.0878906 z M 24 10 A 2 2 0 0 0 24 14 A 2 2 0 0 0 24 10 z M 9.5 21L9.5 34.121094C7.5004201 34.578383 6 36.364467 6 38.5C6 40.98 8.02 43 10.5 43L21 43L21 40L10.5 40C9.67 40 9 39.33 9 38.5C9 37.67 9.67 37 10.5 37L21 37L21 34L19 34L19 21L16 21L16 34L12.5 34L12.5 21L9.5 21 z M 22.5 21L22.5 28.640625C23.06 28.230625 23.76 28 24.5 28L25.5 28L25.5 21L22.5 21 z M 29 21L29 28L32 28L32 21L29 21 z M 35.5 21L35.5 28L38.5 28L38.5 21L35.5 21 z M 25.5 30C24.136406 30 23 31.136406 23 32.5L23 43.5C23 44.863594 24.136406 46 25.5 46L43.5 46C44.863594 46 46 44.863594 46 43.5L46 32.5C46 31.136406 44.863594 30 43.5 30L25.5 30 z M 26 33L43 33L43 35L26 35L26 33 z M 26 38L43 38L43 43L26 43L26 38 z" fill="#1d1d1d" />
                                </svg>
                            </span>
                            <?= $acc['AccountNo'] ?>

                            <button class="uacc-bal" onclick="this.classList.toggle('active')">₹ <?= $acc['Balance'] ?>
                                <span>Check Balance</span>
                            </button>
                        </div>
                    <?php } ?>
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
<?php
include '../footer.php';
?>