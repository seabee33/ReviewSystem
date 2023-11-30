<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$test = "it works!";

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'admin');
define('DB_PASSWORD', 'VeryLongPassword1!?');
define('DB_NAME', 'goldeneggreviews');

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($conn->connect_error){
	die("OOPSIE DOOPSIE! " . $conn->connect_error);
} else {
	// echo "Database connected successfully<br><br>";
}


?>