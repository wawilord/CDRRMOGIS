<!DOCTYPE html>
<?php
session_start();
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();


//session variables
$session_USER_FIRSTNAME = htmlspecialchars($_SESSION['USER_FIRSTNAME']);
$session_USER_MIDDLENAME = htmlspecialchars($_SESSION['USER_MIDDLENAME']);
$session_USER_LASTNAME = htmlspecialchars($_SESSION['USER_LASTNAME']);

$sql = 'SELECT disaster_declare.DISASTER AS DISASTERID, disaster_declare.RADIUS, disaster_declare.ACCEPTED, disaster_declare.NICKNAME, disaster_declare.COMMENT, disaster_declare.STARTED, disaster_declare.BRGY, disaster_declare.ENDED, disaster_declare.ID, disaster_type.NAME AS DISASTERNAME, user_accounts.FIRSTNAME, user_accounts.MIDDLENAME, user_accounts.LASTNAME, disaster_declare.LAT, disaster_declare.LNG FROM `disaster_declare`, `disaster_type`, `user_accounts` WHERE disaster_type.ID = disaster_declare.DISASTER AND user_accounts.USERNAME = disaster_declare.POSTBY AND disaster_declare.ID = ' . $_GET['id'];
$result = $db->connection->query($sql);
$count = mysqli_num_rows($result);
$row = $result->fetch_assoc();

$result_NICKNAME = htmlspecialchars($row['NICKNAME']);
$result_DISASTERID = htmlspecialchars($row['DISASTERID']);
$result_ACCEPTED = htmlspecialchars($row['ACCEPTED']);
$result_STARTED = htmlspecialchars($row['STARTED']);
$result_ENDED = htmlspecialchars($row['ENDED']);
$result_ID = htmlspecialchars($row['ID']);
$result_BRGY = htmlspecialchars($row['BRGY']);
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
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"
    integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="
   crossorigin=""/>
    <link href="css/jquery.datetimepicker.css" rel="stylesheet">
</head>
<body role="document">

