<?php
/*
 *__________________________________________________________________________________________________________
 *
 * TITLE: 			The Barangay Delete Evacuation Form
 * DESCRIPTION: 	Code for deleting assigned evacuation for declared disaster in 
 * 					'evacuation_assigned' table.
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
 * $_POST['DELID']
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
$form_DELID = $_POST['DELID'];

$sql = "DELETE FROM evacuation_assigned WHERE ID = $form_DELID";

if($db->connection->query($sql)) {
	echo 'success: Evacuation center successfully removed.';
}
else {
	echo 'error: Failure in evacuation center removal.';
}
?>