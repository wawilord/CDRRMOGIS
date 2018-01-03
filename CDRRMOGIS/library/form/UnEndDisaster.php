<?php
	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();

	$sql = 'UPDATE			disaster_declare
			SET				ENDED = NULL
			WHERE		ID = ' . $_POST['DECLAREID'];
	if($db->connection->query($sql)){
		echo 'success: Disaster Status Changed to going on.';
	}
	else{
		echo 'error: Something is wrong.';
	}
?>