<div class="modal fade" id="ConfirmDisasterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Complete Report</h4>
            </div>
            <form id="ConfirmDisasterForm" method="post" action="library/form/ConfirmDisasterForm.php">
                <div class="modal-body">
                    <div id="ConfirmDisasterForm_msgbox" tabindex="0"></div>
                    <input type="text" name="id" style="display: none;" value="<?php echo $result_ID; ?>" />
                    <p>
                        By confirming this disaster declare means every report and update was already encoded in the system, and you cannot encode report anymore.
                    </p>
                    <?php
                    $sql = "SELECT * FROM disaster_factors WHERE TYPE_ID=" . $result_DISASTERID;
                    $result = $db->connection->query($sql);
                    $count = mysqli_num_rows($result);
                    if($count > 0){
                        ?>
                        <br />
                        <p>Please enter the following info about the <?php echo $result_DISASTERNAME; ?>.</p>
                        <?php
                        while ($row = $result->fetch_assoc()){
                            ?>
                            <div class="input-group input-group">
                                <span class="input-group-addon" id="sizing-addon1"><?php echo $row['FACTOR_NAME']; ?>: </span>
                                <input type="number" name="FACTOR_<?php echo $row['ID']; ?>" value="0.00" step="0.1" min="0.00" class="form-control" required/>
                                <span class="input-group-addon" id="basic-addon2"><?php echo $row['UNIT']; ?></span>
                            </div>
                            <br />
                            <?php
                        }
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
                    <button type="submit" id="ConfirmDisasterForm_SUBMIT" class="btn btn-success" data-loading-text="Confirming..."><span class="glyphicon glyphicon-check"></span> Complete</button>
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
            <form id="UpdateCOAForm" method="post" action="library/form/UpdateCOAForm.php">
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


<!--Disaster Report Modal-->
<div class="modal fade" id="DisasterReportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add a Verified Disaster Report for <b><?php echo $result_NICKNAME; ?></b></h4>
            </div>
            <form id="DisasterReportForm" method="post" action="library/form/disasterReportForm.php">
                <div class="modal-body">
                    <div id="DR_msgbox" tabindex="0"></div>
                    <div class="panel panel-default">
                        <input type="text" value="<?php echo $result_ID; ?>" name="DECLARE" style="display: none;" />
                        <input type="text" value="<?php echo $result_BRGY; ?>" name="BARANGAY" style="display: none;" />
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="DisasterReportForm_SUBMIT" data-loading-text="Sending Report" class="btn btn-primary">Send</button>
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

<!--Delete Cost of Assistance-->
<div class="modal fade" id="DeleteCOAModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Delete Cost of Assistance</h4>
            </div>
            <form id="DeleteCOAForm" method="post" action="library/form/DeleteCOAForm.php">
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
                 <button type="submit" id="DeleteCOAForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."><span class="glyphicon glyphicon-trash"></span> Delete</button>
                 <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('library/html/navbar.php'); ?>
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1>
            Disaster Profile
         <span class = "pull-right">
            <div class="btn-group"><button id="Barangay_BTN_' + id + '" value="' + id + '" class="btn btn-basic" data-toggle="modal" data-target="#UpdateBarangayModal" onclick="location.href='managedisasterprofilenew.php'"><span class="glyphicon glyphicon-menu-left"></span> Return to Manage Disaster Profile</button><input type="hidden" class="btn" /></div>
        </span>
        </h1>
    </div>
    <div id="pagemessagebox" tabindex="0"></div>
    <div style="clear: both;"></div>
    <br />
    <div class="panel panel-primary">
        <div class="panel-heading">
            Disaster Info
            <a href="#" class="btn btn-default pull-right">Edit Details</a>
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
                    <td><?php echo converttoformaldatetimestring($result_ENDED);  ?></td>
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
            <div style="clear: both;"></div>
        </div>
    </div>
    <br />


    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Location</h3>
        </div>
        <div class="panel-body">
            <div class = "col-lg-8">
                <div id="map" style="height: 50vh; width: 100%;" tabindex="0"> </div>
            </div>
        
            <div class = "col-lg-4">
                <h4> Affected Barangay: able to identify Barangay within disaster radius </h4>
                <h4> Radius: asdada </h4>
                <h4> text </h4>
                <h4> All details about mapping here </h4>
                <br />
                <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#">
                <span class="glyphicon glyphicon-map-marker"></span> Update Location
                </button>

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
                    <li role="presentation" class = "active">
                        <a href="#AcceptedDR" role="tab" data-toggle="tab">Accepted</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="AcceptedDR">
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
                        <br />
                        <button class="btn btn-default pull-right" data-toggle="modal" data-target="#DisasterReportModal">
                        <span class="glyphicon glyphicon-plus"></span> Add a Verified Disaster Report
                        </button>
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
                    <li role="presentation" class = "active">
                        <a href="#AcceptedER" role="tab" data-toggle="tab">Accepted</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel active" class="tab-pane" id="AcceptedER">
                        <br />
                        <!--Accepted reports here-->
                        <div class="panel-group" id="AcceptedEvacAccordion" role="tablist" aria-multiselectable="true">
                            <!--List start-->
                            <div id="PageComponent_AcceptedEvacList">

                            </div>
                        </div>

                    </div>
                        <br />
                        <button class="btn btn-default pull-right" data-toggle="modal" data-target="#AddEvacuationModal">
                            <span class="glyphicon glyphicon-plus"></span> Add a new Evacuation Center
                        </button>
                        <div style="clear: both;"></div>
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

    <div class="pull-right">
        <button class="btn btn-primary" data-toggle="modal" data-target="#ConfirmDisasterModal"><span class="glyphicon glyphicon-check"></span> Complete Report</button>
    </div>
    <div style="clear: both;"></div>

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
</br>
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

var DRForm = {  //Disaster Report Form
        form: document.getElementById('DisasterReportForm'),
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
                        AddAcceptedReport(dummy[0], dummy[1], DRForm.dead.value, DRForm.injured.value, DRForm.missing.value, DRForm.totally.value, DRForm.partially.value, '<?php echo $session_USER_FIRSTNAME . ' ' .$session_USER_MIDDLENAME . ' ' . $session_USER_LASTNAME ?>');
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

    document.getElementById('ConfirmDisasterForm').onsubmit = function (e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $('#ConfirmDisasterForm_SUBMIT').button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                $('#ConfirmDisasterForm_SUBMIT').button('reset');
                DisplayMsg(data, 'ConfirmDisasterForm_msgbox', function (SuccessMsg) {
                    alert('Disaster Declare Confirmed.');
                    window.location = 'managedisasterprofilenew.php';
                });
            }
        });
    };

 var UpdateCOAForm = {
        form: document.getElementById('UpdateCOAForm'),
        modal: document.getElementById('UpdateCOAModal'),
        dswd: document.getElementById('UpdateCOAForm_DSWD'),
        lgu: document.getElementById('UpdateCOAForm_LGU'),
        ngo: document.getElementById('UpdateCOAForm_NGO'),
        msgbox: 'UpdateCOA_msgbox',
        submit: document.getElementById('UpdateCOAForm_SUBMIT')
    };

function OpenDeleteCOAModal(id) {
        DeleteCOAForm.id.value = id;
        DeleteCOAForm.dswd.innerHTML = document.getElementById('COAItem_dswd' + id).innerHTML;
        DeleteCOAForm.lgu.innerHTML = document.getElementById('COAItem_lgu' + id).innerHTML;
        DeleteCOAForm.ngo.innerHTML = document.getElementById('COAItem_ngo' + id).innerHTML;
        DeleteCOAForm.total.innerHTML = document.getElementById('COAItem_total' + id).innerHTML;
        DeleteCOAForm.datetime.innerHTML = document.getElementById('COAItem_datetime' + id).innerHTML;

        $(DeleteCOAForm.modal).modal('show');
    }

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

      <?php

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

    <?php
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
    ?>

 
    var mymap = L.map('map').setView([10.70426, 482.56388], 14);

    
    var circle = L.circle([10.70168, 482.56755], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: 300
    }).addTo(mymap);

  L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
   attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
}).addTo(mymap);


</script>

</body>
</html>



