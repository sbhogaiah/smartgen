<?php

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';

$q = "SELECT * FROM cycles";
$run_q = $db->query($q);

if(mysqli_num_rows($run_q) > 0) {
	$data = $run_q->fetch_all(MYSQLI_ASSOC);

	echo json_encode(['data' => $data]);
}