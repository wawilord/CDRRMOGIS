<?php
	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();


	$obj = array("ID" => '', "NAME" => '', "SIGNALNO" => '', "WINDSPEED" => '', "DATE" => '', "COUNT" => '');
	$name = $_POST['name'];
	$SIGNALNO = $_POST['signalno'];
	$WINDSPEED = $_POST['windspeed'];
	$time = $_POST['time'];

	$sql = 'INSERT INTO `cdrrmogisdata`.`disaster_typhoonprofile` (`ID`, `NAME`, `SIGNALNO`, `WINDSPEED`, `DATESTART`, `DATECREATED`) 
			VALUES (NULL, \'' . $name . '\', \'' . $SIGNALNO . '\', \'' . $WINDSPEED . '\', \'' . $time . ' 00:00:00\', NULL)';

	if($db->connection->query($sql)){
		$id = $db->connection->insert_id;

		$sql = "SELECT disaster_typhoonprofile.*
                FROM disaster_typhoonprofile
                WHERE disaster_typhoonprofile.ID = " . $id . " 
                GROUP BY disaster_typhoonprofile.ID
                ORDER BY disaster_typhoonprofile.DATESTART DESC";
		$result = $db->connection->query($sql);
		$count = mysqli_num_rows($result);
		$row = $result->fetch_assoc();
		$obj['ID'] = $row["ID"];
		$obj['NAME'] = $row["NAME"];
		$obj['SIGNALNO'] = $row["SIGNALNO"];
		$obj['WINDSPEED'] = $row["WINDSPEED"];
		$obj['DATE'] = getdatestring($row["DATESTART"]);
		$obj['COUNT'] = 0;

		echo 'success:' . json_encode($obj);
	}
	else{
		echo 'error: Failed adding.';
	}

?>
