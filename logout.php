<?php
session_start();
$_SESSION[] = [];
session_destroy();
header("Location: /review/login.php");
exit();


?>