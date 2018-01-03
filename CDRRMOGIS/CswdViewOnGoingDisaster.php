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
        if(isset($_GET['confirm'])){
            $sql = 'UPDATE              disaster_declare
                    SET                 ACCEPTED = 1
                    WHERE               ID = ' . $_GET['id'];
            $result = $db->connection->query($sql);
            header('Location: ?id=' . $_GET['id']);
        }


        $sql = "SELECT * FROM disaster_declare WHERE ENDED IS NULL AND ID=" . $_GET['id'];
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

$sql = 'SELECT disaster_declare.RADIUS, disaster_declare.ACCEPTED, disaster_declare.NICKNAME, disaster_declare.COMMENT, disaster_declare.STARTED,disaster_declare.ENDED, disaster_declare.ID, disaster_type.NAME AS DISASTERNAME, user_accounts.FIRSTNAME, user_accounts.MIDDLENAME, user_accounts.LASTNAME, disaster_declare.LAT, disaster_declare.LNG FROM `disaster_declare`, `disaster_type`, `user_accounts` WHERE disaster_type.ID = disaster_declare.DISASTER AND user_accounts.USERNAME = disaster_declare.POSTBY AND disaster_declare.ID = ' . $_GET['id'];
$result = $db->connection->query($sql);
$count = mysqli_num_rows($result);
$row = $result->fetch_assoc();

$result_NICKNAME = htmlspecialchars($row['NICKNAME']);
$result_ACCEPTED = htmlspecialchars($row['ACCEPTED']);
$result_STARTED = htmlspecialchars($row['STARTED']);
$result_ENDED = htmlspecialchars($row['ENDED']);
$result_ID = htmlspecialchars($row['ID']);
$result_DISASTERNAME = htmlspecialchars($row['DISASTERNAME']);
$result_FIRSTNAME = htmlspecialchars($row['FIRSTNAME']);
$result_MIDDLENAME = htmlspecialchars($row['MIDDLENAME']);
$result_LASTNAME = htmlspecialchars($row['LASTNAME']);
$result_COMMENT = htmlspecialchars($row['COMMENT']);
$result_LAT = htmlspecialchars($row['LAT']);
$result_LNG = htmlspecialchars($row['LNG']);
$result_RADIUS = htmlspecialchars($row['RADIUS']);

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
    <link href="css/map.css" rel="stylesheet">
    <link href="css/jquery.datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"
    integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="
    crossorigin=""/>

</head>
<body role="document">
<!--Delete Cost of Assistance-->
<div class="modal fade" id="DeleteCOAModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Delete Cost of Assistance</h4>
            </div>
            <form id="DeleteCOAForm" method="post" action="library/form/CswdDeleteCOAForm.php">
                <div class="modal-body">
                    <div id="DeleteCOAForm_msgbox" tabindex="0"></div>
                    <div><input type="text" id="DeleteCOAForm_ID" name="ID" style="display: none;"></div>
                    <p>Do you want to delete this record?:</p>
                    <table class="table">
                        <thead>
                        <tr>
                            <td></td>
                            <td><b>Cost of Assistance</b></td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Date/Time Encoded: </td>
                            <td id="DeleteCOAForm_datetime"></td>
                        </tr>
                        <tr>
                            <td>DSWD: </td>
                            <td id="DeleteCOAForm_DSWD"></td>
                        </tr>
                        <tr>
                            <td>LGUs: </td>
                            <td id="DeleteCOAForm_LGU"></td>
                        </tr>
                        <tr>
                            <td>NGOs/Other GOs: </td>
                            <td id="DeleteCOAForm_NGO"></td>
                        </tr>
                        <tr>
                            <td>Total: </td>
                            <td id="DeleteCOAForm_total"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
                    <button type="submit" id="DeleteCOAForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."><span class="glyphicon glyphicon-trash"></span> Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Update Cost Of Assistance-->
<div class="modal fade" id="UpdateCOAModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Update Cost Of Assistance</h4>
            </div>
            <form id="UpdateCOAForm" method="post" action="library/form/CswdUpdateCOAForm.php">
                <div class="modal-body">
                    <div id="UpdateCOA_msgbox" tabindex="0"></div>
                    <input type="text" name="DECLAREID" style="display: none;" value="<?php echo $result_ID; ?>">
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">DSWD: ₱</span>
                        <input type="number" id="UpdateCOAForm_DSWD" name="DSWD" value="" min="0" class="form-control">
                    </div>
                    <br />
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">LGUs: ₱</span>
                        <input type="number" id="UpdateCOAForm_LGU" name="LGU" value="" min="0" class="form-control">
                    </div>
                    <br />
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">NGOs/Other GOs: ₱</span>
                        <input type="number" id="UpdateCOAForm_NGO" name="NGO" value="" min="0" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="UpdateCOAForm_SUBMIT" data-loading-text="Updating..." class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Accept Evacuation Report Modal-->
<div class="modal fade" id="AcceptEvacReportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Accept a Report</b></h4>
            </div>
            <form id="AcceptEvacForm" method="post" action="library/form/CswdAcceptEvacReportForm.php">
                <input type="text" id="AcceptEvacForm_ID" name="ID" style="display: none;" />
                <div class="modal-body">
                    <div id="AcceptEvacForm_msgbox" tabindex="0"></div>
                    <p>Please review the report of <b id="AcceptEvacForm_REPORTER"></b> before accepting it.</p>
                    <table class="table">
                        <tr>
                            <td><b>Persons Served: </b></td>
                            <td id="AcceptEvacForm_PERSONS"></td>
                        </tr>
                        <tr>
                            <td><b>Families Served: </b></td>
                            <td id="AcceptEvacForm_FAMILIES"></td>
                        </tr>
                        <tr>
                            <td><b>Date Time: </b></td>
                            <td id="AcceptEvacForm_DATETIME"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="AcceptEvacForm_SUBMIT" data-loading-text="Accepting..." class="btn btn-success">Accept</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Update Evacuation Reports Modal or Send Update-->
<div class="modal fade" id="UpdateEvacuationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Send an Evacuation Report for: <b id="UpdateEvacuationModalLabel_Name"></b></h4>
            </div>
            <form id="EvacuationReportForm" method="post" action="library/form/CswdEvacuationReportForm.php">
                <div class="modal-body">
                    <div id="ER_msgbox" tabindex="0"></div>
                    <input type="text" id="EvacuationReportForm_DECLAREID" name="DECLAREID" style="display: none;" value="<?php echo $result_ID; ?>">
                    <input type="text" id="EvacuationReportForm_EVACID" name="EVACID" style="display: none;" />
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">Persons Served: </span>
                        <input type="number" id="EvacuationReportForm_PERSONS" name="PERSONS" value="" min="0" class="form-control">
                    </div>
                    <br />
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">Families Served: </span>
                        <input type="number" id="EvacuationReportForm_FAMILIES" name="FAMILIES" value="" min="0" class="form-control">
                    </div>
                    <br />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="EvacuationReportForm_SUBMIT" data-loading-text="Sending Report..." class="btn btn-primary">Send Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Add Evacuation Modal-->
<div class="modal fade" id="AddEvacuationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Evacuation Center for <b><?php echo $result_NICKNAME; ?></b></h4>
            </div>
            <form id="AddEvacuationForm" method="post" action="library/form/CswdAddEvacuationForm.php">
                <div class="modal-body">
                    <div id="AE_msgbox" tabindex="0"></div>
                    <p>Select Evacuation Center in the map.</p>
                    <div class="input-group">
                        <span class="input-group-addon" id="basicasdsaasdon1"><span class="glyphicon glyphicon-map-marker"></span> </span>
                        <div class="form-control" style="height: 300px; text-align: center;">
                            <div id="map" style="height: 100%; width: 100%;" tabindex="0"> </div>
                        </div>
                    </div>
                    <br />
                    <input type="text" id="AddEvacuationForm_DECLAREID" name="DECLAREID" value="<?php echo $result_ID; ?>" style="display: none;">
                    <input type="text" id="AddEvacuationForm_EVACID" name="EVACID" style="display: none;">
                    <table class="table">
                        <tr>
                            <th>Name:</th>
                            <td id="S_EvacName"></td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td id="S_EvacAddress"></td>
                        </tr>
                        <tr>
                            <th>Barangay:</th>
                            <td id="S_EvacBrgy"></td>
                        </tr>
                    </table>
                    <br />
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">Persons Served: </span>
                        <input type="number" id="AddEvacuationForm_PERSONS" name="PERSONS" value="" min="0" class="form-control">
                    </div>
                    <br />
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">Families Served: </span>
                        <input type="number" id="AddEvacuationForm_FAMILIES" name="FAMILIES" value="" min="0" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="AddEvacuationForm_SUBMIT" data-loading-text="Adding Evacuation..." class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Disaster Report Modal-->
