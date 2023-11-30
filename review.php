<?php
require("db.php");


if(isset($_GET['reviewID'])){
    $reviewID = htmlspecialchars($_GET['reviewID']);

	$userReviewSQL = "SELECT stu.u_link_id, stu.email, stu.name, u.account_name FROM sent_to_user stu JOIN users u ON u.id=stu.sent_by_agent_id WHERE u_link_id=?";
	$userReviewSTMT = $conn->prepare($userReviewSQL);
	$userReviewSTMT->bind_param("s", $reviewID);
	$userReviewSTMT->execute();
	$userReviewResult = $userReviewSTMT->get_result();

	if($userReviewResult->num_rows == 1){
		$rowData = $userReviewResult->fetch_assoc();
		$reviewID = $rowData['u_link_id'];
		$clientName = $rowData['name'];
		$agentSentBy = $rowData['account_name'];
	}
	if($userReviewResult->num_rows == 0){
		header('Location: /review');
		echo ":(";
	}


} else {
    // header('Location: /review');
    // exit();
	echo ":(";
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Review Page</title>
</head>
<body>
	


Hey <?php echo $clientName; ?>, <?php echo $agentSentBy; ?> would love your feedback on your expereince.
	
<div id="reviewAlertHolder">Temp MSG HERE</div>
<br><br>
<form id="reviewForm">
	<textarea style='height:200px; width:400px;' placeholder='Review details' id='reviewBody' required></textarea>
	<input type="hidden" id='reviewUID' value='<?php echo $reviewID; ?>'>
	<br><br>
	<p>Please note your name and review will be public after review by our team</p>
	<button type="submit">Submit</button>
</form>

	
	
<script src="jqmin.js"></script>
<script>
	$(document).ready(function(){
	$("#reviewForm").submit(function(event){
		event.preventDefault();

		// Get entered data
		var reviewID = $("#reviewUID").val();
		var reviewBodyInput = $("#reviewBody").val();

		//AJAX request to process
		$.ajax({
			url:"reviewProcessor.php",
			method:"POST",
			data:{action:"userMakingReview", reviewIDPHP:reviewID, reviewBodyPHP:reviewBodyInput},
			success: function(response){
				// console.log(response);
				$("#reviewAlertHolder").html(response.message);
				
				if(response.success){
				// Double check response was a success, for some reason :/
				    setTimeout(function(){
					    window.location.href = "/review/app.php";
				    }, 1000);
				}
				
			},
			error: function(){
				$("#reviewAlertHolder").html(response.message);
			}
		})
	})
})
</script>
	
	
	
	
	
	
	
	
	
	
</body>
</html>
