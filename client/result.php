<?php
if (!isset($_GET['query'])) {
    header('Location: /BMS/client/dashboard.php');
}
include 'session_check.php';
include 'layout.php';
include 'sidebar.php';
include '../database/functions.php';
$error = 0;
$query = str_replace("%20", " ", $_GET['query']);
if (!is_numeric($query)) {
    $error = 1;
}
$sql = "SELECT * FROM Accounts WHERE AccountNo LIKE '%$query%'";
$result = mysqli_query($con, $sql);
$rez = [];

for ($i = 0; $i < mysqli_num_rows($result); $i++) {
    $rez[] = mysqli_fetch_assoc($result);
}


?>
<style>
    .result-container {
        color: #ccc;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 30px;
    }

    .result-card {
        cursor: pointer;
        padding: 10px 50px;
        margin-top: 20px;
        background-color: #1d1d1d;
        border-radius: 10px;
    }
</style>
<div class="content-wrapper">
    <div class="back-btn-div">
        <div class="back-btn" onclick="location.href='./dashboard.php'">
            <img src="../public/img/icons/back.svg" alt="back"/>
        </div>
    </div>
    <hr/>
    <div class="result-container">
        <h1>Result for query: <?= $query ?></h1>
        <div class="results">
            <?php
            if ($error) {
                echo "Invalid query";
            }
            foreach ($rez as $acc) {
                ?>
                <div class="result-card" onclick="location.href=`./transactions/transfer.php?accNo=<?= $acc['AccountNo'] ?>`">
                    <h3><?= $acc['AccountNo'] ?></h3>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<?php
include 'footer.php';
?>
