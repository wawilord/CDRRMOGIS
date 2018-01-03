<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE: 			Update Evacuation Center Location
 * DESCRIPTION: 	Code for the updating of LAT and LNG columns of evacuation_list.
 * _____________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 * success: *message for success*	-> 	If encoded successfully
 * error: *message for error* 	-> 	If encoded unsuccessfully
 *______________________________________________________________________________________
 *
 * VARIABLES:
 *
 * $_POST['U_ID']
 * $_POST['U_LAT']
 * $_POST['U_LNG']
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
$form_ID = $db->connection->real_escape_string($_POST['U_ID']);
$form_LAT = $db->connection->real_escape_string($_POST['U_LAT']);
$form_LNG = $db->connection->real_escape_string($_POST['U_LNG']);


$sql = "UPDATE evacuation_list SET
		LAT = '$form_LAT',
		LNG = '$form_LNG'
		WHERE 
		ID = '$form_ID'";

if($db->connection->query($sql)) {
	echo 'success: Successfully updated location!';
	console_log('USER(' . $session_USER_USERNAME . ') updated the location of(' . $form_ID . ')', '../../system/log.txt');
}
else {
	echo 'error: Failure in updating location.';
}
?>