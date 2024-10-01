<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

$order = "";
$type = "";
if (isset($_GET['order'])) {
    $order = $_GET['order'];
    $type = $_GET['type'];
}

if ($order !== "") {
    $sql = "SELECT * FROM Users ORDER BY $order $type";
} else {
    $sql = "SELECT * FROM Users";
}

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
                    <div class="user-account-card" onclick="setOrder('UserID')" style="text-align: center">ID</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder('Username')">Username</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder('Email')">Email</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder('Phone')">Phone</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder('DOB')">D.O.B.</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder('Status')">Status</div>
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
                            <div style="text-transform: capitalize; color: #fff; position: relative">
                                <?= $user['Status'] ?>
                                <span style="position: absolute; right: 10px; top: 50%; translate: 0 -50%; cursor: pointer"
                                    onclick="window.location.href = '/BMS/admin/users/user.php?id=<?= $user['UserID'] ?>' "
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                                        <path d="M11.5 6C8.4802259 6 6 8.4802259 6 11.5L6 36.5C6 39.519774 8.4802259 42 11.5 42L36.5 42C39.519774 42 42 39.519774 42 36.5L42 11.5C42 8.4802259 39.519774 6 36.5 6L11.5 6 z M 11.5 9L36.5 9C37.898226 9 39 10.101774 39 11.5L39 36.5C39 37.898226 37.898226 39 36.5 39L11.5 39C10.101774 39 9 37.898226 9 36.5L9 11.5C9 10.101774 10.101774 9 11.5 9 z M 34.470703 11.986328 A 1.50015 1.50015 0 0 0 34.308594 12L23.5 12 A 1.50015 1.50015 0 1 0 23.5 15L30.878906 15L15.439453 30.439453 A 1.50015 1.50015 0 1 0 17.560547 32.560547L33 17.121094L33 24.5 A 1.50015 1.50015 0 1 0 36 24.5L36 13.689453 A 1.50015 1.50015 0 0 0 34.470703 11.986328 z" fill="#FFFFFF" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setOrder(order) {
        const path = window.location.href.split('?')[0];
        const query = window.location.href.split('?')[1];
        let type = query.split('&')[1].split('=')[1];
        if (type === "ASC") {
            type = "DESC";
        } else {
            type = "ASC";
        }
        window.location.href = `${path}?order=${order}&type=${type}`;
    }
</script>
<?php
include '../footer.php';
?>