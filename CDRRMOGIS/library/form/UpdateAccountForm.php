<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE: 			The Update Account Form
 * DESCRIPTION: 	Code for the updating of accounts in table user_accounts.
 * _____________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 * success: success: *message for success*	-> 	If encoded successfully
 * error: error: *message for error* 	-> 	If encoded unsuccessfully
 *______________________________________________________________________________________
 *
 * VARIABLES:
 *
 * $_POST['USERNAME']
 * $_POST['PASSWORD']
 * $_POST['TYPE']
 * $_POST['FIRSTNAME']
 * $_POST['MIDDLENAME']
 * $_POST['LASTNAME']
 * $_POST['STATUS'];
 * $_POST['BARANGAY']
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
$form_USERNAME = $db->connection->real_escape_string($_POST['USERNAME']);
$form_PASSWORD = $db->connection->real_escape_string($_POST['PASSWORD']);
$form_TYPE = isset($_POST['TYPE']) ? $_POST['TYPE'] : '';
$form_FIRSTNAME = $db->connection->real_escape_string($_POST['FIRSTNAME']);
$form_MIDDLENAME = $db->connection->real_escape_string($_POST['MIDDLENAME']);
$form_LASTNAME = $db->connection->real_escape_string($_POST['LASTNAME']);
$form_STATUS = isset($_POST['STATUS']) ? $_POST['STATUS'] : '';
$form_BARANGAY = $db->connection->real_escape_string($_POST['BARANGAY']);

if($form_TYPE == '') {
	$form_BARANGAY = 'NULL';
	$newtype = "";
}
else if($form_TYPE != 'D') {
	$form_BARANGAY = 'NULL';
	$newtype = "TYPE = '$form_TYPE',";
}
else {
	$newtype = "TYPE = '$form_TYPE',";
}

if($form_STATUS == '') {
	$newstatus = "";
}
else {
	$newstatus = "ENABLED = $form_STATUS,";
}

if($form_PASSWORD == '') {
	$newpass = "";
}
else {
	$newpass = "PASSWORD = '$form_PASSWORD',";
}

$sql = "UPDATE user_accounts SET
		$newpass
		$newtype
		FIRSTNAME = '$form_FIRSTNAME',
		MIDDLENAME = '$form_MIDDLENAME',
		LASTNAME = '$form_LASTNAME',
		$newstatus
		BRGY = $form_BARANGAY
		WHERE 
		USERNAME = '$form_USERNAME'";

if($db->connection->query($sql)) {
	echo 'success: Successfully updated account!';
	console_log('USER(' . $session_USER_USERNAME . ') updated an account(' . $form_USERNAME . ')', '../../system/log.txt');
}
else {
	echo 'error: Failure in updating report.';
}
?>