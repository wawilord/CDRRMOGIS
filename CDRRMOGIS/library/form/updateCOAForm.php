<?php
session_start();
include('../form/connection.php');
include ('../function/functions.php');
$db = new db();

$session_USER_BRGY = $_SESSION['USER_BRGY'];
$session_USER_USERNAME = $_SESSION['USER_USERNAME'];
$form_DECLAREID = $db->connection->real_escape_string($_POST['DECLAREID']);
$form_DSWD = $db->connection->real_escape_string($_POST['DSWD']);
$form_LGU = $db->connection->real_escape_string($_POST['LGU']);
$form_NGO = $db->connection->real_escape_string($_POST['NGO']);

$sql = "INSERT INTO disaster_cost (
		DECLAREID,
		DSWD,
		LGU,
		NGO) 
		VALUES (
		$form_DECLAREID,
		$form_DSWD,
		$form_LGU,
		$form_NGO)";

if($db->connection->query($sql)) {
	$LastID = $db->connection->insert_id;
	$sql = '
			SELECT 
					DATEADDED
			FROM
					disaster_cost
			WHERE
					ID = ' . $LastID;
	$result = $db->connection->query($sql);
	while ($row = $result->fetch_assoc()) {

		echo 'success: ' . $LastID . '!' . converttoformaldatetimestring($row['DATEADDED']);
		console_log('USER(' . $session_USER_USERNAME . ') added a cost of assistance(' . $db->connection->insert_id . ')', '../../system/log.txt');
	}
}
else {
	echo 'error: Cannot update please try again.';
}

?>