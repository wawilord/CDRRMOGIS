<!DOCTYPE html>
    <?php
    session_start();
    include('library/form/connection.php');
    $db = new db();

    //session variables
    $session_USER_FIRSTNAME = htmlspecialchars($_SESSION['USER_FIRSTNAME']);
    $session_USER_MIDDLENAME = htmlspecialchars($_SESSION['USER_MIDDLENAME']);
    $session_USER_LASTNAME = htmlspecialchars($_SESSION['USER_LASTNAME']);
    //query for the address
    $sql = '
            SELECT  barangay.ID,
                    barangay.NAME AS NAME,
                    district.NAME AS DISTRICT,
                    city.NAME AS CITY
            FROM 
                    barangay, 
                    district, 
                    city
            WHERE   
                    barangay.ID = ' . $_GET['brgy'] . '
            AND 
                    barangay.DISTRICT = district.ID
            AND 
                    district.CITY = city.ID
            ';
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    $BarangayInfo = $result->fetch_assoc();

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
</head>
<body role="document">
<?php include('library/html/navbar.php'); ?>
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1>
            Declare a Disaster
            <small> happened in <br />Brgy. <?php echo $BarangayInfo["NAME"] . ', ' . $BarangayInfo["DISTRICT"] . ', ' . $BarangayInfo["CITY"]; ?> City.</small>
        </h1>
    </div>
    <div id="pagemessagebox" tabindex="0"></div>

	<div class="row">
		<p>Note: This will appear on the system that this disaster was declared or reported by You (<b><?php echo $session_USER_FIRSTNAME . ' ' . $session_USER_MIDDLENAME . ' ' . $session_USER_LASTNAME; ?></b>).</p>
		<div class="col-lg-6">
			<form id="BrgyDeclareDisasterForm" method="post"  action="library/form/CswdDeclareDisasterForm.php">
				<div class="input-group">
					<span class="input-group-addon" id="sizing-addon1">What Disaster?</span>
					<select class="form-control" id="BrgyDeclareDisaster_DISASTER" name="DISASTER" required>
						<?php
						$sql = "SELECT * FROM disaster_type WHERE ENABLED=1 ORDER BY NAME ASC";
						$result = $db->connection->query($sql);
						while($row = $result->fetch_assoc()) {
						  ?>
						  <option value="<?php echo $row["ID"]; ?>" id="disaster_type_<?php echo $row["ID"]; ?>"><?php echo $row["NAME"]; ?></option>
						  <?php
						}
						?>
					</select>
				</div>
                <input name="BRGY" value="<?php echo $BarangayInfo['ID']; ?>" style="display: none;" />

			<br />

			<div class="input-group input-group">
			  <span class="input-group-addon" id="sizing-addon1">Nickname</span>
			  <input type="text" id="BrgyDeclareDisaster_NICKNAME" maxlength="50" name="NICKNAME" class="form-control" aria-describedby="sizing-addon1" required>
				<span class="input-group-btn">
					  <button class="btn btn-default" type="button" data-placement="bottom" data-toggle="popover" data-content="_____________________ This is just to differentiate disasters declared in your barangay. You can make any nickname."><span class="glyphicon glyphicon-question-sign"></span> </button>
				</span>
			</div>

			<br />
			<?php

			date_default_timezone_set('Asia/Hong_Kong');
			$time = time();

			?>

			<div class="input-group input-group">
			  <span class="input-group-addon" id="sizing-addon1">When did it start?</span>
			  <input type="text" class="form-control" value="<?php echo date("Y/m/d H:i", $time); ?>" id="BrgyDeclareDisaster_STARTED" name="STARTED" required/>
			</div>

			<br />

			<div class="input-group input-group">
			  <span class="input-group-addon" id="sizing-addon1">Note/Comment</span>
			  <textarea rows="5" class="form-control" id="BrgyDeclareDisaster_COMMENT" name="COMMENT" maxlength="300" placeholder="Place your note or comment here regarding to the disaster."></textarea>
			</div>
			  
			<div class="input-group input-group">
				<input type="text" id="AddEvacuationForm_ADDRESS2" name="ADDRESS2" placeholder="Point the location of evacuation in google map." class="form-control" style="display: none;" disabled>
				<input type="hidden" id="AddEvacuationForm_LAT" name="LAT" required />
				<input type="hidden" id="AddEvacuationForm_LNG" name="LNG" required />
				<input type="hidden" id="AddEvacuationForm_RAD" name="RAD" required />
			</div>

			<br />
          </form>
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
		<input form="BrgyDeclareDisasterForm" class="btn btn-primary pull-right" id="BrgyDeclareDisaster_SUBMIT" type="submit" data-loading-text="Submitting Disaster..." value="Submit" />
	  </div>
  </div>

