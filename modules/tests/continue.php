<?php
session_start();

require_once '../../includes/bootstrap.php'; 
require_once '../../includes/dbconnect.php';

// continue function
function continueTest($db, $q1, $serial, $model, $stage, $stageId, $system, $testerName, $username, $systemName) {

	// get cycle time
	$runCq = $db->query($q1) or die(mysqli_error($db));
	$cycle = $runCq->fetch_array(MYSQLI_NUM);
	$cycletime = $cycle[0];
	$cycletol = $cycle[1];

	// get testeractivity details
	$TAq = "SELECT * FROM testeractivity WHERE Tester='$username'";
	$runTAq = $db->query($TAq);
	$TA = $runTAq->fetch_array(MYSQLI_ASSOC);
	$testId = $TA['TestId'];
	$activity_status = $TA['PausedStatus'];
	$activity_start = $TA['StartDT'];
	$activity_usage = $TA['PausedUsageTime'];
	$activity_down = $TA['PausedDownTime'];

	// add to tests logs
	$log = "Test was continued after a pause at ".$system;
	$logQ = "INSERT INTO testlogs (TestId,SerialNumber,LogText,Author,CreatedAt) 
					VALUES ('$testId','$serial','$log','$testerName',NOW())";
	$runTL = $db->query($logQ);
	
	// update system for burn in test
	if ($stageId == 6) {
		if ($system === "fct 1") {
			$system = 'burn in 1';
		} elseif ($system === "fct 2") {
			$system = 'burn in 2';
		}
	}

	// Bay util
	// check if row for bay already
	$check_bayutil = "SELECT * FROM bayutil WHERE BayDate=CURDATE() AND System='$system'";
	$run_check_bayutil = $db->query($check_bayutil);
	
	if(mysqli_num_rows($run_check_bayutil) == 0) {
		$InsertBayUTR = "INSERT INTO bayutil (System,BayDate,SystemName) VALUES ('$system',CURDATE(),'$systemName')";
		$RunQueryBayUTR = $db->query($InsertBayUTR);
	}

	if($activity_status == 'fail') {
		// update ppi
		$UpdatePPI = "UPDATE ppi SET Status='$activity_status', SerialNumber='$serial', StartDT='$activity_start', FailDT=NOW(), ResumeDT=NOW(), UsageTime='$activity_usage', DownTime='$activity_down', CycleTime='$cycletime', CycleTol='$cycletol', StaticUsageTime='$activity_usage', StaticDownTime='$activity_down' WHERE System='$system'";
		$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die(mysqli_error($db));

		// update bay util
		$UpdateBayUTbl = "UPDATE bayutil SET UsageStart=NULL, UsageEnd=NOW(), DownStart=NOW(), DownEnd=NULL, Status='down' WHERE BayDate=CURDATE() AND System='$system'";
		$RunQueryBayUTbl = $db->query($UpdateBayUTbl);
		
		// Update tests------------------------
		$q3 = "UPDATE tests SET Status='fail' WHERE TestId='$testId'";
		$runTA = $db->query($q3);

		// update testeractivity-----------------------
		$testeractivity_q = "UPDATE testeractivity SET Status='fail', PausedStatus=NULL WHERE Tester = '$username'";
		$run_testeractivity_q = mysqli_query($db, $testeractivity_q) or die('Cannot connect to testeractivity at Continue test!');

		echo json_encode(["stage"=>"fail"]);

	} else {

		// update ppi
		$UpdatePPI = "UPDATE ppi SET Status='$activity_status', SerialNumber='$serial', StartDT='$activity_start', ResumeDT=NOW(), UsageTime='$activity_usage', DownTime='$activity_down', CycleTime='$cycletime', CycleTol='$cycletol', StaticUsageTime='$activity_usage', StaticDownTime='$activity_down' WHERE System='$system'";
		$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die(mysqli_error($db));

		// update bay util
		$UpdateBayUTbl = "UPDATE bayutil SET UsageStart=NOW(), UsageEnd=NULL, DownStart=NULL, DownEnd=NOW(), Status='up' WHERE BayDate=CURDATE() AND System='$system'";
		$RunQueryBayUTbl = $db->query($UpdateBayUTbl);

		// Update tests------------------------
		$q3 = "UPDATE tests SET Status='inprogress' WHERE TestId='$testId'";
		$runTA = $db->query($q3);

		// update testeractivity-----------------------
		$testeractivity_q = "UPDATE testeractivity SET Status='inprogress', PausedStatus=NULL WHERE Tester = '$username'";
		$run_testeractivity_q = mysqli_query($db, $testeractivity_q) or die('Cannot connect to testeractivity at Continue test!');

		echo json_encode(["stage"=>"inprogress"]);
	}

}

$un = $_SESSION['username'];
$sysn = $_SESSION['bay'];

if(isset($_POST['system']) && isset($_POST['serial']) && isset($_POST['model'])) {

	$sys = $_POST['system'];
	$tn = $_POST['tester'];
	$sn = $_POST['serial'];
	$md = $_POST['model'];
	$st = '';

	// get current stage
	$Sq = "SELECT Stage FROM timestamps WHERE SerialNumber = '$sn'";
	$runSq = $db->query($Sq);
	$stId = $runSq->fetch_array(MYSQLI_ASSOC)['Stage'];

	if ($stId == 3) {
		$st = 'hipot';
		$Cq = "SELECT HipotCT, HipotTol FROM cycles WHERE ModelId = '$md'";
		continueTest($db, $Cq, $sn, $md, $st, $stId, $sys, $tn, $un, $sysn);
	} else if ($stId == 5) {
		$st = 'low power';
		$Cq = "SELECT LowPowerCT, LowPowerTol FROM cycles WHERE ModelId = '$md'";
		continueTest($db, $Cq, $sn, $md, $st, $stId, $sys, $tn, $un, $sysn);
	} else if ($stId == 7) {
		$st = 'burn in';
		$Cq = "SELECT BurnInCT, BurnInTol FROM cycles WHERE ModelId = '$md'";
		continueTest($db, $Cq, $sn, $md, $st, $stId, $sys, $tn, $un, $sysn);
	} else if ($stId == 9) {
		$st = 'fct';
		$Cq = "SELECT FctCT, FctTol FROM cycles WHERE ModelId = '$md'";
		continueTest($db, $Cq, $sn, $md, $st, $stId, $sys, $tn, $un, $sysn);
	}

}
