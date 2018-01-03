<!DOCTYPE html>
<?php
session_start();
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();
$disaster_name = '';

//session variables
$session_USER_FIRSTNAME = htmlspecialchars($_SESSION['USER_FIRSTNAME']);
$session_USER_MIDDLENAME = htmlspecialchars($_SESSION['USER_MIDDLENAME']);
$session_USER_LASTNAME = htmlspecialchars($_SESSION['USER_LASTNAME']);

$group_id = '';
$group_name = '';
$group_date = '';
$group_type = '';
$group_disaster_list = array();
$hasTerminal = true;
if(isset($_GET["id"])){
    $group_id = $_GET["id"];
    $sql = "SELECT disaster_profile.*, disaster_type.NAME AS TYPENAME
                    FROM disaster_profile, disaster_type
                    WHERE disaster_profile.TYPE = disaster_type.ID
                    AND disaster_profile.ID = " . $group_id . "
                    GROUP BY disaster_profile.ID";
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while($row = $result->fetch_assoc()){
        $group_name = $row['NAME'];
        $group_date = getdatestring($row['DATESTART']);
        $group_type = $row['TYPENAME'];
    }


    $sql = "SELECT 	DECLAREID
                FROM disaster_declarelist
                WHERE PROFILEID = " . $group_id;
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while($row = $result->fetch_assoc()){
        array_push($group_disaster_list, $row['DECLAREID']);
    }
}
else{
    PageNotAvailable();
}




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
            Available Reports for <?php echo $group_name; ?>
            <br />
            <small>&gt; <?php echo $group_type; ?></small>
        </h1>
    </div>
    <div id="pagemessagebox" tabindex="0"></div>
    <div style="clear: both;"></div>
    <br />
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">List of Available Disaster Status Reports</div>
            <div class="panel-body">
                <div class="list-group" id="list"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">List of Available Evacuation Status Reports</div>
            <div class="panel-body">
                <div class="list-group" id="evaclist"></div>
            </div>
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
<script src="js/jquery.form.min.js"></script>
<script>
    <?php

    ?>
    var page = {
        list: document.getElementById('list'),
        evaclist: document.getElementById('evaclist')
    };

    function AddToList(id, time, time2, isTerminal) {
        var hhtml = '';
        hhtml += '' +
            '<a href="DisasterStatusReport.php?id=' + id + '&time=' + time + '" class="list-group-item">' +
            '    <span class="badge">' +
            '        <span class="glyphicon glyphicon-folder-open"></span> &nbsp; Open' +
            '    </span>' + time2;
            if(isTerminal){
                hhtml += ' <span class="label label-info">Terminal</span>';
            }
            hhtml += '</a>';

        page.list.innerHTML += hhtml;
    }

    function AddToEvacList(id, time, time2){
        page.evaclist.innerHTML += '' +
            '<a href="EvacuationStatusReport.php?id=' + id + '&time=' + time + '" class="list-group-item">' +
            '    <span class="badge">' +
            '        <span class="glyphicon glyphicon-folder-open"></span> &nbsp; Open' +
            '    </span>' + time2 +
            '</a>';
    }

    <?php
        $arg = '';
        foreach ($group_disaster_list as $declareid){
            $arg .= $declareid . ',';
        }
        $sqlvar = 'SET @DECLAREIDS := "' . $arg . '";';
        $db->connection->query($sqlvar);

        $sql = 'SELECT  ISVERIFIED
                FROM    disaster_declare
                WHERE   FIND_IN_SET(ID, @DECLAREIDS)';
        $result = $db->connection->query($sql);
        while ($row = $result->fetch_assoc()) {
            if($row['ISVERIFIED'] != 1){
                $hasTerminal = false;
            }
        }

        $sqlvar = 'SET @DECLAREIDS := "' . $arg . '";';
        $db->connection->query($sqlvar);

        $sql = 'SELECT DATEADDED FROM  (SELECT DATEADDED 
                                                    FROM evacuation_report
                                                    WHERE FIND_IN_SET(DECLAREID, @DECLAREIDS)
                                                    AND ISVERIFIED = 1
                                                UNION
                                                    SELECT DATEADDED 
                                                    FROM disaster_reports
                                                    WHERE FIND_IN_SET(DECLAREID, @DECLAREIDS)
                                                    AND ISVERIFIED = 1
                                                UNION
                                                    SELECT DATEADDED 
                                                    FROM disaster_cost
                                                    WHERE FIND_IN_SET(DECLAREID, @DECLAREIDS)) a
                            GROUP BY YEAR(DATEADDED), MONTH(DATEADDED), DAY(DATEADDED), HOUR(DATEADDED)
                            ORDER BY DATEADDED DESC';
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while ($row = $result->fetch_assoc()) {
            $newtime = substr($row['DATEADDED'], 0, strlen($row['DATEADDED']) - 5) . '00:00';
            ?>
                AddToList('<?php echo $group_id; ?>', '<?php echo $newtime; ?>', '<?php echo converttoformaldatetimestring($newtime); ?>', <?php if($hasTerminal) {echo 'true'; $hasTerminal = false;} else echo 'false'; ?>);
            <?php
        }

        $arg = substr($arg, 0, strlen($arg) - 1);
            $sql = 'SELECT  DATEADDED
                FROM    evacuation_report
                WHERE   DECLAREID IN (' . $arg . ')
                AND     ISVERIFIED = 1
                GROUP BY HOUR(DATEADDED)
                ORDER BY DATEADDED DESC';
                $result = $db->connection->query($sql);
            while ($row = $result->fetch_assoc()) {
                $newtime = substr($row['DATEADDED'], 0, strlen($row['DATEADDED']) - 5) . '00:00';
                ?>
                    AddToEvacList('<?php echo $group_id; ?>', '<?php echo $newtime; ?>', '<?php echo converttoformaldatetimestring($newtime); ?>');
                <?php
            }
    ?>

</script>
</body>
</html>



