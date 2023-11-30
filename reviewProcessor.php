<?php
require("db.php");
$currentDate = date('Y-m-d');

if(isset($_POST["action"])){
	$action = $_POST["action"];
	if($action == "userMakingReview"){
		$reviewID = $_POST["reviewIDPHP"];
		$reviewBody = $_POST["reviewBodyPHP"];

		// Check if review ID excists
		$checkForReviewIDExistSQL = "SELECT u_link_id, email, name, sent_by_agent_id FROM sent_to_user WHERE u_link_id=?";
		$checkForReviewIDExistSTMT = $conn->prepare($checkForReviewIDExistSQL);
		$checkForReviewIDExistSTMT->bind_param("s", $reviewID);
		$checkForReviewIDExistSTMT->execute();
		$checkForUserReviewIdExistResult = $checkForReviewIDExistSTMT->get_result();

		if($checkForUserReviewIdExistResult->num_rows == 1){
			$rowData = $checkForUserReviewIdExistResult->fetch_assoc();
			$clientName = $rowData['name'];
			$clientEmail = $rowData['email'];
			$agentID = $rowData['sent_by_agent_id'];
			$checkForReviewIDExistSTMT->close();

			$newPendingReviewSQL = "INSERT INTO reviews_for_review (agent_id_sent_by, review_by_name, review_by_email, review_creation_date, review_body) VALUES (?,?,?,?,?)";
			$pendingReviewSTMT = $conn->prepare($newPendingReviewSQL);
			$pendingReviewSTMT->bind_param("sssss", $agentID, $clientName, $clientEmail, $currentDate, $reviewBody);
			if($pendingReviewSTMT->execute()){
				$deleteUIDLinkSQL = "DELETE FROM sent_to_user WHERE u_link_id=?";
				$deleteUIDLinkSTMT = $conn->prepare($deleteUIDLinkSQL);
				$deleteUIDLinkSTMT->bind_param("s", $reviewID);
				$reviewResponse = ["success" => FALSE, "message" => "Something went horribly wrong 1 :("];

				if($deleteUIDLinkSTMT->execute()){
					$reviewResponse = ["success" => TRUE, "message" => "Thank you for your review!"];
				} else {
					$reviewResponse = ["success" => FALSE, "message" => "Something went horribly wrong 11 :("];
				}

			}
		}
		if($checkForUserReviewIdExistResult->num_rows == 0){
			$reviewResponse = ["success" => FALSE, "message" => "Something went horribly wrong 2 :("];
		}

		// SEND EMAIL
		// mail();


	} else {
		$reviewResponse = ["success" => FALSE, "message" => "Something went horribly wrong 4 :("];
	}
} else {
	$reviewResponse = ["success" => FALSE, "message" => "Something went horribly wrong 5 :("];
}
header('Content-Type: application/json');
echo json_encode($reviewResponse);





?>