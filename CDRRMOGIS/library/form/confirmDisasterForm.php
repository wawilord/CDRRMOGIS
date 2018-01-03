<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE:
 * DESCRIPTION:
 * _____________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 *______________________________________________________________________________________
 *
 * VARIABLES:
 *
 *
 *______________________________________________________________________________________
 *
 * Note: you can send a custom error message by typing 'msg:' followed by your message.
 * 		 Just make sure that there are no printed or echoed before the 'msg:' keyword.
 *______________________________________________________________________________________
 *
 */
	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();

	$form_ID = $_POST['id'];
	$session_USER_USERNAME = $_SESSION['USER_USERNAME'];
	$factors = array();
	$sql = "SELECT DISASTER FROM disaster_declare WHERE ID=" . $form_ID;
	$result = $db->connection->query($sql);
	$row = $result->fetch_assoc();
	$disasterID = $row['DISASTER'];


	$sql = "SELECT * FROM disaster_factors WHERE TYPE_ID=" . $disasterID;
	$result = $db->connection->query($sql);
	$count = mysqli_num_rows($result);
	while ($row = $result->fetch_assoc()){
		$factors["FACTOR_" . $row['ID']] = $_POST['FACTOR_' . $row['ID']];
	}

	$factors = $db->connection->real_escape_string(json_encode($factors));

	$sql = 'SELECT ID 
			FROM disaster_reports 
			WHERE
			ISVERIFIED=0
			AND DECLAREID=' . $form_ID;
	$result = $db->connection->query($sql);
	$count = mysqli_num_rows($result);
	if($count > 0){
		echo 'error: We cannot confirm this disaster if there are still pending reports. Please Take an action for those reports.';
		exit;
	}
	else
	{
		$sql = 'SELECT ID 
			FROM disaster_reports 
			WHERE
			ISVERIFIED=1
			AND DECLAREID=' . $form_ID;
		$result = $db->connection->query($sql);
		$count = mysqli_num_rows($result);
		if($count < 1){
			echo 'error: You must have at least one verified disaster report to confirm this disaster.';
		}
		else
		{
			$sql = 'SELECT ID 
			FROM evacuation_report 
			WHERE
			ISVERIFIED=0
			AND DECLAREID=' . $form_ID;
			$result = $db->connection->query($sql);
			$count = mysqli_num_rows($result);
			if($count > 0){
				echo 'error: We cannot confirm this disaster if there are still pending reports. Please Take an action for those reports.';
				exit;
			}
			else
			{
				$sql = 'UPDATE disaster_declare
						SET ISVERIFIED=1, ACCEPTED=1, JSON_FACTORS="' . $factors . '"
						WHERE ID=' . $form_ID;
				if($db->connection->query($sql))
				{
					echo 'success: You have Successfully Confirmed the disaster.';
				}
				else
				{
					echo 'error: Error Submission.';
				}
			}
		}
	}
?>
