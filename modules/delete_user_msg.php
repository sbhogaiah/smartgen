<?php

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';

if(isset($_POST['user_name'])){
	
	$username = $_POST['user_name']; 

	$delete = "DELETE FROM usermsg WHERE Name='$username'"; 
	$run_delete = mysqli_query($db,$delete); 

	echo json_encode(['status' => 'ok','$username message' => 'User deleted successfully!']);

}