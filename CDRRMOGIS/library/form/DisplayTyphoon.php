<?php

	include('connection.php');
	include ('../function/functions.php');
	$db = new db();


$rangevalue = $_POST['rangevalue'];


$sql = "SELECT *
			FROM 	disaster_typhoonprofile
			WHERE SIGNALNO = " . $rangevalue;
	$result = $db->connection->query($sql);
	$count = mysqli_num_rows($result);

$sql2 = "SELECT 
               disaster_declare.LAT, disaster_declare.LNG, disaster_declare.RADIUS

                FROM disaster_declare
                INNER JOIN disaster_typhoonlist
                ON disaster_declare.ID = disaster_typhoonlist.DECLAREID
                INNER JOIN disaster_typhoonprofile
                ON disaster_typhoonlist.PROFILEID = disaster_typhoonprofile.ID
                WHERE disaster_typhoonprofile.SIGNALNO = ".$rangevalue;
               $result2 = $db->connection->query($sql2);
			$count2 = mysqli_num_rows($result2);

			echo $count2;
 
?>