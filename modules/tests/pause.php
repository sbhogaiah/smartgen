<?php
session_start();

require_once '../../includes/bootstrap.php'; 
require_once '../../includes/dbconnect.php';

$username = $_SESSION['username'];

if(isset($_POST['system']) && isset($_POST['serial']) && isset($_POST['model'])) {

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
	$q1 = "SELECT TestId FROM testeractivity WHERE Tester='$username'";
	$run_q1 = $db->query($q1);
	$testId = $run_q1->fetch_array(MYSQLI_ASSOC)['TestId'];

	// Update tests------------------------
	$q3 = "UPDATE tests SET Status='pause' WHERE TestId='$testId'";
	$runTA = $db->query($q3);

	// add to tests logs
	$log = "Test was paused at ".$system;
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

	// Get PPI details
	$ppi = "SELECT * FROM ppi WHERE System = '$system'";
	$ppiQ = $db->query($ppi); 
	$ppiData = $ppiQ->fetch_array(MYSQLI_ASSOC);
	$PPIResumeDT = $ppiData['ResumeDT'];
	$PPIStartDT = $ppiData['StartDT'];
	$PPIFailTime = $ppiData['FailDT'];
	$PPIDownTime = $ppiData['DownTime'];
	$PPIUsageTime = $ppiData['UsageTime'];
	$PPIStatus = $ppiData['Status'];

	// update ppi
	$updatePPI = "UPDATE ppi SET Status='idle', SerialNumber=NULL, StartDT=NULL, FailDT=NULL, 
						ResumeDT=NULL, UsageTime=0, DownTime=0, FailCount=0, CycleTime=0, CycleTol=0,
						StaticUsageTime=0,StaticDownTime=0 WHERE SerialNumber = '$serial'";
	$updatePPIQ = $db->query($updatePPI);

	// Update bayutil----------------------
	// get midnight
	$timestamp = strtotime('today midnight');
	$midnight = date("Y/m/d H:i:s",$timestamp);

	if ($PPIStatus == 'inprogress') {
		$UpdateBayUTbl = "UPDATE bayutil SET Status='idle', 
				UsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())), 
				StaticUsageTime=StaticUsageTime+CEIL(TIMESTAMPDIFF(MINUTE,UsageStart,NOW())),
				IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime,
				UsageStart=NULL, UsageEnd=NULL, DownStart=NULL, DownEnd=NULL
				WHERE BayDate=CURDATE() AND System='$system'";						
		$RunUpdateBayUTbl = $db->query($UpdateBayUTbl);
	} else if ($PPIStatus == 'fail') {
		$UpdateBayUTbl = "UPDATE bayutil SET Status='idle', 
				DownTime=StaticDownTime+CEIL(TIMESTAMPDIFF(MINUTE,DownStart,NOW())), 
				StaticDownTime=StaticDownTime+CEIL(TIMESTAMPDIFF(MINUTE,DownStart,NOW())),
				IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime,
				UsageStart=NULL, UsageEnd=NULL, DownStart=NULL, DownEnd=NULL
				WHERE BayDate=CURDATE() AND System='$system'";						
		$RunUpdateBayUTbl = $db->query($UpdateBayUTbl);
	}

	// Update testeractivity-----------------------
	$q2 = "UPDATE testeractivity SET Status='pause', PausedUsageTime='$PPIUsageTime', PausedDownTime='$PPIDownTime', PausedStatus='$PPIStatus' WHERE Tester='$username'";
	$runTA = $db->query($q2);

	echo json_encode(['status'=>'ok', 'message'=>'test paused']);

}
