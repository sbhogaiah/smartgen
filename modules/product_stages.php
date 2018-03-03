<?php

session_start();

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';

if(isset($_POST['serial']) && isset($_POST['model'])) {
	
	$sn = $_POST['serial'];
	$mid = $_POST['model'];
	$stage = $_POST['stage'];
	$operator = $_SESSION['username'];
	$bay = $_SESSION['bay'];

	//sent from production to be received at bay
	if ($stage == '2') {
		$q1 = "UPDATE timestamps SET BayIn=NOW(), BayInOpId='$operator', Stage=2 WHERE SerialNumber='$sn'";
		$run_q1 = $db->query($q1);

		$q2 = "UPDATE products SET Bay='$bay' WHERE SerialNumber='$sn'";
		$run_q2 = $db->query($q2);

		if($run_q1 && $run_q2) {
			echo json_encode(['message' => 'Product received at Bay! Start test...']);
		}
	}

	//tests completed receive at finishing
	if ($stage == '11') {
		$q3 = "UPDATE timestamps SET FinishIn=NOW(), FinishInOpId='$operator', Stage=11 WHERE SerialNumber='$sn'";
		$run_q3 = $db->query($q3);

		if($run_q3) {
			echo json_encode(['message' => 'Product received at finishing!']);
		}
	}

	//Send from finishing
	if ($stage == '12') {
		$q4 = "UPDATE timestamps SET FinishOut=NOW(), FinishOutOpId='$operator', Stage=12 WHERE SerialNumber='$sn'";
		$run_q4 = $db->query($q4);

		if($run_q4) {
			echo json_encode(['message' => 'Product sent from finishing!']);
		}
	}

	//Receive at packaging
	if ($stage == '13') {
		$q5 = "UPDATE timestamps SET PackIn=NOW(), PackInOpId='$operator', Stage=13 WHERE SerialNumber='$sn'";
		$run_q5 = $db->query($q5);

		if($run_q5) {
			echo json_encode(['message' => 'Product received at packaging!']);
		}
	}

	//sent from packaging
	if ($stage == '14') {
		$q6 = "UPDATE timestamps SET PackOut=NOW(), PackOutOpId='$operator', Stage=14 WHERE SerialNumber='$sn'";
		$run_q6 = $db->query($q6);

		if($run_q6) {
			echo json_encode(['message' => 'Product sent from packaging to customer!']);
		}
	}
	
}