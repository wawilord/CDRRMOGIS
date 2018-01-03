<?php
    session_start();
    include('library/form/connection.php');
    include('library/function/functions.php');
    $db = new db();

    $DisasterTypes = array();
    $sql = 'SELECT          *
            FROM            disaster_type
            ORDER BY        NAME ASC';
    $result = $db->connection->query($sql);
    while ($row = $result->fetch_assoc()){
        $DisasterTypes[] = $row;
    }

    $sql = 'SELECT          *
            FROM            disaster_declare
            WHERE           ID = ' . $_GET['id'];
    $result = $db->connection->query($sql);
    $DeclareInfo = $result->fetch_assoc();
?>
<!DOCTYPE html>
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
</head>
<body role="document">
    <?php
        include('library/html/navbar.php');
    ?>
    <div class="container">
        <div class="page-header">
            <h1>
                Edit Disaster Declaration Details<br />
                <small>Bla Bla Bla</small>
            </h1>
        </div>
        <!--Content Start-->
        <div class="row">
            <div id="MsgBox" tabindex="0"></div>
            <form id="BrgyEditDisasterForm" method="post" action="library/form/EditDisasterDetailsForm2.php">
                <div class="col-lg-6">
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon1">What Disaster?</span>
                            <select class="form-control" name="DISASTER" required>
                                <?php
                                    foreach ($DisasterTypes as $DisasterType){
                                ?>
                                        <option value="<?php echo $DisasterType['ID']; ?>" <?php if($DisasterType['ID'] == $DeclareInfo['DISASTER']) echo 'selected'; ?>><?php echo $DisasterType["NAME"]; ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>

                        <br />

                        <div class="input-group input-group">
                            <span class="input-group-addon" id="sizing-addon1">Alias</span>
                            <input type="text" id="BrgyDeclareDisaster_NICKNAME" value="<?php echo $DeclareInfo['NICKNAME']; ?>" maxlength="50" name="NICKNAME" class="form-control" aria-describedby="sizing-addon1" required>
                            <span class="input-group-btn">
                                  <button class="btn btn-default" type="button" data-placement="bottom" data-toggle="popover" data-content="_____________________ This is just to differentiate disasters declared in your barangay. You can make any alias for your declared disaster."><span class="glyphicon glyphicon-question-sign"></span> </button>
                            </span>
                        </div>

                        <br />

                        <div class="input-group input-group">
                            <span class="input-group-addon" id="sizing-addon1">When did it start?</span>
                            <input type="text" class="form-control" value="<?php echo str_replace('-', '/', $DeclareInfo['STARTED']); ?>" id="BrgyDeclareDisaster_STARTED" name="STARTED" required/>
                        </div>
                        <br />

                        <div class="input-group input-group">
                            <span class="input-group-addon" id="sizing-addon1">When did it End?</span>
                            <input type="text" class="form-control" value="<?php echo str_replace('-', '/', $DeclareInfo['ENDED']); ?>" id="BrgyDeclareDisaster_ENDED" name="ENDED" required/>
                        </div>

                        <br />

                        <div class="input-group input-group">
                            <span class="input-group-addon" id="sizing-addon1">Note/Comment</span>
                            <textarea rows="5" class="form-control" id="BrgyDeclareDisaster_COMMENT" name="COMMENT" maxlength="300" placeholder="Place your note or comment here regarding to the disaster."><?php echo $DeclareInfo['COMMENT']; ?></textarea>
                        </div>

                        <div class="input-group input-group">
                            <input type="text" id="AddEvacuationForm_ADDRESS2" name="ADDRESS2" placeholder="Point the location of evacuation in google map." class="form-control" style="display: none;" disabled>
                            <input type="hidden" id="AddEvacuationForm_LAT" name="LAT" />
                            <input type="hidden" id="AddEvacuationForm_LNG" name="LNG" />
                            <input type="hidden" id="AddEvacuationForm_RAD" name="RAD" />
                            <input type="hidden" name="DECLAREID" value="<?php echo $DeclareInfo['ID']; ?>" required />
                        </div>

                        <br />
                </div>
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-addon" id="basicasdsaasdon1"><span class="glyphicon glyphicon-pushpin"></span></span>
                        <div class="form-control" style="width: 100%; height:300px;">
                            <div id="map"></div>
                        </div>
                        <!-- <input id="pac-input" class="controls" onkeydown="if(event.keyCode == 13) {event.preventDefault(); return false; }" type="text" placeholder="Search Box" /> -->
                    </div>
                    <em><small class="pull-right">Location must be within the red lines.</small></em>
                    <br />
                    <div>
                        <label class="input-group input-group">
                            <span class="input-group-addon" id="sizing-addon1">Manually enter radius (meter)</span>
                            <input type="number" value="<?php echo $DeclareInfo['RADIUS']; ?>" id="RADIUSM" class="form-control" />
                            <span class="input-group-btn">
                                  <button class="btn btn-default" onclick="circle.setRadius(parseFloat(document.getElementById('RADIUSM').value));" type="button">
                                      <span class="glyphicon glyphicon-play"></span>
                                  </button>
                            </span>
                        </label>
                    </div>
                    <br />
                    <br />
                    <div class="pull-right">
                        <button class="btn btn-danger" onclick="SubmitDeleteForm(); return false;">False Alarm (Delete)</button>
                        <button class="btn btn-default" onclick="SubmitUnEndForm(); return false;">Un-end Disaster</button>
                        <button class="btn btn-default" onclick="location.reload();">Reset</button>
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </form>
        </div>
        <form id="DeleteForm" method="post" action="library/form/DeleteDisaster.php">
            <input type="hidden" name="DECLAREID" value="<?php echo $DeclareInfo['ID']; ?>">
        </form>
        <form id="UnEndForm" method="post" action="library/form/UnEndDisaster.php">
            <input type="hidden" name="DECLAREID" value="<?php echo $DeclareInfo['ID']; ?>">
        </form>
        <!--Content End-->
        <?php
            include('library/html/footer.php');
        ?>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/1.11.3_jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/app/mylibrary.js"></script>
    <script src="js/app/messagealert.js"></script>
    <script src="js/jquery.datetimepicker.full.min.js"></script>
    <script src="js/jquery.form.min.js"></script>
    <script>
        $(document).ready(function () {
            $('[data-toggle="popover"]').popover();
            $('#BrgyDeclareDisaster_STARTED').datetimepicker({
                mask:'9999/19/39 29:59'
            });
            $('#BrgyDeclareDisaster_ENDED').datetimepicker({
                mask:'9999/19/39 29:59'
            });
        });

        $('#BrgyEditDisasterForm').on('submit', function (e) {
            e.preventDefault();

            document.getElementById('AddEvacuationForm_LAT').value = circle.getCenter().lat();
            document.getElementById('AddEvacuationForm_LNG').value = circle.getCenter().lng();
            document.getElementById('AddEvacuationForm_RAD').value = circle.getRadius();

            $(this).ajaxSubmit({
                success: function (data) {
                    DisplayMsg(data, 'MsgBox', function (SuccessMsg) {

                    });
                }
            });
        });

        function SubmitDeleteForm() {
            if(confirm('Are you sure you want to delete this decalartion?')){
                $('#DeleteForm').ajaxSubmit({
                    success: function (data) {
                        alert('Disaster was deleted. you will be redirected to home page.');
                        window.location = 'index.php';
                    }
                });
            }
        }

        function SubmitUnEndForm() {
            if(confirm('Is the disaster still going on?')){
                $('#UnEndForm').ajaxSubmit({
                    success: function (data) {
                        alert('Disaster status is now going on. you will be redirected to home page.');
                        window.location = 'index.php';
                    }
                });
            }
        }

        <?php
        $DeclareInfo['BrgyCoordinates'] = array();
        $sql = 'SELECT              *
                    FROM                barangay_coordinates
                    WHERE               BARANGAY = ' . $DeclareInfo['BRGY'];
        $result = $db->connection->query($sql);
        while ($row = $result->fetch_assoc()){
            $coordinate = array(
                "lat"=>(double)$row["LAT"],
                "lng"=>(double)$row["LNG"]
            );
            $DeclareInfo['BrgyCoordinates'][] = $coordinate;
        }
        ?>
        var map = null;
        var polygon = null;
        var circle = null;
        var bounds = null;
        var last_dragged_location = null;
        var DeclareInfo = JSON.parse('<?php echo json_encode($DeclareInfo); ?>');
        function initAutocomplete() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 10.715503824811973, lng: 122.56265312433243},
                zoom: 14,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            polygon = new google.maps.Polygon({
                paths: DeclareInfo.BrgyCoordinates,
                strokeColor: 'red',
                strokeOpacity: 1,
                strokeWeight: 2,
                fillColor: 'black',
                fillOpacity: .2,
                map: map,
                clickable: false
            });

            circle = new google.maps.Circle({
                strokeColor: 'green',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: 'green',
                fillOpacity: 0.35,
                map: map,
                center: {lat: parseFloat(DeclareInfo.LAT), lng: parseFloat(DeclareInfo.LNG)},
                radius: parseFloat(DeclareInfo.RADIUS),
                editable: true
            });
            last_dragged_location = circle.getCenter();
            bounds = new google.maps.LatLngBounds();
            var paths = polygon.getPaths();
            paths.forEach(function(path) {
                var ar = path.getArray();
                for(var i = 0, l = ar.length;i < l; i++) {
                    bounds.extend(ar[i]);
                }
            });
            map.fitBounds(bounds);
            circle.addListener('center_changed', function () {
                if(!google.maps.geometry.poly.containsLocation(circle.getCenter(), polygon)){
                    alert('Location must be within the red lines.');
                    circle.setCenter(last_dragged_location);
                }
                else{
                    last_dragged_location = circle.getCenter();
                }
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?libraries=places,drawing&callback=initAutocomplete&key=AIzaSyAuX4dJw6AKx5rbomKRek679WKUACmI9eE" async defer></script>
</body>
</html>
