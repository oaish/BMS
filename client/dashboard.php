<?php
include 'session_check.php';
include 'layout.php';
include 'sidebar.php';

$currentDate = date('Y-m-d H:i:s', strtotime('1 day'));
$thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
$sql = "SELECT * FROM Transactions 
         WHERE (FromAccountNo = " . $_SESSION['Acc']['AccountNo'] . " OR ToAccountNo = " . $_SESSION['Acc']['AccountNo'] . ") 
         AND TransactionDate BETWEEN '$thirtyDaysAgo' AND '$currentDate' ORDER BY TransactionDate DESC";

$result = mysqli_query($con, $sql);
$transactions = [];
$deposits = 0;
$withdrawals = 0;
$transferred = 0;
$received = 0;

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['ToAccountNo'] == $_SESSION['Acc']['AccountNo'] && $row['TransactionType'] == "Transferred") {
        $row['TransactionType'] = "Received";
        $received += $row['Amount'];
    } else if ($row['TransactionType'] == "Transferred") {
        $transferred += $row['Amount'];
    } else if ($row['TransactionType'] == "Deposited") {
        $deposits++;
    } else if ($row['TransactionType'] == "Withdrawn") {
        $withdrawals++;
    }
    $transactions[] = $row;
}

function format_number($number)
{
    return str_repeat("X", 4) . " - " . str_repeat("X", 4) . " - " . str_repeat("X", 4) . " - " . substr($number, 12, 4);
}

?>
    <div class="content-wrapper">
        <div></div>
        <hr/>
        <div class="dashboard-container">
            <div class="dash-card-div">
                <div class="dash-virtual-card">
                    <h3 class="dash-card-title"><?= $_SESSION['Username'] ?></h3>
                    <h2 class="dash-card-number"><?= format_number($_SESSION['Acc']['CardNumber']) ?></h2>
                    <h4 class="dash-card-detail" style="margin-right: 135px;">CVV: <span
                                onclick="this.classList.toggle('active')"><?= $_SESSION['Acc']['CVV'] ?></span></h4>
                    <h4 class="dash-card-detail">Expiry: <?= $_SESSION['Acc']['ExpiryDate'] ?></h4>
                    <div class="dash-card-balance" onclick="this.classList.toggle('active')">
                        <div class="dash-card-balance-txt">Show Balance</div>
                        <div class="dash-card-balance-bal">
                            &#8377; <?= number_format($_SESSION['Acc']['Balance'], 2); ?></div>
                    </div>
                    <img class="dash-card-visa" src="../public/img/visa.png" alt=""/>
                </div>
            </div>
            <div class="dash-card-div">
                <div class="dash-card dash-activity">
                    <div class="activity-title">
                        <h3>My Activity</h3>
                        <span class="activity-date-range">
                            <?= date('M d, Y', strtotime('-30 days')) ?> - <?= date('M d, Y') ?>
                        </span>
                    </div>
                    <div class="activity-grid">
                        <div class="activity-grid-item">
                            <div class="activity-grid-item-img">
                                <img src="../public/img/icons/deposit.svg" alt=""/>
                            </div>
                            <div class="activity-grid-item-val">
                                <p>Deposits</p>
                                <h3><?= $deposits ?></h3>
                            </div>
                        </div>
                        <div class="activity-grid-item">
                            <div class="activity-grid-item-img">
                                <img src="../public/img/icons/withdraw.svg" alt=""/>
                            </div>
                            <div class="activity-grid-item-val">
                                <p>Withdrawals</p>
                                <h3><?= $withdrawals ?></h3>
                            </div>
                        </div>
                        <div class="activity-grid-item">
                            <div class="activity-grid-item-img">
                                <img src="../public/img/icons/transfer.svg" alt=""/>
                            </div>
                            <div class="activity-grid-item-val">
                                <p>Transferred</p>
                                <h3>&#8377; <?= number_format($transferred, 2) ?></h3>
                            </div>
                        </div>
                        <div class="activity-grid-item">
                            <div class="activity-grid-item-img">
                                <img src="../public/img/icons/request.svg" alt=""/>
                            </div>
                            <div class="activity-grid-item-val">
                                <p>Received</p>
                                <h3>&#8377; <?= number_format($received, 2) ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
<?php
include 'footer.php';
?>