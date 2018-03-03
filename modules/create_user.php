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

	$q = "SELECT * FROM users WHERE Username='$username'";
	$run_q = $db->query($q);

	if(mysqli_num_rows($run_q) === 0) {
		$Cq = "INSERT INTO users (Username,Password,Fullname,Email,Role,SecuKy) VALUES ('$username','$passMd5','$fullname','$email', '$role', '$pass')";
		$runCq = $db->query($Cq) or die(mysqli_error($db));

		echo json_encode(['status' => 'ok','message' => 'User created successfully!']);

	} else {
		echo json_encode(['status' => 'error', 'message' => 'User already exists!']);
	}

}