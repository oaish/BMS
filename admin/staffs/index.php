<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

$_SESSION['token'] = rand(1000000000, 9999999999);
$order = "";
$type = "";
if (isset($_GET['order'])) {
    $order = $_GET['order'];
    $type = $_GET['type'];
}

if ($order !== "") {
    $sql = "SELECT * FROM Staff ORDER BY $order $type";
} else {
    $sql = "SELECT * FROM Staff";
}

$result = mysqli_query($con, $sql);
$staffs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $staffs[] = $row;
}

?>
<div class="content-wrapper">
    <div class="back-btn-div">
    <div class="back-btn" onclick="window.location.href = '/BMS/admin/users/'">
            <img src="../../public/img/icons/back.svg" alt="back" />
        </div>    
    </div>
    <hr />
    <div class="dashboard-container">
        <div class="user-accounts-div">
            <div class="dash-card dash-recent">
                <h3 style="color: #ccc">Bank Staffs</h3>
                <hr>
                <div class="user-account-header">
                    <div class="user-account-card" onclick="setOrder('AdminID')" style="text-align: center">ID</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder('Username')">Username</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder('Type')">Password</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder('Type')">Type</div>
                    <div class="user-account-divider"></div>
                    <div class="user-account-card" onclick="setOrder(null)"></div>
                </div>
                <div class="user-accounts">
                    <?php if (empty($staffs)) { ?>
                        <div class="user-account no-req">
                            <div class="pay-req-acc">No Staffs</div>
                        </div>
                    <?php } ?>

                    <?php foreach ($staffs as $idx => $staff) { ?>
                        <div class="user-account">
                            <div style="text-align: center"><?= $staff['AdminID'] ?></div>
                            <div class="user-account-divider"></div>
                            <div><?= $staff['Username'] ?></div>
                            <div class="user-account-divider"></div>
                            <div><?= $staff['Password'] ?></div>
                            <div class="user-account-divider"></div>
                            <div><?= $staff['Type'] ?></div>
                            <div class="user-account-divider"></div>
                            <div class="delete-btn <?= $staff['Username'] === 'admin' ? "disabled" : "" ?>"
                                onclick="deleteStaff(<?= $staff['AdminID'] ?>)"
                            >
                                <img src="/BMS/public/img/icons/trash.svg" alt=""> Delete
                            </div>
                        </div>
                    <?php } ?>
                    <div class="user-account no-req no-staff" onclick="window.location.href = '/BMS/admin/staffs/new.php'">
                        <div class="pay-req-acc">+</div>
                    </div>
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

    function deleteStaff(id) {
        if (confirm("Do you want to delete this staff?")) {
            window.location.href = `/BMS/admin/staffs/delete.php?id=${id}&token=<?= $_SESSION['token'] ?>`;
        }
    }
</script>
<?php
include 'footer.php';
?>