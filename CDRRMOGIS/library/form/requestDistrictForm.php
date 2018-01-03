<?php


$disaster_types = array();
$sql = 'SELECT * FROM disaster_type';
$result = $db->connection->query($sql);
while ($row = $result->fetch_assoc()){
    $disaster_types[] = $row;
}



$district = $db->connection->real_escape_string($_POST['test']);



 $chart_data = '';
                      foreach ($disaster_types as $type){
                          $sql = 'SELECT disaster_declare.ID, disaster_declare.NICKNAME FROM disaster_declare INNER JOIN barangay on disaster_declare.BRGY = barangay.ID INNER JOIN district
                            on barangay.DISTRICT = district.ID WHERE district.ID = '.$district.' AND disaster_declare.DISASTER = ' . $type['ID'] . '  AND ISVERIFIED = 1';
                          $result = $db->connection->query($sql);
                          $count = mysqli_num_rows($result);
                          $chart_data .= "{label: \"" . $type['NAME'] . "\", value: " . $count . "},\n";
                      }
                      $chart_data = substr($chart_data, 0, strlen($chart_data) - 2);
                      echo $chart_data;



}

?>