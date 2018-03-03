<?php
session_start();

require_once '../../includes/bootstrap.php'; 
require_once '../../includes/dbconnect.php';

$username = $_SESSION['username'];

$tester = $_POST['tester'];
$system = $_POST['system'];
$latest = $_POST['latest'];
$serial = $_POST['serial'];

$tester = $tester;

// Get Test Id-----------------------
$q1 = "SELECT TestId FROM testeractivity WHERE Tester='$username'";
$run_q1 = $db->query($q1);
$testId = $run_q1->fetch_array(MYSQLI_ASSOC)['TestId'];

// Update tests------------------------
$q3 = "UPDATE tests SET TesterNames='$tester' WHERE TestId='$testId'";
$runTA = $db->query($q3);

// add to tests logs
$log = $latest." was added to test process at ".$system;
$logQ = "INSERT INTO testlogs (TestId,SerialNumber,LogText,Author,CreatedAt) 
				VALUES ('$testId','$serial','$log','$latest',NOW())";
$runTL = $db->query($logQ);

echo json_encode(['message', 'ok']);
