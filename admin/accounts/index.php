<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

$sql = "SELECT * FROM Users";
$result = mysqli_query($con, $sql);
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

?>
<div class="content-wrapper">
    <div class="back-btn-div">
        <div class="back-btn" onclick="location.href='../dashboard.php'">
            <img src="../../public/img/icons/back.svg" alt="back" />
        </div>
    </div>
    <hr />
    <div class="account-container">
        <div class="user-accounts-div">
            <div class="dash-card dash-recent">
                <h3>User Accounts</h3>
                <hr>
                <div class="user-account-header">
                    <div class="user-account-card" style="text-align: center">ID</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card">Username</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card">Email</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card">Phone</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card">D.O.B.</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card">Status</div>
                </div>
                <div class="user-accounts">
                    <?php if (empty($users)) { ?>
                        <div class="user-account no-req">
                            <div class="pay-req-acc">No Users</div>
                        </div>
                    <?php } ?>

                    <?php foreach ($users as $idx => $user) { ?>
                        <div class="user-account">
                            <div style="text-align: center"><?= $user['UserID'] ?></div>
                            <div class="user-account-divider"></div>
                            <div><?= $user['Username'] ?></div>
                            <div class="user-account-divider"></div>
                            <div><?= $user['Email'] ?></div>
                            <div class="user-account-divider"></div>
                            <div><?= $user['Phone'] ?></div>
                            <div class="user-account-divider"></div>
                            <div><?= $user['DOB']; ?>
                            </div>
                            <div class="user-account-divider"></div>
                            <div style="text-transform: capitalize"><?= $user['Status'] ?></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include '../footer.php';
?>