<div class="modal fade" id="DisasterReportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add a Verified Disaster Report for <b><?php echo $result_NICKNAME; ?></b></h4>
            </div>
            <form id="DisasterReportForm" method="post" action="library/form/CswdDisasterReportForm.php">
                <div class="modal-body">
                    <div id="DR_msgbox" tabindex="0"></div>
                    <div class="panel panel-default">
                        <input type="text" value="<?php echo $result_ID; ?>" name="DECLARE" style="display: none;" />
                        <input type="text" value="<?php echo $brgy_id; ?>" name="BARANGAY" style="display: none;" />
                        <input type="text" value="cswd" id = "disasterReportForm_REPORTID" name="REPORTID" style="display: none;" />
                    
                        <div class="panel-heading">Casualties</div>
                        <div class="panel-body">
                            <div class="input-group input-group">
                                <span class="input-group-addon" id="sizing-addon1">Dead</span>
                                <input type="number" id="DisasterReportForm_DEAD" name="DEAD" value="" min="0" class="form-control" aria-describedby="sizing-addon1">
                            </div>

                            <br />

                            <div class="input-group input-group">
                                <span class="input-group-addon" id="sizing-addon1">Injured</span>
                                <input type="number" id="DisasterReportForm_INJURED" name="INJURED" value="" min="0" class="form-control" aria-describedby="sizing-addon1">
                            </div>

                            <br />

                            <div class="input-group input-group">
                                <span class="input-group-addon" id="sizing-addon1">Missing</span>
                                <input type="number" id="DisasterReportForm_MISSING" name="MISSING" value="" min="0" class="form-control" aria-describedby="sizing-addon1">
                            </div>
                        </div>
                    </div>

                    <br />

                    <div class="panel panel-default">
                        <div class="panel-heading">Damages</div>
                        <div class="panel-body">
                            <div class="input-group input-group">
                                <span class="input-group-addon" id="sizing-addon1">Totally</span>
                                <input type="number" id="DisasterReportForm_TOTALLY" name="TOTALLY" value="" min="0" class="form-control" aria-describedby="sizing-addon1">
                            </div>

                            <br />

                            <div class="input-group input-group">
                                <span class="input-group-addon" id="sizing-addon1">Partially</span>
                                <input type="number" id="DisasterReportForm_PARTIALLY" name="PARTIALLY" value="" min="0" class="form-control" aria-describedby="sizing-addon1">
                            </div>

                            <br />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                 <span class = "pull-left">
                    <p style = "margin-right: 47px"> Reported by:  </p>
                         <select class="form-control" id = "reportIDPICKER">
                           
                            <option value="<?php echo $_SESSION['USER_USERNAME']; ?>" ><?php echo $_SESSION['USER_USERNAME']; ?></option>

                           <?php 

                               $sql ="SELECT user_accounts.USERNAME

                                FROM user_accounts
                                WHERE user_accounts.TYPE = 'D'
                                AND user_accounts.ENABLED = 1";

                            $result = $db->connection->query($sql);
                            $count = mysqli_num_rows($result);

                             while($row = $result->fetch_array())
                             {
                                ?>
                                    <option value="<?php echo $row['USERNAME']; ?>" ><?php echo $row['USERNAME']; ?></option>

                                <?php
                             }

                            ?>   
                         </select>

                    </span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="DisasterReportForm_SUBMIT" data-loading-text="Sending Report" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Accept Report Modal-->
<div class="modal fade" id="AcceptReportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Accept a Report</b></h4>
            </div>
            <form id="AcceptForm" method="post" action="library/form/CswdAcceptReportForm.php">
                <input type="text" id="AcceptForm_ID" name="ID" style="display: none;" />
                <div class="modal-body">
                    <div id="AcceptModal_msgbox" tabindex="0"></div>
                    <p>Please review the report of <b id="AcceptForm_REPORTER"></b> before accepting it.</p>
                    <table class="table">
                        <tr>
                            <td><b>Dead: </b></td>
                            <td id="AcceptForm_DEAD"></td>
                        </tr>
                        <tr>
                            <td><b>Injured: </b></td>
                            <td id="AcceptForm_INJURED"></td>
                        </tr>
                        <tr>
                            <td><b>Missing: </b></td>
                            <td id="AcceptForm_MISSING"></td>
                        </tr>
                        <tr>
                            <td><b>Totally Damaged: </b></td>
                            <td id="AcceptForm_TOTALLY"></td>
                        </tr>
                        <tr>
                            <td><b>Partially Damaged: </b></td>
                            <td id="AcceptForm_PARTIALLY"></td>
                        </tr>
                        <tr>
                            <td><b>Date Time: </b></td>
                            <td id="AcceptForm_DATETIME"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="AcceptForm_SUBMIT" data-loading-text="Accepting..." class="btn btn-success">Accept</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--End Disaster Modal-->
<div class="modal fade" id="EndDisasterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">End <b><?php echo $result_NICKNAME; ?></b></h4>
            </div>
            <form id="EndDisasterForm" method="post" action="library/form/BrgyEndDisasterForm.php">
                <div class="modal-body">
                    <div id="ED_msgbox" tabindex="0"></div>
                    <?php
                    date_default_timezone_set('Asia/Hong_Kong');
                    $time = time();
                    ?>
                    <input type="text" name="DECLARE" value="<?php echo $result_ID; ?>" style="display: none;" />
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">When did the disaster ended?</span>
                        <input type="text" id="EndDisasterForm_ENDED" name="ENDED" class="form-control" value="<?php echo date("Y/m/d H:i", $time); ?>" required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="EndDisasterForm_SUBMIT" data-loading-text="Ending Disaster..." class="btn btn-danger">End Disaster</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('library/html/navbar.php'); ?>
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1 class="red_alert">
            On-Going Disaster
            <small>in<br /><?php echo 'Brgy. ' . $area_brgy . ', ' . $area_district . ', ' . $area_city . ' City'; ?></small>
        </h1>
    </div>
    <div id="pagemessagebox" tabindex="0"></div>
    <div style="clear: both;"></div>
    <br />
    <div class="panel panel-primary">
        <div class="panel-heading">
            Disaster Info
            <a href="BrgyEditDisaster.php?id=<?php echo $result_ID; ?>" class="btn btn-default pull-right">Edit Details</a>
            <div style="clear: both;"></div>
        </div>
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
                                ?>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#EndDisasterModal"><span class="glyphicon glyphicon-arrow-down"></span> End Disaster</button>
                                if the disaster ended.
                                <?php

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
            <div class="pull-right">
            <?php
                if($result_ACCEPTED == 0){
                    ?>
                    <a href="?id=<?php echo $result_ID; ?>&confirm" class="btn btn-success">Confirm Details</a>
                    <?php
                }
                else{
                    ?>
                    <p>Details are confirmed. Barangay cannot edit the details anymore.</p>
                    <?php
                }
            ?>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
    <br />

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Location</h3>
        </div>
    

<div class="panel-body">

        <div class="panel panel-default">
                <div class="panel-body">

                <div class = "col-lg-9">
                           <div id="mapid" style="height: 70vh; width: 100%;" tabindex="0"> </div>
                </div>

                <br>
                <h2> Location Info </h2>
                <hr>
                   <?php

               $sql ='SELECT disaster_declare.LAT, disaster_declare.LNG, disaster_declare.RADIUS, disaster_type.NAME AS COLORNAME

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ID = ' . $_GET['id'];

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

             while($row = $result->fetch_array())
             {
                ?>

                <div style = "text-indent: 47px">

                    <h5> Latitude: <?php echo $row['LAT']; ?></h5>
                    <h5> Longitude: <?php echo $row['LNG']; ?></h5>
                    <h5> Radius: <?php echo $row['RADIUS']; ?></h5>

                </div>
                <?php
             }

            ?>   

    
                </div>
        </div>
        </div>

        </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Disaster Reports</h3>
        </div>
        <div class="panel-body">
            <div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#PendingDR" role="tab" data-toggle="tab">Pending</a>
                    </li>
                    <li role="presentation">
                        <a href="#AcceptedDR" role="tab" data-toggle="tab">Accepted</a>
                    </li>
                    <li role="presentation">
                        <a href="#DeclinedDR" role="tab" data-toggle="tab">Declined</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="PendingDR">
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
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="PendingReportList">
                            </tbody>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="AcceptedDR">
                        <br />
                        <button class="btn btn-default pull-right" data-toggle="modal" data-target="#DisasterReportModal">
                            <span class="glyphicon glyphicon-plus"></span> Add a Verified Disaster Report
                        </button>
                        <div style="clear: both;"></div>
                        <br />
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
                            <tbody id="AcceptedReportList">

                            </tbody>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="DeclinedDR">
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
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="DeclinedReportList">
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Evacuation Reports</h3>
        </div>
        <div class="panel-body">
            <div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#PendingER" role="tab" data-toggle="tab">Pending</a>
                    </li>
                    <li role="presentation">
                        <a href="#AcceptedER" role="tab" data-toggle="tab">Accepted</a>
                    </li>
                    <li role="presentation">
                        <a href="#DeclinedER" role="tab" data-toggle="tab">Declined</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="PendingER">
                        <br />
                        <!--Pending reports here-->
                        <div class="panel-group" id="PendingEvacAccordion" role="tablist" aria-multiselectable="true">
                            <!--List start-->
                            <div id="PageComponent_PendingEvacList">

                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="AcceptedER">
                        <br />
                        <!--Accepted reports here-->
                        <div class="panel-group" id="AcceptedEvacAccordion" role="tablist" aria-multiselectable="true">
                            <!--List start-->
                            <div id="PageComponent_AcceptedEvacList">

                            </div>
                        </div>
                        <button class="btn btn-default pull-right" data-toggle="modal" data-target="#AddEvacuationModal">
                            <span class="glyphicon glyphicon-plus"></span> Add a new Evacuation Center
                        </button>
                        <div style="clear: both;"></div>
                        <br />
                    </div>
                    <div role="tabpanel" class="tab-pane" id="DeclinedER">
                        <br />
                        <!--Declined reports here-->
                        <div class="panel-group" id="DeclinedEvacAccordion" role="tablist" aria-multiselectable="true">
                            <!--List start-->
                            <div id="PageComponent_DeclinedEvacList">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="panel panel-primary">
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
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="CostOfAssistanceTable">
                </tbody>
            </table>
            <button class="btn btn-default pull-right" data-toggle="modal" data-target="#UpdateCOAModal">
                <span class="glyphicon glyphicon-refresh"></span> Update Current Cost of Assistance
            </button>
        </div>
    </div>



    <div style="display: none;">
        <form id="DeclineForm" method="post" action="library/form/CswdDeclineReportForm.php">
            <input id="DeclineForm_ID" name="ID" type="text" />
        </form>
        <form id="MoveForm" method="post" action="library/form/CswdMoveReportForm.php">
            <input id="MoveForm_ID" name="ID" type="text" />
        </form>
        <form id="EvacDeclineForm" method="post" action="library/form/CswdDeclineEvacReportForm.php">
            <input id="EvacDeclineForm_ID" name="ID" type="text" />
        </form>
        <form id="EvacMoveForm" method="post" action="library/form/CswdMoveEvacReportForm.php">
            <input id="EvacMoveForm_ID" name="ID" type="text" />
        </form>
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
<script src="js/jquery.datetimepicker.full.min.js"></script>
<script src="js/jquery.form.min.js"></script>
<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
   integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
   crossorigin=""></script>

