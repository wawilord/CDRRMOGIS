<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE: 			The Add Disaster Type Form
 * DESCRIPTION: 	Code for the creation of new accounts in table user_accounts.
 * _____________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 * success: *ID of submission*	-> 	If encoded successfully
 * error: *message for error* 	-> 	If encoded unsuccessfully
 *______________________________________________________________________________________
 *
 * VARIABLES:
 *
 * $_POST['NAME']
 * $_POST['COLOR']
 * $_POST['FACTORS']
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
$form_COLOR = $db->connection->real_escape_string($_POST['COLOR']);
$form_FACTORS = $_POST['FACTORS'];

$sql = "INSERT INTO disaster_type (
		NAME,
		COLOR,
		ENABLED)
		VALUES (
		'$form_NAME',
		'$form_COLOR',
		1
		)";

if($db->connection->query($sql)) {
	$new_id = $db->connection->insert_id;

	foreach($form_FACTORS as $value) {
		$sql_fac = "INSERT INTO disaster_factors (
				FACTOR_NAME,
				TYPE_ID)
				VALUES (
				'$value',
				$new_id)";
		$db->connection->query($sql_fac);
	}

	echo 'success: ' . $new_id;
	console_log('USER(' . $session_USER_USERNAME . ') added disaster type(' . $new_id . ')', '../../system/log.txt');
}
else {
	echo 'error: Failure in disaster type submission.';
}
?>