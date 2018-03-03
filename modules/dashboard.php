<?php

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';

$dataCol = [];

$q = "SELECT count(Stage) FROM timestamps where DATE(ProdOut) = DATE(CURDATE()) AND MONTH(ProdOut) = MONTH(DATE(CURDATE())) AND YEAR(ProdOut) = YEAR(DATE(CURDATE()))";
$run_q = $db->query($q) or die(mysqli_error($db));
if(mysqli_num_rows($run_q) > 0) {
	$prodToday = $run_q->fetch_array()[0];
	array_push($dataCol, $prodToday);
}

$q1 = "SELECT count(Stage) FROM timestamps where MONTH(ProdOut) = MONTH(DATE(CURDATE())) AND YEAR(ProdOut) = YEAR(DATE(CURDATE()))";
$run_q1 = $db->query($q1) or die(mysqli_error($db));
if(mysqli_num_rows($run_q1) > 0) {
	$prodMonth = $run_q1->fetch_array()[0];
	array_push($dataCol, $prodMonth);
}

$q2 = "SELECT count(Stage) FROM timestamps where Stage > 9 AND DATE(FctOut) = DATE(CURDATE()) AND MONTH(FctOut) = MONTH(DATE(CURDATE())) AND YEAR(FctOut) = YEAR(DATE(CURDATE()))";
$run_q2 = $db->query($q2) or die(mysqli_error($db));
if(mysqli_num_rows($run_q2) > 0) {
	$testedToday = $run_q2->fetch_array()[0];
	array_push($dataCol, $testedToday);
}

$q3 = "SELECT count(Stage) FROM timestamps where Stage > 9 AND MONTH(FctOut) = MONTH(DATE(CURDATE())) AND YEAR(FctOut) = YEAR(DATE(CURDATE()))";
$run_q3 = $db->query($q3) or die(mysqli_error($db));
if(mysqli_num_rows($run_q3) > 0) {
	$testedMonth = $run_q3->fetch_array()[0];
	array_push($dataCol, $testedMonth);
}

$q4 = "SELECT count(Stage) FROM timestamps where Stage > 11 AND DATE(FinishOut) = DATE(CURDATE()) AND MONTH(FinishOut) = MONTH(DATE(CURDATE())) AND YEAR(FinishOut) = YEAR(DATE(CURDATE()))";
$run_q4 = $db->query($q4) or die(mysqli_error($db));
if(mysqli_num_rows($run_q4) > 0) {
	$data = $run_q4->fetch_array();
	$finishedToday = $data[0];
	array_push($dataCol, $finishedToday);
}

$q5 = "SELECT count(Stage) FROM timestamps where Stage > 11 AND MONTH(FinishOut) = MONTH(DATE(CURDATE())) AND YEAR(FinishOut) = YEAR(DATE(CURDATE()))";
$run_q5 = $db->query($q5) or die(mysqli_error($db));
if(mysqli_num_rows($run_q5) > 0) {
	$data = $run_q5->fetch_array();
	$finishedMonth = $data[0];
	array_push($dataCol, $finishedMonth);
}

$q6 = "SELECT count(Stage) FROM timestamps where Stage > 13 AND DATE(PackOut) = CURDATE() AND MONTH(PackOut) = MONTH(CURRENT_DATE()) AND YEAR(PackOut) = YEAR(CURRENT_DATE())";
$run_q6 = $db->query($q6) or die(mysqli_error($db));
if(mysqli_num_rows($run_q6) > 0) {
	$data = $run_q6->fetch_array();
	$packagedToday = $data[0];
	array_push($dataCol, $packagedToday);
}

$q7 = "SELECT count(Stage) FROM timestamps where Stage > 13 AND MONTH(PackOut) = MONTH(DATE(CURDATE())) AND YEAR(PackOut) = YEAR(DATE(CURDATE()))";
$run_q7 = $db->query($q7) or die(mysqli_error($db));
if(mysqli_num_rows($run_q7) > 0) {
	$data = $run_q7->fetch_array();
	$packagedMonth = $data[0];
	array_push($dataCol, $packagedMonth);
}

