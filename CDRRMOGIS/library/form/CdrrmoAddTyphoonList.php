<?php
	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();

	$groupid = $_POST['groupid'];
	$ids = json_decode($_POST['id']);
	$sqlargs = '';

	foreach ($ids as $id){
		$sqlargs .= '(' . $groupid . ', ' . $id . '),';
	}
	$sqlargs = substr($sqlargs, 0, strlen($sqlargs)-1);
	$sql = 'INSERT INTO disaster_typhoonlist (PROFILEID, DECLAREID)
			VALUES ' . $sqlargs;

	if($db->connection->query($sql)){
		echo 'success: Added to the list.';
	}
	else{
		echo 'error: Failed to add.';
	}
?>
