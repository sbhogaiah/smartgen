<?php

require_once '../includes/bootstrap.php'; 
require_once '../includes/dbconnect.php';

session_start();

// import error messages
$errors = new ErrorMessages();

/* Initialize */
if($_POST['role']) {

	/* if role is guest */
	if(isset($_POST['role']) && $_POST['role'] == "guest") {
		$_SESSION['username'] = 'guest';
		$_SESSION['fullname'] = 'Guest';
		$_SESSION['id'] = '0';
		$_SESSION['role'] = 'guest';
		$_SESSION['bay'] = 'none';
		
		$data = ['url' => BASE_URL.'admin_charts.php'];

		echo json_encode(['url' => BASE_URL.'admin_charts.php'], JSON_UNESCAPED_SLASHES);

		exit();
	}

	/* if role is not guest */
	if(isset($_POST['username']) && isset($_POST['password'])) {
		
		/* Post data */
		$username = strip_tags($_POST['username']);
		$password = strip_tags($_POST['password']);
		$role = strip_tags($_POST['role']);

		$username = stripslashes($username);
		$password = stripslashes($password);
		$role = stripslashes($role);

		$username = mysqli_real_escape_string($db, $username);
		$password = mysqli_real_escape_string($db, $password);
		$role = mysqli_real_escape_string($db, $role);

		$password = md5($password);

		// check if admin role
		if($role != "admin") {

			// not admin
			$sql = "SELECT * FROM users WHERE username ='$username' LIMIT 1";
			$query = mysqli_query($db, $sql);
			$row = mysqli_fetch_array($query);

			if (count($row)>=1) {

				$id = $row['UserID'];
				$full_name = $row['Fullname'];
				$table_role = $row['Role'];
				$db_password = $row['Password'];
				$bay = $row['Bay'];
				$system = $row['System'];

				if($role == $table_role) {

					if ($password == $db_password) {
						
						$_SESSION['username'] = $username;
						$_SESSION['fullname'] = $full_name;
						$_SESSION['id'] = $id;
						$_SESSION['role'] = $table_role;
						$_SESSION['bay'] = $bay;
						$_SESSION['system'] = $system;
						
						// add last login date
						$current_datatime = date("Y-m-d H:i:s");
						$lastlogin_query = "UPDATE users SET Lastlogin = '$current_datatime' WHERE UserID = '$id'";
						$run_lastlogin_query = mysqli_query($db, $lastlogin_query) or die(mysqli_error($db));

						// check role and redirect
						switch ($role) {
							case 'production':
								echo json_encode(['url' => BASE_URL.'production.php'], JSON_UNESCAPED_SLASHES);
								break;
							case 'packaging':
								echo json_encode(['url' => BASE_URL.'packaging.php'], JSON_UNESCAPED_SLASHES);
								break;
							case 'finishing':
								echo json_encode(['url' => BASE_URL.'finishing.php'], JSON_UNESCAPED_SLASHES);
								break;
							case 'testing':
								echo json_encode(['url' => BASE_URL.'testing.php'], JSON_UNESCAPED_SLASHES);
								break;
						}
					} else {
						echo json_encode(['error' => $errors->password]);
					}

				} else {
					echo json_encode(['error' => $errors->role]);
				}

			} else {
				echo json_encode(['error' => $errors->username]);
			}
			// not admin
		} else {
			// admin
			$sql = "SELECT * FROM admin WHERE username ='$username' LIMIT 1";
			$query = mysqli_query($db, $sql);
			$row = mysqli_fetch_array($query);

			$id = $row['AdminID'];
			$full_name = $row['Fullname'];
			$db_password = $row['Password'];

			if (count($row)>=1 && $password == $db_password) {
						
				$_SESSION['username'] = $username;
				$_SESSION['fullname'] = $full_name;
				$_SESSION['id'] = $id;
				$_SESSION['role'] = "admin";
				$_SESSION['bay'] = "";
				$_SESSION['system'] = "";

				echo json_encode(['url' => BASE_URL.'admin_charts.php'], JSON_UNESCAPED_SLASHES);
				
			} else {
				echo json_encode(['error' => $errors->password]);
			}
			//admin
		}

	}
}