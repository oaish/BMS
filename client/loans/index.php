<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

$id = $_SESSION['UserID'];
$loans = [];
$sql = "SELECT * FROM Loans WHERE UserId = $id";
$result = mysqli_query($con, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $loans[] = $row;
}
?>
<div class="content-wrapper">
    <div class="back-btn-div">
        <div class="back-btn" onclick="location.href='../dashboard.php'">
            <img src="../../public/img/icons/back.svg" alt="back" />
        </div>
    </div>
    <hr />
    <div class="loan-container">
        <div class="dash-recent-loan-div">
            <div class="dash-card dash-recent">
                <h3>Recent Loans</h3>
                <hr>
                <div class="recent-loan-header">
                    <div class="recent-loan-card" style="text-align: center">ID</div>
                    <div class="recent-loan-divider"></div>
                    <div class="recent-loan-card">Type</div>
                    <div class="recent-loan-divider"></div>
                    <div class="recent-loan-card">Amount</div>
                    <div class="recent-loan-divider"></div>
                    <div class="recent-loan-card">Interest Rate</div>
                    <div class="recent-loan-divider"></div>
                    <div class="recent-loan-card">Repayment Period</div>
                    <div class="recent-loan-divider"></div>
                    <div class="recent-loan-card">Monthly Installment</div>
                    <div class="recent-loan-divider"></div>
                    <div class="recent-loan-card">Status</div>
                </div>
                <div class="dash-recent-loans">
                    <?php if (empty($loans)) { ?>
                        <div class="recent-loan no-req">
                            <div class="pay-req-acc">No Loans</div>
                        </div>
                    <?php } ?>

                    <?php foreach ($loans as $idx => $loan) { ?>
                        <div class="recent-loan">
                            <div style="text-align: center"><?= $loan['LoanId'] ?></div>
                            <div class="recent-loan-divider"></div>
                            <div><?= $loan['LoanType']; ?></div>
                            <div class="recent-loan-divider"></div>
                            <div>&#8377; <?= number_format($loan['LoanAmount'], 2); ?></div>
                            <div class="recent-loan-divider"></div>
                            <div><?= $loan['InterestRate'] ?> %</div>
                            <div class="recent-loan-divider"></div>
                            <div><?= $loan['RepaymentPeriod'] ?> Months</div>
                            <div class="recent-loan-divider"></div>
                            <div>&#8377; <?= $loan['MonthlyInstallment'] ?></div>
                            <div class="recent-loan-divider"></div>
                            <div><?= $loan['LoanStatus'] ?></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="loan-opt-buttons">
            <a href="./apply.php" class="loan-opt-btn">Apply for a Loan</a>
            <a href="./loan_calc.php" class="loan-opt-btn">Calculate Loan</a>
        </div>
    </div>
</div>
<?php
include '../footer.php';
?>