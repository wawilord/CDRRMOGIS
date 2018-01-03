<?php
/*
 *__________________________________________________________________________________________________________
 *
 * TITLE: 			The Barangay Evacuation Report Form
 * DESCRIPTION: 	Code for adding a report for the assigned evacuation in 
 * 					'evacuation_reports' table.
 *__________________________________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 * success: *date time added*	-> 	If encoded successfully
 * error: *message for error* 	-> 	If encoded unsuccessfully
 *__________________________________________________________________________________________________________
 *
 * VARIABLES:
 *
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

$form_EVACID = $db->connection->real_escape_string($_POST['EVACID']);
$form_DECLAREID = $db->connection->real_escape_string($_POST['DECLAREID']);
$session_USER_USERNAME = $_SESSION['USER_USERNAME'];
$form_FAMILIES = $db->connection->real_escape_string($_POST['FAMILIES']);
$form_PERSONS = $db->connection->real_escape_string($_POST['PERSONS']);
$limit = 10000;

if($form_FAMILIES < 0 || $form_PERSONS < 0) {
	echo "error: Numbers can't be negative;";
	exit;
}
else if($form_FAMILIES > $limit || $form_PERSONS > $limit) {
	echo "error: Numbers can't exceed barangay population;";
	exit;	
}
if(!ctype_digit($form_FAMILIES) || !ctype_digit($form_PERSONS)) {
	echo "error: Numeric input is required.";
}

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
		0
		)";

if($db->connection->query($sql)) {
	$newID = $db->connection->insert_id;
	$sql2 = "SELECT DATEADDED FROM evacuation_report WHERE ID = $newID";

	$result = $db->connection->query($sql2);
	$row = $result->fetch_assoc();

	echo 'success: ' . converttoformaldatetimestring($row["DATEADDED"]);
	console_log('USER(' . $session_USER_USERNAME . ') submitted an evacuation report(' . $newID . ')', '../../system/log.txt');
}
else {
	echo 'error: Failure in report submission.';
}


?>
