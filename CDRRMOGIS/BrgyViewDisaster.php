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

$sql = 'SELECT disaster_declare.RADIUS, disaster_declare.ACCEPTED, disaster_declare.NICKNAME, disaster_declare.STARTED,disaster_declare.ENDED, disaster_declare.ID, disaster_declare.LAT, disaster_declare.LNG, disaster_type.NAME AS DISASTERNAME, user_accounts.FIRSTNAME, user_accounts.MIDDLENAME, user_accounts.LASTNAME FROM `disaster_declare`, `disaster_type`, `user_accounts` WHERE disaster_declare.BRGY = ' . $_SESSION['USER_BRGY'] . ' AND disaster_type.ID = disaster_declare.DISASTER AND user_accounts.USERNAME = disaster_declare.POSTBY AND disaster_declare.ID = ' . $_GET['id'];
$result = $db->connection->query($sql);
$count = mysqli_num_rows($result);
$row = $result->fetch_assoc();

$result_NICKNAME = htmlspecialchars($row['NICKNAME']);
$result_ACCPETED = htmlspecialchars($row['ACCEPTED']);
$result_STARTED = htmlspecialchars($row['STARTED']);
$result_ENDED = htmlspecialchars($row['ENDED']);
$result_ID = htmlspecialchars($row['ID']);
$result_DISASTERNAME = htmlspecialchars($row['DISASTERNAME']);
$result_FIRSTNAME = htmlspecialchars($row['FIRSTNAME']);
$result_MIDDLENAME = htmlspecialchars($row['MIDDLENAME']);
$result_LASTNAME = htmlspecialchars($row['LASTNAME']);
$result_LAT = htmlspecialchars($row['LAT']);
$result_LNG = htmlspecialchars($row['LNG']);
$result_RADIUS = htmlspecialchars($row['RADIUS']);

?>
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
</head>
<body role="document">

<!--Modals-->
<div>
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

    <!--Delete Evacuation Modal-->
    <div class="modal fade" id="DeleteEvacuationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete <b id="DeleteEvacuationModalLabel_NAME1"></b></h4>
                </div>
                <form id="DeleteEvacuationForm" method="post" action="library/form/BrgyDeleteEvacuationForm.php">
                    <input type="text" id="DeleteEvacuationForm_DELID" name="DELID" value="" style="display: none;" />
                    <div class="modal-body">
                        <div id="DE_msgbox" tabindex="0"></div>
                        <p>Are sure, you want to delete <b id="DeleteEvacuationModalLabel_NAME2"></b></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="DeleteEvacuationForm_SUBMIT" data-loading-text="Deleting Evacuation..." class="btn btn-danger">Delete Evacuation</button>
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
                    <h4 class="modal-title">Send a Disaster Report for <b><?php echo $result_NICKNAME; ?></b></h4>
                </div>
                <form id="DisasterReportForm" method="post" action="library/form/BrgyDisasterReportForm.php">
                    <div class="modal-body">
                        <div id="DR_msgbox" tabindex="0"></div>
                        <div class="panel panel-default">
                            <input type="text" value="<?php echo $result_ID; ?>" name="DECLARE" style="display: none;" />
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

    <!--Add Evacuation Modal-->
    <div class="modal fade" id="AddEvacuationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Evacuation Center for <b><?php echo $result_NICKNAME; ?></b></h4>
                </div>
                <form id="AddEvacuationForm" method="post" action="library/form/BrgyAddEvacuationForm.php">
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

    <!--Update Evacuation Reports Modal or Send Update-->
    <div class="modal fade" id="UpdateEvacuationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Send an Evacuation Report for: <b id="UpdateEvacuationModalLabel_Name"></b></h4>
                </div>
                <form id="EvacuationReportForm" method="post" action="library/form/BrgyEvacuationReportForm.php">
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
</div>

<!--Nav Bar-->
<?php include('library/html/navbar.php'); ?>

