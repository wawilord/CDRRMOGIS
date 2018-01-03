<!DOCTYPE html>
<?php
session_start();
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();
$area_city = '';
$area_district = '';
$area_brgy = '';
$brgy_id = '';


if(!isset($_GET['id']))
{
    PageNotAvailable();
}
else
{
    if(!ctype_digit($_GET['id']))
    {
        PageNotAvailable();
    }
    else
    {
        $sql = "SELECT * FROM disaster_declare WHERE ENDED IS NOT NULL AND ID=" . $_GET['id'];
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        if($count < 1)
        {
            PageNotAvailable();
        }
        else{
            $row = $result->fetch_assoc();
            $brgy_id = $row['BRGY'];
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
                    barangay.ID = ' . $row['BRGY'] . '
            AND 
                    barangay.DISTRICT = district.ID
            AND 
                    district.CITY = city.ID
            ';
            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);
            $row = $result->fetch_assoc();
            $area_brgy = htmlspecialchars($row['BRGYNAME']);
            $area_district = htmlspecialchars($row['DISTRICTNAME']);
            $area_city = htmlspecialchars($row['CITYNAME']);
        }
    }
}

//session variables
$session_USER_FIRSTNAME = htmlspecialchars($_SESSION['USER_FIRSTNAME']);
$session_USER_MIDDLENAME = htmlspecialchars($_SESSION['USER_MIDDLENAME']);
$session_USER_LASTNAME = htmlspecialchars($_SESSION['USER_LASTNAME']);

$sql = 'SELECT disaster_declare.NICKNAME, disaster_declare.COMMENT, disaster_declare.STARTED,disaster_declare.ENDED, disaster_declare.ID, disaster_type.NAME AS DISASTERNAME, user_accounts.FIRSTNAME, user_accounts.MIDDLENAME, user_accounts.LASTNAME FROM `disaster_declare`, `disaster_type`, `user_accounts` WHERE disaster_type.ID = disaster_declare.DISASTER AND user_accounts.USERNAME = disaster_declare.POSTBY AND disaster_declare.ID = ' . $_GET['id'];
$result = $db->connection->query($sql);
$count = mysqli_num_rows($result);
$row = $result->fetch_assoc();

