<?php
session_start();

require_once '../../includes/bootstrap.php'; 
require_once '../../includes/dbconnect.php';

$systemName = $_SESSION['bay'];

	// update ppi table based on conditions
	$query = "SELECT * FROM ppi";
	$run_query = mysqli_query($db, $query);

	if(mysqli_num_rows($run_query)>0) {

		while($row = mysqli_fetch_assoc($run_query)) {
			
			// check if status is inprogress
			if($row['Status'] == "inprogress") {

				if ($row['ResumeDT'] == NULL) {
					$upd_query = "UPDATE ppi SET UsageTime=StaticUsageTime+FLOOR(TIMESTAMPDIFF(MINUTE,StartDT,NOW())) WHERE System = '$row[System]'";
					$run_upd_query = mysqli_query($db, $upd_query);
				} else {
					$upd_query = "UPDATE ppi SET UsageTime=StaticUsageTime+FLOOR(TIMESTAMPDIFF(MINUTE,ResumeDT,NOW())) WHERE System = '$row[System]'";
					$run_upd_query = mysqli_query($db, $upd_query);
				}

				// check if curtime is midnight 
				// get midnight
				$timestamp = strtotime('today midnight');
				$midnight = date("Y/m/d H:i:s",$timestamp);

				// check if row exists if it doesnt that mean test has passed midnight
				$check_bayutil = "SELECT * FROM bayutil WHERE BayDate=CURDATE() AND System ='$row[System]'";
				$run_check_bayutil = mysqli_query($db, $check_bayutil);

				if(mysqli_num_rows($run_check_bayutil) == 0) {
					// if the test is running the usage start should be now at midnight
					$InsertBayUTR = "INSERT INTO bayutil (System,BayDate,UsageStart,DownEnd,Status,SystemName) VALUES ('$row[System]',CURDATE(), '$midnight', NULL,'up','$systemName')";  
					$RunQueryBayUTR = mysqli_query($db,$InsertBayUTR) or die("Connection was not created for InsertBayUTR at Start test!");

					// for previous day bayUtil
					if ($row['ResumeDT'] == NULL) { // check if failing first time or not
						$q10 = "UPDATE bayutil SET UsageTime=StaticUsageTime+FLOOR(TIMESTAMPDIFF(MINUTE,'$row[StartDT]','$midnight')), 
											StaticUsageTime=StaticUsageTime+FLOOR(TIMESTAMPDIFF(MINUTE,'$row[StartDT]','$midnight')), 
											IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime  
						 					WHERE BayDate=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND System='$system'";
						$runQ10 = $db->query($q10);
					} else {
						$q11 = "UPDATE bayutil SET UsageTime=StaticUsageTime+FLOOR(TIMESTAMPDIFF(MINUTE,'$row[ResumeDT]','$midnight')), 
											StaticUsageTime=StaticUsageTime+FLOOR(TIMESTAMPDIFF(MINUTE,'$row[ResumeDT]','$midnight')), 
											IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime  
						 					WHERE BayDate=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND System='$system'";
						$runQ11 = $db->query($q11);
					}
				}

			}

			// check if status is fail
			if($row['Status'] == "fail") {
				// if ($row['ResumeDT'] == NULL) {
					$upd_query2 = "UPDATE ppi SET DownTime=StaticDownTime + FLOOR(TIMESTAMPDIFF(MINUTE,FailDT,NOW())) WHERE System = '$row[System]'";
					$run_upd_query2 = mysqli_query($db, $upd_query2);
				// } else {
				// 	$upd_query3 = "UPDATE ppi SET DownTime=StaticDownTime + TIMESTAMPDIFF(MINUTE,FailDT,ResumeDT) WHERE System = '$row[System]'";;
				// 	$run_upd_query3 = mysqli_query($db, $upd_query3);
				// }

				// check if curtime is midnight 
				$timestamp = strtotime('today midnight');
				$midnight = date("Y/m/d H:i:s",$timestamp);
					
				$check_bayutil = "SELECT * FROM bayutil WHERE BayDate=CURDATE() AND System ='$row[System]'";
				$run_check_bayutil = mysqli_query($db, $check_bayutil);

				if(mysqli_num_rows($run_check_bayutil) == 0) {
					// if the test is running the usage start should be now at midnight
					$InsertBayUTR = "INSERT INTO bayutil (System,BayDate,DownStart,UsageEnd,Status) VALUES ('$row[System]',CURDATE(), '$midnight', NULL,'down')";  
					$RunQueryBayUTR = mysqli_query($db,$InsertBayUTR);
				}

			}

		}

	} else {
		echo "No data found in database: get_ppi.php";		
		exit();
	}


	// data variable for full data
	$data = array();
	// get all info from ppi table
	$get_query = "SELECT * FROM ppi";
	$run_get_query = mysqli_query($db, $get_query);

	if(mysqli_num_rows($run_get_query)>0) {

		while($r = mysqli_fetch_assoc($run_get_query)) {
		    array_push($data, $r);
		}
		echo json_encode($data);

	} 