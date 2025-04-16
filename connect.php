<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "asm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ( !$conn ) {
    die("ket noi khong thanh cong". mysql_connect_error());
}

?>