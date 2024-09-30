<?php
include '../session_check.php';
include 'layout.php';
include '../sidebar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['del'])) {
        $sql = "DELETE FROM Accounts WHERE UserID = " . $_SESSION['UserID'];
        $result = mysqli_query($con, $sql);
        $sql = "DELETE FROM Users WHERE UserID = " . $_SESSION['UserID'];
        $result = mysqli_query($con, $sql);
        if ($result) {
            header("Location: /BMS/client/auth/logout.php");
            exit;
        }
    }
}

?>
    <div class="content-wrapper">
        <div class="back-btn-div">
            <div class="back-btn" onclick="location.href='../dashboard.php'">
                <img src="../../public/img/icons/back.svg" alt="back"/>
            </div>
        </div>
        <hr/>
        <div class="settings-container">
            <h1>User Details</h1>
            <hr>
            <form class="set-grid" method="POST">
                <label for="user-name">Username</label>
                <input type="text" id="user-name" name="username"
                       value="<?php echo $_SESSION['Username']; ?>" <?= isset($_POST['uEdit']) ? "" : "readonly" ?>>
            </form>

            <form class="set-grid" method="POST">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       value="<?php echo $_SESSION['Email']; ?>" <?= isset($_POST['eEdit']) ? "" : "readonly" ?>>
            </form>

            <form class="set-grid" method="POST">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone"
                       value="<?php echo $_SESSION['Phone']; ?>" <?= isset($_POST['eEdit']) ? "" : "readonly" ?>>
            </form>

            <form class="set-grid" method="POST">
                <label for="dob">Date of Birth</label>
                <input type="text" id="dob" name="dob"
                       value="<?php echo $_SESSION['DOB']; ?>" <?= isset($_POST['eEdit']) ? "" : "readonly" ?>>
            </form>

            <form class="set-grid" method="POST" onsubmit="return false">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       value="<?php echo $_SESSION['Password']; ?>" readonly>
                <button class="" id="passEye"></button>
            </form>

            <hr>

            <form class="set-grid" method="POST" name="deleteAccount">
                <label for="del">Delete Account</label>
                <input type="text" id="del" name="del" value="Delete your account" readonly>
                <button class="delete"
                        name="delete"></button>
            </form>
        </div>
    </div>

    <script>
        const passEye = document.getElementById("passEye")
        const password = document.getElementById("password")
        document.deleteAccount.onsubmit = (e) => {
            e.preventDefault()
            let confirmDelete = confirm("Are you sure you want to delete your account?")
            if (confirmDelete) {
                document.deleteAccount.submit()
            }
        }

        passEye.onclick = () => {
            passEye.classList.toggle('hide')
            if (password.type === "password") {
                password.type = "text"
            } else {
                password.type = "password"
            }
        }
    </script>

<?php
include '../footer.php';
?>