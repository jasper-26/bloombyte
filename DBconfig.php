<?php
$servername = "111.118.214.164";
$username = "mncacjmx_pharma";
$password = "T(lS20-3p4";
$dbname = "mncacjmx_pharma";
$connDB = mysqli_connect($servername, $username, $password, $dbname,3306);

if ($connDB->connect_error) {
    die("Connection failed: " . $connDB->connect_error);
}
?>