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
		$sqlargs .= $id . ',';
	}
	$sqlargs = substr($sqlargs, 0, strlen($sqlargs)-1);
	$sql = 'DELETE FROM disaster_declarelist 
				WHERE DECLAREID IN (' . $sqlargs . ')
				AND PROFILEID = ' . $groupid;
	if($db->connection->query($sql)){
		echo 'success: Deleted from the list.';
	}
	else{
		echo 'error: Failed to delete';
	}
?>
