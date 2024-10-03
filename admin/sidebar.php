<?php
$current_path = $_SERVER['REQUEST_URI'];
$db_active = $acc_active = $card_active = $trans_active = $settings_active = '';
if (strpos($current_path, 'staffs') !== false) {
    $db_active = 'staffs';
} elseif (strpos($current_path, 'users') !== false) {
    $acc_active = 'users';
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

    <div class="sidebar-btn <?php if ($acc_active) echo 'active' ?>" onclick="location.href='/BMS/admin/users/'" style="display: <?= $type === "Bank Teller" ? "none" : "" ?>">
        <div class="sidebar-btn-icon">
            <img src="/BMS/public/img/icons/account.svg" alt="dashboard">
        </div>
        <p>Users</p>
    </div>
    
    <div class="sidebar-btn <?php if ($db_active) echo 'active' ?>" onclick="location.href='/BMS/admin/staffs'"  style="display: <?= $type === "Bank Teller" ? "none" : "" ?>">
        <div class="sidebar-btn-icon">
            <img src="/BMS/public/img/icons/staffs.svg" alt="staffs">
        </div>
        <p>Staffs</p>
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