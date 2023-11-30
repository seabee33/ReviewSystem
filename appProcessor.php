<?php
require("db.php");
if(!isset($_SESSION["loggedIn"])){
	header("Location: /review/login.php");
	exit();
}

if(isset($_POST["action"])){
	$currentDate = date('Y-m-d');
	$action = $_POST["action"];
	if($action == "sendReviewRequest"){
		$clientNameInput = $_POST["clientNameInput"];
		$clientEmailInput = $_POST["clientEmailInput"];
		$agentAccountNameInput = $_POST["agentAccountNameInput"];

		$randomData = bin2hex(random_bytes(16));

		// Check if username exists
		$checkForUserExistSQL = "INSERT INTO sent_to_user (u_link_id, email, name, date_sent, sent_by_agent_id) VALUES (?,?,?,?,?)";
		$checkForUserExistSTMT = $conn->prepare($checkForUserExistSQL);
		$checkForUserExistSTMT->bind_param("ssssi", $randomData, $clientEmailInput, $clientNameInput, $currentDate, $_SESSION['account_id']);
		$checkForUserExistSTMT->execute();
		$checkForUserExistResult = $checkForUserExistSTMT->get_result();

		// SEND EMAIL
		// mail();
		$loginResponse = ["success" => TRUE, "message" => "Email sent to $clientEmailInput, link: http://192.168.0.34:82/review/review.php?reviewID=$randomData"];
	}

} 
if(isset($_GET['action'])){
	$action = $_GET['action'];
	if($action == "approveOrDeleteReview"){
		$reviewAction = htmlspecialchars($_GET["reviewAction"]);
		$reviewDBID = htmlspecialchars($_GET['DBID']);
		if($reviewAction == "approve"){
			$agentApprovedBy = $_SESSION['account_name'];
			$copySQL = "SELECT agent_id_sent_by, review_by_name, review_by_email, review_creation_date, review_body FROM reviews_for_review WHERE id=?";
			$copySTMT = $conn->prepare($copySQL);
			$copySTMT->bind_param("i", $reviewDBID);
			if($copySTMT->execute()){
				$copyResult = $copySTMT->get_result();
				$copyRowData = $copyResult->fetch_assoc();
				$agentSentBy = $copyRowData['agent_id_sent_by'];
				$clientReviewName = $copyRowData['review_by_name'];
				$clientReviewEmail = $copyRowData['review_by_email'];
				$reviewCreationDate = $copyRowData['review_creation_date'];
				$reviewBody = $copyRowData['review_body'];
				$agentApprovedBy = $_SESSION['account_id'];
				
				$approvedReviewSQL = "INSERT INTO approved_reviews (review_for_agent, review_by_name, review_creation_date, review_approved_by_agent_id, review_body) VALUES(?,?,?,?,?)";
				$approvedReviewSTMT = $conn->prepare($approvedReviewSQL);
				$approvedReviewSTMT->bind_param("sssis",$agentSentBy, $clientReviewName, $reviewCreationDate, $agentApprovedBy, $reviewBody);
				if($approvedReviewSTMT->execute()){
					$deletePendingReviewSQL = "DELETE FROM reviews_for_review WHERE id=?";
					$deletePendingReviewSTMT = $conn->prepare($deletePendingReviewSQL);
					$deletePendingReviewSTMT->bind_param("i", $reviewDBID);
					if($deletePendingReviewSTMT->execute()){
						header("Location: /review/app.php?msg=1");
						exit();
					} else {
						header("Location: /review/app.php?msg=2");
						exit();
					}
				} else {
					header("Location: /review/app.php?msg=3");
					exit();
				}
			} else {
				header("Location: /review/app.php?msg=3");
				exit();
			}
		} 
		if($reviewAction == "delete"){
			$deletePendingReviewSQL = "DELETE FROM reviews_for_review WHERE id=?";
			$deletePendingReviewSTMT = $conn->prepare($deletePendingReviewSQL);
			$deletePendingReviewSTMT->bind_param("i", $reviewDBID);
			if($deletePendingReviewSTMT->execute()){
				header("Location: /review/app.php?msg=5");
				exit();
			}
		}
		
		else {
			header("Location: /review/app.php?msg=4");
			exit();
		}
	}
}

header('Content-Type: application/json');
echo json_encode($loginResponse);





?>