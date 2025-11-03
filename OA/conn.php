<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "db_oa";
$conn = mysqli_connect($server, $username, $password);
mysqli_select_db($conn, $database);
?>