<script>

    $('#EndDisasterForm_ENDED').datetimepicker({
        mask:'9999/19/39 29:59'
    });
    $('#EndDisasterForm').on('submit', function (e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            success: function (data) {
                DisplayMsg(data, 'ED_msgbox', function (SuccessMsg) {
                    alert('Disaster Ended. You will be redirected to your dashboard.');
                    window.location = 'index.php';
                });
            }
        });
    });

    var name_of_this_user = '<?php echo $session_USER_FIRSTNAME; ?> <?php echo $session_USER_MIDDLENAME; ?> <?php echo $session_USER_LASTNAME; ?>';
    <?php
    $EvacList = array();
    $sql = 'SELECT          evacuation_list.ID,
                                    evacuation_list.EVACNAME,
                                    barangay.NAME AS BARANGAY,
                                    evacuation_list.EVACADDRESS1,
                                    evacuation_list.EVACADDRESS2,
                                    evacuation_list.LAT,
                                    evacuation_list.LNG
                    FROM            evacuation_list,
                                    barangay
                    WHERE           evacuation_list.BARANGAY = barangay.ID
                    GROUP BY        evacuation_list.ID';
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while($row = $result->fetch_assoc()){
        $EvacList[] = $row;
    }
    ?>

    var dir = document.URL.substr(0,document.URL.lastIndexOf('/'));
    var _GoogleMaps = {
        map: null,
        circle: null,
        mapIcons: {
            GreenEvacuationCenter: dir + '/img/offcial/map/evac_icon.png',
            YellowEvacuationCenter: dir + '/img/offcial/map/evac_icon_f.png',
            RedEvacuationCenter: dir + '/img/offcial/map/evac_icon_x.png'
        },
        EvacuationList: JSON.parse('<?php echo json_encode($EvacList); ?>'),
        DisasterInfo: {
            NICKNAME: "<?php echo $result_NICKNAME; ?>",
            LAT: <?php echo $result_LAT; ?>,
            LNG: <?php echo $result_LNG; ?>,
            RAD: <?php echo $result_RADIUS; ?>,
            marker: null
        },
        SelectedEvacuationCenter: null,
        DeselectEvacuationCenters: function () {
            _GoogleMaps.EvacuationList.forEach(function (EvacuationCenter) {
                EvacuationCenter.marker.setIcon(_GoogleMaps.mapIcons.YellowEvacuationCenter);
                _GoogleMaps.SelectedEvacuationCenter = null;
            });

            document.getElementById('AddEvacuationForm_EVACID').value = '';
            document.getElementById('S_EvacName').innerHTML = '';
            document.getElementById('S_EvacAddress').innerHTML = '';
            document.getElementById('S_EvacBrgy').innerHTML = '';
        }
    };
    function initAutocomplete() {
        _GoogleMaps.map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: parseFloat(_GoogleMaps.DisasterInfo.LAT),
                lng: parseFloat(_GoogleMaps.DisasterInfo.LNG)
            },
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [{"featureType": "poi", "stylers": [{ "visibility": "off" }]}] //Remove Labels
        });

        _GoogleMaps.circle = new google.maps.Circle({
            strokeColor: 'red',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: 'red',
            fillOpacity: 0.35,
            map: _GoogleMaps.map,
            center: {
                lat: parseFloat(_GoogleMaps.DisasterInfo.LAT),
                lng: parseFloat(_GoogleMaps.DisasterInfo.LNG)
            },
            radius: parseFloat(_GoogleMaps.DisasterInfo.RAD),
            editable: false
        });

        _GoogleMaps.EvacuationList.forEach(function (EvacuationCenter) {
            EvacuationCenter.marker = new google.maps.Marker({
                position: {
                    lat: parseFloat(EvacuationCenter.LAT),
                    lng: parseFloat(EvacuationCenter.LNG)
                },
                map: _GoogleMaps.map,
                title: EvacuationCenter.EVACNAME,
                icon: _GoogleMaps.mapIcons.YellowEvacuationCenter,
                info: EvacuationCenter
            });

            EvacuationCenter.marker.addListener('click', function (event) {
                if(_GoogleMaps.circle.getBounds().contains(this.getPosition())){
                    alert('The System would not allow to use that evacuation center because it is in the radius of the disaster.')
                }
                else{
                    _GoogleMaps.DeselectEvacuationCenters();
                    this.setIcon(_GoogleMaps.mapIcons.GreenEvacuationCenter);
                    _GoogleMaps.SelectedEvacuationCenter = this.info;

                    document.getElementById('AddEvacuationForm_EVACID').value = this.info.ID;
                    document.getElementById('S_EvacName').innerHTML = this.info.EVACNAME;
                    document.getElementById('S_EvacAddress').innerHTML = this.info.EVACADDRESS1;
                    document.getElementById('S_EvacBrgy').innerHTML = this.info.BARANGAY;
                }
            });
        });
        _GoogleMaps.DisasterInfo.marker = new google.maps.Marker({
            position: {
                lat: parseFloat(_GoogleMaps.DisasterInfo.LAT),
                lng: parseFloat(_GoogleMaps.DisasterInfo.LNG)
            },
            map: _GoogleMaps.map,
            title: "Disaster",
            clickable: false
        });

        _GoogleMaps.map.addListener('click', function (event) {
            _GoogleMaps.DeselectEvacuationCenters();
        });
    }

    $('#AddEvacuationModal').on('shown.bs.modal', function (e) {
        google.maps.event.trigger(map, 'resize');
        _GoogleMaps.map.setCenter(_GoogleMaps.DisasterInfo.marker.getPosition());
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&key=AIzaSyAuX4dJw6AKx5rbomKRek679WKUACmI9eE" async defer></script>
<script>
    //script Manipulation
    function AddEvacuationCenter(evac_type, evac_id, evac_name, evac_address1, evac_address2, evac_lat, evac_lng) {
        var evac_type_txt = '';
        switch(evac_type)
        {
            case 1:
                evac_type_txt = 'Pending';
                break;
            case 2:
                evac_type_txt = 'Accepted';
                break;
            case 3:
                evac_type_txt = 'Declined';
                break;
        }

        /*
         <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#">
         <span class="glyphicon glyphicon-plus"></span> Add a new Evacuation Center
         </button>
         <div style="clear: both;"></div>
         */

        var ItemHtml = '' +
            '<div class="panel panel-default" id="' + evac_type_txt + 'EvacItem_' + evac_id + '">'+
            '   <div class="panel-heading" role="tab">'+
            '       <h4 class="panel-title">'+
            '           <a role="button" data-toggle="collapse" data-parent="#' + evac_type_txt + 'EvacAccordion" href="#' + evac_type_txt + 'EvacItemPanel_' + evac_id + '" aria-expanded="true" aria-controls="collapseOne">'+
            '               <small><span class="glyphicon glyphicon-triangle-bottom"></span></small>'+
            '               <span id="' + evac_type_txt + 'EvacName_' + evac_id + '">' + evac_name + '</span>'+
            '           </a>';

        if(evac_type == 2)
        {
            ItemHtml += ''+
                '<button class="btn btn-default pull-right" onclick="initiateEvacUpdateReport(' + evac_id + ')" data-toggle="modal" data-target="#UpdateEvacuationModal">'+
                '    <span class="glyphicon glyphicon-plus"></span> Add an update for this Evacuation Center'+
                '</button>'+
                '<div style="clear: both;"></div>';
        }

        ItemHtml += ''+
            '       </h4>'+
            '    </div>'+
            '    <div id="' + evac_type_txt + 'EvacItemPanel_' + evac_id + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">'+
            '       <div class="panel-body">'+
            '           <div class="row">'+
            '               <div class="col-lg-6 col-sm-6 col-md-6">'+
            '                   <img src="https://maps.googleapis.com/maps/api/staticmap?markers=color:red%7C' + evac_lat + ',' + evac_lng + '&amp;center=' + evac_lat + ',' + evac_lng + '&amp;zoom=14&amp;size=300x150&amp;maptype=roadmap&key=AIzaSyCalJXL3IZ37jpy9s0K5ge-xgojC8fXWOM">'+
            '               </div>'+
            '               <div class="col-lg-6 col-sm-6 col-md-6">'+
            '                   <div id="' + evac_type_txt + 'EvacLat_' + evac_id + '" style="display:none;">' + evac_lat + '</div>'+
            '                   <div id="' + evac_type_txt + 'EvacLng_' + evac_id + '" style="display:none;">' + evac_lng + '</div>'+
            '                   <h5>Google Map Address: <b id="' + evac_type_txt + 'EvacAddress2_' + evac_id + '">' + evac_address2 + '</b></h5>'+
            '                   <h5>Complete Adress: <b id="' + evac_type_txt + 'EvacAddress1_' + evac_id + '">' + evac_address1 + '</b></h5>'+
            '               </div>'+
            '           </div>'+
            '           <br />'+
            '           <h4>Previous Reports</h4>'+
            '           <table class="table">'+
            '               <thead>'+
            '                   <tr>'+
            '                       <th>Date Time</th>'+
            '                       <th>Persons Served</th>'+
            '                       <th>Families Served</th>'+
            '                       <th>Reporter</th>';

        if(evac_type != 2) {
            ItemHtml += '                       <th>Action</th>';
        }

        ItemHtml += ''+
            '                   </tr>'+
            '               </thead>'+
            '               <tbody id="' + evac_type_txt + 'EvacReportList_' + evac_id + '">'+
            '               </tbody>'+
            '           </table>'+
            '       </div>'+
            '   </div>'+
            '</div>';

        var componentlist = document.getElementById('PageComponent_' + evac_type_txt + 'EvacList');
        componentlist.innerHTML += ItemHtml;
    }
    function RemoveEvacuationCenter(evac_type, evac_id) {
        var evac_type_txt = '';
        switch(evac_type)
        {
            case 1:
                evac_type_txt = 'Pending';
                break;
            case 2:
                evac_type_txt = 'Accepted';
                break;
            case 3:
                evac_type_txt = 'Declined';
                break;
        }

        $('#' + evac_type_txt + 'EvacItem_' + evac_id).remove();
    }

    function AddEvacuationReport(evac_type, evac_id, report_id, evac_datetime, evac_persons, evac_families, evac_reporter){

        var evac_type_txt = '';
        switch(evac_type)
        {
            case 1:
                evac_type_txt = 'Pending';
                break;
            case 2:
                evac_type_txt = 'Accepted';
                break;
            case 3:
                evac_type_txt = 'Declined';
                break;
        }

        var HtmlItem = '' +
            '<tr id="' + evac_type_txt + 'EvacReport_' + report_id + '">'+
            '    <td id="' + evac_type_txt + 'EvacReportTime_' + report_id + '">' + evac_datetime + '</td>'+
            '    <td id="' + evac_type_txt + 'EvacReportPersons_' + report_id + '">' + evac_persons + '</td>'+
            '    <td id="' + evac_type_txt + 'EvacReportFamilies_' + report_id + '">' + evac_families + '</td>'+
            '    <td id="' + evac_type_txt + 'EvacReportReporter_' + report_id + '">' + evac_reporter + '</td>';
        if(evac_type == 1)
        {
            HtmlItem += ''+
                '    <td>'+
                '        <button type="button" id="EvacDeclineBtn_' + report_id + '" data-loading-text="Declining..." onclick="SubmitEvacDecline(' + evac_id + ', ' + report_id + ');" class="btn btn-danger">Decline</button>'+
                '        <button type="button" data-toggle="modal" data-target="#AcceptEvacReportModal" onclick="initiateEvacAcceptForm(' + evac_id + ',' + report_id + ')" class="btn btn-success">Accept</button>'+
                '    </td>';
        }
        if(evac_type == 3)
        {
            HtmlItem += ''+
                '<td>'+
                '    <button type="button" id="EvacMoveBtn_' + report_id + '" data-loading-text="Moving..." onclick="SubmitEvacMove(' + evac_id + ', ' + report_id + ');" class="btn btn-warning">Move to pending</button>'+
                '</td>';
        }
        HtmlItem += '</tr>';

        document.getElementById(evac_type_txt + 'EvacReportList_' + evac_id).innerHTML += HtmlItem;
    }
    function RemoveEvacuationReport(evac_type, evac_id, report_id){
        var evac_type_txt = '';
        switch(evac_type)
        {
            case 1:
                evac_type_txt = 'Pending';
                break;
            case 2:
                evac_type_txt = 'Accepted';
                break;
            case 3:
                evac_type_txt = 'Declined';
                break;
        }
        $('#' + evac_type_txt + 'EvacReport_' + report_id).remove();

        if(document.getElementById(evac_type_txt + 'EvacReportList_' + evac_id).innerHTML.trim() == '')
        {
            RemoveEvacuationCenter(evac_type, evac_id);
        }
    }

    function AcceptEvacuationReport(evac_id, report_id){
        var evac_datetime = document.getElementById('PendingEvacReportTime_' + report_id).innerHTML;
        var evac_persons = document.getElementById('PendingEvacReportPersons_' + report_id).innerHTML;
        var evac_families = document.getElementById('PendingEvacReportFamilies_' + report_id).innerHTML;
        var evac_reporter = document.getElementById('PendingEvacReportReporter_' + report_id).innerHTML;

        if(document.getElementById('AcceptedEvacItem_' + evac_id) == null)
        {
            var evac_name = document.getElementById('PendingEvacName_' + evac_id).innerHTML;
            var evac_address1 = document.getElementById('PendingEvacAddress1_' + evac_id).innerHTML;
            var evac_address2 = document.getElementById('PendingEvacAddress2_' + evac_id).innerHTML;
            var evac_lat = document.getElementById('PendingEvacLat_' + evac_id).innerHTML;
            var evac_lng = document.getElementById('PendingEvacLng_' + evac_id).innerHTML;

            AddEvacuationCenter(2, evac_id, evac_name, evac_address1, evac_address2, evac_lat, evac_lng);
            AddEvacuationReport(2, evac_id, report_id, evac_datetime, evac_persons, evac_families, evac_reporter);
        }
        else
        {
            AddEvacuationReport(2, evac_id, report_id, evac_datetime, evac_persons, evac_families, evac_reporter);
        }
        RemoveEvacuationReport(1, evac_id, report_id);
    }
    function DeclineEvacuationReport(evac_id, report_id){
        var evac_datetime = document.getElementById('PendingEvacReportTime_' + report_id).innerHTML;
        var evac_persons = document.getElementById('PendingEvacReportPersons_' + report_id).innerHTML;
        var evac_families = document.getElementById('PendingEvacReportFamilies_' + report_id).innerHTML;
        var evac_reporter = document.getElementById('PendingEvacReportReporter_' + report_id).innerHTML;

        if(document.getElementById('DeclinedEvacItem_' + evac_id) == null)
        {
            var evac_name = document.getElementById('PendingEvacName_' + evac_id).innerHTML;
            var evac_address1 = document.getElementById('PendingEvacAddress1_' + evac_id).innerHTML;
            var evac_address2 = document.getElementById('PendingEvacAddress2_' + evac_id).innerHTML;
            var evac_lat = document.getElementById('PendingEvacLat_' + evac_id).innerHTML;
            var evac_lng = document.getElementById('PendingEvacLng_' + evac_id).innerHTML;

            AddEvacuationCenter(3, evac_id, evac_name, evac_address1, evac_address2, evac_lat, evac_lng);
            AddEvacuationReport(3, evac_id, report_id, evac_datetime, evac_persons, evac_families, evac_reporter);
        }
        else
        {
            AddEvacuationReport(3, evac_id, report_id, evac_datetime, evac_persons, evac_families, evac_reporter);
        }
        RemoveEvacuationReport(1, evac_id, report_id);
    }
    function MoveEvacuationReportToPending(evac_id, report_id){
        var evac_datetime = document.getElementById('DeclinedEvacReportTime_' + report_id).innerHTML;
        var evac_persons = document.getElementById('DeclinedEvacReportPersons_' + report_id).innerHTML;
        var evac_families = document.getElementById('DeclinedEvacReportFamilies_' + report_id).innerHTML;
        var evac_reporter = document.getElementById('DeclinedEvacReportReporter_' + report_id).innerHTML;

        if(document.getElementById('PendingEvacItem_' + evac_id) == null)
        {
            var evac_name = document.getElementById('DeclinedEvacName_' + evac_id).innerHTML;
            var evac_address1 = document.getElementById('DeclinedEvacAddress1_' + evac_id).innerHTML;
            var evac_address2 = document.getElementById('DeclinedEvacAddress2_' + evac_id).innerHTML;
            var evac_lat = document.getElementById('DeclinedEvacLat_' + evac_id).innerHTML;
            var evac_lng = document.getElementById('DeclinedEvacLng_' + evac_id).innerHTML;

            AddEvacuationCenter(1, evac_id, evac_name, evac_address1, evac_address2, evac_lat, evac_lng);
            AddEvacuationReport(1, evac_id, report_id, evac_datetime, evac_persons, evac_families, evac_reporter);
        }
        else
        {
            AddEvacuationReport(1, evac_id, report_id, evac_datetime, evac_persons, evac_families, evac_reporter);
        }
        RemoveEvacuationReport(3, evac_id, report_id);
    }

    //Page Variables
    var AEForm = {  //Add Evacuation Form
        form: document.getElementById('AddEvacuationForm'),
        evacid: document.getElementById('AddEvacuationForm_EVACID'),
        persons: document.getElementById('AddEvacuationForm_PERSONS'),
        families: document.getElementById('AddEvacuationForm_FAMILIES'),
        submit: '#AddEvacuationForm_SUBMIT',
        modal: '#AddEvacuationModal',
        msgbox: 'AE_msgbox'
    };
    var ERForm = {  //Evacuation Report Form
        form: document.getElementById('EvacuationReportForm'),
        evacid: document.getElementById('EvacuationReportForm_EVACID'),
        persons: document.getElementById('EvacuationReportForm_PERSONS'),
        families: document.getElementById('EvacuationReportForm_FAMILIES'),
        submit: '#EvacuationReportForm_SUBMIT',
        modal: '#UpdateEvacuationModal',
        modalLabel_name: document.getElementById('UpdateEvacuationModalLabel_Name'),
        msgbox: 'ER_msgbox'
    };
    var EvacDeclineForm = {
        form: document.getElementById('EvacDeclineForm'),
        id: document.getElementById('EvacDeclineForm_ID'),
        evacid: ''
    };
    var EvacMoveForm = {
        form: document.getElementById('EvacMoveForm'),
        id: document.getElementById('EvacMoveForm_ID'),
        evacid: ''
    };
    var EvacAcceptForm = {
        form: document.getElementById('AcceptEvacForm'),
        id: document.getElementById('AcceptEvacForm_ID'),
        evacid: '',
        msgbox: document.getElementById('AcceptEvacForm_msgbox'),
        persons: document.getElementById('AcceptEvacForm_PERSONS'),
        families: document.getElementById('AcceptEvacForm_FAMILIES'),
        datetime: document.getElementById('AcceptEvacForm_DATETIME'),
        reporter: document.getElementById('AcceptEvacForm_REPORTER'),
        modal: document.getElementById('AcceptEvacReportModal')
    };

    //initiate values
    function initiateEvacAcceptForm(evac_id, report_id) {
        var evac_datetime = document.getElementById('PendingEvacReportTime_' + report_id).innerHTML;
        var evac_persons = document.getElementById('PendingEvacReportPersons_' + report_id).innerHTML;
        var evac_families = document.getElementById('PendingEvacReportFamilies_' + report_id).innerHTML;
        var evac_reporter = document.getElementById('PendingEvacReportReporter_' + report_id).innerHTML;

        EvacAcceptForm.evacid = evac_id;
        EvacAcceptForm.id.value = report_id;
        EvacAcceptForm.datetime.innerHTML = evac_datetime;
        EvacAcceptForm.persons.innerHTML = evac_persons;
        EvacAcceptForm.families.innerHTML = evac_families;
        EvacAcceptForm.reporter.innerHTML = evac_reporter;
    }
    function initiateEvacUpdateReport(evac_id){
        ERForm.evacid.value = evac_id;
        ERForm.modalLabel_name.innerHTML = document.getElementById('AcceptedEvacName_' + evac_id).innerHTML;
    }


    //Reset Form Scripts
    function ResetAEForm() {
        AEForm.persons.value = 0;
        AEForm.families.value = 0;
    }
    function ResetERForm() {
        ERForm.persons.value = 0;
        ERForm.families.value = 0;
    }

    //Validation Scripts
    function AEForm_isValid(){
        //Validation code here
        var ae_id = AEForm.evacid.value;
        var ae_persons = AEForm.persons.value;
        var ae_families = AEForm.families.value;

        if(ae_id == '')
        {
            createmessagein(3, 'Please select an evacuation center.', AEForm.msgbox);
            return false;
        }

        if(ae_persons < 1 && ae_families < 1)
        {
            createmessagein(3, 'There must be atleast 1 person entered in the evacuation center if you want it to open.', AEForm.msgbox);
            return false;
        }

        if((ae_families * 2) > ae_persons)
        {
            createmessagein(3, 'Please indicate the number of persons that are in the evacuation center. The number of persons cannot be lower than the number of families. a family includes atleast 2 persons', AEForm.msgbox);
            return false;
        }

        //Validation ends here
        return true;
    }
    function ERForm_isValid(){
        //Validation code here
        var ae_persons = ERForm.persons.value;
        var ae_families = ERForm.families.value;


        if((ae_families * 2) > ae_persons)
        {
            createmessagein(3, 'Please indicate the number of persons that are in the evacuation center. The number of persons cannot be lower than the number of families. a family includes atleast 2 persons', ERForm.msgbox);
            return false;
        }
        //Validation ends here
        return true;
    }

    //Submission Scripts
    AEForm.form.onsubmit = function (e) {
        e.preventDefault();
        if(AEForm_isValid())
        {
            var persons = AEForm.persons.value;
            var families = AEForm.families.value;
            $(this).ajaxSubmit({
                beforeSend:function()
                {
                    $(AEForm.submit).button('loading');
                },
                uploadProgress:function(event,position,total,percentCompelete)
                {

                },
                success:function(data)
                {
                    $(AEForm.submit).button('reset');
                    var server_message = data.trim();
                    if(!isWhitespace(GetSuccessMsg(server_message)))
                    {
                        alert('Report sent.');
                        var servervalues = GetSuccessMsg(server_message).split('!');
                        createmessagein(1, GetSuccessMsg(server_message), AEForm.msgbox);
                        location.reload();
                        ResetAEForm();
                        $(AEForm.modal).modal('hide');
                    }
                    else if(!isWhitespace(GetWarningMsg(server_message)))
                    {
                        createmessagein(2, GetWarningMsg(server_message), AEForm.msgbox);
                    }
                    else if(!isWhitespace(GetErrorMsg(server_message)))
                    {
                        createmessagein(3, GetErrorMsg(server_message), AEForm.msgbox);
                    }
                    else if(!isWhitespace(GetServerMsg(server_message)))
                    {
                        createmessagein(4, GetServerMsg(server_message), AEForm.msgbox);
                    }
                    else
                    {
                        createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', AEForm.msgbox);
                    }
                }
            });
        }
    };
    ERForm.form.onsubmit = function (e) {
        e.preventDefault();
        if(ERForm_isValid())
        {
            $(this).ajaxSubmit({
                beforeSend:function()
                {
                    $(ERForm.submit).button('loading');
                },
                uploadProgress:function(event,position,total,percentCompelete)
                {

                },
                success:function(data)
                {
                    $(ERForm.submit).button('reset');
                    var server_message = data.trim();
                    if(!isWhitespace(GetSuccessMsg(server_message)))
                    {
                        var servervalues = GetSuccessMsg(server_message).split('!');
                        alert('Report sent.');
                        createmessagein(1, GetSuccessMsg(server_message), ERForm.msgbox);
                        AddEvacuationReport(2, ERForm.evacid.value, servervalues[0], servervalues[1], ERForm.persons.value, ERForm.families.value, name_of_this_user);
                        ResetERForm();
                        $(ERForm.modal).modal('hide');
                    }
                    else if(!isWhitespace(GetWarningMsg(server_message)))
                    {
                        createmessagein(2, GetWarningMsg(server_message), ERForm.msgbox);
                    }
                    else if(!isWhitespace(GetErrorMsg(server_message)))
                    {
                        createmessagein(3, GetErrorMsg(server_message), ERForm.msgbox);
                    }
                    else if(!isWhitespace(GetServerMsg(server_message)))
                    {
                        createmessagein(4, GetServerMsg(server_message), ERForm.msgbox);
                    }
                    else
                    {
                        createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', ERForm.msgbox);
                    }
                }
            });
        }
    };
    EvacAcceptForm.form.onsubmit = function (e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend: function () {
                $('#AcceptEvacForm_SUBMIT').button('loading');
            },
            uploadProgress: function (event, position, total, percentCompelete) {

            },
            success: function (data) {
                $('#AcceptEvacForm_SUBMIT').button('reset');
                var server_message = data.trim();
                if(server_message == 'success')
                {
                    AcceptEvacuationReport(EvacAcceptForm.evacid, EvacAcceptForm.id.value);
                    $(EvacAcceptForm.modal).modal('hide');
                }
                else if(!isWhitespace(GetSuccessMsg(server_message)))
                {
                    alert(GetSuccessMsg(server_message));
                }
                else if(!isWhitespace(GetWarningMsg(server_message)))
                {
                    alert(GetWarningMsg(server_message));
                }
                else if(!isWhitespace(GetErrorMsg(server_message)))
                {
                    alert(GetErrorMsg(server_message));
                }
                else if(!isWhitespace(GetServerMsg(server_message)))
                {
                    alert(GetServerMsg(server_message));
                }
                else
                {
                    alert('Oh Snap! There is a problem with the server or your connection.');
                }
            }
        });
    };
    function SubmitEvacDecline(evac_id, report_id){
        EvacDeclineForm.id.value = report_id;
        EvacDeclineForm.evacid = evac_id;
        $(EvacDeclineForm.form).ajaxSubmit({
            beforeSend: function () {
                $('#EvacDeclineBtn_' + report_id).button('loading');
            },
            uploadProgress: function (event, position, total, percentCompelete) {

            },
            success: function (data) {
                $('#EvacDeclineBtn_' + report_id).button('reset');
                var server_message = data.trim();
                if(server_message == 'success')
                {
                    DeclineEvacuationReport(evac_id, report_id);
                }
                else if(!isWhitespace(GetSuccessMsg(server_message)))
                {
                    alert(GetSuccessMsg(server_message));
                }
                else if(!isWhitespace(GetWarningMsg(server_message)))
                {
                    alert(GetWarningMsg(server_message));
                }
                else if(!isWhitespace(GetErrorMsg(server_message)))
                {
                    alert(GetErrorMsg(server_message));
                }
                else if(!isWhitespace(GetServerMsg(server_message)))
                {
                    alert(GetServerMsg(server_message));
                }
                else
                {
                    alert('Oh Snap! There is a problem with the server or your connection.');
                }
            }
        });
    }
    function SubmitEvacMove(evac_id, report_id){
        EvacMoveForm.id.value = report_id;
        EvacMoveForm.evacid = evac_id;
        $(EvacMoveForm.form).ajaxSubmit({
            beforeSend: function () {
                $('#EvacMoveBtn_' + report_id).button('loading');
            },
            uploadProgress: function (event, position, total, percentCompelete) {

            },
            success: function (data) {
                $('#EvacMoveBtn_' + report_id).button('reset');
                var server_message = data.trim();
                if(server_message == 'success')
                {
                    MoveEvacuationReportToPending(evac_id, report_id);
                }
                else if(!isWhitespace(GetSuccessMsg(server_message)))
                {
                    alert(GetSuccessMsg(server_message));
                }
                else if(!isWhitespace(GetWarningMsg(server_message)))
                {
                    alert(GetWarningMsg(server_message));
                }
                else if(!isWhitespace(GetErrorMsg(server_message)))
                {
                    alert(GetErrorMsg(server_message));
                }
                else if(!isWhitespace(GetServerMsg(server_message)))
                {
                    alert(GetServerMsg(server_message));
                }
                else
                {
                    alert('Oh Snap! There is a problem with the server or your connection.');
                }
            }
        });
    }

    //-------------------------------------------------------------------------------------------------------------------
    // Disaster Reports
    //-------------------------------------------------------------------------------------------------------------------
    var AcceptForm = {
        form: document.getElementById('AcceptForm'),
        id: document.getElementById('AcceptForm_ID'),
        msgbox: document.getElementById('AcceptForm_msgbox'),
        dead: document.getElementById('AcceptForm_DEAD'),
        injured: document.getElementById('AcceptForm_INJURED'),
        missing: document.getElementById('AcceptForm_MISSING'),
        totally: document.getElementById('AcceptForm_TOTALLY'),
        partially: document.getElementById('AcceptForm_PARTIALLY'),
        datetime: document.getElementById('AcceptForm_DATETIME'),
        reporter: document.getElementById('AcceptForm_REPORTER'),
        modal: document.getElementById('AcceptReportModal')
    };
    var DeclineForm = {
        form: document.getElementById('DeclineForm'),
        id: document.getElementById('DeclineForm_ID')
    };
    var MoveForm = {
        form: document.getElementById('MoveForm'),
        id: document.getElementById('MoveForm_ID')
    };
     var DRForm = {  //Disaster Report Form
        form: document.getElementById('DisasterReportForm'),
        reportid: document.getElementById('disasterReportForm_REPORTID'),
        dead: document.getElementById('DisasterReportForm_DEAD'),
        injured: document.getElementById('DisasterReportForm_INJURED'),
        missing: document.getElementById('DisasterReportForm_MISSING'),
        totally: document.getElementById('DisasterReportForm_TOTALLY'),
        partially: document.getElementById('DisasterReportForm_PARTIALLY'),
        submit : '#DisasterReportForm_SUBMIT',
        modal: '#DisasterReportModal',
        msgbox: 'DR_msgbox'
    };


    function ResetDRForm() {
        DRForm.dead.value = 0;
        DRForm.injured.value = 0;
        DRForm.missing.value = 0;
        DRForm.totally.value = 0;
        DRForm.partially.value = 0;
    }

    function AddPendingReport(PendingID, PDateTime, PDead, PInjured, PMissing, PTotally, PPartially, Preporter) {
        document.getElementById('PendingReportList').innerHTML += '' +
            '<tr id="Pending' + PendingID + '">' +
            '    <td id="Pending' + PendingID + 'DateTime">' + PDateTime + '</td>' +
            '    <td id="Pending' + PendingID + 'Dead">' + PDead + '</td>' +
            '    <td id="Pending' + PendingID + 'Injured">' + PInjured + '</td>' +
            '    <td id="Pending' + PendingID + 'Missing">' + PMissing + '</td>' +
            '    <td id="Pending' + PendingID + 'Totally">' + PTotally + '</td>' +
            '    <td id="Pending' + PendingID + 'Partially">' + PPartially + '</td>' +
            '    <td id="Pending' + PendingID + 'Reporter">' + Preporter + '</td>' +
            '    <td>' +
            '        <button type="button" id="DeclineBtn' + PendingID + '" data-loading-text="Declining..." onclick="DeclineClicked(' + PendingID + ')" class="btn btn-danger">Decline</button>' +
            '        <button type="button" data-toggle="modal" data-target="#AcceptReportModal" onclick="AcceptClicked(' + PendingID + ')" class="btn btn-success">Accept</button>' +
            '    </td>' +
            '</tr>';
    }
    function DeletePendingReport(PendingID) {
        document.getElementById('Pending' + PendingID).remove();
    }
    function AddAcceptedReport(AccpetedID, ADateTime, ADead, AInjured, AMissing, ATotally, APartially, Areporter) {
        document.getElementById('AcceptedReportList').innerHTML += '' +
            '<tr id="Accepted' + AccpetedID + '">' +
            '    <td id="Accepted' + AccpetedID + 'DateTime">' + ADateTime + '</td>' +
            '    <td id="Accepted' + AccpetedID + 'Dead">' + ADead + '</td>' +
            '    <td id="Accepted' + AccpetedID + 'Injured">' + AInjured + '</td>' +
            '    <td id="Accepted' + AccpetedID + 'Missing">' + AMissing + '</td>' +
            '    <td id="Accepted' + AccpetedID + 'Totally">' + ATotally + '</td>' +
            '    <td id="Accepted' + AccpetedID + 'Partially">' + APartially + '</td>' +
            '    <td id="Accepted' + AccpetedID + 'Reporter">' + Areporter + '</td>' +
            '</tr>';
    }
    function AddDeclinedReport(DeclinedID, DDateTime, DDead, DInjured, DMissing, DTotally, DPartially, Dreporter) {
        document.getElementById('DeclinedReportList').innerHTML += '' +
            '<tr id="Declined' + DeclinedID + '">' +
            '    <td id="Declined' + DeclinedID + 'DateTime">' + DDateTime + '</td>' +
            '    <td id="Declined' + DeclinedID + 'Dead">' + DDead + '</td>' +
            '    <td id="Declined' + DeclinedID + 'Injured">' + DInjured + '</td>' +
            '    <td id="Declined' + DeclinedID + 'Missing">' + DMissing + '</td>' +
            '    <td id="Declined' + DeclinedID + 'Totally">' + DTotally + '</td>' +
            '    <td id="Declined' + DeclinedID + 'Partially">' + DPartially + '</td>' +
            '    <td id="Declined' + DeclinedID + 'Reporter">' + Dreporter + '</td>' +
            '    <td>' +
            '        <button type="button" id="MoveBtn' + DeclinedID + '" data-loading-text="Moving..." onclick="MoveClicked(' + DeclinedID + ')" class="btn btn-warning">Move to pending</button>' +
            '    </td>' +
            '</tr>';
    }
    function DeleteDeclinedReport(DeclinedID) {
        document.getElementById('Declined' + DeclinedID).remove();
    }

    function AcceptReport(AcceptID) {
        var AcceptDateTime = document.getElementById('Pending' + AcceptID + 'DateTime').innerHTML;
        var AcceptDead = document.getElementById('Pending' + AcceptID + 'Dead').innerHTML;
        var AcceptInjured = document.getElementById('Pending' + AcceptID + 'Injured').innerHTML;
        var AcceptMissing = document.getElementById('Pending' + AcceptID + 'Missing').innerHTML;
        var AcceptTotally = document.getElementById('Pending' + AcceptID + 'Totally').innerHTML;
        var AcceptPartially = document.getElementById('Pending' + AcceptID + 'Partially').innerHTML;
        var AcceptReporter = document.getElementById('Pending' + AcceptID + 'Reporter').innerHTML;
        AddAcceptedReport(AcceptID, AcceptDateTime, AcceptDead, AcceptInjured, AcceptMissing, AcceptTotally, AcceptPartially, AcceptReporter);
        DeletePendingReport(AcceptID);
    }
    function DeclineReport(DeclineID) {
        var DeclineDateTime = document.getElementById('Pending' + DeclineID + 'DateTime').innerHTML;
        var DeclineDead = document.getElementById('Pending' + DeclineID + 'Dead').innerHTML;
        var DeclineInjured = document.getElementById('Pending' + DeclineID + 'Injured').innerHTML;
        var DeclineMissing = document.getElementById('Pending' + DeclineID + 'Missing').innerHTML;
        var DeclineTotally = document.getElementById('Pending' + DeclineID + 'Totally').innerHTML;
        var DeclinePartially = document.getElementById('Pending' + DeclineID + 'Partially').innerHTML;
        var DeclineReporter = document.getElementById('Pending' + DeclineID + 'Reporter').innerHTML;
        AddDeclinedReport(DeclineID, DeclineDateTime, DeclineDead, DeclineInjured, DeclineMissing, DeclineTotally, DeclinePartially, DeclineReporter);
        DeletePendingReport(DeclineID);
    }
    function MoveToPending(DeclineID) {
        var DeclineDateTime = document.getElementById('Declined' + DeclineID + 'DateTime').innerHTML;
        var DeclineDead = document.getElementById('Declined' + DeclineID + 'Dead').innerHTML;
        var DeclineInjured = document.getElementById('Declined' + DeclineID + 'Injured').innerHTML;
        var DeclineMissing = document.getElementById('Declined' + DeclineID + 'Missing').innerHTML;
        var DeclineTotally = document.getElementById('Declined' + DeclineID + 'Totally').innerHTML;
        var DeclinePartially = document.getElementById('Declined' + DeclineID + 'Partially').innerHTML;
        var DeclineReporter = document.getElementById('Declined' + DeclineID + 'Reporter').innerHTML;
        AddPendingReport(DeclineID, DeclineDateTime, DeclineDead, DeclineInjured, DeclineMissing, DeclineTotally, DeclinePartially, DeclineReporter);
        DeleteDeclinedReport(DeclineID);
    }

    function DeclineClicked(DeclineID) {
        DeclineForm.id.value = DeclineID;
         $(DeclineForm.form).ajaxSubmit({
             beforeSend: function () {
                 $('#DeclineBtn' + DeclineID).button('loading');
             },
             uploadProgress: function (event, position, total, percentCompelete) {

             },
             success: function (data) {
                 $('#DeclineBtn' + DeclineID).button('reset');
                 var server_message = data.trim();
                 if(server_message == 'success')
                 {
                     DeclineReport(DeclineID);
                 }
                 else if(!isWhitespace(GetSuccessMsg(server_message)))
                 {
                     alert(GetSuccessMsg(server_message));
                 }
                 else if(!isWhitespace(GetWarningMsg(server_message)))
                 {
                     alert(GetWarningMsg(server_message));
                 }
                 else if(!isWhitespace(GetErrorMsg(server_message)))
                 {
                     alert(GetErrorMsg(server_message));
                 }
                 else if(!isWhitespace(GetServerMsg(server_message)))
                 {
                     alert(GetServerMsg(server_message));
                 }
                 else
                 {
                     alert('Oh Snap! There is a problem with the server or your connection.');
                 }
             }
         });
    }
    function MoveClicked(MoveID) {
        MoveForm.id.value = MoveID;
        $(MoveForm.form).ajaxSubmit({
            beforeSend: function () {
                $('#MoveBtn' + MoveID).button('loading');
            },
            uploadProgress: function (event, position, total, percentCompelete) {

            },
            success: function (data) {
                $('#MoveBtn' + MoveID).button('reset');
                var server_message = data.trim();
                if(server_message == 'success')
                {
                    MoveToPending(MoveID);
                }
                else if(!isWhitespace(GetSuccessMsg(server_message)))
                {
                    alert(GetSuccessMsg(server_message));
                }
                else if(!isWhitespace(GetWarningMsg(server_message)))
                {
                    alert(GetWarningMsg(server_message));
                }
                else if(!isWhitespace(GetErrorMsg(server_message)))
                {
                    alert(GetErrorMsg(server_message));
                }
                else if(!isWhitespace(GetServerMsg(server_message)))
                {
                    alert(GetServerMsg(server_message));
                }
                else
                {
                    alert('Oh Snap! There is a problem with the server or your connection.');
                }
            }
        });
    }
    function AcceptClicked(AcceptID) {
        AcceptForm.id.value = AcceptID;
        AcceptForm.dead.innerHTML = document.getElementById('Pending' + AcceptID + 'Dead').innerHTML;
        AcceptForm.injured.innerHTML = document.getElementById('Pending' + AcceptID + 'Injured').innerHTML;
        AcceptForm.missing.innerHTML = document.getElementById('Pending' + AcceptID + 'Missing').innerHTML;
        AcceptForm.totally.innerHTML = document.getElementById('Pending' + AcceptID + 'Totally').innerHTML;
        AcceptForm.partially.innerHTML = document.getElementById('Pending' + AcceptID + 'Partially').innerHTML;
        AcceptForm.datetime.innerHTML = document.getElementById('Pending' + AcceptID + 'DateTime').innerHTML;
        AcceptForm.reporter.innerHTML = document.getElementById('Pending' + AcceptID + 'Reporter').innerHTML;
    }

    AcceptForm.form.onsubmit = function (e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend: function () {
                $('#AcceptForm_SUBMIT').button('loading');
            },
            uploadProgress: function (event, position, total, percentCompelete) {

            },
            success: function (data) {
                $('#AcceptForm_SUBMIT').button('reset');
                var server_message = data.trim();
                if(server_message == 'success')
                {
                    AcceptReport(AcceptForm.id.value );
                    $(AcceptForm.modal).modal('hide');
                }
                else if(!isWhitespace(GetSuccessMsg(server_message)))
                {
                    alert(GetSuccessMsg(server_message));
                }
                else if(!isWhitespace(GetWarningMsg(server_message)))
                {
                    alert(GetWarningMsg(server_message));
                }
                else if(!isWhitespace(GetErrorMsg(server_message)))
                {
                    alert(GetErrorMsg(server_message));
                }
                else if(!isWhitespace(GetServerMsg(server_message)))
                {
                    alert(GetServerMsg(server_message));
                }
                else
                {
                    alert('Oh Snap! There is a problem with the server or your connection.');
                }
            }
        });
    };
        DRForm.form.onsubmit = function (e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DRForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                $(DRForm.submit).button('reset');
                var server_message = data.trim();
                if(!isWhitespace(GetSuccessMsg(server_message)))
                {
                    var dummy = GetSuccessMsg(server_message).split('_');
                    AddAcceptedReport(dummy[0], dummy[1], DRForm.dead.value, DRForm.injured.value, DRForm.missing.value, DRForm.totally.value, DRForm.partially.value, DRForm.reportid.value);
                    ResetDRForm();
                    $(DRForm.modal).modal('hide');
                }
                else if(!isWhitespace(GetWarningMsg(server_message)))
                {
                    createmessagein(2, GetWarningMsg(server_message), DRForm.msgbox);
                }
                else if(!isWhitespace(GetErrorMsg(server_message)))
                {
                    createmessagein(3, GetErrorMsg(server_message), DRForm.msgbox);
                }
                else if(!isWhitespace(GetServerMsg(server_message)))
                {
                    createmessagein(4, GetServerMsg(server_message), DRForm.msgbox);
                }
                else
                {
                    createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', DRForm.msgbox);
                }
            }
        });
    };

