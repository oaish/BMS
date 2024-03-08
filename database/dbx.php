<?php
$hostname = 'srv313.hstgr.io';
$username = 'u641503458_BMS';
$password = 'a@A123BMS';
$dbname = 'u641503458_BMS';

$con = @mysqli_connect($hostname, $username, $password, $dbname);
if(mysqli_connect_errno())
{
    echo 'Error in connecting to DB ' . mysqli_connect_error();
    exit;
}
?>