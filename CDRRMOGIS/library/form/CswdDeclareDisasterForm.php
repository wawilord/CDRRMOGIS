<?php
	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();


	$disaster_name = '';
	$article = '';
	$barangay_name = '';

	$bsql = "SELECT NAME FROM barangay WHERE ID = " . $_POST['BRGY'];
	$bresult = $db->connection->query($bsql);
	while ($brow = $bresult->fetch_assoc()) {
		$barangay_name = htmlspecialchars($brow['NAME']);
	}

	$dsql = "SELECT NAME FROM disaster_type WHERE ID = " . $_POST['DISASTER'];
	$dresult = $db->connection->query($dsql);
	while ($drow = $dresult->fetch_assoc()) {
		$disaster_name = strtolower(htmlspecialchars($drow['NAME']));
		if(in_array(substr($disaster_name, 0, 1), array('a','e','i','o','u')))
			$article = "An";
		else
			$article = "A";
	}

	$sql = "INSERT INTO `cdrrmodata`.`disaster_declare` (`ID`, 
														`DISASTER`, 
														`BRGY`, 
														`POSTBY`, 
														`NICKNAME`, 
														`STARTED`, 
														`ENDED`, 
														`COMMENT`, 
														`DATECREATED`, 
														`LAT`, 
														`LNG`, 
														`RADIUS`, 
														`ISVERIFIED`, 
														`JSON_FACTORS`) 
		VALUES 											(NULL, 
														'" . $_POST['DISASTER'] . "', 
														'" . $_POST['BRGY'] . "', 
														'" . $_SESSION['USER_USERNAME'] . "', 
														'" . $_POST['NICKNAME'] . "', 
														'" . $_POST['STARTED'] . "', 
														NULL, 
														'" . $_POST['COMMENT'] . "', 
														CURRENT_TIMESTAMP, 
														'" . $_POST['LAT'] . "', 
														'" . $_POST['LNG'] . "', 
														'" . $_POST['RAD'] . "', 
														'0', 
														NULL);";

	if($result = $db->connection->query($sql))
	{
		$brgy_id = $db->connection->insert_id;
		echo 'success';

		$content = "$article $disaster_name has occured in Brgy. $barangay_name.";
		$sql_news = "INSERT INTO newsfeed (
			CONTENT,
			POSTBY,
			DECLAREID)
			VALUES (
			'$content',
			'" . $_SESSION['USER_USERNAME'] . "',
			$brgy_id)";
		$db->connection->query($sql_news);
	}
	else
	{
		echo 'error';
	}
?>