<?php  
$hostname = 'localhost';
$username = 'root';
$password = '';
$dbName = 'hotel_booking';

$connect = new mysqli($hostname, $username, $password, $dbName);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}
?>