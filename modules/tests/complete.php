<?php

session_start();

require_once '../../includes/bootstrap.php'; 
require_once '../../includes/dbconnect.php';

$username = $_SESSION['username'];

if(isset($_POST['system']) && isset($_POST['serial']) && isset($_POST['model']) && isset($_POST['tester'])) {

	$system = $_POST['system'];
	$testerName = $_POST['tester'];
	$serial = $_POST['serial'];
	$model = $_POST['model'];
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
	$TIDq = "SELECT TestId FROM testeractivity WHERE Tester='$username'";
	$run_TIDq = $db->query($TIDq);
	$testId = $run_TIDq->fetch_array(MYSQLI_ASSOC)['TestId'];

	// Update testeractivity-----------------------
	$TAq = "UPDATE testeractivity SET Status='idle',SerialNumber=NULL, ModelId=NULL, StartDT=NULL, Stage=NULL, PausedUsageTime=0, PausedDownTime=0, PausedStatus=NULL, TestId=NULL WHERE Tester='$username'";
	$runTA = $db->query($TAq);

	// add to tests logs
	$log = "Test completed at ".$system;
	$logQ = "INSERT INTO testlogs (TestId,SerialNumber,LogText,Author,CreatedAt) 
					VALUES ('$testId','$serial','$log','$testerName',NOW())";
	$runTL = $db->query($logQ);

	// update timestamps
	if ($stage === 'hipot') {
		$TSHq = "UPDATE timestamps SET HipotOut=NOW(), Stage=4 WHERE SerialNumber='$serial'";
		$runP = $db->query($TSHq);
	} else if ($stage === 'low power') {
		$TSLPq = "UPDATE timestamps SET LowPowerOut=NOW(), Stage=6 WHERE SerialNumber='$serial'";
		$runP = $db->query($TSLPq);
	} else if ($stage === 'burn in') {
		$TSBIq = "UPDATE timestamps SET BurnInOut=NOW(), Stage=8 WHERE SerialNumber='$serial'";
		$runP = $db->query($TSBIq);
	} else if ($stage === 'fct') {
		$TSLPq = "UPDATE timestamps SET fctOut=NOW(), Stage=10 WHERE SerialNumber='$serial'";
		$runP = $db->query($TSLPq);
	}

	// update system for burn in test
	if ($stageId == 7) {
		if ($system === "fct 1") {
			$system = 'burn in 1';
		} elseif ($system === "fct 2") {
			$system = 'burn in 2';
		}
	}

	// Get PPI Details
	$PPIq = "SELECT * FROM ppi WHERE System='$system'";
	$RunQueryPPI = $db->query($PPIq);
	$PPI=mysqli_fetch_assoc($RunQueryPPI); 
	$PPIResumeDT = $PPI['ResumeDT'];
	$PPIStartDT = $PPI['StartDT'];
	$PPIUsageTime = $PPI['UsageTime'];
	$PPIDownTime = $PPI['DownTime'];

	// Update tests------------------------
	$Tq = "UPDATE tests SET Status='completed',testCompleted=1,End=NOW(),UsageTime='$PPIUsageTime',DownTime='$PPIDownTime' WHERE TestId='$testId'";
	$runTA = $db->query($Tq);

	// Update bayutil----------------------
	// get midnight
	$timestamp = strtotime('today midnight');
	$midnight = date("Y/m/d H:i:s",$timestamp);

	// check if row exists if it doesnt that mean test has passed midnight
	$check_bayutil = "SELECT * FROM bayutil WHERE BayDate=CURDATE() AND System='$system'";
	$run_check_bayutil = $db->query($check_bayutil);

	if(mysqli_num_rows($run_check_bayutil) == 0) { // overlap and row does not exist
		// if the test is running the usage start should be now at midnight
		$systemName = $_SESSION['bay'];
		$InsertBayUTR = "INSERT INTO bayutil (System,BayDate,UsageStart,UsageEnd,Status,SystemName) 
									VALUES ('$system',CURDATE(),'$midnight',NOW(),'idle','$systemName')";  
		$RunQueryBayUTR = $db->query($InsertBayUTR);

		$UpdateCurBayUTbl = "UPDATE bayutil SET UsageTime=CEIL(TIMESTAMPDIFF(MINUTE,'$midnight',NOW())), 
								StaticUsageTime=CEIL(TIMESTAMPDIFF(MINUTE,'$midnight',NOW())), 
								IdleTime=1440-StaticUsageTime, StaticIdleTime=1440-StaticUsageTime  
			 					WHERE BayDate=CURDATE() AND System='$system'";
		$RunQueryCurBayUTbl = $db->query($UpdateCurBayUTbl);
		
		// for previous day bayUtil
		if ($PPIResumeDT == NULL) { // check if failing first time or not
			$UpdateBayUTbl = "UPDATE bayutil SET UsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPIStartDT','$midnight')), 
								StaticUsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPIStartDT','$midnight')), 
								IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime  
			 					WHERE BayDate=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND System='$system'";
			$RunQueryBayUTbl = $db->query($UpdateBayUTbl);
		} else {
			$UpdateBayUTbl = "UPDATE bayutil SET UsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPIResumeDT','$midnight')), 
								StaticUsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,'$PPIResumeDT','$midnight')), 
								IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime  
			 					WHERE BayDate=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND System='$system'";
			$RunQueryBayUTbl = $db->query($UpdateBayUTbl);
		}

	} else { // no overlap
		// your were adding total test time which includes downTime and usagetime to the bayutil usage time which is wrong
		$UpdateBayUTbl = "UPDATE bayutil SET UsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())),
									StaticUsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())), 
									IdleTime=1440-StaticUsageTime-StaticDownTime, 
									StaticIdleTime=1440-StaticUsageTime-StaticDownTime, 
									UsageEnd=NOW(), Status='idle' 
									WHERE BayDate=CURDATE() AND System='$system'";						
		$RunQueryBayUTbl = mysqli_query($db,$UpdateBayUTbl);
	}

	// Update ppi----------------------	
	$UpdatePPI = "UPDATE ppi SET SerialNumber=NULL, Status='idle', StartDT=NULL, FailDT=NULL, ResumeDT=NULL, UsageTime=0, DownTime=0, StaticUsageTime=0, StaticDownTime=0, FailCount=0, CycleTime=0, CycleTol=0 WHERE System='$system'";
	$RunUpdatePPI = $db->query($UpdatePPI);

	// Update tests------------------------
	$tQ = "UPDATE tests SET Status='complete',testCompleted=1, EndTest=NOW(), UsageTime='$PPIUsageTime', DownTime='$PPIDownTime' WHERE TestId='$testId'";
	$runTQ = $db->query($tQ);

	echo json_encode(['status'=>'ok', 'message'=>'test complete']);

}
