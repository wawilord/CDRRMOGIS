<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE: 			The Update Barangay Location Form
 * DESCRIPTION: 	Code for the updating of barangay locations.
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
 * $_POST['BARANGAY']
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
$form_BARANGAY = $db->connection->real_escape_string($_POST['BARANGAY']);
$form_LAT = $_POST['LAT'];
$form_LNG = $_POST['LNG'];

$sql_delete = "DELETE FROM
			barangay_coordinates
			WHERE 
			BARANGAY = $form_BARANGAY";

if($db->connection->query($sql_delete)) {
	$check = true;
	$index = 0;
	
	foreach($form_LAT as $value) {
		$sql_insert = "INSERT INTO barangay_coordinates (
		BARANGAY,
		LAT,
		LNG)
		VALUES (
		$form_BARANGAY,
		$value,
		$form_LNG[$index]
		)";
		$index++;
		if($db->connection->query($sql_insert)) {
			
		}
		else {
			$check = false;
		}
	}
	
	if($check) {
		echo 'success: ' . $form_BARANGAY;
		console_log('USER(' . $session_USER_USERNAME . ') updated location of barangay(' . $brgy_id . ')', '../../system/log.txt');
	}
	else {
		echo 'error: Failure in adding map coordinates.';
	}
}
else {
	echo 'error: Failure in map updating.';
}
?>