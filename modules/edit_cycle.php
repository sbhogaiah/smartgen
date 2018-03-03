<?php

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';

if (isset($_POST['model'])) {
	
	$model = $_POST['model'];
	$hipotCT = hoursToMinutes($_POST['hipot_ct']);
	$lowPowerCT = hoursToMinutes($_POST['lp_ct']);
	$burnInCT = hoursToMinutes($_POST['bi_ct']);
	$fctCT = hoursToMinutes($_POST['fct_ct']);
	$hipotTol = $_POST['hipot_tol'];
	$lowPowerTol = $_POST['lp_tol'];
	$burnInTol = $_POST['bi_tol'];
	$fctTol = $_POST['fct_tol'];

	$Cq = "UPDATE cycles SET HipotCT='$hipotCT',LowPowerCT='$lowPowerCT',BurnInCT='$burnInCT',FctCT='$fctCT',HipotTol='$hipotTol',LowPowerTol='$lowPowerTol',BurnInTol='$burnInTol',FctTol='$fctTol' WHERE ModelId='$model'";
	$runCq = $db->query($Cq) or die(mysqli_error($db));

	echo json_encode(['status' => 'ok','message' => 'Cycle updated successfully!']);
}