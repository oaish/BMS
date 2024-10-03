<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

$id = $_SESSION['UserID'];
$sql = "SELECT * FROM Users WHERE UserID = $id";
$result = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM Accounts WHERE UserID = $id";
$result = mysqli_query($con, $sql);
$account = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $startDate = date('Y-m-d');
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $period = $_POST['period'];
    $endDate = $_POST['endDate'];
    $userId = $_SESSION['UserID'];
    $installments = $_POST['installments'];
    $interest = floatval(str_replace('%', '', $_POST['interest']));
    

    if ($amount < 5000) {
        echo "<script>alert('Error: Loan amount should be greater than ₹5000')</script>";
    } else {
        $sql = "INSERT INTO Loans
                VALUES(DEFAULT, $userId, '$type', $amount, $interest, $period, $installments, 'Pending', '$startDate', '$endDate')";
        $result = mysqli_query($con, $sql);
        if ($result) {
            echo "<script>alert('Loan applied successfully')</script>";
        } else {
            echo "<script>alert('Error: Something went wrong')</script>";
        }
    }
}

?>
<div class="content-wrapper">
    <div class="back-btn-div">
        <div class="back-btn" onclick="location.href='./index.php'">
            <img src="../../public/img/icons/back.svg" alt="back" />
        </div>
    </div>
    <hr />
    <div class="loan-container" style="padding: 50px; display: flex; justify-content: center; align-items: center">
        <div class="apply-loan-div">
            <h3>Loan Application</h3>
            <hr>
            <form action="" method="POST" class="apply-loan-form" id="apply-form">
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
                    </div>

                    <div class="account-details" style="margin-top: 10px;">
                        <div class="account-details-card">
                            <h5>Phone Number</h5>
                            <h4><?= $user['Phone'] ?></h4>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Account Number</h5>
                            <h4><?= $account['AccountNo'] ?></h4>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Account Type</h5>
                            <h4><?= $account['AccountType']; ?> Account</h4>
                        </div>
                    </div>

                    <div class="account-details" style="margin-top: 10px;">
                        <div class="account-details-card">
                            <h5>Loan Type</h5>
                            <select name="type" id="type" class="form-input" onchange="setInterestRate()" required>
                                <option value="">-- Select Type --</option>
                                <option value="Car Loan">Car Loan</option>
                                <option value="Home Loan">Home Loan</option>
                                <option value="Personal Loan">Personal Loan</option>
                                <option value="Educational Loan">Educational Loan</option>
                            </select>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Loan Amount</h5>
                            <input type="number" placeholder="Enter Loan Amount" name="amount" id="amount" class="form-input" required>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Repayment Period</h5>
                            <select name="period" class="form-input" id="period" onchange="handlePeriodChange()" required>
                                <option value="">-- Select Period --</option>
                                <option value="3">3 Months</option>
                                <option value="6">6 Months</option>
                                <option value="12">12 Months</option>
                                <option value="24">24 Months</option>
                            </select>
                        </div>
                    </div>

                    <div class="account-details" style="margin-top: 10px;">
                        <div class="account-details-card" readonly>
                            <h5>Rate of Interest</h5>
                            <input type="text" placeholder="X.XX %" name="interest" id="interest" class="form-input" readonly>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>Monthly Installments</h5>
                            <input type="number" value="0" name="installments" id="installments" class="form-input" readonly>
                        </div>
                        <div class="acc-details-divider"></div>
                        <div class="account-details-card">
                            <h5>End Date</h5>
                            <input type="datetime" name="endDate" id="endDate" placeholder="XX/XX/XXXX" class="form-input" readonly>
                        </div>
                    </div>

                    <div class="account-details" style="margin-top: 10px;">
                        <div class="account-details-card"></div>
                        <div class="acc-details-divider"></div>

                        <div class="account-details-card">
                            <button type="submit">Apply</button>
                        </div>

                        <div class="acc-details-divider"></div>
                        <div class="account-details-card"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const applyForm = document.querySelector("#apply-form");

    applyForm.onchange = () => {
        calculateEMI();
    }

    function calculateEMI() {
        const amount = parseFloat(document.querySelector('#amount').value);
        const period = parseInt(document.querySelector('#period').value);
        const annualInterestRate = parseFloat(document.querySelector('#interest').value);

        console.log(amount, period, annualInterestRate);

        if (isNaN(amount) || isNaN(period) || isNaN(annualInterestRate)) {
            return;
        }

        const monthlyInterestRate = annualInterestRate / 12 / 100;

        const emi = (amount * monthlyInterestRate * Math.pow(1 + monthlyInterestRate, period)) /
            (Math.pow(1 + monthlyInterestRate, period) - 1);

        document.querySelector('#installments').value = emi.toFixed(2);
    }

    function handlePeriodChange() {
        const period = parseInt(document.querySelector('#period').value);
        const currentDate = new Date();
        const endDate = new Date(currentDate.setMonth(currentDate.getMonth() + period));
        const formattedEndDate = endDate.toISOString().split('T')[0];
        document.querySelector("#endDate").value = formattedEndDate;
    }

    function setInterestRate() {
        const loanType = document.querySelector('#type').value;
        let interestRate;

        console.log(loanType);

        switch (loanType) {
            case 'Car Loan':
                interestRate = "7.5 %"; 
                break;
            case 'Personal Loan':
                interestRate = "10.0 %"; 
                break;
            case 'Home Loan':
                interestRate = "5.0 %"; 
                break;
            case 'Educational Loan':
                interestRate = "6.5 %"; 
                break;
            default:
                interestRate = "X.X %";
        }

        document.querySelector('#interest').value = interestRate;
    }
</script>

<?php
include '../footer.php';
?>