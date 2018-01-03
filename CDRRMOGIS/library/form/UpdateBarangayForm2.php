<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE: 			The Update Barangay Form 2
 * DESCRIPTION: 	Code for the updating of barangay info in table barangay_info.
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
 * $_POST['MEN']
 * $_POST['WOMEN']
 * $_POST['MEN']
 * $_POST['MINORS']
 * $_POST['ADULTS']
 * $_POST['PWD']
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
$form_MEN = $db->connection->real_escape_string($_POST['MEN']);
$form_WOMEN = $db->connection->real_escape_string($_POST['WOMEN']);
$form_MINORS = $db->connection->real_escape_string($_POST['MINORS']);
$form_ADULTS = $db->connection->real_escape_string($_POST['ADULTS']);
$form_PWD = $db->connection->real_escape_string($_POST['PWD']);
$form_LIGHT = $db->connection->real_escape_string($_POST['LIGHT']);
$form_CONCRETE = $db->connection->real_escape_string($_POST['CONCRETE']);
$form_BOTH = $db->connection->real_escape_string($_POST['BOTH']);
$form_AREA = $db->connection->real_escape_string($_POST['AREA']);


$sql = "INSERT INTO barangay_info (
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
		$form_ID,
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

if($db->connection->query($sql)) {
	echo 'success: ' . $form_ID;
	console_log('USER(' . $session_USER_USERNAME . ') updated barangay info(' . $form_ID . ')', '../../system/log.txt');
}
else {
	echo 'error: Failure in updating report.';
}
?>