</script>
<script>
    //Variables
    var UpdateCOAForm = {
        form: document.getElementById('UpdateCOAForm'),
        modal: document.getElementById('UpdateCOAModal'),
        dswd: document.getElementById('UpdateCOAForm_DSWD'),
        lgu: document.getElementById('UpdateCOAForm_LGU'),
        ngo: document.getElementById('UpdateCOAForm_NGO'),
        msgbox: 'UpdateCOA_msgbox',
        submit: document.getElementById('UpdateCOAForm_SUBMIT')
    };
    var DeleteCOAForm = {
        form: document.getElementById('DeleteCOAForm'),
        modal: document.getElementById('DeleteCOAModal'),
        id: document.getElementById('DeleteCOAForm_ID'),
        dswd: document.getElementById('DeleteCOAForm_DSWD'),
        lgu: document.getElementById('DeleteCOAForm_LGU'),
        ngo: document.getElementById('DeleteCOAForm_NGO'),
        total: document.getElementById('DeleteCOAForm_total'),
        datetime: document.getElementById('DeleteCOAForm_datetime'),
        msgbox: 'DeleteCOAForm_msgbox',
        submit: document.getElementById('DeleteCOAForm_SUBMIT')
    };

    function AddNewCostOfAssistance(id, cost_datetime, cost_dswd, cost_lgu, cost_ngo) {
        var total = parseInt(cost_dswd) + parseInt(cost_lgu) + parseInt(cost_ngo);
        document.getElementById('CostOfAssistanceTable').innerHTML += ''+
            '<tr id="COAItem_' + id + '">'+
            '   <td id="COAItem_datetime' + id + '">' + cost_datetime + '</td>'+
            '   <td id="COAItem_dswd' + id + '">₱ ' + numberWithCommas(cost_dswd) + '</td>'+
            '   <td id="COAItem_lgu' + id + '">₱ ' + numberWithCommas(cost_lgu) + '</td>'+
            '   <td id="COAItem_ngo' + id + '">₱ ' + numberWithCommas(cost_ngo) + '</td>'+
            '   <td id="COAItem_total' + id + '">₱ ' + numberWithCommas(total) + '</td>'+
            '   <td><button class="btn btn-danger" onclick="OpenDeleteCOAModal(' + id + '); return false;"> <span class="glyphicon glyphicon-trash"></span> Delete</button></td>'+
            '</tr>';
    }
    function DeleteCostOfAssistance(id) {
        $('#COAItem_' + id).remove();
    }
    
    function OpenDeleteCOAModal(id) {
        DeleteCOAForm.id.value = id;
        DeleteCOAForm.dswd.innerHTML = document.getElementById('COAItem_dswd' + id).innerHTML;
        DeleteCOAForm.lgu.innerHTML = document.getElementById('COAItem_lgu' + id).innerHTML;
        DeleteCOAForm.ngo.innerHTML = document.getElementById('COAItem_ngo' + id).innerHTML;
        DeleteCOAForm.total.innerHTML = document.getElementById('COAItem_total' + id).innerHTML;
        DeleteCOAForm.datetime.innerHTML = document.getElementById('COAItem_datetime' + id).innerHTML;

        $(DeleteCOAForm.modal).modal('show');
    }

    $(UpdateCOAForm.form).on('submit', function (e) {
        var cost_dswd = UpdateCOAForm.dswd.value;
        var cost_lgu = UpdateCOAForm.lgu.value;
        var cost_ngo = UpdateCOAForm.ngo.value;

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(UpdateCOAForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                $(UpdateCOAForm.submit).button('reset');
                DisplayMsg(data, UpdateCOAForm.msgbox, function (SuccessMsg) {

                    var dummy = SuccessMsg.split('!');

                    AddNewCostOfAssistance(dummy[0], dummy[1], cost_dswd, cost_lgu, cost_ngo);
                    UpdateCOAForm.form.reset();
                    $(UpdateCOAForm.modal).modal('hide');
                });
            }
        });
    });
    $(DeleteCOAForm.form).on('submit', function (e) {
        var id = DeleteCOAForm.id.value;

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DeleteCOAForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                $(DeleteCOAForm.submit).button('reset');
                DisplayMsg(data, DeleteCOAForm.msgbox, function (SuccessMsg) {
                    DeleteCostOfAssistance(id);
                    DeleteCOAForm.form.reset();
                    $(DeleteCOAForm.modal).modal('hide');
                });
            }
        });
    });

    <?php
    $sql = '
            SELECT * FROM 
                    disaster_reports
            WHERE   
                    DECLAREID = ' . $_GET['id'] . '
            AND
                    ISVERIFIED = 0
            ORDER BY DATEADDED ASC';
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while ($row = $result->fetch_assoc())
    {
        $DRr_ID = htmlspecialchars($row['ID']);
        $DRr_DATEADDED = htmlspecialchars(converttoformaldatetimestring($row['DATEADDED']));
        $DRr_DEAD = htmlspecialchars($row['CSLTDEAD']);
        $DRr_INJURED = htmlspecialchars($row['CSLTINJURED']);
        $DRr_MISSING = htmlspecialchars($row['CSLTMISSING']);
        $DRr_TOTALLY = htmlspecialchars($row['DMGTOTALLY']);
        $DRr_PARTIALLY = htmlspecialchars($row['DMGPARTIALLY']);
        $DRr_REPORTER = htmlspecialchars($row['UPLOADER']);
        $sql2 = '
                SELECT * FROM 
                        user_accounts
                WHERE   
                        USERNAME = "' . $DRr_REPORTER . '"';
        $result2 = $db->connection->query($sql2);
        if($row2 = $result2->fetch_assoc())
        {
            $DRr_REPORTER = htmlspecialchars($row2['FIRSTNAME']) . ' ' . htmlspecialchars($row2['MIDDLENAME']) . ' ' . htmlspecialchars($row2['LASTNAME']);
        }
    ?>AddPendingReport(<?php echo $DRr_ID; ?>, '<?php echo $DRr_DATEADDED; ?>', <?php echo $DRr_DEAD; ?>, <?php echo $DRr_INJURED; ?>, <?php echo $DRr_MISSING; ?>, <?php echo $DRr_TOTALLY; ?>, <?php echo $DRr_PARTIALLY; ?>, '<?php echo $DRr_REPORTER; ?>');<?php
    }

    $sql = '
            SELECT * FROM 
                    disaster_reports
            WHERE   
                    DECLAREID = ' . $_GET['id'] . '
            AND
                    ISVERIFIED = 1
            ORDER BY DATEADDED ASC';
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while ($row = $result->fetch_assoc())
    {
    $DRr_ID = htmlspecialchars($row['ID']);
    $DRr_DATEADDED = htmlspecialchars(converttoformaldatetimestring($row['DATEADDED']));
    $DRr_DEAD = htmlspecialchars($row['CSLTDEAD']);
    $DRr_INJURED = htmlspecialchars($row['CSLTINJURED']);
    $DRr_MISSING = htmlspecialchars($row['CSLTMISSING']);
    $DRr_TOTALLY = htmlspecialchars($row['DMGTOTALLY']);
    $DRr_PARTIALLY = htmlspecialchars($row['DMGPARTIALLY']);
    $DRr_REPORTER = htmlspecialchars($row['UPLOADER']);
    $sql2 = '
                SELECT * FROM 
                        user_accounts
                WHERE   
                        USERNAME = "' . $DRr_REPORTER . '"';
    $result2 = $db->connection->query($sql2);
    if($row2 = $result2->fetch_assoc())
    {
        $DRr_REPORTER = htmlspecialchars($row2['FIRSTNAME']) . ' ' . htmlspecialchars($row2['MIDDLENAME']) . ' ' . htmlspecialchars($row2['LASTNAME']);
    }
    ?>AddAcceptedReport(<?php echo $DRr_ID; ?>, '<?php echo $DRr_DATEADDED; ?>', <?php echo $DRr_DEAD; ?>, <?php echo $DRr_INJURED; ?>, <?php echo $DRr_MISSING; ?>, <?php echo $DRr_TOTALLY; ?>, <?php echo $DRr_PARTIALLY; ?>, '<?php echo $DRr_REPORTER; ?>');<?php
    }

    $sql = '
            SELECT * FROM 
                    disaster_reports
            WHERE   
                    DECLAREID = ' . $_GET['id'] . '
            AND
                    ISVERIFIED = 2
            ORDER BY DATEADDED ASC';
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while ($row = $result->fetch_assoc())
    {
    $DRr_ID = htmlspecialchars($row['ID']);
    $DRr_DATEADDED = htmlspecialchars(converttoformaldatetimestring($row['DATEADDED']));
    $DRr_DEAD = htmlspecialchars($row['CSLTDEAD']);
    $DRr_INJURED = htmlspecialchars($row['CSLTINJURED']);
    $DRr_MISSING = htmlspecialchars($row['CSLTMISSING']);
    $DRr_TOTALLY = htmlspecialchars($row['DMGTOTALLY']);
    $DRr_PARTIALLY = htmlspecialchars($row['DMGPARTIALLY']);
    $DRr_REPORTER = htmlspecialchars($row['UPLOADER']);
    $sql2 = '
                SELECT * FROM 
                        user_accounts
                WHERE   
                        USERNAME = "' . $DRr_REPORTER . '"';
    $result2 = $db->connection->query($sql2);
    if($row2 = $result2->fetch_assoc())
    {
        $DRr_REPORTER = htmlspecialchars($row2['FIRSTNAME']) . ' ' . htmlspecialchars($row2['MIDDLENAME']) . ' ' . htmlspecialchars($row2['LASTNAME']);
    }
    ?>AddDeclinedReport(<?php echo $DRr_ID; ?>, '<?php echo $DRr_DATEADDED; ?>', <?php echo $DRr_DEAD; ?>, <?php echo $DRr_INJURED; ?>, <?php echo $DRr_MISSING; ?>, <?php echo $DRr_TOTALLY; ?>, <?php echo $DRr_PARTIALLY; ?>, '<?php echo $DRr_REPORTER; ?>');<?php
    }
        //evac_id, evac_name, evac_address1, evac_address2, evac_lat, evac_lng
    $sql = '
            SELECT
                    evacuation_list.ID,
                    evacuation_list.EVACNAME,
                    evacuation_list.EVACADDRESS1,
                    evacuation_list.EVACADDRESS2,
                    evacuation_list.LAT,
                    evacuation_list.LNG
             FROM 
                    evacuation_list,
                    evacuation_report
                    
            WHERE   
                    evacuation_report.DECLAREID = ' . $_GET['id'] . '
            AND
                    evacuation_report.EVACID = evacuation_list.ID
            GROUP BY
                    evacuation_list.ID';
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while ($row = $result->fetch_assoc()) {
        $ECr_ID = htmlspecialchars($row['ID']);
        $ECr_EVACNAME = htmlspecialchars($row['EVACNAME']);
        $ECr_EVACADDRESS1 = htmlspecialchars($row['EVACADDRESS1']);
        $ECr_EVACADDRESS2 = htmlspecialchars($row['EVACADDRESS2']);
        $ECr_LAT = htmlspecialchars($row['LAT']);
        $ECr_LNG = htmlspecialchars($row['LNG']);
    ?>AddEvacuationCenter(1, <?php echo $ECr_ID; ?>, '<?php echo $ECr_EVACNAME; ?>', '<?php echo $ECr_EVACADDRESS1; ?>', '<?php echo $ECr_EVACADDRESS2; ?>', <?php echo $ECr_LAT; ?>, <?php echo $ECr_LNG; ?>);<?php
    }

    $dummyvariable = array();
    //evac_id, evac_name, evac_address1, evac_address2, evac_lat, evac_lng
    $sql = '
            SELECT 
                    evacuation_report.ID,
                    evacuation_report.EVACID,
                    evacuation_report.SRVFAMILIES,
                    evacuation_report.SRVPERSONS,
                    evacuation_report.DATEADDED,
                    evacuation_report.ISVERIFIED,
                    user_accounts.FIRSTNAME,
                    user_accounts.MIDDLENAME,
                    user_accounts.LASTNAME
             FROM 
                    evacuation_report,
                    user_accounts
                    
            WHERE   
                    evacuation_report.DECLAREID = ' . $_GET['id'] . '
            AND
                    user_accounts.USERNAME = evacuation_report.UPLOADER
            GROUP BY
                    evacuation_report.ID';

    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while ($row = $result->fetch_assoc()) {
    $ECr_ID = htmlspecialchars($row['ID']);
    $ECr_EVACID = htmlspecialchars($row['EVACID']);
    $ECr_SRVFAMILIES = htmlspecialchars($row['SRVFAMILIES']);
    $ECr_SRVPERSONS = htmlspecialchars($row['SRVPERSONS']);
    $ECr_DATEADDED = converttoformaldatetimestring($row['DATEADDED']);
    $ECr_ISVERIFIED = htmlspecialchars($row['ISVERIFIED']);
    $ECr_FULLNAME = htmlspecialchars($row['FIRSTNAME']) . ' ' . htmlspecialchars($row['MIDDLENAME']) . ' ' . htmlspecialchars($row['LASTNAME']);
    array_push($dummyvariable, array($ECr_EVACID, $ECr_ID, $ECr_ISVERIFIED));
    ?>AddEvacuationReport(1, <?php echo $ECr_EVACID; ?>, <?php echo $ECr_ID; ?>, '<?php echo $ECr_DATEADDED; ?>', <?php echo $ECr_SRVPERSONS; ?>, <?php echo $ECr_SRVFAMILIES; ?>, '<?php echo $ECr_FULLNAME; ?>');<?php
    }

        foreach ($dummyvariable as $item)
        {
            if($item[2] == 1)
            {
                ?>AcceptEvacuationReport(<?php echo $item[0]; ?>, <?php echo $item[1]; ?>);<?php
            }
            else if($item[2] == 2)
            {
                ?>DeclineEvacuationReport(<?php echo $item[0]; ?>, <?php echo $item[1]; ?>);<?php
        }
    }
    //evac_id, evac_name, evac_address1, evac_address2, evac_lat, evac_lng
    $sql = '
            SELECT * FROM 
                    disaster_cost
                    
            WHERE   
                    DECLAREID = ' . $_GET['id'];
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while ($row = $result->fetch_assoc()) {
    ?>AddNewCostOfAssistance(<?php echo $row['ID']; ?>, '<?php echo converttoformaldatetimestring($row['DATEADDED']); ?>', <?php echo $row['DSWD']; ?>, <?php echo $row['LGU']; ?>, <?php echo $row['NGO']; ?>);<?php
    }
    ?>
</script>


<script>

  var map = L.map('mapid').setView([10.719950067615137, 122.554175308317468], 13);
            mapLink = 
                '<a href="http://openstreetmap.org">OpenStreetMap</a>';
            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                maxZoom: 18,
                id: 'mapbox.light'
                }).addTo(map);

              
        var circle = L.circle([

            <?php

               $sql ='SELECT disaster_declare.LAT, disaster_declare.LNG, disaster_declare.RADIUS, disaster_type.COLOR

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ID = ' . $_GET['id'];

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

             while($row = $result->fetch_array())
             {
                ?>
                '<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>'],{

                    color: '<?php echo $row['COLOR']; ?>',
                    fillColor: '<?php echo $row['COLOR']; ?>',
                    radius: '<?php echo $row['RADIUS']; ?>',
                <?php
             }

            ?>
           
            fillOpacity: 0.5
        }).addTo(map);


      document.getElementById('reportIDPICKER').onchange = function () {
            document.getElementById('disasterReportForm_REPORTID').value = event.target.value  
        }                                     


</script>

</body>
</html>



