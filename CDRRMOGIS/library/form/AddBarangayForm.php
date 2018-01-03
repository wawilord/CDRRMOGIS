<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE: 			The Add Barangay Form
 * DESCRIPTION: 	Code for the creation of new barangays in table barangay.
 * _____________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 * success: *new ID insert*	-> 	If encoded successfully
 * error: *message for error* 	-> 	If encoded unsuccessfully
 *______________________________________________________________________________________
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
 *__________________________________________________________________________________________________________
 *
 * Note: you can send a custom message by typing keywords 'msg:', 'success:', 'error:', and 'warning:' 
 *		 followed by your message. Just make sure that there are no printed or echoed before the keyword.
 *__________________________________________________________________________________________________________
 *
 */
 
session_start();
include('../form/connection.php');
include ('../function/functions.php');
$db = new db();

$session_USER_USERNAME = $_SESSION['USER_USERNAME'];
$form_NAME = $db->connection->real_escape_string($_POST['NAME']);
$form_DISTRICT = $db->connection->real_escape_string($_POST['DISTRICT']);
$form_MEN = $db->connection->real_escape_string($_POST['MEN']);
$form_WOMEN = $db->connection->real_escape_string($_POST['WOMEN']);
$form_MINORS = $db->connection->real_escape_string($_POST['MINORS']);
$form_ADULTS = $db->connection->real_escape_string($_POST['ADULTS']);
$form_PWD = $db->connection->real_escape_string($_POST['PWD']);
$form_LIGHT = $db->connection->real_escape_string($_POST['LIGHT']);
$form_CONCRETE = $db->connection->real_escape_string($_POST['CONCRETE']);
$form_BOTH = $db->connection->real_escape_string($_POST['BOTH']);
$form_AREA = $db->connection->real_escape_string($_POST['AREA']);

$sql = "INSERT INTO barangay (
		NAME,
		DISTRICT)
		VALUES (
		'$form_NAME',
		$form_DISTRICT
		)";

if($db->connection->query($sql)) {
	$brgy_id = $db->connection->insert_id;
	$sql2 = "INSERT INTO barangay_info (
			BARANGAY,
			MEN,
			WOMEN,
			MINORS,
			ADULTS,
			PWD,
			T_HOUSES,
			C_HOUSES,
			L_HOUSES,
			CL_HOUSES,
			Area,
			isFloodProne)
			VALUES (
			$brgy_id,
			$form_MEN,
			$form_WOMEN,
			$form_MINORS,
			$form_ADULTS,
			$form_PWD,
			$form_LIGHT + $form_CONCRETE + $form_BOTH,
			$form_CONCRETE,
			$form_LIGHT,
			$form_BOTH,
			$form_AREA,
			0
			)";
			

		if($db->connection->query($sql2)) {
			echo 'success: ' . $brgy_id;
			console_log('USER(' . $session_USER_USERNAME . ') added a barangay(' . $brgy_id . ')', '../../system/log.txt');
		}
	else {
		echo 'error: Failure in adding barangay information.';
	}
}
else {
	echo 'error: Failure in account submission.';
}
?>