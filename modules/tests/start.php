<?php
session_start();

require_once '../../includes/bootstrap.php'; 
require_once '../../includes/dbconnect.php';

function startTest($db, $q1, $serial, $model, $stage, $stageId, $system, $testerName, $username, $systemName) {
	$runCq = $db->query($q1) or die(mysqli_error($db));

	$cycle = $runCq->fetch_array(MYSQLI_NUM);
	$cycletime = $cycle[0];
	$cycletol = $cycle[1];


	// add to tests table
	$Tq = "INSERT INTO tests (SerialNumber,ModelId,Stage,System,StartTest,Status,TesterNames) 
					VALUES ('$serial','$model','$stage','$system',NOW(),'inprogress','$testerName')";
	$runTq = $db->query($Tq) or die(mysqli_error($db));
	$q1id = $db->insert_id;

	// update products & timestamps
	$runP = '';
	if ($stage === 'hipot') {
		$PHq = "UPDATE products SET HipotId='$q1id' WHERE SerialNumber='$serial'";
		$runP = $db->query($PHq) or die(mysqli_error($db));

		$TSHq = "UPDATE timestamps SET HipotIn=NOW(), Stage=3 WHERE SerialNumber='$serial'";
		$runP = $db->query($TSHq) or die(mysqli_error($db));
	} else if ($stage === 'low power') {
		$PLPq = "UPDATE products SET LowPowerId='$q1id' WHERE SerialNumber='$serial'";
		$runP = $db->query($PLPq) or die(mysqli_error($db));

		$TSLPq = "UPDATE timestamps SET LowPowerIn=NOW(), Stage=5 WHERE SerialNumber='$serial'";
		$runP = $db->query($TSLPq) or die(mysqli_error($db));
	} else if ($stage === 'burn in') {
		$PBIq = "UPDATE products SET BurnInId='$q1id' WHERE SerialNumber='$serial'";
		$runP = $db->query($PBIq) or die(mysqli_error($db));

		$TSBIq = "UPDATE timestamps SET BurnInIn=NOW(), Stage=7 WHERE SerialNumber='$serial'";
		$runP = $db->query($TSBIq) or die(mysqli_error($db));
	} else if ($stage === 'fct') {
		$PFCTq = "UPDATE products SET FctId='$q1id' WHERE SerialNumber='$serial'";
		$runP = $db->query($PFCTq) or die(mysqli_error($db));

		$TSLPq = "UPDATE timestamps SET fctIn=NOW(), Stage=9 WHERE SerialNumber='$serial'";
		$runP = $db->query($TSLPq) or die(mysqli_error($db));
	}

	// add to tests logs
	$log = "Test Started at ".$system;
	$LOGq = "INSERT INTO testlogs (TestId,SerialNumber,LogText,Author,CreatedAt) 
					VALUES ('$q1id','$serial','$log','$testerName',NOW())";
	$runTL = $db->query($LOGq) or die(mysqli_error($db));

	// insert into testeractivity-----------------------
	$TAq = "UPDATE testeractivity SET SerialNumber='$serial',ModelId='$model',StartDT=NOW(),Stage='$stage',Status='inprogress',TestCount=TestCount+1,TestId='$q1id' WHERE Tester='$username'";
	$runTA = $db->query($TAq) or die(mysqli_error($db));

	// update system for burn in test
	if ($stageId == 6) {
		if ($system === "fct 1") {
			$system = 'burn in 1';
		} elseif ($system === "fct 2") {
			$system = 'burn in 2';
		}
	}

	// update ppi
	$PPIq = "UPDATE ppi SET SerialNumber = '$serial', Status = 'inprogress', StartDT = NOW(), CycleTime = '$cycletime', CycleTol = '$cycletol' WHERE System = '$system'";
	$runPPI = $db->query($PPIq) or die(mysqli_error($db));

	// update bay util
	$check_bu = "SELECT * FROM bayutil WHERE BayDate=CURDATE() AND System='$system'";
	$run_check_bu = $db->query($check_bu) or die(mysqli_error($db));

	if($run_check_bu->num_rows === 0) {
		$insert_bu = "INSERT INTO bayutil (System, BayDate, SystemName) VALUES ('$system',CURDATE(),'$systemName')";  
		$run_insert_bu = $db->query($insert_bu) or die(mysqli_error($db));
	}

	$BUq = "UPDATE bayutil SET IdleTime=1440-StaticUsageTime-StaticDownTime, StaticIdleTime=1440-StaticUsageTime-StaticDownTime, UsageStart=NOW(), UsageEnd=NULL, DownStart=NULL, DownEnd=NOW(), Status='up'  WHERE System = '$system'";
	$runBayUtil = $db->query($BUq) or die(mysqli_error($db));

	echo json_encode(['status'=>'ok', 'message'=>'test started']);	
}

// initialize
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
	$runSq = $db->query($Sq) or die(mysqli_error($db));
	$stId = $runSq->fetch_array(MYSQLI_ASSOC)['Stage'];

	$Cq = '';

	if ($stId == 2) {
		$st = 'hipot';
		$Cq = "SELECT HipotCT, HipotTol FROM cycles WHERE ModelId = '$md'";
		startTest($db, $Cq, $sn, $md, $st, $stId, $sys, $tn, $un, $sysn);
	} else if ($stId == 4) {
		$st = 'low power';
		$Cq = "SELECT LowPowerCT, LowPowerTol FROM cycles WHERE ModelId = '$md'";
		startTest($db, $Cq, $sn, $md, $st, $stId, $sys, $tn, $un, $sysn);
	} else if ($stId == 6) {
		$st = 'burn in';
		$Cq = "SELECT BurnInCT, BurnInTol FROM cycles WHERE ModelId = '$md'";
		startTest($db, $Cq, $sn, $md, $st, $stId, $sys, $tn, $un, $sysn);
	} else if ($stId == 8) {
		$st = 'fct';
		$Cq = "SELECT FctCT, FctTol FROM cycles WHERE ModelId = '$md'";
		startTest($db, $Cq, $sn, $md, $st, $stId, $sys, $tn, $un, $sysn);
	}
	
} else {
	echo "one of the required fields are empty!";
}
