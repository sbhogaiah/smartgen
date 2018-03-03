<?php

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';

if(isset($_POST['model'])){
	
	$model = $_POST['model']; 

	$delete = "DELETE FROM cycles WHERE ModelId='$model'"; 
	$run_delete = mysqli_query($db,$delete); 

	echo json_encode(['status' => 'ok','message' => 'Cycle deleted successfully!']);

}