// hipot
$q8 = "SELECT count(Stage) FROM timestamps where Stage >= 4 AND DATE(HipotOut) = DATE(CURDATE()) AND MONTH(HipotOut) = MONTH(DATE(CURDATE())) AND YEAR(HipotOut) = YEAR(DATE(CURDATE()))";
$run_q8 = $db->query($q8) or die(mysqli_error($db));
if(mysqli_num_rows($run_q8) > 0) {
	$data = $run_q8->fetch_array();
	$hipotToday = $data[0];
	array_push($dataCol, $hipotToday);
}

$q9 = "SELECT SUM(FailCount) FROM tests where Stage = 'hipot' AND DATE(EndTest) = DATE(CURDATE()) AND MONTH(EndTest) = MONTH(DATE(CURDATE())) AND YEAR(EndTest) = YEAR(DATE(CURDATE())) GROUP BY Stage";
$run_q9 = $db->query($q9) or die(mysqli_error($db));
if(mysqli_num_rows($run_q9) > 0) {
	$data = $run_q9->fetch_array();
	$hipotFailToday = $data[0];
	array_push($dataCol, $hipotFailToday);
}

$q10 = "SELECT count(Stage) FROM timestamps where Stage >= 4 AND MONTH(HipotOut) = MONTH(DATE(CURDATE())) AND YEAR(HipotOut) = YEAR(DATE(CURDATE()))";
$run_q10 = $db->query($q10) or die(mysqli_error($db));
if(mysqli_num_rows($run_q10) > 0) {
	$data = $run_q10->fetch_array();
	$hipotMonth = $data[0];
	array_push($dataCol, $hipotMonth);
}

$q11 = "SELECT SUM(FailCount) FROM tests where Stage = 'hipot' AND MONTH(EndTest) = MONTH(DATE(CURDATE())) AND YEAR(EndTest) = YEAR(DATE(CURDATE())) GROUP BY Stage";
$run_q11 = $db->query($q11) or die(mysqli_error($db));
if(mysqli_num_rows($run_q11) > 0) {
	$data = $run_q11->fetch_array();
	$hipotFailMonth = $data[0];
	array_push($dataCol, $hipotFailMonth);
}

// low power
$q12 = "SELECT count(Stage) FROM timestamps where Stage >= 6 AND DATE(LowPowerOut) = DATE(CURDATE()) AND MONTH(LowPowerOut) = MONTH(DATE(CURDATE())) AND YEAR(LowPowerOut) = YEAR(DATE(CURDATE()))";
$run_q12 = $db->query($q12) or die(mysqli_error($db));
if(mysqli_num_rows($run_q12) > 0) {
	$data = $run_q12->fetch_array();
	$lowPowerToday = $data[0];
	array_push($dataCol, $lowPowerToday);
}

$q13 = "SELECT SUM(FailCount) FROM tests where Stage = 'low power' AND DATE(EndTest) = DATE(CURDATE()) AND MONTH(EndTest) = MONTH(DATE(CURDATE())) AND YEAR(EndTest) = YEAR(DATE(CURDATE())) GROUP BY Stage";
$run_q13 = $db->query($q13) or die(mysqli_error($db));
if(mysqli_num_rows($run_q13) > 0) {
	$data = $run_q13->fetch_array();
	$lowPowerFailToday = $data[0];
	array_push($dataCol, $lowPowerFailToday);
}

$q14 = "SELECT count(Stage) FROM timestamps where Stage >= 6 AND MONTH(LowPowerOut) = MONTH(DATE(CURDATE())) AND YEAR(LowPowerOut) = YEAR(DATE(CURDATE()))";
$run_q14 = $db->query($q14) or die(mysqli_error($db));
if(mysqli_num_rows($run_q14) > 0) {
	$data = $run_q14->fetch_array();
	$lowPowerMonth = $data[0];
	array_push($dataCol, $lowPowerMonth);
}

$q15 = "SELECT SUM(FailCount) FROM tests where Stage = 'low power' AND MONTH(EndTest) = MONTH(DATE(CURDATE())) AND YEAR(EndTest) = YEAR(DATE(CURDATE())) GROUP BY Stage";
$run_q15 = $db->query($q15) or die(mysqli_error($db));
if(mysqli_num_rows($run_q15) > 0) {
	$data = $run_q15->fetch_array();
	$lowPowerFailMonth = $data[0];
	array_push($dataCol, $lowPowerFailMonth);
}

