<?php
/*
 *__________________________________________________________________________________________________________
 *
 * TITLE: 			The Barangay Edit Evacuation Form
 * DESCRIPTION: 	Code for editing evacuation centers in 
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
 * $_POST['ID']
 * $_POST['NAME']
 * $_POST['BARANGAY']
 * $_POST['ADDRESS1']
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
$form_EVACNAME = $db->connection->real_escape_string($_POST['NAME']);
$form_BARANGAY = $db->connection->real_escape_string($_POST['BARANGAY']);
$form_ADDRESS1 = $db->connection->real_escape_string($_POST['ADDRESS1']);

$sql = "UPDATE evacuation_list SET
		EVACNAME = '$form_EVACNAME',
		BARANGAY = $form_BARANGAY,
        EVACADDRESS1 = '$form_ADDRESS1'
		WHERE 
		ID = $form_ID";

if($db->connection->query($sql)) {
	echo 'success: ' . $db->connection->insert_id;
	console_log('USER(' . $session_USER_USERNAME . ') edited evacuation center (' . $form_ID . ')', '../../system/log.txt');
}
else {
	echo 'error';
}
 ?>