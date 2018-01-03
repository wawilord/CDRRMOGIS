<?php
//echo 'msg: ';
/*
 *
 * ADDING MINOR DISASTER REPORT FORM (INSERTING)
 *
 *
 * Variables:
 *
 * $_POST['DISASTER']
 * $_POST['BARANGAY']
 * $_POST['DEAD']
 * $_POST['INJURED']
 * $_POST['MISSING']
 * $_POST['TOTALLY']
 * $_POST['PARTIALLY']
 * $_POST['EVACUEES']
 *
 *
 * echo 'success';      -> When successfully inserted
 * echo 'error';        -> When unsuccessful
 *
 *
 * note: if you want to create a message you can make a custom message by typing 'msg:' followed by the message
 * example:     ->     echo 'msg: error bla bla bla';
 *
 */
 
session_start();
include('../form/connection.php');

$db = new db();
$uploader = $_SESSION["username"];
$disaster = $db->connection->real_escape_string($_POST['DISASTER']);
$barangay = $db->connection->real_escape_string($_POST['BARANGAY']);
$dead = $db->connection->real_escape_string($_POST['DEAD']);
$injured = $db->connection->real_escape_string($_POST['INJURED']);
$missing = $db->connection->real_escape_string($_POST['MISSING']);
$totally = $db->connection->real_escape_string($_POST['TOTALLY']);
$partially = $db->connection->real_escape_string($_POST['PARTIALLY']);
$evacuees = $db->connection->real_escape_string($_POST['EVACUEES']);
$DSWD = $db->connection->real_escape_string($_POST['DSWD']);
$LGU = $db->connection->real_escape_string($_POST['LGU']);
$NGO = $db->connection->real_escape_string($_POST['NGO']);

$sql = "INSERT INTO damagereports (barangay, disasterID, uploader, dmgTotally,
								   dmgPartially, dmgEvac, csltdead, csltMissing,
								   asstDSWD, asstLGU, asstNGO, isVerified) VALUES (
								   '$barangay', $disaster, '$uploader', $totally,
								   $partially, $evacuees, $dead, $injured, $missing,
								   $DSWD, $LGU, $NGO);";

if($db->connection->query($sql)) {
	echo 'success';
}
else {
	echo 'error';
}
?>