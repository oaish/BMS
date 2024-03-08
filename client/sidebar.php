<?php
$current_path = $_SERVER['REQUEST_URI'];
$db_active = $acc_active = $card_active = $trans_active = '';
if (strpos($current_path, 'dashboard') !== false) {
    $db_active = 'dashboard';
} elseif (strpos($current_path, 'account') !== false) {
    $acc_active = 'account';
} elseif (strpos($current_path, 'card') !== false) {
    $card_active = 'card';
} elseif (strpos($current_path, 'transaction') !== false) {
    $trans_active = 'transaction';
}

$accounts = [];
$sql = "SELECT * FROM Accounts WHERE UserID = '" . $_SESSION['UserID'] . "'";
$result = mysqli_query($con, $sql);
$count = mysqli_num_rows($result);
if ($count > 0) {
    $arr = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $arr[] = $row;
    }
    $accounts = $arr;
}

if (isset($_SESSION['AccIndex'])) {
    $acc_index = $_SESSION['AccIndex'];
}
else {
    $acc_index = $_SESSION['AccIndex'] = 0;
}
$username = $_SESSION['Username'];
$_SESSION['Acc'] = $accounts[$acc_index];

if (isset($_POST['acc_change'])) {
    $acc_index = $_SESSION['AccIndex'] = $_POST['acc_change'];
    $_SESSION['Acc'] = $accounts[$acc_index];
}

?>

<div class="sidebar">
    <div class="side-profile-div" id="sidebarProfile" onclick="this.classList.toggle('active')">
        <div class="side-profile-img">
            <img src="/BMS/public/img/banks/banco_seguro.png" alt="">
        </div>
        <div class="side-profile-name">
            <h4><?= $username ?></h4>
            <p><?= $_SESSION['Acc']['AccountNo'] ?></p>
        </div>
        <div class="profile-swap">
            <img src="/BMS/public/img/icons/drop_down.svg" alt="swap">
        </div>
        <form action="" method="post" class="profile-drop-down" id="sidebarDropDown">
            <?php foreach ($accounts as $idx => $acc) {
                if ($acc_index != $idx) { ?>
                    <div class="profile-acc-card">
                        <button type="submit" name="acc_change" value="<?= $idx ?>">
                            <?= $acc['AccountNo'] ?> - <?= $acc['AccountType'] ?>
                        </button>
                    </div>
                <?php } ?>
            <?php } ?>
        </form>
    </div>

    <div class="sidebar-search">
        <div class="sidebar-search-icon">
            <img src="/BMS/public/img/icons/search.svg" alt="search">
        </div>
        <label>
            <input type="search" placeholder="Search">
        </label>
    </div>

    <div class="sidebar-btn <?php if ($db_active) echo 'active' ?>" onclick="location.href='/BMS/client/dashboard.php'">
        <div class="sidebar-btn-icon">
            <img src="/BMS/public/img/icons/home.svg" alt="dashboard">
        </div>
        <p>Dashboard</p>
    </div>

    <div class="sidebar-btn <?php if ($acc_active) echo 'active' ?>" onclick="location.href='/BMS/client/account/'">
        <div class="sidebar-btn-icon">
            <img src="/BMS/public/img/icons/account.svg" alt="dashboard">
        </div>
        <p>Account</p>
    </div>

    <div class="sidebar-btn <?php if ($trans_active) echo 'active' ?>"
         onclick="location.href='/BMS/client/transactions/'">
        <div class="sidebar-btn-icon">
            <img src="/BMS/public/img/icons/transactions.svg" alt="dashboard">
        </div>
        <p>Transactions</p>
    </div>
    <div class="sidebar-btn"
         onclick="location.href='/BMS/client/auth/logout.php'">
        <div class="sidebar-btn-icon">
            <img src="/BMS/public/img/icons/logout.svg" alt="dashboard">
        </div>
        <p>Logout</p>
    </div>

    <div class="sidebar-btn btn-bottom">
        <div class="sidebar-btn-icon">
            <img src="/BMS/public/img/icons/settings.svg" alt="dashboard">
        </div>
        <p>Settings</p>
    </div>

</div>
