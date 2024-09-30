<?php
$hostname = "mercenary-oaishazher-a815.e.aivencloud.com";
$database = "defaultdb";
$port = 25845;
$username = "avnadmin";
$password = "";

$con = @mysqli_connect($hostname, $username, $password, $database, $port);
if(mysqli_connect_errno())
{
    echo 'Error in connecting to DB ' . mysqli_connect_error();
    exit;
}
?>