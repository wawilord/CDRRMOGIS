<?php
	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();

	$obj = array("ID" => '', "DATE" => '', "TYPE" => '', "NAME" => '', "COUNT" => '');
	$name = $_POST['name'];
	$disaster = $_POST['disaster'];
	$time = $_POST['time'];

	$sql = 'INSERT INTO `cdrrmodata`.`disaster_profile` (`ID`, `NAME`, `TYPE`, `DATESTART`, `DATECREATED`) 
			VALUES (NULL, \'' . $name . '\', \'' . $disaster . '\', \'' . $time . ' 00:00:00\', NULL)';

	if($db->connection->query($sql)){
		$id = $db->connection->insert_id;

		$sql = "SELECT disaster_profile.*, disaster_type.NAME AS TYPENAME
                FROM disaster_profile, disaster_type
                WHERE disaster_profile.TYPE = disaster_type.ID
                AND disaster_profile.ID = " . $id . " 
                GROUP BY disaster_profile.ID
                ORDER BY disaster_profile.DATESTART DESC";
		$result = $db->connection->query($sql);
		$count = mysqli_num_rows($result);
		$row = $result->fetch_assoc();
		$obj['ID'] = $row["ID"];
		$obj['TYPE'] = $row["TYPENAME"];
		$obj['NAME'] = $row["NAME"];
		$obj['DATE'] = getdatestring($row["DATESTART"]);
		$obj['COUNT'] = 0;

		echo 'success:' . json_encode($obj);
	}
	else{
		echo 'error: Failed adding.';
	}

?>