<!--Site Content-->
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1>
            <?php echo $result_DISASTERNAME; ?>
            <small> in <br />Brgy. <?php echo $result_BRGYNAME . ', ' . $result_DISTRICTNAME . ', ' . $result_CITYNAME; ?> City.</small>
        </h1>
    </div>
    <div id="pagemessagebox" tabindex="0"></div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Disaster Info
                <?php if($result_ACCPETED == 0){
                    ?>
                    <a href="BrgyEditDisaster.php?id=<?php echo $result_ID; ?>" class="btn btn-primary pull-right">Edit Details</a>
                    <div style="clear: both;"></div>
                    <?php
                } ?>
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
                                <div class="component-for-on-going">
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#EndDisasterModal"><span class="glyphicon glyphicon-arrow-down"></span> End Disaster</button>
                                    if the disaster ended.
                                </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="color-gray">Declared/Posted by:</span></td>
                        <td><?php echo $result_FIRSTNAME . ' ' . $result_MIDDLENAME . ' ' . $result_LASTNAME; ?></td>
                    </tr>
					<tr>
						<td><span class="color-gray">Location:</span></td>
						<td>
							<img src="https://maps.googleapis.com/maps/api/staticmap?
								markers=color:red%7C<?php echo $result_LAT; ?>,<?php echo $result_LNG; ?>
								&center=<?php echo $result_LAT; ?>,<?php echo $result_LNG; ?>
								&zoom=14
								&size=300x150
								&maptype=roadmap
                                &key=AIzaSyCalJXL3IZ37jpy9s0K5ge-xgojC8fXWOM" 
							/> 
						</td>
					</tr>
                </table>
            </div>
        </div>
        <br />

        <div class="panel panel-default">
            <div class="panel-heading" role="tab">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#PrevDisasterReportPanel" aria-expanded="true" aria-controls="collapseOne">
                        <small><span class="glyphicon glyphicon-triangle-bottom"></span></small> Previous Disaster Reports
                    </a>
                    <button class="btn btn-primary pull-right component-for-on-going" data-toggle="modal" data-target="#DisasterReportModal">
                        <span class="glyphicon glyphicon-send"></span> Send a Disaster Report
                    </button>
                    <div style="clear: both;"></div>
                </h4>
            </div>
            <div id="PrevDisasterReportPanel" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
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
                        </tr>
                        </thead>
                        <tbody id="PageComponent_DRLIST">
                        <tr>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            List of Evacuation Centers
            <button class="btn btn-primary pull-right component-for-on-going" data-toggle="modal" data-target="#AddEvacuationModal">
                <span class="glyphicon glyphicon-plus"></span> Add New Evacuation Center
            </button>
            <div style="clear: both;"></div>
        </div>
        <div class="panel-body">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <!--List start-->
                <div id="PageComponent_ECLIST">

                </div>
            </div>
        </div>
    </div>
    <br />
    <a href="BrgyModifyPendingReports.php?id=<?php echo $result_ID ?>" class="btn btn-default">Modify Pending Reports</a>
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
<script>
    $('#EndDisasterForm_ENDED').datetimepicker({
        mask:'9999/19/39 29:59'
    });

    <?php
        $EvacList = array();
        $sql = 'SELECT              evacuation_list.ID,
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
<script src="https://maps.googleapis.com/maps/api/js?libraries=places,drawing&callback=initAutocomplete&key=AIzaSyAuX4dJw6AKx5rbomKRek679WKUACmI9eE" async defer></script>


