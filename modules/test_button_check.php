<?php

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';

if (isset($_GET['type'])) {

	$type = $_GET['type'];
	
	$q = "SELECT system, Status FROM ppi WHERE system LIKE '$type%'";
	$run_q = mysqli_query($db, $q) or die(mysqli_error($db));

	if(mysqli_num_rows($run_q) > 0) {
		$data = $run_q->fetch_all(MYSQLI_ASSOC);

		echo json_encode(['data' => $data]);
	}

}