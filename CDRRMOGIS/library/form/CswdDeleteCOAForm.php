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

$session_USER_USERNAME = $_SESSION['USER_USERNAME'];
$form_ID = $_POST['ID'];

$sql = "DELETE FROM disaster_cost WHERE ID = $form_ID";

if($db->connection->query($sql)) {
	echo 'success: Deletion Success!';
}
else {
	echo 'error: Deletion Failed.';
}
?>