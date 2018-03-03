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

	$Cq = '';

	$Cq = "UPDATE usermsg SET Department='$user_dept',Phone='$user_phone',Email='$user_email',BayDown='$user_role_baydown',PowerFailure='$user_role_powerfailure',UnitFailure='$user_role_unitfailure' WHERE Name='$user_name'";
	
	$runCq = $db->query($Cq) or die(mysqli_error($db));

	echo json_encode(['status' => 'ok','message' => 'User updated successfully!']);
}