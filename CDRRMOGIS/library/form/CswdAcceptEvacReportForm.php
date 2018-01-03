<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE:
 * DESCRIPTION:
 * _____________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 *______________________________________________________________________________________
 *
 * VARIABLES:
 *
 *
 *______________________________________________________________________________________
 *
 * Note: you can send a custom error message by typing 'msg:' followed by your message.
 * 		 Just make sure that there are no printed or echoed before the 'msg:' keyword.
 *______________________________________________________________________________________
 *
 */
	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();

	$form_ID = $_POST['ID'];
	$session_USER_USERNAME = $_SESSION['USER_USERNAME'];

	$sql = 'UPDATE evacuation_report SET ISVERIFIED=1 WHERE ID=' . $form_ID;

	if($db->connection->query($sql)) {
		echo 'success';
		console_log('USER(' . $session_USER_USERNAME . ') accepted the evacuation report(' . $form_ID . ')', '../../system/log.txt');
	}
	else {
		echo 'error';
	}
?>
