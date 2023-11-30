



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="reviewStyles.css">
</head>
<body>
	



<div class="reFlex">
	<div class="reTopBanner">
		<?php
			session_start();

			if(isset($_SESSION["loggedIn"])){
				echo "<span class='reWhite'>Already Logged In, click <a class='reSLink' href='app.php'> HERE </a> to go to app</span>";
			} else {
				echo "<span class='logOutButton'>Not Logged In, click <a class='reSLink' href='login.php'>HERE</a> to log in</span>";
			}

		?>
	</div>
	<div class="reCenter">

		<div>
			<div class="reLogoDiv">
				<img src="../assets/img/golden-eggs-logo.png">
			</div>

			<div id="registerAlertHolder"></div>
			<br><br>
			<form action="" id="registerForm">
				<input type="text" 		placeholder="username"			id="usernameInput" 		required>
				<br><br>
				<input type="password"  placeholder="password" 			id="passwordInput"		required>
				<br>	<br>	<br>
				<button type="submit">Submit</button>
			</form>
		</div>

		<div class="reBottomBanner"></div>
	</div>
</div>





</body>


<script src="jqmin.js"></script>
<script>
	$(document).ready(function(){
	$("#registerForm").submit(function(event){
		event.preventDefault();

		// Get entered data
		var usernameInput = $("#usernameInput").val();
		var passwordInput = $("#passwordInput").val();

		//AJAX request to process
		$.ajax({
			url:"registerProcessor.php",
			method:"POST",
			data:{action:"register", username:usernameInput, password:passwordInput},
			success: function(response){
				// console.log(response);
				$("#registerAlertHolder").html(response.message);
			},
			error: function(){
				// console.log("AJAX Error");
				$("#registerAlertHolder").html(":( Something went horribly wrong");
			}
		})
	})
})

console.log("register script loaded!");

</script>



</html>