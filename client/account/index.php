<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

$card_no = $_SESSION['Acc']['CardNumber'];
?>
    <div class="content-wrapper">
        <div class="back-btn-div">
            <div class="back-btn" onclick="location.href='../dashboard.php'">
                <img src="../../public/img/icons/back.svg" alt="back"/>
            </div>
        </div>
        <hr/>
        <div class="account-container">
            <div class="account-cards">
                <div class="new-account-card" onclick="location.href='create.php'">
                    <div><img src="../../public/img/icons/new_account.svg" alt="add"></div>
                    <span>New Account</span>
                </div>
                <div class="new-account-card" onclick="location.href='beneficiary.php'">
                    <div><img src="../../public/img/icons/beneficiary.svg" alt="add"></div>
                    <span>Beneficiary</span>
                </div>
            </div>

            <h3>Account Details</h3>
            <div class="account-details">
                <div class="account-details-card">
                    <h5>Account Number</h5>
                    <h4><?= $_SESSION['Acc']['AccountNo'] ?></h4>
                </div>
                <div class="acc-details-divider"></div>
                <div class="account-details-card">
                    <h5>Account Type</h5>
                    <h4><?= $_SESSION['Acc']['AccountType'] ?> Account</h4>
                </div>
                <div class="acc-details-divider"></div>
                <div class="account-details-card">
                    <h5>Balance</h5>
                    <h4>&#8377; <?= number_format($_SESSION['Acc']['Balance'], 2); ?>
                    </h4>
                </div>
            </div>

            <h3>Virtual Debit Card</h3>
            <div class="account-debit-card-div">
                <div class="debit-card-header">
                    <img src="../../public/img/banks/banco_seguro.png" alt="kotak"/>
                    <h4 style="color: white; font-size: 20px; font-family: 'Agency FB', sans-serif">Banco Seguro</h4>
                </div>
                <div class="debit-card-body">
                    <div class="db-body-title">Card no.</div>
                    <div class="db-body-title">Expiry Date</div>
                    <div class="db-body-title">CVV (Security)</div>
                    <div class="db-body-content">
                        <div class="db-card-part"><?= substr($card_no, 0, 4) ?></div>
                        <div class="db-card-part"><?= substr($card_no, 4, 4) ?></div>
                        <div class="db-card-part"><?= substr($card_no, 8, 4) ?></div>
                        <div class="db-card-part"><?= substr($card_no, 12, 4) ?></div>
                    </div>
                    <div class="db-body-content">
                        <div class="db-card-part"><?= $_SESSION['Acc']['ExpiryDate'] ?></div>
                    </div>
                    <div class="db-body-content">
                        <div class="db-card-part"><?= $_SESSION['Acc']['CVV'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
include '../footer.php';
?>