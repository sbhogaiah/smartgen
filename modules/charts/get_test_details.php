<?php
require_once '../../includes/bootstrap.php'; 
require_once '../../includes/dbconnect.php';

if(isset($_GET['system'])) {
	$baySystem = $_GET['system'];

	switch($baySystem){
		case "Hipot,(F848)":
			$system = "hipot 1";
			break;
		case "Low Power,(FT1347)":
			$system = "low power 1";
			break;
		case "Burn In,(FT1346)":
			$system = "burn in 1";
			break;
		case "FCT,(FT1346)":
			$system = "fct 1";
			break;
		case "Hipot,(F838)":
			$system = "hipot 2";
			break;
		case "Low Power,(FT1363)":
			$system = "low power 2";
			break;
		case "Burn In,(FT1362)":
			$system = "burn in 2";
			break;
		case "FCT,(FT1362)":
			$system = "fct 2";
			break;
		default:
			break;
	}
	
	$q = "SELECT SerialNumber FROM ppi WHERE System = '$system'";
	$run_q = $db->query($q) or die(mysqli_error($db));
	$serial = mysqli_fetch_assoc($run_q)['SerialNumber'];
	
	$stage = substr($system, 0, -2);
	
	$test_q = "SELECT * FROM tests WHERE SerialNumber = '$serial' AND Stage = '$stage' ORDER BY TestId DESC LIMIT 1";
	$run_test_q = $db->query($test_q) or die(mysqli_error($db));
	$test = mysqli_fetch_assoc($run_test_q);

	$prod_q = "SELECT ModelId FROM products WHERE SerialNumber = '$serial'";
	$run_prod_q = $db->query($prod_q) or die(mysqli_error($db));
	$model = mysqli_fetch_assoc($run_prod_q);
	
	echo json_encode(array_merge($test, $model));

}
