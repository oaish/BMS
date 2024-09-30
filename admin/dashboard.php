<?php
include 'session_check.php';
include 'layout.php';
include 'sidebar.php';

// $currentDate = date('Y-m-d H:i:s', strtotime('1 day'));
// $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
// $sql = "SELECT * FROM Transactions 
//          WHERE (FromAccountNo = " . $_SESSION['Acc']['AccountNo'] . " OR ToAccountNo = " . $_SESSION['Acc']['AccountNo'] . ") 
//          AND TransactionDate BETWEEN '$thirtyDaysAgo' AND '$currentDate' ORDER BY TransactionDate DESC";

$result = mysqli_query($con, $sql);
$transactions = [];
$deposits = 0;
$withdrawals = 0;
$transferred = 0;
$received = 0;

// while ($row = mysqli_fetch_assoc($result)) {
//     if ($row['ToAccountNo'] == $_SESSION['Acc']['AccountNo'] && $row['TransactionType'] == "Transferred") {
//         $row['TransactionType'] = "Received";
//         $received += $row['Amount'];
//     } else if ($row['TransactionType'] == "Transferred") {
//         $transferred += $row['Amount'];
//     } else if ($row['TransactionType'] == "Deposited") {
//         $deposits++;
//     } else if ($row['TransactionType'] == "Withdrawn") {
//         $withdrawals++;
//     }
//     $transactions[] = $row;
// }

function format_number($number)
{
    return str_repeat("X", 4) . " - " . str_repeat("X", 4) . " - " . str_repeat("X", 4) . " - " . substr($number, 12, 4);
}

?>
<div class="content-wrapper">
    <div></div>
    <hr />
    <div class="dashboard-container">

    </div>
</div>
<?php
include 'footer.php';
?>