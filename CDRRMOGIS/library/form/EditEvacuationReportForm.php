<?php

	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();

$sql = 'UPDATE			evacuation_report
		SET				SRVPERSONS = ' . $_POST["PERSONS"] . ',
						SRVFAMILIES = ' . $_POST["FAMILIES"] . ',
						DATEADDED = "' . $_POST["TIME"] . '"
		WHERE			ID = ' . $_POST["ID"];
if($db->connection->query($sql)){
	echo 'success: Edit Success!';
}
else{
	echo 'error: Something is wrong. :(';
}
?>