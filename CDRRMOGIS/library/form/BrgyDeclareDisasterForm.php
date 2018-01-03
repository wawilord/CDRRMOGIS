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
	$form_COMMENT = $db->connection->real_escape_string($_POST['COMMENT']);
	$form_LAT = $db->connection->real_escape_string($_POST['LAT']);
	$form_LNG = $db->connection->real_escape_string($_POST['LNG']);
	$form_RAD = $db->connection->real_escape_string($_POST['RAD']);
	$session_USER_BRGY = $db->connection->real_escape_string($_SESSION['USER_BRGY']);
	$session_USER_USERNAME = $db->connection->real_escape_string($_SESSION['USER_USERNAME']);


	date_default_timezone_set('Asia/Hong_Kong');
	$time = time();

	if(date("Y/m/d", $time) < substr($form_STARTED,0, 10))
	{
		echo 'msg: Are you trying to declare a disaster that started in the future? That\'s impossible. Please check the start date of your disaster.';
		exit;
	}

	//sql
	$sql = "SELECT ID FROM disaster_declare WHERE BRGY = " . $_SESSION['USER_BRGY'] . " AND NICKNAME = '" . $form_NICKNAME . "'";
	$result = $db->connection->query($sql);
	$count = mysqli_num_rows($result);
	if($count > 0)
	{
		echo 'msg: You already made a disaster with a nickname of <b>' . htmlspecialchars($_POST['NICKNAME']) . '</b>';
		exit;
	}
	
	$bsql = "SELECT NAME FROM barangay WHERE ID = $session_USER_BRGY";
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
			COMMENT,
			POSTBY,
			LAT,
			LNG,
			RADIUS)
			VALUES(
			'" . $form_NICKNAME . "',
			" . $form_DISASTER. ",
			" . $session_USER_BRGY . ",
			STR_TO_DATE('" . $form_STARTED . ":00', '%Y/%m/%d %H:%i:%s'),
			'" . $form_COMMENT . "',
			'" . $session_USER_USERNAME . "',
			'" . $form_LAT . "',
			'" . $form_LNG . "',
			'" . $form_RAD . "'
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