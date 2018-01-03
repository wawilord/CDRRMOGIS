<?php
/*
 * ___________________________________________________________________________________
 *
 * TITLE: 			The Add Barangay Form
 * DESCRIPTION: 	Code for the creation of new barangays in table barangay.
 * ___________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 * success: new ID insert	-> 	If encoded successfully
 * error: message for error 	-> 	If encoded unsuccessfully
 *____________________________________________________________________________________
 *
 * VARIABLES:
 *
 * $_POST['NAME']
 * $_POST['DISTRICT']
 * $_POST['MEN']
 * $_POST['WOMEN']
 * $_POST['MINORS']
 * $_POST['ADULTS']
 * $_POST['PWD']
 * $_POST['LAT']
 * $_POST['LNG']
 *
 *________________________________________________________________________________________________________
 *
 * Note: you can send a custom message by typing keywords 'msg:', 'success:', 'error:', and 'warning:' 
 *		 followed by your message. Just make sure that there are no printed or echoed before the keyword.
 *________________________________________________________________________________________________________
 *
 */
 
session_start();
include('../form/connection.php');
include ('../function/functions.php');
$db = new db();

$session_USER_USERNAME = $_SESSION['USER_USERNAME'];
$form_NAME = $db->connection->real_escape_string($_POST['NAME']);
$form_DISTRICT = $db->connection->real_escape_string($_POST['DISTRICT']);
$form_MAPID = $db->connection->real_escape_string($_POST['MAPID']);

$sql = 'SELECT MAPID
			FROM barangay 
			WHERE
			MAPID = '.$form_MAPID;
	$result = $db->connection->query($sql);
	$count = mysqli_num_rows($result);
	if($count > 0){
		echo 'error: Location is already chosen!';
	}
else
{

	$sql = "INSERT INTO barangay (
			NAME,
			DISTRICT,
			MAPID)
			VALUES (
			'$form_NAME',
			'$form_DISTRICT',
			'$form_MAPID')";

			if($db->connection->query($sql)) 
			{
				$brgy_id = $db->connection->insert_id;
				echo 'success';
				console_log('USER(' . $session_USER_USERNAME . ') added a barangay(' . $brgy_id . ')', '../../system/log.txt');
			}
	else {
		echo 'error: Failure in account submission.';
	}
}


?>