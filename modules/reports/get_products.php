
<?php
require_once '../../includes/bootstrap.php'; 
require_once '../../includes/dbconnect.php';

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

extract($_POST);

switch ($reportType) {
	
	case "serial" :
		$query = " SELECT th.*, pr.*
			FROM timestamps as th
			LEFT JOIN products pr
			ON th.SerialNumber = pr.SerialNumber
			WHERE th.SerialNumber = '".$sn."'";
	break;
	case "datereport":
		$fromDate = $fromDate . " 00:00:00";
		$toDate = $toDate . " 23:59:59";
		$query = " SELECT th.*, pr.*
			FROM timestamps as th
			LEFT JOIN products pr
			ON th.SerialNumber = pr.SerialNumber
			WHERE pr.CreatedAt >= '".$fromDate."' AND pr.CreatedAt <= '".$toDate."'";				
	break;
	case "oneday":
		$query = " SELECT th.*, pr.*
			FROM timestamps as th
			LEFT JOIN products pr
			ON th.SerialNumber = pr.SerialNumber
			WHERE pr.CreatedAt > timestampadd(hour, -24, now())";	
	break;
	case "oneweek":
		$query = " SELECT th.*, pr.*
			FROM timestamps as th
			LEFT JOIN products pr
			ON th.SerialNumber = pr.SerialNumber
			WHERE pr.CreatedAt > timestampadd(day, -7, now())";			
	break;
	case "halfmonth":
		$query = " SELECT th.*, pr.*
			FROM timestamps as th
			LEFT JOIN products pr
			ON th.SerialNumber = pr.SerialNumber
			WHERE pr.CreatedAt > timestampadd(day, -15, now())";			
	break;
	case "month":
		$query = " SELECT th.*, pr.*
			FROM timestamps as th
			LEFT JOIN products pr
			ON th.SerialNumber = pr.SerialNumber
			WHERE pr.CreatedAt > timestampadd(day, -30, now())";	
	break;
	
	default:
	break;
}


$result = mysqli_query($db, $query);
$resultset = [];

$data = [];
while($row = mysqli_fetch_assoc($result)){
    // $resultset[]=$row;

    $hipotId = $row["HipotId"];
    $q2 = "SELECT *
			FROM tests
			WHERE TestId = '$hipotId'";	
	$q2result = mysqli_query($db, $q2) or die(mysqli_error($db));

	$lpId = $row["LowPowerId"];
    $q3 = "SELECT *
			FROM tests
			WHERE TestId = '$lpId'";	
	$q3result = mysqli_query($db, $q3) or die(mysqli_error($db));

	$biId = $row["BurnInId"];
    $q4 = "SELECT *
			FROM tests
			WHERE TestId = '$biId'";	
	$q4result = mysqli_query($db, $q4) or die(mysqli_error($db));

	$fctId = $row["FctId"];
    $q5 = "SELECT *
			FROM tests
			WHERE TestId = '$fctId'";	
	$q5result = mysqli_query($db, $q5) or die(mysqli_error($db));

	$data[] = ['timestamp' => $row, 'hipot' => mysqli_fetch_assoc($q2result), 'lowpower' => mysqli_fetch_assoc($q3result), 'burnin' => mysqli_fetch_assoc($q4result), 'fct' => mysqli_fetch_assoc($q5result)];

}

echo json_encode($data);
mysqli_free_result($result);

/* close connection */
mysqli_close($db);


