<?php
require("db.php");

$registerMsgUserNotFound = "Username not found, admin must add new user";
$registerMsgSomethingWentWrong = "Something went wrong and I don't know why :(";
$registerMsgPasswordSet = "Password set successfully, redirecting to log in page...";
$registerMsgPasswordAlreadySet = "Password already set, please log in";

if(isset($_POST["action"])){
	$action = $_POST["action"];
	if($action == "register"){
		$username = $_POST["username"];
		$rawPassword = $_POST["password"];

		$securePassword = password_hash($rawPassword, PASSWORD_DEFAULT);

		// Check if user exists and password is NULL
		$checkForUserExistSQL = "SELECT username, password FROM users WHERE username=?";
		$checkForUserExistSTMT = $conn->prepare($checkForUserExistSQL);
		$checkForUserExistSTMT->bind_param("s", $username);
		$checkForUserExistSTMT->execute();
		$checkForUserExistResult = $checkForUserExistSTMT->get_result();

		if($checkForUserExistResult->num_rows == 1){
			// User already exists and password is null
			$rowData = $checkForUserExistResult->fetch_assoc();
			$storedPassword = $rowData["password"];
			if(is_null($storedPassword)){
				$addNewUserSQL = "UPDATE users set password=? WHERE username=?";
				$addNewUserSTMT = $conn->prepare($addNewUserSQL);
				$addNewUserSTMT->bind_param("ss", $securePassword, $username);
				$addNewUserSTMT->execute();
				$registerResponse = ["success" => true, "message" => $registerMsgPasswordSet];
			} else {
				$registerResponse = ["success" => true, "message" => $registerMsgPasswordAlreadySet];
			}
		} else {
			// username does NOT exist
			$registerResponse = ["success" => true, "message" => $registerMsgUserNotFound];
		}
	}
} else {
	$registerResponse = ["success" => false, "message" => $registerMsgSomethingWentWrong];
}
header('Content-Type: application/json');
echo json_encode($registerResponse);





?>