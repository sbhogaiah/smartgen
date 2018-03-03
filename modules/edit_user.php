<?php

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';

if (isset($_POST['username'])) {
	
	$username = $_POST['username'];
	$pass = $_POST['pass'];
	$passMd5 = md5($_POST['pass']);
	$fullname = $_POST['fullname'];
	$email = $_POST['email'];
	$role = $_POST['role'];

	$Cq = '';

	if($pass) {
		$Cq = "UPDATE users SET Password='$passMd5',Email='$email',Fullname='$fullname',Role='$role', SecuKy='$pass' WHERE Username='$username'";
	} else {
		$Cq = "UPDATE users SET Email='$email',Fullname='$fullname',Role='$role' WHERE Username='$username'";
	}
	
	$runCq = $db->query($Cq) or die(mysqli_error($db));

	echo json_encode(['status' => 'ok','message' => 'User updated successfully!']);
}