<script>


    //Page Variables
    var PageComponent = {
        ended: document.getElementById('PageComponent_ENDED'),
        drlist: document.getElementById('PageComponent_DRLIST'),
        eclist: document.getElementById('PageComponent_ECLIST')
    };

    //Form Variables
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
    var EDForm = {  //End Disaster Form
        form: document.getElementById('EndDisasterForm'),
        ended: document.getElementById('EndDisasterForm_ENDED'),
        submit: '#EndDisasterForm_SUBMIT',
        modal: '#EndDisasterModal',
        msgbox: 'ED_msgbox'
    };
    /*var DEForm = {  //Delete Evacuation Form
        form: document.getElementById('DeleteEvacuationForm'),
        delid: document.getElementById('DeleteEvacuationForm_DELID'),
        submit: '#DeleteEvacuationForm_SUBMIT',
        modal: '#DeleteEvacuationModal',
        modalLabel_name1: document.getElementById('DeleteEvacuationModalLabel_NAME1'),
        modalLabel_name2: document.getElementById('DeleteEvacuationModalLabel_NAME2'),
        msgbox: 'DE_msgbox'
    };*/
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


    //Page Manipulation Scripts
    function AddEvacuationCenter(evac_id, evac_name, evac_address1, evac_address2, evac_lat, evac_lng, capacity) {

        var evacgooglemap = "https://maps.googleapis.com/maps/api/staticmap?markers=color:red%7C" + evac_lat + "," + evac_lng + "&center=" + evac_lat + "," + evac_lng + "&zoom=14&size=300x150&maptype=roadmap&key=AIzaSyCalJXL3IZ37jpy9s0K5ge-xgojC8fXWOM";
        PageComponent.eclist.innerHTML += '' +
            '<div class="panel panel-default" id="EclistItem_' + evac_id + '">' +
            '    <div class="panel-heading" role="tab">' +
            '        <h4 class="panel-title">' +
            '            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#EclistItemPanel_' + evac_id + '" aria-expanded="true" aria-controls="collapseOne">' +
            '                <small><span class="glyphicon glyphicon-triangle-bottom"></span></small> ' + evac_name +
            '            </a>' +
            '            <div class="pull-right component-for-on-going">' +
            '                <button class="btn btn-primary" onclick="ERForm.evacid.value=' + evac_id + '; ERForm.modalLabel_name.innerHTML=\'' + evac_name + '\';" data-toggle="modal" data-target="#UpdateEvacuationModal">' +
            '                    <span class="glyphicon glyphicon-send"></span> Send Status Report' +
            '                </button>' +
            '                <span id="ErDelBtn_' + evac_id + '">' +
            '                </span>' +
            '            </div>' +
            '            <div style="clear: both;"></div>' +
            '        </h4>' +
            '    </div>' +
            '    <div id="EclistItemPanel_' + evac_id + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">' +
            '        <div class="panel-body">' +
            '            <div class="row">' +
            '                <div class="col-lg-6 col-sm-6 col-md-6">' +
            '                    <img id="EclistItemMap_' + evac_id + '"/>' +
            '                </div>' +
            '                <div class="col-lg-6 col-sm-6 col-md-6">' +
            '                    <h5>Google Map Address: <b>' + evac_address2 + '</b></h5>' +
            '                    <h5>Complete Adress: <b>' + evac_address1 + '</b></h5>' +
            '                    <h5>Capacity: <b>' + capacity + '</b></h5>' +
            '                </div>' +
            '            </div>' +
            '            <br />' +
            '            <h4>Previous Reports</h4>' +
            '            <table class="table">' +
            '               <thead>' +
            '                   <tr>' +
            '                       <th>Date Time</th>' +
            '                       <th>Persons Served</th>' +
            '                       <th>Families Served</th>' +
            '                   </tr>' +
            '               </thead>' +
            '               <tbody id="ERLIST_' + evac_id + '">' +
            '               </tbody>' +
            '            </table>' +
            '        </div>' +
            '    </div>' +
            '</div>';
        document.getElementById('EclistItemMap_' + evac_id).src = evacgooglemap;
    }
    function AddEvacuationReport(evar_id, evar_datetime, evar_persons, evar_families){
        var ERLIST = document.getElementById('ERLIST_' + evar_id);
        ERLIST.innerHTML = '' +
            '<tr>'+
            '   <td>' + evar_datetime + '</td>'+
            '   <td>' + evar_persons + '</td>'+
            '   <td>' + evar_families + '</td>'+
            '</td>' + ERLIST.innerHTML;
    }
    function AddDisasterReport(adr_datetime, adr_dead, adr_injured, adr_missing, adr_totally, adr_partially) {
        PageComponent.drlist.innerHTML = '' +
            '<tr>'+
            '   <td>' + adr_datetime + '</td>'+
            '   <td>' + adr_dead + '</td>'+
            '   <td>' + adr_injured + '</td>'+
            '   <td>' + adr_missing + '</td>'+
            '   <td>' + adr_totally + '</td>'+
            '   <td>' + adr_partially + '</td>'+
            '</td>' + PageComponent.drlist.innerHTML;
    }
    /*function RemoveEvacuation(evare_id) {
        $("#EclistItem_" + evare_id).remove();
    }*/
    function EndTheDisaster(ed_datetime) {
        $('.component-for-on-going').remove();
        PageComponent.ended.innerHTML = ed_datetime;
    }
    function UnhideComponentForOnGoing(){
        $('.component-for-on-going').css('display', 'block');
    }


    //Reset Form Scripts
    function ResetDRForm() {
        DRForm.dead.value = 0;
        DRForm.injured.value = 0;
        DRForm.missing.value = 0;
        DRForm.totally.value = 0;
        DRForm.partially.value = 0;
    }
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

        if(document.getElementById('AddEvacuationForm_EVACID').value == ''){
            createmessagein(3, 'Please Select an Evacuation Center.', AEForm.msgbox);
            return false;
        }

        //Validation ends here
        return true;
    }
    function ERForm_isValid(){
        //Validation code here

        //Validation ends here
        return true;
    }
    function DRForm_isValid(){
        //Validation code here

        //Validation ends here
        return true;
    }
    /*function DEForm_isValid(){
        //Validation code here

        //Validation ends here
        return true;
    }*/
    function EDForm_isValid(){
        //Validation code here

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
                        alert('Report sent.');
                        createmessagein(1, GetSuccessMsg(server_message), ERForm.msgbox);
                        AddEvacuationReport(ERForm.evacid.value, GetSuccessMsg(server_message), ERForm.persons.value, ERForm.families.value);
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
    DRForm.form.onsubmit = function (e) {
        e.preventDefault();
        if(DRForm_isValid())
        {
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
                        alert('Report sent.');
                        createmessagein(1, GetSuccessMsg(server_message), DRForm.msgbox);
                        AddDisasterReport(GetSuccessMsg(server_message), DRForm.dead.value, DRForm.injured.value, DRForm.missing.value, DRForm.totally.value, DRForm.partially.value);
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
        }
    };
    /*DEForm.form.onsubmit = function (e) {
        e.preventDefault();
        if(DEForm_isValid())
        {
            $(this).ajaxSubmit({
                beforeSend:function()
                {
                    $(DEForm.submit).button('loading');
                },
                uploadProgress:function(event,position,total,percentCompelete)
                {

                },
                success:function(data)
                {
                    $(DEForm.submit).button('reset');
                    var server_message = data.trim();
                    if(!isWhitespace(GetSuccessMsg(server_message)))
                    {
                        createmessagein(1, GetSuccessMsg(server_message), DEForm.msgbox);
                        RemoveEvacuation(DEForm.delid.value);
                    }
                    else if(!isWhitespace(GetWarningMsg(server_message)))
                    {
                        createmessagein(2, GetWarningMsg(server_message), DEForm.msgbox);
                    }
                    else if(!isWhitespace(GetErrorMsg(server_message)))
                    {
                        createmessagein(3, GetErrorMsg(server_message), DEForm.msgbox);
                    }
                    else if(!isWhitespace(GetServerMsg(server_message)))
                    {
                        createmessagein(4, GetServerMsg(server_message), DEForm.msgbox);
                    }
                    else
                    {
                        createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', DEForm.msgbox);
                    }
                }
            });
        }
    };*/
    EDForm.form.onsubmit = function (e) {
        e.preventDefault();
        if(EDForm_isValid())
        {
            $(this).ajaxSubmit({
                beforeSend:function()
                {
                    $(EDForm.submit).button('loading');
                },
                uploadProgress:function(event,position,total,percentCompelete)
                {

                },
                success:function(data)
                {
                    $(EDForm.submit).button('reset');
                    var server_message = data.trim();
                    if(!isWhitespace(GetSuccessMsg(server_message)))
                    {
                        alert('Disaster ended.');
                        createmessagein(1, GetSuccessMsg(server_message), EDForm.msgbox);
                        EndTheDisaster(GetSuccessMsg(server_message));
                        $(EDForm.modal).modal('hide');
                    }
                    else if(!isWhitespace(GetWarningMsg(server_message)))
                    {
                        createmessagein(2, GetWarningMsg(server_message), EDForm.msgbox);
                    }
                    else if(!isWhitespace(GetErrorMsg(server_message)))
                    {
                        createmessagein(3, GetErrorMsg(server_message), EDForm.msgbox);
                    }
                    else if(!isWhitespace(GetServerMsg(server_message)))
                    {
                        createmessagein(4, GetServerMsg(server_message), EDForm.msgbox);
                    }
                    else
                    {
                        createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', EDForm.msgbox);
                    }
                }
            });
        }
    };


    <?php
    $sql = '
            SELECT * FROM 
                    disaster_reports
            WHERE   
                    DECLAREID = ' . $result_ID . '
            ORDER BY DATEADDED ASC';
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while ($row = $result->fetch_assoc())
    {

    $DRr_DATEADDED = htmlspecialchars(converttoformaldatetimestring($row['DATEADDED']));
    $DRr_DEAD = htmlspecialchars($row['CSLTDEAD']);
    $DRr_INJURED = htmlspecialchars($row['CSLTINJURED']);
    $DRr_MISSING = htmlspecialchars($row['CSLTMISSING']);
    $DRr_TOTALLY = htmlspecialchars($row['DMGTOTALLY']);
    $DRr_PARTIALLY = htmlspecialchars($row['DMGPARTIALLY']);

    //function AddDisasterReport(adr_datetime, adr_dead, adr_injured, adr_missing, adr_totally, adr_partially)
    ?>
    AddDisasterReport('<?php echo $DRr_DATEADDED; ?>', <?php echo $DRr_DEAD; ?>, <?php echo $DRr_INJURED; ?>, <?php echo $DRr_MISSING; ?>, <?php echo $DRr_TOTALLY; ?>, <?php echo $DRr_PARTIALLY; ?>);
    <?php
    }
    ?>


    //add  evacuation centers
    <?php
    $sql = 'SELECT 
                    evacuation_list.ID, 
                    evacuation_list.EVACNAME, 
                    evacuation_list.EVACADDRESS1, 
                    evacuation_list.EVACADDRESS2, 
                    evacuation_list.LAT, 
                    evacuation_list.LNG,
                    evacuation_list.CAPACITY
            FROM    
                    evacuation_list, 
                    evacuation_report       
            WHERE   
                    evacuation_report.EVACID = evacuation_list.ID
            AND 
                    evacuation_report.DECLAREID = ' . $result_ID . '
            GROUP BY
                    evacuation_list.ID';
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while ($row = $result->fetch_assoc())
    {
        $ECr_ID = htmlspecialchars($row['ID']);
        $ECr_EVACNAME = htmlspecialchars($row['EVACNAME']);
        $ECr_EVACADDRESS1 = htmlspecialchars($row['EVACADDRESS1']);
        $ECr_EVACADDRESS2 = htmlspecialchars($row['EVACADDRESS2']);
        $ECr_LAT = htmlspecialchars($row['LAT']);
        $ECr_LNG = htmlspecialchars($row['LNG']);
    ?>
        AddEvacuationCenter(<?php echo $ECr_ID; ?>, '<?php echo $ECr_EVACNAME; ?>', '<?php echo $ECr_EVACADDRESS1; ?>', '<?php echo $ECr_EVACADDRESS2; ?>', <?php echo $ECr_LAT; ?>, <?php echo $ECr_LNG; ?>, <?php echo $row['CAPACITY']; ?>);
    <?php
    }
    ?>

    //add  evacuation reports
    <?php
    $sql = 'SELECT 
                    EVACID,
                    SRVFAMILIES,
                    SRVPERSONS,
                    DATEADDED
            FROM     
                    evacuation_report       
            WHERE   
                    DECLAREID = ' . $result_ID;
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while ($row = $result->fetch_assoc())
    {
        $ERr_EVACID = htmlspecialchars($row['EVACID']);
        $ERr_SRVFAMILIES = htmlspecialchars($row['SRVFAMILIES']);
        $ERr_SRVPERSONS = htmlspecialchars($row['SRVPERSONS']);
        $ERr_DATEADDED = htmlspecialchars(converttoformaldatetimestring($row['DATEADDED']));
    ?>
        AddEvacuationReport(<?php echo $ERr_EVACID; ?>, '<?php echo $ERr_DATEADDED; ?>', <?php echo $ERr_SRVPERSONS; ?>, <?php echo $ERr_SRVFAMILIES; ?>);
    <?php
    }


        if($result_ENDED != '')
        {
            echo 'EndTheDisaster("' . converttoformaldatetimestring($result_ENDED) . '");';
        }
        else
        {
            echo 'UnhideComponentForOnGoing();';
        }
    ?>

</script>
</body>
</html>