<?php
session_start();

require_once '../../includes/bootstrap.php'; 
require_once '../../includes/dbconnect.php';

$username = $_SESSION['username'];

if(isset($_POST['system']) && isset($_POST['serial']) && isset($_POST['model']) && isset($_POST['tester']) && isset($_POST['reason'])) {

	$system = $_POST['system'];
	$testerName = $_POST['tester'];
	$serial = $_POST['serial'];
	$model = $_POST['model'];
	$reason = $_POST['reason'];
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

	// Update timestamp------------------------
	if ($stage === 'hipot') {
		$TSHq = "UPDATE timestamps SET HipotIn=NULL, Stage=2 WHERE SerialNumber='$serial'";
		$runP = $db->query($TSHq);
	} else if ($stage === 'low power') {
		$TSLPq = "UPDATE timestamps SET LowPowerIn=NULL, Stage=4 WHERE SerialNumber='$serial'";
		$runP = $db->query($TSLPq);
	} else if ($stage === 'burn in') {
		$TSBIq = "UPDATE timestamps SET BurnInIn=NULL, Stage=6 WHERE SerialNumber='$serial'";
		$runP = $db->query($TSBIq);
	} else if ($stage === 'fct') {
		$TSLPq = "UPDATE timestamps SET fctIn=NULL, Stage=8 WHERE SerialNumber='$serial'";
		$runP = $db->query($TSLPq);
	}

	// add to tests logs
	$log = "Test was stopped and product removed due to '".$reason."' from ".$system;
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

	// PPI and BAY UTIL-----------------------------------------------
	// update ppi
	$UpdatePPI = "UPDATE ppi SET Status='idle', SerialNumber=NULL, StartDT=NULL, FailDT=NULL, 
						ResumeDT=NULL, UsageTime=0, DownTime=0, FailCount=0, CycleTime=0, CycleTol=0,
						StaticUsageTime=0,StaticDownTime=0 WHERE SerialNumber = '$serial'";
	$RunUpdatePPI = mysqli_query($db, $UpdatePPI) or die(mysqli_error($db));

	// Update bayutil----------------------
	$UpdateBayUTbl = "UPDATE bayutil SET Status='idle' WHERE BayDate=CURDATE() AND System='$system'";						
	$RunUpdateBayUTbl = $db->query($UpdateBayUTbl);

	// update testeractivity-----------------------
	$testeractivity_q = "UPDATE testeractivity SET Status='idle', Stage=NULL, SerialNumber=NULL, ModelId=NULL, StartDT=NULL WHERE Tester = '$username'";
	$run_testeractivity_q = mysqli_query($db, $testeractivity_q) or die(mysqli_error($db));

	echo json_encode(['message'=>'ok']);

} else {
	echo "one of the required fields are empty!";
}
