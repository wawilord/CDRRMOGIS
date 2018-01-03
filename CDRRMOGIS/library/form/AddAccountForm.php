<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE: 			The Add Account Form
 * DESCRIPTION: 	Code for the creation of new accounts in table user_accounts.
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
 * $_POST['USERNAME']
 * $_POST['PASSWORD']
 * $_POST['TYPE']
 * $_POST['FIRSTNAME']
 * $_POST['MIDDLENAME']
 * $_POST['LASTNAME']
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
$form_TYPE = $db->connection->real_escape_string($_POST['TYPE']);
$form_FIRSTNAME = $db->connection->real_escape_string($_POST['FIRSTNAME']);
$form_MIDDLENAME = $db->connection->real_escape_string($_POST['MIDDLENAME']);
$form_LASTNAME = $db->connection->real_escape_string($_POST['LASTNAME']);
$form_BARANGAY = $db->connection->real_escape_string($_POST['BARANGAY']);

$sql = "SELECT USERNAME FROM user_accounts WHERE USERNAME = '$form_USERNAME'";
$result = $db->connection->query($sql);
$count = mysqli_num_rows($result);

if((strlen(trim($form_USERNAME)) < 1) and (strlen(trim($form_PASSWORD)) < 1)) {
	echo 'error: Username and password must use at least one character.';
	exit;
}

else if(str_word_count($form_USERNAME) > 1) {
	echo 'error: Username must not contain spaces.';
	exit;
}

if($count > 0) {
	echo 'error: Username already exists.';
	exit;
}

if($form_TYPE != 'D') {
	$form_BARANGAY = 'NULL';
}

$sql = "INSERT INTO user_accounts (
		USERNAME,
		PASSWORD,
		TYPE,
		FIRSTNAME,
		MIDDLENAME,
		LASTNAME,
		ENABLED,
		BRGY)
		VALUES (
		'$form_USERNAME',
		'$form_PASSWORD',
		'$form_TYPE',
		'$form_FIRSTNAME',
		'$form_MIDDLENAME',
		'$form_LASTNAME',
		1,
		$form_BARANGAY
		)";

if($db->connection->query($sql)) {
	echo 'success: Successfully created account!';
	console_log('USER(' . $session_USER_USERNAME . ') added an account(' . $form_USERNAME . ')', '../../system/log.txt');
}
else {
	echo 'error: Failure in account submission.';
}
?>