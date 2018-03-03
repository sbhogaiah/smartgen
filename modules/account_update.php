<?php

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';

if(isset($_POST['user_pass']) && isset($_POST['fullname'])) {

	$user_id = $_POST['user_id'];
	$name = $_POST['fullname'];
	$password = $_POST['user_pass'];
	$password = mysqli_real_escape_string($db,$password);
	
	$q = "UPDATE users SET Fullname='$name', Password=md5('$password'), SecuKy='$password' WHERE UserID='$user_id'";
	$run_q = mysqli_query($db, $q) or die('Cannot update user info! Try again..');

	if($run_q) {
		session_start();
		$_SESSION['fullname'] = $name;
		echo json_encode(['status' => 'ok', 'message' => 'User details updated successfully!']);
	}

}
