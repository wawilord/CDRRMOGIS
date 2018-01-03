<?php
/*
 *__________________________________________________________________________________________________________
 *
 * TITLE: 			The Barangay Add Evacuation Form
 * DESCRIPTION: 	Code for adding or assigning evacuation for declared disaster in 
 * 					'evacuation_assigned' table.
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
 * $_POST['DECLAREID']
 * $_POST['EVACID']
 * $_POST['PERSONS']
 * $_POST['FAMILIES']
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

$session_USER_BRGY = $_SESSION['USER_BRGY'];
$session_USER_USERNAME = $_SESSION['USER_USERNAME'];
$form_DECLAREID = $db->connection->real_escape_string($_POST['DECLAREID']);
$form_EVACID = $db->connection->real_escape_string($_POST['EVACID']);
$form_PERSONS = $db->connection->real_escape_string($_POST['PERSONS']);
$form_FAMILIES = $db->connection->real_escape_string($_POST['FAMILIES']);

$sql = "INSERT INTO evacuation_report (
		EVACID,
		DECLAREID,
		UPLOADER,
		SRVFAMILIES,
		SRVPERSONS,
		ISVERIFIED) 
		VALUES (
		$form_EVACID,
		$form_DECLAREID,
		'$session_USER_USERNAME',
		$form_FAMILIES,
		$form_PERSONS,
		0)";

if($db->connection->query($sql)) {
	echo 'success: ' . $db->connection->insert_id;
	console_log('USER(' . $session_USER_USERNAME . ') sent an evacuation report(' . $db->connection->insert_id . ')', '../../system/log.txt');
}
else {
	echo 'error';
}
?>