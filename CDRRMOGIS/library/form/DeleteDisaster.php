<?php
	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();

	$sql = 'DELETE FROM	disaster_cost
			WHERE		DECLAREID = ' . $_POST['DECLAREID'];
	$db->connection->query($sql);
	$sql = 'DELETE FROM	disaster_declarelist
				WHERE		DECLAREID = ' . $_POST['DECLAREID'];
	$db->connection->query($sql);
	$sql = 'DELETE FROM	disaster_reports
				WHERE		DECLAREID = ' . $_POST['DECLAREID'];
	$db->connection->query($sql);
	$sql = 'DELETE FROM	evacuation_report
				WHERE		DECLAREID = ' . $_POST['DECLAREID'];
	$db->connection->query($sql);
	$sql = 'DELETE FROM	newsfeed
				WHERE		DECLAREID = ' . $_POST['DECLAREID'];
	$db->connection->query($sql);
	$sql = 'DELETE FROM	disaster_declare
				WHERE		ID = ' . $_POST['DECLAREID'];
	if($db->connection->query($sql)){
		echo 'success: Disaster Declaration Deleted.';
	}
	else{
		echo 'error: Something is wrong.';
	}
?>