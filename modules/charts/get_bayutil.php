<?php
require_once '../../includes/bootstrap.php'; 
require_once '../../includes/dbconnect.php';

	$date = $_GET['date'];

	if($date=='today') {
		// update ppi table based on conditions
		$query = "SELECT * FROM bayutil WHERE BayDate=CURDATE()";
		$run_query = mysqli_query($db, $query) or die('Cannot get bay util data : get_bayutil.php');

		if(mysqli_num_rows($run_query)>0) {

			while($row = mysqli_fetch_assoc($run_query)) {
				
				// check if status is inprogress
				if($row['Status'] == "up") {

					$upd_query2 = "UPDATE bayutil SET UsageTime=StaticUsageTime+TIMESTAMPDIFF(MINUTE,UsageStart,NOW()) WHERE BayDate=CURDATE() AND System = '$row[System]'";
					$run_upd_query2 = mysqli_query($db, $upd_query2);
					
				}

				if($row['Status'] == "down") {

					$upd_query2 = "UPDATE bayutil SET DownTime=StaticDownTime+TIMESTAMPDIFF(MINUTE,DownStart,NOW()) WHERE BayDate=CURDATE() AND System = '$row[System]'";
					$run_upd_query2 = mysqli_query($db, $upd_query2);
					
				}

			}

		}
		
		// send records after update to frontend
		$get_query = "SELECT * FROM bayutil WHERE BayDate=CURDATE()";
		$run_get_query = mysqli_query($db, $get_query) or die('Cannot get bay util data : get_bayutil.php');

		$data = [];
		if(mysqli_num_rows($run_get_query)>0) {

			while($r = mysqli_fetch_assoc($run_get_query)) {
			    array_push($data, $r);
			}
			echo json_encode($data);

		} else {
			echo "No data found!";
			//echo "No data found in database: get_bayutil.php";		
			exit();
		}
	} else { // old
		$get_query = "SELECT * FROM bayutil WHERE BayDate='$date'";
		$run_get_query = mysqli_query($db, $get_query);

		$data = [];
		if(mysqli_num_rows($run_get_query)>0) {

			while($r = mysqli_fetch_assoc($run_get_query)) {
			    array_push($data, $r);
			}
			echo json_encode($data);

		} else {
			echo "No data found!";		
			exit();
		}
	}