</div> <!--Content ends here-->

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

<!-- Mapping Script -->
<script>
    var map;
    var evac_marker;
	var coordinates = [];
	var declare_range;
    function initAutocomplete() {
        <?php 
		$coordinatesphp = "";
		$sql = "SELECT LAT, LNG FROM barangay_coordinates WHERE BARANGAY = " . $BarangayInfo['ID'];
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while($row = $result->fetch_assoc()){
            $coordinatesphp .= "{lat: " . $row['LAT'] . ", lng: " . $row['LNG'] . "},\n";
        }
		$coordinatesphp = substr($coordinatesphp, 0, -2);
		?>
		coordinates = [<?php echo $coordinatesphp; ?>];
		
		map = new google.maps.Map(document.getElementById('map'), {
            center: polycenter(coordinates),
            zoom: 14,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
		
        var drawingManager = new google.maps.drawing.DrawingManager({
			drawingControl: false,
			drawingControlOptions: {
				position: google.maps.ControlPosition.TOP_CENTER,
				drawingModes: ['circle']
			},
			circleOptions: {
				fillColor: 'red',
				fillOpacity: 0.2,
				strokeWeight: 0.3,
				clickable: false,
				editable: true,
				zIndex: 1
			}
        });
		
        drawingManager.setMap(map);
		//drawingManager.setDrawingMode('circle');
		
		google.maps.event.addListener(drawingManager, 'circlecomplete', function (circle) {
			declare_range = circle;
			polyOver.remove();
			drawingManager.setDrawingMode(null);
			updateCirclePost(declare_range)();
			
			google.maps.event.addListener(declare_range, 'radius_changed', updateCirclePost(declare_range));
			google.maps.event.addListener(declare_range, 'center_changed', updateCirclePost(declare_range));
		});
		
		function updateCirclePost(circle) {
			return function(){
				document.getElementById('AddEvacuationForm_LAT').value = circle.getCenter().lat();
				document.getElementById('AddEvacuationForm_LNG').value = circle.getCenter().lng();
				document.getElementById('AddEvacuationForm_RAD').value = circle.getRadius();
			};
		}
		
		var brgyCoords = coordinates;
		brgyCoords.push(coordinates[0]);
		
		//Create the borders of the barangay.
		var brgyPolygon = new google.maps.Polygon({
		clickable: false,
		path: brgyCoords,
		geodesic: true,
		strokeColor: 'black',
		strokeOpacity: 0.4,
		fillColor: 'black',
		fillOpacity: 0.15,
		strokeWeight: 1
		});

		brgyPolygon.setMap(map);
		
		var polyOver = google.maps.event.addListener(map, 'mousemove', function (event) {
			var isInside = google.maps.geometry.poly.containsLocation(event.latLng, brgyPolygon);
			
			if(isInside) {
				drawingManager.setDrawingMode('circle');
			}
		});

    }
	
	function polycenter(coords) {
        var llng = coords[0].lng;
        var hlng = coords[0].lng;
        var llat = coords[0].lat;
        var hlat = coords[0].lat;

        for(var i = 0; i < coords.length; i++)
        {
            if(coords[i].lat < llat)
            {
                llat = coords[i].lat;
            }

            if(coords[i].lat > hlat)
            {
                hlat = coords[i].lat;
            }

            if(coords[i].lng < llng)
            {
                llng = coords[i].lng;
            }

            if(coords[i].lng > hlng)
            {
                hlng = coords[i].lng;
            }
        }
        var f_lat;
        var f_lng;
        f_lat = (hlat + llat)/2;
        f_lng = (hlng + llng)/2;

        return {lat: f_lat, lng: f_lng};
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places,drawing&callback=initAutocomplete&key=AIzaSyAuX4dJw6AKx5rbomKRek679WKUACmI9eE"
        async defer></script>

<script>
    $(function () {
        $('[data-toggle="popover"]').popover()
    });

    $('#BrgyDeclareDisaster_STARTED').datetimepicker({
        mask:'9999/19/39 29:59'
    });

    var BDDForm = {
        id: document.getElementById('BrgyDeclareDisasterForm'),
        disaster: document.getElementById('BrgyDeclareDisaster_DISASTER'),
        nickname: document.getElementById('BrgyDeclareDisaster_NICKNAME'),
        started: document.getElementById('BrgyDeclareDisaster_STARTED'),
        comment: document.getElementById('BrgyDeclareDisaster_COMMENT'),
        submit: '#BrgyDeclareDisaster_SUBMIT'
    };

    BDDForm.id.onsubmit = function (e) {
        e.preventDefault();
        BDDForm.nickname.value = BDDForm.nickname.value.trim();

        if(isWhitespace(BDDForm.disaster.value))
        {
            createmessage(3, 'Please select a disaster.', false);
            return false;
        }
        else if(isWhitespace(BDDForm.nickname.value))
        {
            createmessage(3, 'Nickname cannot be whitespace.', false);
            return false;
        }
        else if(haswrongspaces(BDDForm.nickname.value))
        {
            createmessage(3, 'Please Check your nickname. Double space or more is not allowed.', false);
            return false;
        }
        else if(isbelow(2, BDDForm.nickname.value))
        {
            createmessage(3, 'Nickname must be at least 2 characters.', false);
            return false;
        }
        else if(isWhitespace(BDDForm.started.value))
        {
            createmessage(3, 'Please enter the date and time when the disaster happened', false);
            return false;
        }


        if (confirm('Please confirm the form to submit: \n' +
            '\nDisaster: ' + document.getElementById('disaster_type_' + BDDForm.disaster.value).innerHTML +
            '\nNickname: ' + BDDForm.nickname.value +
            '\nDate Started: ' + BDDForm.started.value))
        {

        }
        else
        {
            return false;
        }

        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(BDDForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                $(BDDForm.submit).button('reset');
                var server_message = data.trim();
                if(server_message == 'success')
                {
                    createmessage(1, 'You have successfully submitted the disaster. <a href="BrgyOnGoingDisasterList.php">Click Here to manage On-Going Disaster.</a>', false);
                    BDDForm.id.reset();
                }
                else if(server_message == 'error')
                {
                    createmessage(3, 'There is a problem with submitting your report.', false);
                }
                else if(!isWhitespace(GetSuccessMsg(server_message)))
                {
                    createmessage(1, GetSuccessMsg(server_message), false);
                }
                else if(!isWhitespace(GetWarningMsg(server_message)))
                {
                    createmessage(2, GetWarningMsg(server_message), false);
                }
                else if(!isWhitespace(GetErrorMsg(server_message)))
                {
                    createmessage(3, GetErrorMsg(server_message), false);
                }
                else if(!isWhitespace(GetServerMsg(server_message)))
                {
                    createmessage(4, GetServerMsg(server_message), false);
                }
                else
                {
                    createmessage(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', false);
                }
            }
        });
    };

</script>

</body>
</html>



