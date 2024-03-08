<?php
session_start();
session_destroy();
if(isset($_COOKIE['remember_user'])) {
    $cookie_name = "remember_user";
    unset($_COOKIE[$cookie_name]);
    setcookie($cookie_name, null, -1, '/');
}
header("Location: login.php");
exit();
?>
