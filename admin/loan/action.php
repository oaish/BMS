<?php
include '../session_check.php';

$id = $_GET['id'];

if (isset($_GET['Reject'])) {
    $sql = "UPDATE Loans SET LoanStatus = 'Rejected' WHERE LoanId = $id";
    $result = mysqli_query($con, $sql);
    header("Location: /BMS/admin/loan/");
    exit;
}

if (isset($_GET['Approve'])) {
    $sql = "UPDATE Loans SET LoanStatus = 'Approved' WHERE LoanId = $id";
    $result = mysqli_query($con, $sql);

    $sql = "SELECT * FROM Loans WHERE LoanId = $id";
    $result = mysqli_query($con, $sql);
    $loan = mysqli_fetch_assoc($result);

    $sql = "SELECT * FROM Accounts WHERE UserID = ". $loan['UserId'];
    $result = mysqli_query($con, $sql);
    $acc = mysqli_fetch_assoc($result);

    $bal = floatval($acc['Balance']) +  floatval($loan['LoanAmount']);

    $sql = "UPDATE Accounts SET Balance = ". ($bal) . " WHERE UserID = ".$loan['UserId'];
    $result = mysqli_query($con, $sql);
    
    header("Location: /BMS/admin/loan/");
    exit;
}

