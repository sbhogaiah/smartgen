<?php

session_start();

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';


if(isset($_POST['serial']) && isset($_POST['model'])) {

	$sn = trim($_POST['serial']);
	$mid = trim($_POST['model']);
	$operator = $_SESSION['username'];
		
	$insertProduct = "INSERT INTO products (SerialNumber,ModelId,CreatedAt ) VALUES ('$sn', '$mid', NOW())";
	$run1 = mysqli_query($db, $insertProduct) or die(mysqli_error($db));

	$insertProductTS = "INSERT INTO timestamps (SerialNumber,ProdOut,ProdOutOpId,Stage) VALUES ('$sn',(NOW()),'$operator',1)";
	$run2 = mysqli_query($db, $insertProductTS) or die(mysqli_error($db));

	echo json_encode(['message' => "Product record created successfully!"]);

}
