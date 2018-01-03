<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE: 			The Update Password Form
 * DESCRIPTION: 	Code for the updating of passwords for all users in user_accounts.
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
 * $_POST['OLDPASS']
 * $_POST['NEWPASS1']
 * $_POST['NEWPASS2']
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
$form_OLDPASS = $db->connection->real_escape_string($_POST['OLDPASS']);
$form_NEWPASS1 = $db->connection->real_escape_string($_POST['NEWPASS1']);
$form_NEWPASS2 = $db->connection->real_escape_string($_POST['NEWPASS2']);

$check_sql = "SELECT PASSWORD from user_accounts WHERE USERNAME = '$session_USER_USERNAME'";
$check_result = $db->connection->query($check_sql);
$check_count = mysqli_num_rows($check_result);
while ($check_row = $check_result->fetch_assoc()) {
    $check_PASS = htmlspecialchars($check_row['PASSWORD']);
}

if($check_count != 1) {
    echo "error: Your account does not exist.";
    exit;  
}

if ($check_PASS != $form_OLDPASS) {
    echo "error: Old password is incorrect.";
    exit;
}

if ($form_NEWPASS1 != $form_NEWPASS2) {
    echo "error: New passwords don't match.";
    exit;
}

$sql = "UPDATE user_accounts SET
		PASSWORD = '$form_NEWPASS1'
		WHERE 
		USERNAME = '$session_USER_USERNAME'";

if($db->connection->query($sql)) {
    echo 'success: Successfully updated password!';
	console_log('USER(' . $session_USER_USERNAME . ') updated the account\'s password', '../../system/log.txt');
}
else {
    echo 'error: Failure in updating password.';
}
?>