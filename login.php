<?php
session_start();

if(isset($_SESSION["loggedIn"])){
	if($_SESSION["loggedIn"] == TRUE){
		echo "<a href='logout.php'>Log Out</a>";
	}
} else {
	echo "session loggedin not set";
}

?> 



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Review login</title>
	<link rel="stylesheet" href="reviewStyles.css">

</head>
<body>
	


<div class="reFlex">
	<div class="reTopBanner"></div>
	<div class="reCenter">
		<div>
			<div class="reLogoDiv">
				<img src="../assets/img/golden-eggs-logo.png">
			</div>
			<div id="loginAlertHolder"></div>
			<br><br>
			<form class="reviewLoginForm" id="loginForm">
				<input type="text" 		placeholder="username"			id="usernameInput" 		required>
				<br><br>
				<input type="password"  placeholder="password" 			id="passwordInput"		required>
				<br>	<br>	<br>
				<button type="submit">Submit</button>
			</form>
			<br><br><br><br><br>
			<a href="register.php">Register <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
  <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
</svg></a>
		</div>
	</div>
	<div class="reBottomBanner"></div>
</div>




</body>


<script src="jqmin.js"></script>
<script>
	$(document).ready(function(){
	$("#loginForm").submit(function(event){
		event.preventDefault();

		// Get entered data
		var usernameInput = $("#usernameInput").val();
		var passwordInput = $("#passwordInput").val();

		//AJAX request to process
		$.ajax({
			url:"loginProcessor.php",
			method:"POST",
			data:{action:"login", username:usernameInput, password:passwordInput},
			success: function(response){
				// console.log(response);
				$("#loginAlertHolder").html(response.message);
				
				if(response.success){
				// Double check response was a success, for some reason :/
				    setTimeout(function(){
					    window.location.href = "/review/app.php";
				    }, 1000);
				}
				
			},
			error: function(){
				$("#loginAlertHolder").html(response.message);
			}
		})
	})
})

console.log("register script loaded!");

</script>



</html>
