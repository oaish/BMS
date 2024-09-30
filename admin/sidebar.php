<?php
$current_path = $_SERVER['REQUEST_URI'];
$db_active = $acc_active = $card_active = $trans_active = $settings_active = '';
if (strpos($current_path, 'dashboard') !== false) {
    $db_active = 'dashboard';
} elseif (strpos($current_path, 'account') !== false) {
    $acc_active = 'account';
} elseif (strpos($current_path, 'card') !== false) {
    $card_active = 'card';
} elseif (strpos($current_path, 'transaction') !== false) {
    $trans_active = 'transaction';
} elseif (strpos($current_path, 'settings') !== false) {
    $settings_active = 'settings';
}

$username = $_SESSION['Username'];
$type = $_SESSION['Type'];
$sql = "SELECT * FROM Staff WHERE AdminID = '" . $_SESSION['AdminID'] . "'";
$result = mysqli_query($con, $sql);
$_SESSION['Acc'] = mysqli_fetch_assoc($result);
?>

<div class="sidebar">
    <div class="side-profile-div" id="sidebarProfile">
        <div class="side-profile-img">
            <img src="/BMS/public/img/icons/monarch.svg" alt="">
        </div>
        <div class="side-profile-name">
            <h4 style="text-transform: capitalize"><?= $username ?></h4>
            <p><?= $type ?> Login</p>
        </div>
    </div>

    <div class="sidebar-btn <?php if ($db_active) echo 'active' ?>" onclick="location.href='/BMS/admin/dashboard.php'">
        <div class="sidebar-btn-icon">
            <img src="/BMS/public/img/icons/home.svg" alt="dashboard">
        </div>
        <p>Dashboard</p>
    </div>

    <div class="sidebar-btn <?php if ($acc_active) echo 'active' ?>" onclick="location.href='/BMS/admin/accounts/'" style="display: <?= $type === "Bank Teller" ? "none" : "" ?>">
        <div class="sidebar-btn-icon">
            <img src="/BMS/public/img/icons/account.svg" alt="dashboard">
        </div>
        <p>Accounts</p>
    </div>

    <div class="sidebar-btn <?php if ($trans_active) echo 'active' ?>"
        onclick="location.href='/BMS/admin/transactions/'" style="display: <?= $type === "Admin" ? "none" : "" ?>">
        <div class="sidebar-btn-icon">
            <img src="/BMS/public/img/icons/transactions.svg" alt="dashboard">
        </div>
        <p>Transactions</p>
    </div>
    <div class="sidebar-btn"
        onclick="location.href='/BMS/admin/auth/logout.php'">
        <div class="sidebar-btn-icon">
            <img src="/BMS/public/img/icons/logout.svg" alt="dashboard">
        </div>
        <p>Logout</p>
    </div>

    <div class="sidebar-btn btn-bottom <?php if ($settings_active) echo 'active' ?>"
        onclick="location.href='/BMS/admin/settings/'">
        <div class="sidebar-btn-icon">
            <img src="/BMS/public/img/icons/settings.svg" alt="dashboard">
        </div>
        <p>Settings</p>
    </div>
</div>