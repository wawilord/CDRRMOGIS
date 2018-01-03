<?php

/*
POST VALUES:
	DISASTER
	NICKNAME
	STARTED
	COMMENT
	LAT
	LNG
	RAD
	DECLAREID
*/
	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();

	$sql = 'UPDATE			disaster_declare
			SET				DISASTER = ' . $_POST["DISASTER"] . ',
							NICKNAME = "' . $db->connection->real_escape_string($_POST["NICKNAME"]) . '",
							STARTED = "' . $_POST["STARTED"] . '",
							COMMENT = "' . $db->connection->real_escape_string($_POST["COMMENT"]) . '",
							LAT = ' . $_POST["LAT"] . ',
							LNG = ' . $_POST["LNG"] . ',
							RADIUS = ' . $_POST["RAD"] . '
			WHERE			ID = ' . $_POST["DECLAREID"];
	if($db->connection->query($sql)){
		echo 'success: Edit Success!';
	}
	else{
		echo 'error: Something is wrong. :(';
	}
?>