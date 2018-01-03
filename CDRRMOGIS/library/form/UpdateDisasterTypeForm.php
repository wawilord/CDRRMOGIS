<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE: 			The Update Disaster Type Form
 * DESCRIPTION: 	Code for the updating of disaster types in table disaster_types.
 * _____________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 * success: *updated ID*	-> 	If encoded successfully
 * error: *message for error* 	-> 	If encoded unsuccessfully
 *______________________________________________________________________________________
 *
 * VARIABLES:
 *
 * $_POST['ID']
 * $_POST['NAME']
 * $_POST['COLOR']
 * $_POST['STATUS']
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
$form_ID = $db->connection->real_escape_string($_POST['ID']);
$form_NAME = $db->connection->real_escape_string($_POST['NAME']);
$form_COLOR = $db->connection->real_escape_string($_POST['COLOR']);
$form_STATUS = $db->connection->real_escape_string($_POST['STATUS']);

$sql = "UPDATE disaster_type SET
		NAME = '$form_NAME',
		COLOR = '$form_COLOR',
		ENABLED = $form_STATUS
		WHERE 
		ID = $form_ID";

if($db->connection->query($sql)) {
	echo 'success: ' . $form_ID;
	console_log('USER(' . $session_USER_USERNAME . ') updated disaster type(' . $form_ID . ')', '../../system/log.txt');
}
else {
	echo 'error: Failure in updating report.';
}
?>