<?php

	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();

/*
ID
TIME
DEAD
INJURED
MISSING
TOTALLY
PARTIALLY
 */

$sql = 'UPDATE			disaster_reports
		SET				CSLTDEAD = ' . $_POST["DEAD"] . ',
						CSLTINJURED = ' . $_POST["INJURED"] . ',
						CSLTMISSING = ' . $_POST["MISSING"] . ',
						DMGTOTALLY = ' . $_POST["TOTALLY"] . ',
						DMGPARTIALLY = ' . $_POST["PARTIALLY"] . ',
						DATEADDED = "' . $_POST["TIME"] . '"
		WHERE			ID = ' . $_POST["ID"];
if($db->connection->query($sql)){
	echo 'success: Edit Success!';
}
else{
	echo 'error: Something is wrong. :(';
}
?>