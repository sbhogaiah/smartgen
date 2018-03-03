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
	$idQ = "SELECT TestId FROM testeractivity WHERE Tester='$username'";
	$runIdQ = $db->query($idQ);
	$testId = $runIdQ->fetch_array(MYSQLI_ASSOC)['TestId'];

	// Update testeractivity-----------------------
	$taQ = "UPDATE testeractivity SET Status='inprogress' WHERE Tester='$username'";
	$runTaQ = $db->query($taQ);

	// Update tests------------------------
	$tQ = "UPDATE tests SET Status='inprogress' WHERE TestId='$testId'";
	$runTQ = $db->query($tQ);

	// Tests logs------------------------
	$log = "Test resumed at ".$system;
	$logQ = "INSERT INTO testlogs (TestId,SerialNumber,LogText,Author,CreatedAt) 
					VALUES ('$testId','$serial','$log','$testerName',NOW())";
	$runTL = $db->query($logQ);

	// update system for burn in test------------------------
	if ($stageId == 7) {
		if ($system === "fct 1") {
			$system = 'burn in 1';
		} elseif ($system === "fct 2") {
			$system = 'burn in 2';
		}
	}

	// PPI-----------------------------------------------
	$PPIQ = "SELECT * FROM ppi WHERE System = '$system'";
	$runPPIQ = $db->query($PPIQ); 

	$PPIData=$runPPIQ->fetch_array(MYSQLI_ASSOC);
	$PPIStartDT = $PPIData['StartDT'];
	$PPIResumeDT = $PPIData['ResumeDT'];
	$PPIFailDT = $PPIData['FailDT'];
	
	// if failed first time
	if ($PPIResumeDT == NULL) {
		$UpdatePPI = "UPDATE ppi SET Status='inprogress', ResumeDT=NOW(), StaticDownTime=FLOOR(TIMESTAMPDIFF(MINUTE,FailDT,NOW())) WHERE System = '$system'";
		$RunUpdatePPI = $db->query($UpdatePPI);
	} else {
		$UpdatePPI = "UPDATE ppi SET Status='inprogress', ResumeDT=NOW(), StaticDownTime=StaticDownTime+FLOOR(TIMESTAMPDIFF(MINUTE,FailDT,NOW())) WHERE System = '$system'";
		$RunUpdatePPI = $db->query($UpdatePPI);
	}

	// Update bayutil----------------------
	// get midnight
	$timestamp = strtotime('today midnight');
	$midnight = date("Y/m/d H:i:s",$timestamp);

	// check if row exists if it doesnt that mean test has passed midnight
	$check_bayutil = "SELECT * FROM bayutil WHERE BayDate=CURDATE() AND System = '$system'";
	$run_check_bayutil = $db->query($check_bayutil);

	if(mysqli_num_rows($run_check_bayutil) === 0) { // overlap and row does not exist

		// if the test is running the down start should be now at midnight
		$systemName = $_SESSION['bay'];
		$InsertBayUTR = "INSERT INTO bayutil (System,BayDate,DownStart,DownEnd,UsageStart,Status,SystemName) 
									VALUES ('$system',CURDATE(),'$midnight',NOW(),NOW(),'up','$systemName')";  
		$RunQueryBayUTR = $db->query($InsertBayUTR);

		$UpdateCurBayUTbl = "UPDATE bayutil SET DownTime=FLOOR(TIMESTAMPDIFF(MINUTE,'$midnight',DownEnd)), 
								StaticDownTime=FLOOR(TIMESTAMPDIFF(MINUTE,'$midnight',DownEnd)), 
								IdleTime=1440-StaticDownTime, StaticIdleTime=1440-StaticDownTime  
			 					WHERE BayDate=CURDATE() AND System = '$system'";
		$RunQueryCurBayUTbl = $db->query($UpdateCurBayUTbl);

		// Update previous bayUtil
		$UpdateBayUTbl = "UPDATE bayutil SET DownTime=StaticDownTime+FLOOR(TIMESTAMPDIFF(MINUTE,'$PPIFailDT','$midnight')), 
							StaticDownTime=StaticDownTime+FLOOR(TIMESTAMPDIFF(MINUTE,'$PPIFailDT','$midnight')), 
							IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime  
		 					WHERE BayDate=DATE_SUB(CURDATE(),INTERVAL 1 DAY) AND System = '$system'";
		$RunQueryBayUTbl = $db->query($UpdateBayUTbl);

	} else { // no overlap
		// update bay util
		$UpdateBayUTbl = "UPDATE bayutil SET DownTime=StaticDownTime+FLOOR(TIMESTAMPDIFF(MINUTE,DownStart,NOW())),
								StaticDownTime=StaticDownTime+FLOOR(TIMESTAMPDIFF(MINUTE,DownStart,NOW())),
								IdleTime=1440-StaticUsageTime-StaticDownTime,
								StaticIdleTime=1440-StaticUsageTime-StaticDownTime,
								UsageStart=NOW(), DownEnd=NOW(), DownStart=NULL, UsageEnd=NULL,
								Status='up' 
								WHERE BayDate=CURDATE() AND System = '$system'";						
		$RunUpdateBayUTbl = $db->query($UpdateBayUTbl);
	}

	echo json_encode(['status'=>'ok', 'message'=>'test resumed']);

}
