<?php

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';


if (isset($_POST['user_name'])) {
	
	$user_name = $_POST['user_name'];
	$user_dept = $_POST['user_dept'];
	$user_phone = $_POST['user_phone'];
	$user_email = $_POST['user_email'];
	$user_role_baydown = $_POST['user_role_baydown'];
	$user_role_powerfailure = $_POST['user_role_powerfailure'];
	$user_role_unitfailure = $_POST['user_role_unitfailure'];

	$q = "SELECT * FROM usermsg WHERE Name='$user_name'";	
	$run_q = $db->query($q);

	if(mysqli_num_rows($run_q) === 0) {		
		$Cq = "INSERT INTO usermsg (Name,Department,Phone,Email,BayDown,PowerFailure,UnitFailure) VALUES ('$user_name','$user_dept','$user_phone','$user_email','$user_role_baydown','$user_role_powerfailure', '$user_role_unitfailure') ";
		$runCq = $db->query($Cq) or die(mysqli_error($db));

		echo json_encode(['status' => 'ok','message' => 'User created successfully!']);

	} else {
		echo json_encode(['status' => 'error', 'message' => 'User already exists!']);
	}

}