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
    header("Location: /BMS/admin/loan/");
    exit;
}

