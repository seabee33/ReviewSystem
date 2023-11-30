<?php
session_start();
if(!isset($_SESSION["loggedIn"])){
	header("Location: /review/login.php");
	exit();
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>User Management</title>
	<link rel="stylesheet" href="reviewStyles.css">
	<style>
		td{
			padding:5px;
		}
	</style>
</head>
<body>

<div class="reTopBanner2">
	<img class="reBannerLogo" src="../assets/img/golden-eggs-logo.png">
	<div class="reBannerRight reBannerText">
	<?php
	if(isset($_SESSION["loggedIn"])){
		if($_SESSION["loggedIn"] == TRUE){
			$account_id = $_SESSION['account_id'];
			$accountName = $_SESSION["account_name"];
			
			echo "<span>Welcome $accountName &nbsp;&nbsp;&nbsp;</span> <a class='logOutButton' href='logout.php'>Log Out</a>";
		}
	}
	?>
	</div> 
</div>


<br><br><br>




<div class='reContent'>

	<p>Click <a href="app.php">HERE</a> to go back</p>
	<br><br>

	<div class="alertBox" id='alertBox'></div>

	<form class="" id='sendReviewForm'>
		<p class="reParagraph">Add New User</p>
		<br><br>
		<input class="reInput" type="text" id='newUser' placeholder='First Name'>
		<br><br>
		<input class="reInput" type="text" id='newUserName' placeholder='username'>
	<br><br>
		<button class="reButton" type='submit'> Add </button>
	</form>
	<br><br><br><br>
</div>





<div class="reContent">
<p class="reParagraph">Registered Users</p>
<table class="reTable">
	<tr>
		<th>User Name</th>
		<th>First name</th>
		<th>Password</th>
		<th>Action</th>
	</tr>
<?php
	require("db.php");
	$pendingReviewTableSQL = "SELECT id, username, account_name, password FROM users ORDER BY id asc";
	$pendingReviewTableSTMT = $conn->prepare($pendingReviewTableSQL);
	if($pendingReviewTableSTMT->execute()){
		
		$tableData = $pendingReviewTableSTMT->get_result();

		while($rowData = $tableData->fetch_assoc()){
			$username = $rowData['username'];
			$usersFirstName = $rowData['account_name'];
			$userpassword = $rowData['password'];
			if($userpassword == NULL){
				$userpassword = "not set";
			} else {
				$userpassword = "already set";
			}
			$userID = $rowData['id'];
				
			echo "
			<tr>
				<td>$username</td>
				<td>$usersFirstName</td>
				<td>$userpassword</td>
				<td> <a href='userActions?action=1&userID=userID=$userID'>Delete</a> / <a href='userActions?action=2&userID=$userID'>Reset Password<a/>
			</tr>
			";
		}

		// Close the statement only if it was successfully created and executed
		if ($pendingReviewTableSTMT) {
			$pendingReviewTableSTMT->close();
		}
	}

?>
</table>
</div>

<br><br><br><br>



<br><br><br><br><br><br><br><br>

<div class="reBottomBanner"></div>

</body>


</html>