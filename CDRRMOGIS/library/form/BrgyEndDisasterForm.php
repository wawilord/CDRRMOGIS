<?php
/*
 *__________________________________________________________________________________________________________
 *
 * TITLE: 			The Barangay End Disaster Form
 * DESCRIPTION: 	Code for the updating the end time of declared disaster in
 * 					'disaster_declare' table.
 *__________________________________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 * success: *message for success*	-> 	If encoded successfully
 * error: *message for error* 	-> 	If encoded unsuccessfully
 *__________________________________________________________________________________________________________
 *
 * VARIABLES:
 *
 * $_POST['DECLARE']
 * $_POST['ENDED']
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
$form_DECLARE = $_POST['DECLARE'];
$form_ENDED = $_POST['ENDED'];

$sql = "UPDATE disaster_declare SET ENDED = STR_TO_DATE('" . $form_ENDED . ":00', '%Y/%m/%d %H:%i:%s') WHERE ID = $form_DECLARE";

if($db->connection->query($sql)) {
	$sql2 = "SELECT ENDED FROM disaster_declare WHERE ID = $form_DECLARE";
	
	$result = $db->connection->query($sql2);
	$row = $result->fetch_assoc();
	
	echo 'success: ' . converttoformaldatetimestring($row["ENDED"]);
}
else {
	echo 'error: Failure in ending disaster.';
}
?>