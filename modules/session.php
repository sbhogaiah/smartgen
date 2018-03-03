<?php
require_once 'includes/bootstrap.php'; 

	session_start();

	// SESSION CHECK ~~~~~~~~~~~
	if(isset($_SESSION['id'])) {
		$session_userid = $_SESSION['id'];
	} else {
		header("Location:".BASE_URL);
	}

	if(isset($_SESSION['username'])) {
		$session_username = $_SESSION['username'];
	}

	if(isset($_SESSION['fullname'])) {
		$session_fullname = $_SESSION['fullname'];
	}

	if(isset($_SESSION['role'])) {
		$session_role = $_SESSION['role'];
	}

	if(isset($_SESSION['bay'])) {
		$session_bay = $_SESSION['bay'];
	}

	if(isset($_SESSION['system'])) {
		$session_system = $_SESSION['system'];
	} 
	// SESSION CHECK ~~~~~~~~~~~