<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

$currentDate = date('Y-m-d H:i:s', strtotime('1 day'));
$thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
$sql = "SELECT * FROM Transactions 
         WHERE (FromAccountNo = " . $_SESSION['Acc']['AccountNo'] . " OR ToAccountNo = " . $_SESSION['Acc']['AccountNo'] . ") 
         AND TransactionDate BETWEEN '$thirtyDaysAgo' AND '$currentDate' ORDER BY TransactionDate DESC";
$result = mysqli_query($con, $sql);
$transactions = [];

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['ToAccountNo'] == $_SESSION['Acc']['AccountNo'] && $row['TransactionType'] == "Transferred") {
        $row['TransactionType'] = "Received";
    }
    $transactions[] = $row;
}

$sql = "SELECT * FROM PayRequests 
        WHERE (ToAccountNo = " . $_SESSION['Acc']['AccountNo'] . ")";
$result = mysqli_query($con, $sql);
$payRequests = [];

while ($row = mysqli_fetch_assoc($result)) {
    $payRequests[] = $row;
}

?>
    <div class="content-wrapper">
        <div class="back-btn-div">
            <div class="back-btn" onclick="location.href='../dashboard.php'">
                <img src="../../public/img/icons/back.svg" alt="back"/>
            </div>
        </div>
        <hr/>
        <div class="transactions-container">
            <div class="trans-grid">
                <div class="trans-div">
                    <h2>Transactions</h2>
                    <div class="trans-btn-group">
                        <a class="trans-btn" href="deposit.php">
                            <img src="../../public/img/icons/deposit.svg" alt="deposit"/>
                            <span>Deposit</span>
                        </a>
                        <a class="trans-btn" href="withdraw.php">
                            <img src="../../public/img/icons/withdraw.svg" alt="withdraw"/>
                            <span>Withdraw</span>
                        </a>
                        <a class="trans-btn" href="transfer.php">
                            <img src="../../public/img/icons/transfer.svg" alt="transfer"/>
                            <span>Transfer Money</span>
                        </a>
                        <a class="trans-btn" href="request.php">
                            <img src="../../public/img/icons/request.svg" alt="request"/>
                            <span>Request Money</span>
                        </a>
                    </div>
                </div>

                <div class="trans-div">
                    <h2>Pay Requests</h2>
                    <div class="trans-pay-req-div">
                        <?php if (empty($payRequests)) { ?>
                            <div class="trans-pay-req no-req">
                                <img src="../../public/img/icons/rupee.svg" alt="rupee"/>
                                <div class="pay-req-acc">No Requests</div>
                            </div>
                        <?php } ?>

                        <?php foreach ($payRequests as $p) { ?>
                            <div class="trans-pay-req">
                                <img src="../../public/img/icons/rupee.svg" alt="rupee"/>
                                <div class="pay-req-acc"><?= $p['FromAccountNo'] ?>:
                                    <span class="pay-req-amount">&#8377; <?= number_format($p['Amount'], 2); ?></span>
                                </div>
                                <a href="transfer.php?accNo=<?= $p['FromAccountNo'] ?>&amt=<?= $p['Amount'] ?>&id=<?= $p['PayRequestID'] ?>">
                                    <img src="../../public/img/icons/send.svg" alt="send"/>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="trans-div flex-col col-span-2">
                    <h2>Transaction History</h2>
                    <div class="trans-history-div">
                        <div class="trans-history-header">
                            <div class="trans-history-card" style="text-align: center">ID</div>
                            <div class="trans-history-divider"></div>
                            <div class="trans-history-card">From</div>
                            <div class="trans-history-divider"></div>
                            <div class="trans-history-card">To</div>
                            <div class="trans-history-divider"></div>
                            <div class="trans-history-card">Amount</div>
                            <div class="trans-history-divider"></div>
                            <div class="trans-history-card">Type</div>
                            <div class="trans-history-divider"></div>
                            <div class="trans-history-card">Date</div>
                        </div>
                        <div class="trans-history-body">
                            <?php if (empty($transactions)) { ?>
                                <div class="trans-history no-req">
                                    <div class="pay-req-acc">No Transactions</div>
                                </div>
                            <?php } ?>


                            <?php foreach ($transactions as $idx => $t) { ?>
                                <div class="trans-history">
                                    <div style="text-align: center"><?= $idx + 1 ?></div>
                                    <div class="trans-history-divider"></div>
                                    <div><?= $t['FromAccountNo'] == 0 ? "Cash" : $t['FromAccountNo'] ?></div>
                                    <div class="trans-history-divider"></div>
                                    <div><?= $t['ToAccountNo'] == 0 ? "Cash" : $t['ToAccountNo'] ?></div>
                                    <div class="trans-history-divider"></div>
                                    <div>&#8377; <?= number_format($t['Amount'], 2); ?></div>
                                    <div class="trans-history-divider"></div>
                                    <div><?= $t['TransactionType']; ?>
                                    </div>
                                    <div class="trans-history-divider"></div>
                                    <div><?= $t['TransactionDate'] ?></div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
include '../footer.php';
?>