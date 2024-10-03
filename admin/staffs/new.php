<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

$type = "";
$username = "";
$password = "";
$confirm_password = "";
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $type = $_POST['type'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Error: Passwords do not match')</script>";
    } else {
        $sql = "SELECT * FROM Staff WHERE Username = '$username'";
        $result = mysqli_query($con, $sql);
        $staff = mysqli_fetch_assoc($result);
        if ($staff) {
            echo "<script>alert('Error: Username already exists')</script>";
        } else {
            $sql = "INSERT INTO Staff VALUES (DEFAULT, '$username', '$password', '$type')";
            $result = mysqli_query($con, $sql);
            header('Location: /BMS/admin/staffs');
            exit;
        }
    }
}
?>
<div class="content-wrapper">
    <div></div>
    <hr />
    <div class="dashboard-container">
        <div class="new-staff-div">
            <form action="" method="POST">
                <label>Add Staff</label>
                <hr>
                <input name="username" type="text" placeholder="Enter Username" pattern="^[\w]{4,}$" value="<?= $username  ?>" required="required" title="Username should be 4 characters long">
                <input name="password" type="text" placeholder="Enter Password" pattern="^[\w]{4,}$" value="<?= $password  ?>" required="required">
                <input name="confirmPassword" type="text" placeholder="Confirm Password" pattern="^[\w]{4,}$" value="<?= $confirm_password  ?>" required="required">
                <select name="type" required>
                    <option value="">Select Type</option>
                    <option value="Admin">Admin</option>
                    <option value="Bank Teller">Bank Teller</option>
                </select>
                <input type="submit" value="Add">
            </form>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>