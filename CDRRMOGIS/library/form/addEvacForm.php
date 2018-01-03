<?php
/*
 *__________________________________________________________________________________________________________
 *
 * TITLE: 			The Barangay Add Evacuation Form
 * DESCRIPTION: 	Code for adding evacuation centers in 
 * 					'evacuation_list' table.
 *__________________________________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 * success: *id of inserted record*	-> 	If encoded successfully
 * error: *message for error* 	-> 	If encoded unsuccessfully
 *__________________________________________________________________________________________________________
 *
 * VARIABLES:
 *
 * $_POST['NAME']
 * $_POST['BARANGAY']
 * $_POST['ADDRESS1']
 * $_POST['ADDRESS2']
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
$form_EVACNAME = $db->connection->real_escape_string($_POST['NAME']);
$form_ADDRESS1 = $db->connection->real_escape_string($_POST['ADDRESS']);
$form_BARANGAY = $db->connection->real_escape_string($_POST['BARANGAY']);

$sql = "INSERT INTO evacuation_list (
		EVACNAME,
		BARANGAY, 
		EVACADDRESS1) 
		VALUES (
		'$form_EVACNAME',
		'$form_BARANGAY',
		'$form_ADDRESS1')";

if($db->connection->query($sql)) {
	echo 'success: ' . $db->connection->insert_id;
	console_log('USER(' . $session_USER_USERNAME . ') assigned an evacuation center(' . $db->connection->insert_id . ')', '../../system/log.txt');
}
else {
	echo 'error';
}
?>