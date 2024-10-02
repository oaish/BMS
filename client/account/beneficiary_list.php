<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';
require_once('../../database/functions.php');

$MainAccID = $_SESSION['Acc']['AccountNo'];
$allBeneficiary = getAllBeneficiary($MainAccID);
?>

<div class="content-wrapper">
    <div class="back-btn-div">
        <div class="back-btn" onclick="location.href='./'">
            <img src="../../public/img/icons/back.svg" alt="back" />
        </div>
    </div>
    <hr />

    <div class="beneficiary-list-base">
        <div class="beneficiary-list">
            <p style="color:#fff; font-size: 25px; margin-bottom: 5px;">Beneficiary List</p>
            <hr  style="font-size: 25px; margin-bottom: 5px;">
            <table style="color:#fff; width: 100%;">
                <tr style="text-align: left; background-color: white; color: #333;">
                    <th style="width: 30px;">#</th>
                    <th>Name</th>
                    <th>Acc No</th>
                </tr>
                <?php if (empty($allBeneficiary)) { ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">No Beneficiaries </td>
                    </tr>
                <?php } ?>

                <?php
                $sr = 1;
                foreach ($allBeneficiary as $key => $val) {
                    $sql = "SELECT u.Username FROM Accounts acc
                                INNER JOIN Users u
                                ON u.UserID = acc.UserID
                                WHERE acc.AccountNo = '" . $val . "'";
                    $result = mysqli_query($con, $sql);
                    if ($result && $row = mysqli_fetch_array($result)) {
                        $username = $row['Username'];
                        $acc_no = $val;
                    }
                ?>
                    <tr>
                        <td style="width: 30px;"><?= $sr; ?></td>
                        <td><?= $username; ?></td>
                        <td><?= $acc_no; ?></td>
                    </tr>
                <?php
                    $sr++;
                }
                ?>
            </table>
        </div>
    </div>
</div>
<?php
include '../footer.php';
?>