<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

?>

<head>
    <link rel="stylesheet" href="../../public/css/client/loan_calc.css">
</head>
<div class="content-wrapper">
    <div class="back-btn-div">
        <div class="back-btn" onclick="location.href='./index.php'">
            <img src="../../public/img/icons/back.svg" alt="back" />
        </div>
    </div>
    <hr />
    <div class="container">
        <h1>Loan Calculator</h1>
        <div class="box">
            <div class="left">
                <form id="loan-form">
                    <div>
                        <div class="content1">
                            <p>Loan Amount</p>
                            <div class="loan">
                                <span class="span-tag">₹ </span>
                                <input type="text" name="loan-amount-input" id="loan-amount-input" value="600000">
                            </div>
                        </div>
                        <input type="range" name="loan-amount-range" id="loan-amount-range" min="10000" max="1000000" step="1000" value="600000">
                    </div>
                    <div>
                        <div class="content1">
                            <p>Rate of interest (p.a)</p>
                            <div class="loan">
                                <input type="text" name="interest-rate-input" id="rate" value="10">
                                <span class="span-tag-1">% </span>
                            </div>
                        </div>
                        <input type="range" name="interest-rate-range" id="rate-range" min="1" max="50" step="1" value="10">
                    </div>
                    <div>
                        <div class="content1">
                            <p>Loan Term</p>
                            <div class="loan">
                                <input type="text" name="loan-term-input" id="year" value="5">
                                <span class="span-tag-1">Yr</span>
                            </div>
                        </div>
                        <input type="range" name="loan-term-range" id="term-range" min="1" max="30" step="1" value="5">
                    </div>
                </form>
                <div id="results" class="results">
                    <div>
                        <p>Monthly Payment : </p>
                        <p id="monthly-payment"> </p>
                    </div>
                    <div>
                        <p>Total Interest :</p>
                        <p id="total-interest"> </p>
                    </div>
                    <div>
                        <p>Total Payment : </p>
                        <p id="total-payment"> </p>
                    </div>
                </div>
            </div>
            <div class="right">
                <canvas id="loanChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php
include '../footer.php';
?>