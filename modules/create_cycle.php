<?php

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';
if (isset($_POST['model'])) {
	
	$model = $_POST['model'];
	$family = $_POST['family'];
	$rating = $_POST['rating'];
	$hipotCT = hoursToMinutes($_POST['hipot_ct']);
	$lowPowerCT = hoursToMinutes($_POST['lp_ct']);
	$burnInCT = hoursToMinutes($_POST['bi_ct']);
	$fctCT = hoursToMinutes($_POST['fct_ct']);
	$hipotTol = $_POST['hipot_tol'];
	$lowPowerTol = $_POST['lp_tol'];
	$burnInTol = $_POST['bi_tol'];
	$fctTol = $_POST['fct_tol'];

	$q = "SELECT * FROM cycles WHERE ModelId='$model'";
	$run_q = $db->query($q);

	if(mysqli_num_rows($run_q) === 0) {
		$Cq = "INSERT INTO cycles (ModelId,Family,Rating,HipotCT,LowPowerCT,BurnInCT,FctCT,HipotTol,LowPowerTol,BurnInTol,FctTol) VALUES ('$model','$family','$rating','$hipotCT','$lowPowerCT','$burnInCT','$fctCT','$hipotTol','$lowPowerTol','$burnInTol','$fctTol')";
		$runCq = $db->query($Cq) or die(mysqli_error($db));

		echo json_encode(['status' => 'ok','message' => 'Cycle created successfully!']);

	} else {
		echo json_encode(['status' => 'error', 'message' => 'Model already exists!']);
	}

}