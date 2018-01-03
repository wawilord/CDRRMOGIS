<!DOCTYPE html>
<?php
session_start();
include ('library/form/BrgyOnly.php');
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();

//session variables
$session_USER_FIRSTNAME = htmlspecialchars($_SESSION['USER_FIRSTNAME']);
$session_USER_MIDDLENAME = htmlspecialchars($_SESSION['USER_MIDDLENAME']);
$session_USER_LASTNAME = htmlspecialchars($_SESSION['USER_LASTNAME']);

//query for the address
$sql = '
            SELECT  
                    barangay.NAME AS BRGYNAME,
                    district.NAME AS DISTRICTNAME,
                    city.NAME AS CITYNAME
            FROM 
                    barangay, 
                    district, 
                    city
            WHERE   
                    barangay.ID = ' . $_SESSION['USER_BRGY'] . '
            AND 
                    barangay.DISTRICT = district.ID
            AND 
                    district.CITY = city.ID
            ';
$result = $db->connection->query($sql);
$count = mysqli_num_rows($result);
$row = $result->fetch_assoc();

//variables for the address
$result_BRGYNAME = htmlspecialchars($row['BRGYNAME']);
$result_DISTRICTNAME = htmlspecialchars($row['DISTRICTNAME']);
$result_CITYNAME = htmlspecialchars($row['CITYNAME']);

?>
<!--This Page is for the admin only -->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Disaster Risk Management</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
</head>
<body role="document">
<?php include('library/html/navbar.php'); ?>
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1>
            Previous Disasters
            <small> in <br />Brgy. <?php echo $result_BRGYNAME . ', ' . $result_DISTRICTNAME . ', ' . $result_CITYNAME; ?> City.</small>
        </h1>
    </div>
    <div id="pagemessagebox" tabindex="0"></div>


            <div class="list-group">
                <div class="row">
                    <?php

                    $sql = 'SELECT disaster_declare.NICKNAME, disaster_declare.STARTED, disaster_declare.ID, disaster_type.COLOR, disaster_type.NAME AS DISASTERNAME FROM `disaster_declare`, `disaster_type` WHERE disaster_declare.BRGY = ' . $_SESSION['USER_BRGY'] . ' AND disaster_declare.ENDED IS NOT NULL AND disaster_type.ID = disaster_declare.DISASTER ORDER BY disaster_declare.STARTED DESC';
                    $result = $db->connection->query($sql);
                    $count = mysqli_num_rows($result);
                    while($row = $result->fetch_assoc()) {

                        $result_NICKNAME = htmlspecialchars($row['NICKNAME']);
                        $result_STARTED = htmlspecialchars($row['STARTED']);
                        $result_ID = htmlspecialchars($row['ID']);
                        $result_DISASTERNAME = htmlspecialchars($row['DISASTERNAME']);
                        $result_COLOR = $row['COLOR'];

                        ?>

                        <div class="col-lg-6">
                            <a href="BrgyViewDisaster.php?id=<?php echo $result_ID; ?>" class="list-group-item">
                                <p class="list-group-item-text pull-right"><?php echo converttoformaldatetimestring($result_STARTED); ?></p>
                                <h2 class="list-group-item-heading"><?php echo $result_NICKNAME; ?></h2>
                                <h4><span class="glyphicon glyphicon-stop" style="color: <?php echo $result_COLOR;   ?>"></span> <?php echo $result_DISASTERNAME; ?></h4>
                            </a>
                            <br/>
                        </div>
                    <?php
                    }
                    ?>

                </div>
            </div>

</div>
<!--FOOTER HERE-->
<?php include('library/html/footer.php'); ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/1.11.3_jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/app/mylibrary.js"></script>
<script src="js/app/messagealert.js"></script>

</body>
</html>



