<?php

session_start();
include('../form/connection.php');
include ('../function/functions.php');
$db = new db();

$session_USER_USERNAME = $_SESSION['USER_USERNAME'];
$form_EVACNAME = $db->connection->real_escape_string($_POST['NAME']);
$form_ADDRESS = $db->connection->real_escape_string($_POST['ADDRESS']);


$sql = "INSERT INTO evacuation_list (
		EVACNAME, 
		EVACADDRESS) 
		VALUES (
		'$form_EVACNAME',
		'$form_ADDRESS')";

if($db->connection->query($sql)) {
	echo 'success: ' . $db->connection->insert_id;
	console_log('USER(' . $session_USER_USERNAME . ') assigned an evacuation center(' . $db->connection->insert_id . ')', '../../system/log.txt');
}
else {
	echo 'error';
}
?>