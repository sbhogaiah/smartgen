<?php
session_start();

require_once '../../includes/bootstrap.php'; 
require_once '../../includes/dbconnect.php';

$username = $_SESSION['username'];

$check_query = "SELECT * FROM testeractivity WHERE Tester = '$username'";
$run_check_query = mysqli_query($db, $check_query);

if(mysqli_num_rows($run_check_query) > 0) {

	$data = mysqli_fetch_assoc($run_check_query);

	if($data['Status'] != 'idle') {

		$testId = $data['TestId'];
		// get tester from products
		$get_tester_q = "SELECT TesterNames FROM tests WHERE TestId = '$testId'";
		$run_get_tester_q = mysqli_query($db, $get_tester_q);
		$tester = mysqli_fetch_assoc($run_get_tester_q);
		
		$data2 = array_merge($data, $tester);

		echo json_encode(['data' => $data2]);
	} else {
		echo json_encode(['message' => 'idle']);
	}

}else {
		echo json_encode(['message' => 'idle']);
}
