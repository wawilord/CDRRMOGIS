<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE: 			The Add News Form
 * DESCRIPTION: 	Code for the creation of new news in table newsfeed.
 * _____________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 * success: success: json_encode($array)	-> 	If encoded successfully
 * error: *message for error* 	-> 	If encoded unsuccessfully
 *______________________________________________________________________________________
 *
 * VARIABLES:
 *
 * $_POST['CONTENT']
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
$form_CONTENT = $db->connection->real_escape_string($_POST['CONTENT']);

$sql = "INSERT INTO newsfeed (
		CONTENT,
		POSTBY)
		VALUES (
		'$form_CONTENT',
		'$session_USER_USERNAME')";
		
if($db->connection->query($sql)) {
	$post_id = $db->connection->insert_id;
	$message = array('id' => $post_id, 'content' => $form_CONTENT, 'postby' => $session_USER_USERNAME, 'timestamp' => date('M d, Y H:i', time()));
	echo 'success: ' . json_encode($message);
	console_log('USER(' . $session_USER_USERNAME . ') added a post(' . $post_id . ')', '../../system/log.txt');
}
else {
	echo 'error: Failure in account submission.';
}