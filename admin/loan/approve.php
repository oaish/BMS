<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

if (!isset($_GET['id'])) {
    header('Location: /BMS/admin/users');
    exit;
}

$id = $_GET['id'];
$sql = "SELECT * FROM Loans WHERE LoanId = $id";
$result = mysqli_query($con, $sql);
$loan = mysqli_fetch_assoc($result);
$otherloan = false;

$loanid = $loan['UserId'];

$loans = []; 


$sql = "SELECT * FROM Loans WHERE UserID = $loanid";
$result = mysqli_query($con, $sql);

while ($loanss = mysqli_fetch_assoc($result)) {
    $loans[] = $loanss; 
}

if (!empty($loans)) {
    foreach ($loans as $loanss) {
        if ($loanss['LoanStatus']!== 'Pending') {
            $otherLoan = true;
            break; 
        }
    }
}



$sql = "SELECT * FROM Users WHERE UserID = $loanid";
$result = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($result);




?>

<div class="content-wrapper">
    <div class="back-btn-div">
        <div class="back-btn" onclick="window.location.href = '/BMS/admin/loan/'">
            <img src="../../public/img/icons/back.svg" alt="back" />
        </div>
    </div>
    <hr />
    <div class="account-container">
        <div class="user-page-div">
            <div class="user-details-base">
                <h3>Pending Loan Details</h3>
                <hr>
                <div class="user-details-container bg">
                    <div class="account-details" >
                        <div class="account-details-card">
                            <h5>Loan Id</h5>
                            <h4 style="text-transform: capitalize;">
                                <?= $loan['LoanId']; ?>
                            </h4>
                        </div>  
                        <div class="acc-details-divider"></div>
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
                            <h5>Phone</h5>
                            <h4><?= $user['Phone']; ?></h4>
                        </div>
                        
                    </div>
                    <div class="account-details">
                        <div class="account-details-card">
                            <h5>Loan Type</h5>
                            <h4 style="text-transform: capitalize;">
                                <?= $loan['LoanType']; ?>
                            </h4>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Loan Amount </h5>
                            <h4><?= $loan['LoanAmount'] ?></h4>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Interest Rate </h5>
                            <h4><?= $loan['InterestRate'] ?></h4>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Repayment Period </h5>
                            <h4><?= $loan['RepaymentPeriod'] ?></h4>
                        </div>
                        
                    </div>
                    <div class="account-details">
                        <div class="account-details-card">
                            <h5>Monthly Installment </h5>
                            <h4>&#8377; <?= $loan['MonthlyInstallment']; ?>
                            </h4>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Start Date  </h5>
                            <h4><?= substr($loan['StartDate'],0,10) ?></h4>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>End Date  </h5>
                            <h4><?= substr($loan['EndDate'],0,10) ?></h4>
                        </div>  
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Loan Status </h5>
                            <h4><?= $loan['LoanStatus'] ?></h4>
                        </div>
                       
                    </div>

                    <?php if($loan["LoanStatus"] == "Pending") { ?>
                    <div class="action-buttons">
                        <button class="action-btn delete" onclick="performAction('Approve', `<?= $loan['LoanId'] ?>`)">
                            Approve
                        </button>
                        <button class="action-btn block" onclick="performAction('Reject', `<?= $loan['LoanId'] ?>`)">
                            Reject 
                        </button>
                    </div>
                    <?php } ?>
                </div>
            </div>
            
        </div>
        <?php if(isset($otherLoan)) { ?>
            <div class="user-page-div">
                <div class="user-details-base">
                    <div class="select-tag">
                        <h3>Other Loan Status Details</h3>
                     
                    </div>
                    <hr>
                    <?php 
                        if (!empty($loans)) {
                            foreach ($loans as $loanDetail) {
                                if ($loanDetail['LoanStatus'] !== 'Pending') {
                        ?>
                    <div class="user-details-container bg">
                        <div class="account-details" >
                            <div class="account-details-card">
                                <h5>Loan Id</h5>
                                <h4 style="text-transform: capitalize;">
                                    <?= $loanDetail['LoanId']; ?>
                                </h4>
                            </div>  
                            <div class="acc-details-divider"></div>
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
                                <h5>Phone</h5>
                                <h4><?= $user['Phone']; ?></h4>
                            </div>
                            
                        </div>
                        <div class="account-details">
                            <div class="account-details-card">
                                <h5>Loan Type</h5>
                                <h4 style="text-transform: capitalize;">
                                    <?= $loanDetail['LoanType']; ?>
                                </h4>
                            </div>
                            <div class="acc-details-divider"></div>
                            <div class="account-details-card">
                                <h5>Loan Amount </h5>
                                <h4><?= $loanDetail['LoanAmount'] ?></h4>
                            </div>
                            <div class="acc-details-divider"></div>
                            <div class="account-details-card">
                                <h5>Interest Rate </h5>
                                <h4><?= $loanDetail['InterestRate'] ?></h4>
                            </div>
                            <div class="acc-details-divider"></div>
                            <div class="account-details-card">
                                <h5>Repayment Period </h5>
                                <h4><?= $loanDetail['RepaymentPeriod'] ?></h4>
                            </div>
                            
                        </div>
                        <div class="account-details">
                            <div class="account-details-card">
                                <h5>Monthly Installment </h5>
                                <h4>&#8377; <?= $loanDetail['MonthlyInstallment']; ?>
                                </h4>
                            </div>
                            <div class="acc-details-divider"></div>
                            <div class="account-details-card">
                                <h5>Start Date  </h5>
                                <h4><?= substr($loanDetail['StartDate'],0,10) ?></h4>
                            </div>
                            <div class="acc-details-divider"></div>
                            <div class="account-details-card">
                                <h5>End Date  </h5>
                                <h4><?= substr($loanDetail['EndDate'],0,10) ?></h4>
                            </div>  
                            <div class="acc-details-divider"></div>
                            <div class="account-details-card">
                                <h5>Loan Status </h5>
                                <h4><?= $loanDetail['LoanStatus'] ?></h4>
                            </div>
                        
                        </div>
                    </div>
                    <?php
                                }
                            }
                        }
                        ?>
                </div>
                
            </div>
        <?php } ?>
    </div>
</div>

<script>
   function performAction(action, id) {
    if (confirm(`Do you want to ${action} this loan?`)) {
        window.location.href = `/BMS/admin/loan/action.php?${action}=true&id=${id}`;
    }
    }
</script>



<?php
include '../footer.php';
?>