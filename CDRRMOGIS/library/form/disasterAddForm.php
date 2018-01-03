<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE: 			The Barangay Declare Disaster Form
 * DESCRIPTION: 	Code for the encoding of declared disaster by the barangay in
 * 					'disaster_declare' table.
 * _____________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 * success 	-> 	If encoded successfully
 * error 	-> 	If encoded unsuccessfully
 *______________________________________________________________________________________
 *
 * VARIABLES:
 *
 * $_POST['DISASTER']	->	select
 * $_POST['NICKNAME']	->	text
 * $_POST['STARTED']	->	text
 * $_POST['COMMENT']	->	textarea
 * $_POST['LAT']	->	hidden
 * $_POST['LNG']	->	hidden
 * $_POST['RAD']	->	hidden
 *
 *______________________________________________________________________________________
 *
 * Note: you can send a custom error message by typing 'msg:' followed by your message.
 * 		 Just make sure that there are no printed or echoed before the 'msg:' keyword.
 *______________________________________________________________________________________
 *
 */
	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();

	//form variables
	$form_DISASTER = $db->connection->real_escape_string($_POST['DISASTER']);
	$form_NICKNAME = $db->connection->real_escape_string($_POST['NICKNAME']);
	$form_STARTED = $db->connection->real_escape_string($_POST['STARTED']);
	$form_ENDED = $db->connection->real_escape_string($_POST['ENDED']);
	$form_COMMENT = $db->connection->real_escape_string($_POST['COMMENT']);
	// $form_LAT = $db->connection->real_escape_string($_POST['LAT']);
	// $form_LNG = $db->connection->real_escape_string($_POST['LNG']);
	// $form_RAD = $db->connection->real_escape_string($_POST['RAD']);
	$form_BARANGAY = $db->connection->real_escape_string($_POST['BARANGAY']);
	$session_USER_BRGY = $db->connection->real_escape_string($_SESSION['USER_BRGY']);
	$session_USER_USERNAME = $db->connection->real_escape_string($_SESSION['USER_USERNAME']);


	
	$bsql = "SELECT NAME FROM barangay WHERE ID = $form_BARANGAY";
	$bresult = $db->connection->query($bsql);
	while ($brow = $bresult->fetch_assoc()) {
		$barangay_name = htmlspecialchars($brow['NAME']);
	}
	
	$dsql = "SELECT NAME FROM disaster_type WHERE ID = $form_DISASTER";
	$dresult = $db->connection->query($dsql);
	while ($drow = $dresult->fetch_assoc()) {
		$disaster_name = strtolower(htmlspecialchars($drow['NAME']));
		if(in_array(substr($disaster_name, 0, 1), array('a','e','i','o','u'))) 
			$article = "An";
		else
			$article = "A";
	}

	$sql = "INSERT INTO disaster_declare(
			NICKNAME, 
			DISASTER, 
			BRGY, 
			STARTED,
			ENDED,
			COMMENT,
			POSTBY,
			LAT,
			LNG,
			RADIUS,
			ISVERIFIED)
			VALUES(
			'" . $form_NICKNAME . "',
			" . $form_DISASTER. ",
			" . $form_BARANGAY . ",
			STR_TO_DATE('" . $form_STARTED . ":00', '%Y/%m/%d %H:%i:%s'),
			STR_TO_DATE('" . $form_ENDED . ":00', '%Y/%m/%d %H:%i:%s'),
			'" . $form_COMMENT . "',
			'" . $session_USER_USERNAME . "',
			0,
			0,
			0,
			1
			)";

	if($result = $db->connection->query($sql))
	{
		$brgy_id = $db->connection->insert_id;
		echo 'success';
		console_log('USER(' . $session_USER_USERNAME . ') declared a disaster(' . $brgy_id . ')', '../../system/log.txt');
		
		$content = "$article $disaster_name has occured in Brgy. $barangay_name.";
		$sql_news = "INSERT INTO newsfeed (
		CONTENT,
		POSTBY,
		DECLAREID)
		VALUES (
		'$content',
		'$session_USER_USERNAME',
		$brgy_id)";
		$db->connection->query($sql_news);
	}
	else
	{
		echo 'error';
	}
?>