// burn in
$q16 = "SELECT count(Stage) FROM timestamps where Stage >= 8 AND DATE(BurnInOut) = DATE(CURDATE()) AND MONTH(BurnInOut) = MONTH(DATE(CURDATE())) AND YEAR(BurnInOut) = YEAR(DATE(CURDATE()))";
$run_q16 = $db->query($q16) or die(mysqli_error($db));
if(mysqli_num_rows($run_q16) > 0) {
	$data = $run_q16->fetch_array();
	$burnInToday = $data[0];
	array_push($dataCol, $burnInToday);
}

$q17 = "SELECT SUM(FailCount) FROM tests where Stage = 'burn in' AND DATE(EndTest) = CURDATE() AND MONTH(EndTest) = MONTH(CURRENT_DATE()) AND YEAR(EndTest) = YEAR(CURRENT_DATE()) GROUP BY Stage";
$run_q17 = $db->query($q17) or die(mysqli_error($db));
if(mysqli_num_rows($run_q17) > 0) {
	$data = $run_q17->fetch_array();
	$burnInFailToday = $data[0];
	array_push($dataCol, $burnInFailToday);
}

$q18 = "SELECT count(Stage) FROM timestamps where Stage >= 8 AND MONTH(BurnInOut) = MONTH(DATE(CURDATE())) AND YEAR(BurnInOut) = YEAR(DATE(CURDATE()))";
$run_q18 = $db->query($q18) or die(mysqli_error($db));
if(mysqli_num_rows($run_q18) > 0) {
	$data = $run_q18->fetch_array();
	$burnInMonth = $data[0];
	array_push($dataCol, $burnInMonth);
}

$q19 = "SELECT SUM(FailCount) FROM tests where Stage = 'burn in' AND MONTH(EndTest) = MONTH(DATE(CURDATE())) AND YEAR(EndTest) = YEAR(DATE(CURDATE())) GROUP BY Stage";
$run_q19 = $db->query($q19) or die(mysqli_error($db));
if(mysqli_num_rows($run_q19) > 0) {
	$data = $run_q19->fetch_array();
	$burnInFailMonth = $data[0];
	array_push($dataCol, $burnInFailMonth);
}


// fct
$q20 = "SELECT count(Stage) FROM timestamps where Stage >= 10 AND DATE(FctOut) = DATE(CURDATE()) AND MONTH(FctOut) = MONTH(DATE(CURDATE())) AND YEAR(FctOut) = YEAR(DATE(CURDATE()))";
$run_q20 = $db->query($q20) or die(mysqli_error($db));
if(mysqli_num_rows($run_q20) > 0) {
	$data = $run_q20->fetch_array();
	$fctToday = $data[0];
	array_push($dataCol, $fctToday);
}

$q21 = "SELECT SUM(FailCount) FROM tests where Stage = 'fct' AND DATE(EndTest) = DATE(CURDATE()) AND MONTH(EndTest) = MONTH(DATE(CURDATE())) AND YEAR(EndTest) = YEAR(DATE(CURDATE())) GROUP BY Stage";
$run_q21 = $db->query($q21) or die(mysqli_error($db));
if(mysqli_num_rows($run_q21) > 0) {
	$data = $run_q21->fetch_array();
	$fctFailToday = $data[0];
	array_push($dataCol, $fctFailToday);
}

$q22 = "SELECT count(Stage) FROM timestamps where Stage >= 10 AND MONTH(FctOut) = MONTH(DATE(CURDATE())) AND YEAR(FctOut) = YEAR(DATE(CURDATE()))";
$run_q22 = $db->query($q22) or die(mysqli_error($db));
if(mysqli_num_rows($run_q22) > 0) {
	$data = $run_q22->fetch_array();
	$fctMonth = $data[0];
	array_push($dataCol, $fctMonth);
}

$q23 = "SELECT SUM(FailCount) FROM tests where Stage = 'fct' AND DATE(EndTest) = DATE(CURDATE()) AND MONTH(EndTest) = MONTH(DATE(CURDATE())) AND YEAR(EndTest) = YEAR(DATE(CURDATE())) GROUP BY Stage";
$run_q23 = $db->query($q23) or die(mysqli_error($db));
if(mysqli_num_rows($run_q23) > 0) {
	$data = $run_q23->fetch_array();
	$fctFailMonth = $data[0];
	array_push($dataCol, $fctFailMonth);
}

echo json_encode($dataCol);