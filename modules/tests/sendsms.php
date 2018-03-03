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
	$Phoneselect = "Yes";	

	switch ($failReason) {
		case "Bay Down":
			$failtype = "BayDown";
			$query_phone = "SELECT Phone FROM usermsg WHERE BayDown='$Phoneselect'";
		break;
		case "Unit Failure":
			$failtype = "UnitFailure";
			$query_phone = "SELECT Phone FROM usermsg WHERE UnitFailure='$Phoneselect'";
		break;
		case "Power Failure":
			$failtype = "PowerFailure";
			$query_phone = "SELECT Phone FROM usermsg WHERE PowerFailure='$Phoneselect'";			
		break;
		default:
			$failtype = "NoMatch";
		break;
	}

	 if ($failtype != "NoMatch") {
			
		$result = $db->query($query_phone);
		$phoneNum = array();
		//Get all the phone numbers into an array.
		while($row = mysqli_fetch_assoc($result)) {
	  		$phoneNum[] = $row['Phone'];
		}
		//convert the phone numbers in array into comma seperated values.
		$phonestr = implode(",", $phoneNum);	

		$uname="somashekarbhg";
		$Password="soma123";	
		$sender='UTNSYS';
		$number=$phonestr;
		$priority='ndnd';
		$stype='normal';
		$msg = sprintf("Testing Failed! :- \nReason : %s \nSystem : %s \nTester : %s \nSerial : %s \nModel : %s",$failReason,$system,$testerName,$serial,$model);		
	    $message=urlencode($msg);

	  	$var="user=".$uname."&pass=".$Password."&sender=".$sender."&phone=".$number."&text=".$message."&priority=".$priority."&stype=".$stype."";
	  	$curl=curl_init('http://bhashsms.com/api/sendmsg.php?'.$var);
	  //	curl_setopt($curl,CURLOPT_PROXY,'http://proxy.com:80');
	   	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	   	$response=curl_exec($curl);
	   	curl_close($curl);
	   	echo json_encode(['status'=>'ok', 'message'=>'SMS sent']);

   }
}



