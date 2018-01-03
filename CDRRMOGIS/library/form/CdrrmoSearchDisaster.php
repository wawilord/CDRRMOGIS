<?php
	session_start();
	//db initialization
	include('connection.php');
	include ('../function/functions.php');
	$db = new db();

	$groupid = $_GET['GROUPID'];
	$barangayid = $_GET['BARANGAY'];
	$nickname = $_GET['NICKNAME'];
	$disasterid = $_GET['DISASTERID'];
	$Dstart = $_GET['TIMESTART'] . ' 00:00:00';
	$Dend = $_GET['TIMEEND'] . ' 00:00:00';
	$disastertype = "";
	$disasterstart = "";
	$sqlarg = "";

	$sql = "SELECT `DATESTART` 
			FROM 	disaster_profilename
			WHERE ID = " . $groupid;
	$result = $db->connection->query($sql);
	if($row = $result->fetch_assoc()){
		$disastertype = $row['TYPE'];
		$disasterstart = $row['DATESTART'];
	}
	if($barangayid != ""){
		$sqlarg .= "disaster_declare.BRGY = " . $barangayid . " AND ";
	}
	if($nickname != ""){
		$sqlarg .= "disaster_declare.NICKNAME LIKE '%" . $nickname . "%' AND ";
	}
	if($disasterid != ""){
		$sqlarg .= "disaster_declare.ID = " . $disasterid . " AND ";
	}
	if($_GET['TIMESTART'] != ""){
		$sqlarg .= "disaster_declare.STARTED >= '" . $Dstart . "' AND ";
	}
	if($_GET['TIMEEND'] != ""){
		$sqlarg .= "disaster_declare.STARTED <= '" . $Dend . "' AND ";
	}
	$sqlarg .= 'disaster_declare.DISASTER = ' . $disastertype;

	$sql = 'SELECT 	disaster_declare.ID,
					disaster_declare.NICKNAME,
					disaster_declare.STARTED,
					disaster_type.NAME AS DISASTER,
					barangay.NAME AS BARANGAY
 					
			FROM 	disaster_declare, 
					disaster_type,
					barangay
			WHERE 	disaster_declare.ID NOT IN(	SELECT 	DECLAREID 
									  			FROM 	disaster_profilelist
												WHERE 	PROFILEID = ' . $groupid . ') 
			AND		disaster_declare.BRGY = barangay.ID
			AND		disaster_declare.DISASTER = disaster_type.ID
			AND ' . $sqlarg . '
			ORDER BY disaster_declare.STARTED DESC';

	$result = $db->connection->query($sql);
	$count = mysqli_num_rows($result);
	$row = array();
	while ($r = $result->fetch_assoc()){
		$r["STARTED"] = converttoformaldatetimestring($r["STARTED"]);
		$row[] = $r;
	}
	if($count > 0){
		echo 'success:' . json_encode($row);
	}
	else{
		echo 'msg: No Results.';
	}
?>
