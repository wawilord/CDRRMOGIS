<?php
	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();

	$id = $_POST['id'];
	$name = $_POST['name'];
	$disaster = $_POST['disaster'];
	$time = $_POST['time'] . ' 00:00:00';

	$original_type = '';
	$original_date = '';
	$original_name = '';
	$sql = 'SELECT * FROM disaster_profile WHERE ID = ' . $id;
	$result = $db->connection->query($sql);
	$row = $result->fetch_assoc();
	$original_name = $row['NAME'];
	$original_date = $row['DATESTART'];
	$original_type = $row['TYPE'];

	if($original_name === $name and $original_type === $disaster and $original_date === $time){
		echo 'msg: Hmmmm... Looks like there is no changes.';
		exit;
	}

	if($original_type != $disaster){
		$sql = 'SELECT * FROM disaster_declarelist WHERE PROFILEID = ' . $id;
		$result = $db->connection->query($sql);
		$count = mysqli_num_rows($result);
		if($count > 0){
			echo 'error: We cannot change disaster type since there are existing disaster declare included to this group. Please delete those.';
			exit;
		}
	}

	if($original_date != $time){
		$sql = 'SELECT * 
				FROM disaster_declarelist, disaster_declare 
				WHERE disaster_declarelist.PROFILEID = ' . $id . '
				AND disaster_declare.ID = disaster_declarelist.DECLAREID
				AND disaster_declare.STARTED < "' . $time . '"';
		$result = $db->connection->query($sql);
		$count = mysqli_num_rows($result);
		if($count > 0){
			echo 'error: Date doesn\'t comply with some disaster declare in this group. Please delete them first.';
			exit;
		}
	}

	$sql = 'UPDATE 	disaster_profile
			SET 	`NAME` = "' . $name . '",
					`TYPE` = ' . $disaster . ',
					DATESTART = "' . $time . '"
			WHERE 	ID = ' . $id;
	if($db->connection->query($sql)){
		echo 'success: Update Successful';
	}
	else{
		echo 'success: Something is wrong';
	}
?>