$result_NICKNAME = htmlspecialchars($row['NICKNAME']);
$result_STARTED = htmlspecialchars($row['STARTED']);
$result_ENDED = htmlspecialchars($row['ENDED']);
$result_ID = htmlspecialchars($row['ID']);
$result_DISASTERNAME = htmlspecialchars($row['DISASTERNAME']);
$result_FIRSTNAME = htmlspecialchars($row['FIRSTNAME']);
$result_MIDDLENAME = htmlspecialchars($row['MIDDLENAME']);
$result_LASTNAME = htmlspecialchars($row['LASTNAME']);
$result_COMMENT = htmlspecialchars($row['COMMENT']);

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
            Confirm Disaster
            <small>in<br /><?php echo 'Brgy. ' . $area_brgy . ', ' . $area_district . ', ' . $area_city . ' City'; ?></small>
        </h1>
    </div>
    <div id="pagemessagebox" tabindex="0"></div>
    <div style="clear: both;"></div>
    <br />
    <div class="panel panel-default">
        <div class="panel-heading">Disaster Info</div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr>
                    <td><h2><span class="color-gray">Alias:</span></h2></td>
                    <td><h2><?php echo $result_NICKNAME; ?></h2></td>
                </tr>
                <tr>
                    <td><h4><span class="color-gray">Disaster:</span></h4></td>
                    <td><h4><?php echo $result_DISASTERNAME;  ?></h4></td>
                </tr>
                <tr>
                    <td><span class="color-gray">Disaster Declaration ID:</span></td>
                    <td><?php echo $result_ID;  ?></td>
                </tr>
                <tr>
                    <td><span class="color-gray">Time Started:</span></td>
                    <td><?php echo converttoformaldatetimestring($result_STARTED);  ?></td>
                </tr>
                <tr>
                    <td><span class="color-gray">Time Ended:</span></td>
                    <td id="PageComponent_ENDED">
                        <?php
                        if($result_ENDED == '')
                        {
                            echo 'The disaster is still going on.';
                        }
                        else
                        {
                            echo converttoformaldatetimestring($result_ENDED);
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="color-gray">Declared/Posted by:</span></td>
                    <td><?php echo $result_FIRSTNAME . ' ' . $result_MIDDLENAME . ' ' . $result_LASTNAME; ?></td>
                </tr>
                <?php if($result_COMMENT != ''){ ?>
                    <tr>
                        <td><span class="color-gray">Note/Comment for this disaster: </span></td>
                        <td><?php echo $result_COMMENT; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <br />

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Disaster Reports</h3>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                <tr>
                    <th>Date Time</th>
                    <th>Dead</th>
                    <th>Injured</th>
                    <th>Missing</th>
                    <th>Totally Damaged</th>
                    <th>Partially Damaged</th>
                    <th>Reported by</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = 'SELECT disaster_reports.*, user_accounts.FIRSTNAME, user_accounts.MIDDLENAME, user_accounts.LASTNAME 
                                FROM disaster_reports, user_accounts
                                WHERE disaster_reports.DECLAREID = ' . $result_ID . ' 
                                AND disaster_reports.ISVERIFIED = 1
                                AND disaster_reports.UPLOADER = user_accounts.USERNAME';
                        $result = $db->connection->query($sql);
                        $count = mysqli_num_rows($result);
                        while($row = $result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?php echo converttoformaldatetimestring($row['DATEADDED']); ?></td>
                                    <td><?php echo $row['CSLTDEAD']; ?></td>
                                    <td><?php echo $row['CSLTINJURED']; ?></td>
                                    <td><?php echo $row['CSLTMISSING']; ?></td>
                                    <td><?php echo $row['DMGTOTALLY']; ?></td>
                                    <td><?php echo $row['DMGPARTIALLY']; ?></td>
                                    <td><?php echo $row['FIRSTNAME'] . ' ' . $row['MIDDLENAME'] . ' ' . $row['LASTNAME']; ?></td>
                                </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Evacuation Reports</h3>
        </div>
        <div class="panel-body">
            <!--Accepted reports here-->
            <div class="panel-group" id="AcceptedEvacAccordion" role="tablist" aria-multiselectable="true">
                <!--List start-->
                <div>

                    <?php
                        $dummy = '';
                        $data = array();
                        $sql = 'SELECT  evacuation_report.*,
                                        evacuation_list.EVACNAME,
                                        evacuation_list.EVACADDRESS1,
                                        evacuation_list.EVACADDRESS2,
                                        evacuation_list.LAT,
                                        evacuation_list.LNG,
                                        user_accounts.FIRSTNAME, 
                                        user_accounts.MIDDLENAME, 
                                        user_accounts.LASTNAME
                                FROM    evacuation_report,
                                        evacuation_list,
                                        user_accounts
                                WHERE   evacuation_report.DECLAREID = ' . $result_ID . ' 
                                AND     evacuation_list.ID = evacuation_report.EVACID
                                AND     evacuation_report.ISVERIFIED = 1
                                AND     evacuation_report.UPLOADER = user_accounts.USERNAME    
                                ORDER BY evacuation_report.EVACID';
                        $result = $db->connection->query($sql);
                        $count = mysqli_num_rows($result);
                        while($row = $result->fetch_assoc()) {
                            if($dummy != $row['EVACID']){
                                $data[] = array("d1" => $row, "d2" => array());
                                $dummy = $row['EVACID'];
                            }
                            $data[sizeof($data) - 1]["d2"][] = $row;
                        }

                    /*
                        foreach ($data as $dataa){
                            echo $dataa["d1"]["EVACID"] . '<br />';
                            foreach ($dataa["d2"] as $dd){
                                echo $dd["DATEADDED"] . '<br />';
                            }
                        }
                    */

                    foreach ($data as $dataa){
                        ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#AcceptedEvacAccordion" href="#connect_<?php echo $dataa["d1"]["EVACID"]; ?>" aria-expanded="true" aria-controls="collapseOne" class="">
                                            <small><span class="glyphicon glyphicon-triangle-bottom"></span></small>
                                            <span><?php echo $dataa["d1"]["EVACNAME"]; ?></span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="connect_<?php echo $dataa["d1"]["EVACID"]; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="true">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6 col-md-6">
                                                <img src="https://maps.googleapis.com/maps/api/staticmap?markers=color:red%7C<?php echo $dataa["d1"]["LAT"]; ?>,<?php echo $dataa["d1"]["LNG"]; ?>&center=<?php echo $dataa["d1"]["LAT"]; ?>,<?php echo $dataa["d1"]["LNG"]; ?>&zoom=14&size=300x150&maptype=roadmap&key=AIzaSyCalJXL3IZ37jpy9s0K5ge-xgojC8fXWOM" />
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-md-6">
                                                <h5>Google Map Address: <b><?php echo $dataa["d1"]["EVACADDRESS1"]; ?></b></h5>
                                                <h5>Complete Adress: <b><?php echo $dataa["d1"]["EVACADDRESS2"]; ?></b></h5>
                                            </div>
                                        </div>
                                        <br />
                                        <h4>Previous Reports</h4>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Date Time</th>
                                                <th>Persons Served</th>
                                                <th>Families Served</th>
                                                <th>Reporter</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach ($dataa["d2"] as $dd){
                                                        ?>
                                                        <tr>
                                                            <td><?php echo converttoformaldatetimestring($dd["DATEADDED"]); ?></td>
                                                            <td><?php echo $dd["SRVPERSONS"]; ?></td>
                                                            <td><?php echo $dd["SRVFAMILIES"]; ?></td>
                                                            <td><?php echo $dd['FIRSTNAME'] . ' ' . $dd['MIDDLENAME'] . ' ' . $dd['LASTNAME']; ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Cost of Assistance</h3>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                <tr>
                    <th>Date Time</th>
                    <th>DSWD</th>
                    <th>LGUs</th>
                    <th>NGOs/Other GOs</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = 'SELECT * 
                            FROM disaster_cost
                            WHERE DECLAREID = ' . $result_ID;
                    $result = $db->connection->query($sql);
                    $count = mysqli_num_rows($result);
                    while($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo converttoformaldatetimestring($row['DATEADDED']); ?></td>
                            <td>₱ <?php echo $row['DSWD']; ?></td>
                            <td>₱ <?php echo $row['LGU']; ?></td>
                            <td>₱ <?php echo $row['NGO']; ?></td>
                            <td>₱ <?php echo $row['DSWD'] + $row['LGU'] + $row['NGO']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<!--FOOTER HERE-->
<?php include('library/html/footer.php'); ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/1.11.3_jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>

</body>
</html>



