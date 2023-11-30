<?php
require("db.php");

$loginMsgUserNotFound = "Username not found, admin must add new user";
$loginMsgSomethingWentWrong = "Something went wrong and I don't know why :(";
$loginMsgPasswordNotSet = "Password not set yet, please register first";
$loginMsgPasswordCorrect = "Correct login, redirecting to app now...";
$loginMsgPasswordWrong = "Incorrect password, please try again";

if(isset($_POST["action"])){
	$action = $_POST["action"];
	if($action == "login"){
		$username = $_POST["username"];
		$rawPassword = $_POST["password"];

		// Check if username exists
		$checkForUserExistSQL = "SELECT id, username, password, account_name FROM users WHERE username=?";
		$checkForUserExistSTMT = $conn->prepare($checkForUserExistSQL);
		$checkForUserExistSTMT->bind_param("s", $username);
		$checkForUserExistSTMT->execute();
		$checkForUserExistResult = $checkForUserExistSTMT->get_result();

		if($checkForUserExistResult->num_rows == 1){
			$rowData = $checkForUserExistResult->fetch_assoc();
			$storedPassword = $rowData["password"];
			if(is_null($storedPassword)){
				$loginResponse = ["success" => FALSE, "message" => $loginMsgPasswordNotSet];
			} elseif(password_verify($rawPassword, $storedPassword)) {
				// Correct password entered
				$_SESSION["loggedIn"] = TRUE;
				$_SESSION["account_name"] = $rowData['account_name'];
				$_SESSION["account_id"] = $rowData['id'];
				$loginResponse = ["success" => TRUE, "message" => $loginMsgPasswordCorrect];
			} else {
				$loginResponse = ["success" => FALSE, "message" => $loginMsgPasswordWrong];
			}
		} else {
			// username does NOT exist
			$loginResponse = ["success" => FALSE, "message" => $loginMsgUserNotFound];
		}
	}
} else {
	$loginResponse = ["success" => FALSE, "message" => $loginMsgSomethingWentWrong];
}
header('Content-Type: application/json');
echo json_encode($loginResponse);





?>