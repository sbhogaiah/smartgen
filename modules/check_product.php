<?php

require_once '../includes/bootstrap.php';
require_once '../includes/dbconnect.php';

if (isset($_POST['model']) && isset($_POST['serial'])) {
	
	$sn = trim($_POST['serial']);
	$mid = trim($_POST['model']);

	$products_query = "SELECT * FROM products WHERE SerialNumber = '$sn'";
	$run_products_query = mysqli_query($db, $products_query);

	$stage_query = "SELECT Stage FROM timestamps WHERE SerialNumber = '$sn'";
	$run_stage_query = mysqli_query($db,$stage_query); 

	if(mysqli_num_rows($run_products_query) > 0) {

		$products_data = mysqli_fetch_assoc($run_products_query);	
		$stage_data = mysqli_fetch_assoc($run_stage_query);	
		$data = array_merge($products_data, $stage_data);

		if(strcasecmp($products_data['ModelId'], $mid) == 0) {	
			echo json_encode(['message' => 'Product details found!','data' => $data]);
		} else {
			echo json_encode(['error' => 'Serial Number and Model ID do not match!']);
		}

	} else {

		$model_query = "SELECT * FROM cycles WHERE ModelId = '$mid'";
		$run_model_query = mysqli_query($db,$model_query); 

		if(mysqli_num_rows($run_model_query) <= 0) {
			echo json_encode(['error' => 'No such model!']);
		} else {
			echo json_encode(['message' => 'Product not found!']);
		}
	}

}