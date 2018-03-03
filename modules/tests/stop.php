<?php

session_start();

require_once '../../includes/bootstrap.php'; 
require_once '../../includes/dbconnect.php';

$username = $_SESSION['username'];

if(isset($_POST['system']) && isset($_POST['serial']) && isset($_POST['model']) && isset($_POST['tester']) && isset($_POST['failReason'])) {

	$system = $_POST['system'];
	$testerName = $_POST['tester'];
	$serial = $_POST['serial'];
	$model = $_POST['model'];
	$failReason = $_POST['failReason'];
	$stage = '';

	// get current stage
	$Sq = "SELECT Stage FROM timestamps WHERE SerialNumber = '$serial'";
	$runSq = $db->query($Sq);
	$stageId = $runSq->fetch_array(MYSQLI_ASSOC)['Stage'];

	if ($stageId == 3) {
		$stage = 'hipot';
	} else if ($stageId == 5) {
		$stage = 'low power';
	} else if ($stageId == 7) {
		$stage = 'burn in';
	} else if ($stageId == 9) {
		$stage = 'fct';
	}

	// Get Test Id-----------------------
	$q1 = "SELECT TestId FROM testeractivity WHERE Tester='$username'";
	$run_q1 = $db->query($q1);
	$testId = $run_q1->fetch_array(MYSQLI_ASSOC)['TestId'];

	// Update testeractivity-----------------------
	$q2 = "UPDATE testeractivity SET Status='fail', FailCount=FailCount+1 WHERE Tester='$username'";
	$runTA = $db->query($q2);

	// Update tests------------------------
	$q3 = "UPDATE tests SET Status='fail', FailCount=FailCount+1 WHERE TestId='$testId'";
	$runTA = $db->query($q3);

	// add to tests logs
	$log = "Test failed due to ".$failReason." at ".$system;
	$logQ = "INSERT INTO testlogs (TestId,SerialNumber,LogText,Author,CreatedAt) 
					VALUES ('$testId','$serial','$log','$testerName',NOW())";
	$runTL = $db->query($logQ);

	// update system for burn in test
	if ($stageId == 7) {
		if ($system === "fct 1") {
			$system = 'burn in 1';
		} elseif ($system === "fct 2") {
			$system = 'burn in 2';
		}
	}

	// Update ppi------------------------
	$q4 = "SELECT * FROM ppi WHERE System = '$system'";
	$runPPI = $db->query($q4); 
	$PPI = $run_q1->fetch_array(MYSQLI_ASSOC);
	$PPIResumeDT = $PPI['ResumeDT'];
	$PPIStartDT = $PPI['StartDT'];

	// if failed first time
	if ($PPIResumeDT == NULL) {

		$q5 = "UPDATE ppi SET Status='fail', FailDT=NOW(), StaticUsageTime=FLOOR(TIMESTAMPDIFF(MINUTE,StartDT,NOW())), FailCount = FailCount+1 WHERE System = '$system'";
		$runQ5 = $db->query($q5);

	} else {// if failed second time

		$q6 = "UPDATE ppi SET Status='fail', FailDT=NOW(), StaticUsageTime=StaticUsageTime+FLOOR(TIMESTAMPDIFF(MINUTE,ResumeDT,NOW())), FailCount = FailCount+1 WHERE System = '$system'";
		$runQ6 = $db->query($q6);

	}


	// Update bayutil----------------------
	// get midnight
	$timestamp = strtotime('today midnight');
	$midnight = date("Y/m/d H:i:s",$timestamp);

	// check if row exists if it doesnt that mean test has passed midnight
	$q7 = "SELECT * FROM bayutil WHERE BayDate=CURDATE() AND System='$system'";
	$runQ7 = $db->query($q7);

	if($runQ7->num_rows === 0) { // overlap and row does not exist
		// if the test is running the usage start should be now at midnight
		$systemName = $_SESSION['bay'];
		$q8 = "INSERT INTO bayutil (System,BayDate,UsageStart,UsageEnd,DownStart,Status,SystemName) 
									VALUES ('$system',CURDATE(),'$midnight',NOW(),NOW(),'down','$systemName')";  
		$runQ8 = $db->query($q8);

		$q9 = "UPDATE bayutil SET UsageTime=FLOOR(TIMESTAMPDIFF(MINUTE,'$midnight',NOW())), 
								StaticUsageTime=FLOOR(TIMESTAMPDIFF(MINUTE,'$midnight',NOW())), 
								IdleTime=1440-StaticUsageTime, StaticIdleTime=1440-StaticUsageTime  
			 					WHERE BayDate=CURDATE() AND System='$system'";
		$runQ9 = $db->query($q9);
		
		// for previous day bayUtil
		if ($PPIResumeDT == NULL) { // check if failing first time or not
			$q10 = "UPDATE bayutil SET UsageTime=StaticUsageTime+FLOOR(TIMESTAMPDIFF(MINUTE,'$PPIStartDT','$midnight')), 
								StaticUsageTime=StaticUsageTime+FLOOR(TIMESTAMPDIFF(MINUTE,'$PPIStartDT','$midnight')), 
								IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime  
			 					WHERE BayDate=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND System='$system'";
			$runQ10 = $db->query($q10);
		} else {
			$q11 = "UPDATE bayutil SET UsageTime=StaticUsageTime+FLOOR(TIMESTAMPDIFF(MINUTE,'$PPIResumeDT','$midnight')), 
								StaticUsageTime=StaticUsageTime+FLOOR(TIMESTAMPDIFF(MINUTE,'$PPIResumeDT','$midnight')), 
								IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime  
			 					WHERE BayDate=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND System='$system'";
			$runQ11 = $db->query($q11);
		}

	} else { // no overlap
		$q13 = "UPDATE bayutil SET UsageTime = StaticUsageTime+FLOOR(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())), 
							StaticUsageTime = StaticUsageTime+FLOOR(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())), 
							IdleTime=1440-StaticUsageTime-StaticDownTime,
							StaticIdleTime=1440-StaticUsageTime-StaticDownTime, 
							UsageStart=NULL,DownEnd=NULL, UsageEnd=NOW(),DownStart=NOW(),
							Status='down' 
							WHERE BayDate=CURDATE() AND System = '$system'";
		$runQ13 = $db->query($q13) or die("Connection was not created for bayutil at Stop Test!");
	}

	echo json_encode(['status'=>'ok', 'message'=>'test stopped']);
}
