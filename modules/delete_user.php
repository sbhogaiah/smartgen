<?php

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';

if(isset($_POST['username'])){
	
	$username = $_POST['username']; 

	$delete = "DELETE FROM users WHERE Username='$username'"; 
	$run_delete = mysqli_query($db,$delete); 

	echo json_encode(['status' => 'ok','message' => 'User deleted successfully!']);

}