<?php
session_start();
include ('library/form/CdrrmoOnly.php');
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 5;

$total_sql = 'SELECT * FROM evacuation_list WHERE EVACNAME LIKE "%'.$search.'%"';
$total_result = $db->connection->query($total_sql);
$total_count = mysqli_num_rows($total_result);
$total_page = ceil($total_count/$limit);

$page = isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $total_page ? $_GET['page'] : '1';
$offset = ($page * $limit) - $limit;

if($page < 2) {
	$disable_previous = 'disabled';
	$disable_previous2 = 'pointer-events: none;';
	$bottom_page = 'display:none';
}
else {
	$disable_previous = '';
	$disable_previous2 = '';
	$bottom_page = '';
}
if($total_page < 2) {
	$top_page = 'display:none';
}
else {
	$top_page = '';
}
if($total_page == $page || $total_page < 1) {
	$disable_next = 'disabled';
	$disable_next2 = 'pointer-events: none;';
	$top_page = 'display:none';
}
else {
	$disable_next = '';
	$disable_next2 = '';
	$top_page = '';
}

?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Manage Disaster Types</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="css/map.css" rel="stylesheet">
	

</head>
<body role="document">

<!-- Modals -->

<!-- Map Modal (Updating) -->
<div class="modal fade" id="MapModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="UpdateEvac_Title">Evacuation Center</h4>
			</div>
			<div class="modal-body">
				<div id="UL_msgbox" tabindex="0"></div>
				
				<div id="map2" style="width: 100%; height:500px;"></div>
			</div>
			<div class ="modal-footer">
				<form id="UpdateEvacForm" method="post" action="library/form/CdrrmoEvacLocUpdate.php">
					<input type="hidden" name="U_ID" id="UpdateEvac_ID" />
					<input type="hidden" name="U_LAT" id="UpdateEvac_LAT" />
					<input type="hidden" name="U_LNG" id="UpdateEvac_LNG" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" id="UpdateEvac_SUBMIT" data-loading-text="Updating Location..." class="btn btn-primary" disabled>Update</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!--Edit Evacuation Modal-->
<div class="modal fade" id="EditEvacuationModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Edit Evacuation Center</h4>
			</div>
			<form id="EditEvacForm" method="post" action="library/form/CdrrmoEvacEdit.php">
				<div class="modal-body">
					<div id="EE_msgbox" tabindex="0"></div>

					<!-- ID -->
					<input id="EditEvacForm_ID" name="ID" type="hidden" class="form-control">

					<!-- Evacuation Name -->
					<div class="input-group input-group">
						<span class="input-group-addon">Evacuation Name</span>
						<input id="EditEvacForm_NAME" name="NAME" type="text" class="form-control">
					</div>
					<br />
					
					<!-- Barangay -->
					<div class="input-group">
						<span class="input-group-addon">Barangay: </span>
						<select id="EditEvacForm_STATUS" name="BARANGAY" class="form-control">
							<?php
							$sql = "SELECT * FROM barangay ORDER BY NAME DESC";
							$result = $db->connection->query($sql);
							while($row = $result->fetch_assoc()) {
								?>
								<option value="<?php echo $row["ID"]; ?>" id="barangay_<?php echo $row["ID"]; ?>"><?php echo $row["NAME"]; ?></option>
								<?php
							}
							?>
						</select>
					</div>
					<br />

					<!-- Complete Address -->
					<div class="input-group input-group">
						<span class="input-group-addon">Complete Address</span>
						<input id="EditEvacForm_ADDRESS1" name="ADDRESS1" type="text" class="form-control">
					</div>
					<br />

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" id="EditEvacForm_SUBMIT" data-loading-text="Editing Evacuation..." class="btn btn-primary">Edit</button>
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
				<h4 class="modal-title">Add Evacuation Center</h4>
			</div>
			<form id="AddEvacuationForm" method="post" action="library/form/AdminAddEvacuationForm.php">
				<div class="modal-body">
					<div id="AE_msgbox" tabindex="0"></div>
					
					<!-- Evacuation Name -->
					<div class="input-group input-group">
						<span class="input-group-addon">Evacuation Name</span>
						<input id="AddEvacuationForm_NAME" name="NAME" type="text" class="form-control">
					</div>
					<br />
					
					<!-- Barangay -->
					<div class="input-group">
						<span class="input-group-addon">Barangay: </span>
						<select id="AddEvacuationForm_STATUS" name="BARANGAY" class="form-control">
							<?php
							$sql = "SELECT * FROM barangay ORDER BY NAME DESC";
							$result = $db->connection->query($sql);
							while($row = $result->fetch_assoc()) {
								?>
								<option value="<?php echo $row["ID"]; ?>" id="barangay_<?php echo $row["ID"]; ?>"><?php echo $row["NAME"]; ?></option>
								<?php
							}
							?>
						</select>
					</div>
					<br />
					
					<!-- Complete Address -->
					<div class="input-group input-group">
						<span class="input-group-addon">Complete Address</span>
						<input id="AddEvacuationForm_ADDRESS1" name="ADDRESS1" type="text" class="form-control">
					</div>
					<br />
					
					
					<!-- Map -->
					<div class="input-group">
						<span class="input-group-addon" id="basicasdsaasdon1"><span class="glyphicon glyphicon-pushpin"></span> </span>
						<div class="form-control" style="width: 100%; height:300px;">
							<div id="map"></div>
						</div>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" id="AddEvacuationForm_SUBMIT" data-loading-text="Adding Evacuation..." class="btn btn-primary">Add</button>
				</div>
			</form>
			
			<input id="pac-input" class="controls" onkeydown="if(event.keyCode == 13) {event.preventDefault(); return false; }" type="text" placeholder="Search Box" />
		</div>
	</div>
</div>

<!-- Content starts here -->
<?php include('library/html/navbar.php'); ?>

<div class="container">
    <div class="page-header">
		<button class="btn btn-primary pull-right" data-toggle="modal" data-target="#AddEvacuationModal">
			<span class="glyphicon glyphicon-plus"></span> Add Evacuation Center
		</button>
        <h1>Manage Evacuation Centers</h1>
    </div>
	
	<!-- Evacuation Center Search Bar -->
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<form id="SearchForm" method="get">
				<div class="input-group">
					<input id="SearchInput" name="search" type="text" class="form-control" placeholder="Search.." value="<?php echo $search; ?>" />
					<span class="input-group-btn">
						<button id="SearchSubmit" class="btn btn-secondary" type="submit"><span class="glyphicon glyphicon-search"></span>&nbsp Search</button>
					</span>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Disaster Type List -->
	<table class="table table-striped table-hover">
		<thead>
		<tr>
			<th>Evacuation Center</th>
			<th>Barangay</th>
			<th>Complete Address</th>
			<th>Google Maps Address</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody id="PageComponent_ECLIST">
		</tbody>
	</table>
	<div id="pagemessagebox" tabindex="0"></div>
	
	<!-- Pagination -->
	<ul class="pager">
		<li style="<?php echo $bottom_page; ?>">
			<a href="CdrrmoEvacManage.php?search=<?php echo $search; ?>&page=1" style=""><span class="glyphicon glyphicon-menu-left"></span>&nbsp 1</a>
		</li>
		<li class="<?php echo $disable_previous; ?>">
			<a href="CdrrmoEvacManage.php?search=<?php echo $search; ?>&page=<?php echo $page - 1; ?>" style="<?php echo $disable_previous2; ?>"><span class="glyphicon glyphicon-menu-left"></span>&nbsp Previous</a>
		</li>
		<li class="disabled">
			<span><h4 style="margin-top: 0.3rem; margin-bottom: 0.3rem;">Page <?php echo $page; ?></h4></span>
		</li>
		<li class="<?php echo $disable_next; ?>">
			<a href="CdrrmoEvacManage.php?search=<?php echo $search; ?>&page=<?php echo $page + 1; ?>" style="<?php echo $disable_next2; ?>">Next&nbsp <span class="glyphicon glyphicon-menu-right"></span></a>
		</li>
		<li style="<?php echo $top_page; ?>">
			<a href="CdrrmoEvacManage.php?search=<?php echo $search; ?>&page=<?php echo $total_page; ?>" style=""><?php echo $total_page; ?>&nbsp <span class="glyphicon glyphicon-menu-right"></span></a>
		</li>
	</ul>
</div>

<?php include('library/html/footer.php'); ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/1.11.3_jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/app/mylibrary.js"></script>
<script src="js/app/messagealert.js"></script>
<script src="js/jquery.datetimepicker.full.min.js"></script>
<script src="js/jquery.form.min.js"></script>

<!-- Map Script -->
<script>
    $('#EndDisasterForm_ENDED').datetimepicker({
        mask:'9999/19/39 29:59'
    });

    function geocodeLatLng(geocoder,latlng) {
        geocoder.geocode({'location': latlng}, function(results, status) {
            document.getElementById('AddEvacuationForm_ADDRESS2').value = "Getting Location...";
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    document.getElementById('AddEvacuationForm_LAT').value = latlng.lat();
                    document.getElementById('AddEvacuationForm_LNG').value = latlng.lng();
                    document.getElementById('AddEvacuationForm_ADDRESS2').value = results[1].formatted_address;

                } else {
                    alert("There is no address for this area.");
                }
            } else {
                if(status == "ZERO_RESULTS")
                {
                    alert("There is no address for this area.");
                }
            }
        });
    }

    var map;
	var map2;
    var evac_marker;
	var evac_marker2;
    function initAutocomplete() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 10.7149629, lng: 122.5476471},
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
		map2 = new google.maps.Map(document.getElementById('map2'), {
            center: {lat: 10.7149629, lng: 122.5476471},
            zoom: 16,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        evac_marker = new google.maps.Marker({
            position: map.getCenter(),
            map: map,
            title: 'Evacuation Center',
            draggable: true,
            animation: google.maps.Animation.BOUNCE
        });
		
		evac_marker2 = new google.maps.Marker({
            position: map2.getCenter(),
            map: map2,
            title: 'Evacuation',
            draggable: true,
            animation: google.maps.Animation.BOUNCE
        });


        var geocoder = new google.maps.Geocoder;
		
        map.addListener('click', function(event) {
            evac_marker.setPosition(event.latLng);
            evac_marker.setAnimation(google.maps.Animation.BOUNCE);
            geocodeLatLng(geocoder,event.latLng);
        });
		
		map2.addListener('click', function(event) {
            evac_marker2.setPosition(event.latLng);
            evac_marker2.setAnimation(google.maps.Animation.BOUNCE);
			
            document.getElementById("UpdateEvac_LAT").value = event.latLng.lat();
			document.getElementById("UpdateEvac_LNG").value = event.latLng.lng();
			document.getElementById("UpdateEvac_SUBMIT").disabled = false;
        });

        evac_marker.addListener('dragend', function(event) {
            geocodeLatLng(geocoder,event.latLng);
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {

                evac_marker.setPosition(place.geometry.location);

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
    }

    $('#AddEvacuationModal').on('shown.bs.modal', function (e) {
        google.maps.event.trigger(map, "resize");
        map.setCenter(evac_marker.position);
    });
	
	$('#MapModal').on('shown.bs.modal', function (e) {
        google.maps.event.trigger(map2, "resize");
        map2.setCenter(evac_marker2.position);
    });

</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&key=AIzaSyAuX4dJw6AKx5rbomKRek679WKUACmI9eE"
        async defer></script>

<!-- Page Script -->
<script>
    var PageComponent = {
        eclist: document.getElementById('PageComponent_ECLIST')
    };
	
    var AEForm = {  //Add Evacuation Form
        form: document.getElementById('AddEvacuationForm'),
        name: document.getElementById('AddEvacuationForm_NAME'),
        address1: document.getElementById('AddEvacuationForm_ADDRESS1'),
        address2: document.getElementById('AddEvacuationForm_ADDRESS2'),
        lat: document.getElementById('AddEvacuationForm_LAT'),
        lng: document.getElementById('AddEvacuationForm_LNG'),
        submit: '#AddEvacuationForm_SUBMIT',
        modal: '#AddEvacuationModal',
        msgbox: 'AE_msgbox'
    };
	
	var ULForm = {
		form: document.getElementById('UpdateEvacForm'),
		title: document.getElementById("UpdateEvac_Title"),
		id: document.getElementById('UpdateEvac_ID'),
		lat: document.getElementById('UpdateEvac_LAT'),
		lng: document.getElementById('UpdateEvac_LNG'),
		submit: '#UpdateEvac_SUBMIT',
        modal: '#MapModal',
        msgbox: 'UL_msgbox'
	};

	var EEForm = {
		form: document.getElementById('EditEvacForm'),
		id: document.getElementById('EditEvacForm_ID'),
		name: document.getElementById('EditEvacForm_NAME'),
		address1: document.getElementById('EditEvacForm_ADDRESS1'),
		brgy: document.getElementById('EditEvacForm_STATUS'),
		submit: '#EditEvacForm_SUBMIT',
        modal: '#EditEvacuationModal',
        msgbox: 'EE_msgbox'
	};
	
	//Add new account to account list
	function AddEvac(id, name, barangay, address1, address2, lat, lng) {
        PageComponent.eclist.innerHTML = PageComponent.eclist.innerHTML +
            '<tr>' +
            '   <td id="Evac_name_' + id + '">' + name + '</td>'+
            '   <td id="Evac_barangay_' + id + '">' + barangay + '</td>'+
            '   <td id="Evac_address1_' + id + '">' + address1 + '</td>'+
			'   <td id="Evac_lat_' + id + '" style="display:none">' + lat + '</td>'+
			'   <td id="Evac_lng_' + id + '" style="display:none">' + lng + '</td>'+
            '   <td id="Evac_address2_' + id + '"><a onclick="loadMapModal(' + id + ')" data-toggle="modal" data-target="#MapModal"><img src="https://maps.googleapis.com/maps/api/staticmap?markers=color:red%7C'+ lat +','+ lng +'&center='+ lat +','+ lng +'&zoom=15&size=250x150&maptype=roadmap&key=AIzaSyCalJXL3IZ37jpy9s0K5ge-xgojC8fXWOM" /></a></td>'+
			'   <td><button id="EditEvac_BTN' + id + '" value="' + id + '" class="btn btn-default" onclick="EditFill(' + id + ')" data-toggle="modal" data-target="#EditEvacuationModal"><span class="glyphicon glyphicon-pencil"></span>&nbspEdit</button></td>'+
            '</tr>';
			//onclick="UpdateFill(\'' + id + '\')"
    }

	function EditFill(id) {
		var name = document.getElementById('Evac_name_' + id).innerHTML;
		var barangay = document.getElementById('Evac_barangay_' + id).innerHTML;
		var address1 = document.getElementById('Evac_address1_' + id).innerHTML;

		EEForm.id.value = id;
		EEForm.name.value = name;
		EEForm.address1.value = address1;

		for(var i = 0; i < EEForm.brgy.options.length; i++) {
			if(EEForm.brgy.options[i].text == barangay) {
				EEForm.brgy.selectedIndex = i;
			}
		}
	}

	function EditEvac(id, name, barangay, address1) {
		document.getElementById('Evac_name_' + id).innerHTML = name;
		document.getElementById('Evac_barangay_' + id).innerHTML = barangay;
		address1 = document.getElementById('Evac_address1_' + id).innerHTML = address1;
	}
	
	function loadMapModal(id) {
		var modalLatlng = new google.maps.LatLng(document.getElementById("Evac_lat_" + id).innerHTML, document.getElementById("Evac_lng_" + id).innerHTML);
		var evacname = document.getElementById("Evac_name_" + id).innerHTML;
		
		ULForm.id.value = id;
		ULForm.lat.value = document.getElementById("Evac_lat_" + id).innerHTML;
		ULForm.lng.value = document.getElementById("Evac_lng_" + id).innerHTML;		
		document.getElementById("UpdateEvac_SUBMIT").disabled = true;
		ULForm.title.innerHTML = evacname;
		evac_marker2.setPosition(modalLatlng);
		evac_marker2.setAnimation(google.maps.Animation.BOUNCE);
		evac_marker2.title = evacname;
	}
	
	function ResetAEForm() {
        AEForm.name.value = '';
        AEForm.address1.value = '';
        AEForm.address2.value = '';
        AEForm.lat.value = '';
        AEForm.lng.value = '';
    }
	
	AEForm.form.onsubmit = function (e) {
        e.preventDefault();
		AEForm.address2.disabled = '';
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
				AEForm.address2.disabled = 'disabled';
				$(AEForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{
					createmessagein(1, "Evacuation Center Successfully Added!", AEForm.msgbox);
					AddEvac(GetSuccessMsg(server_message), AEForm.name.value, AEForm.address1.value, AEForm.address2.value, AEForm.lat.value, AEForm.lng.value);
					ResetAEForm();
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
    };

	EEForm.form.onsubmit = function (e) {
        e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(EEForm.submit).button('loading');
			},
			uploadProgress:function(event,position,total,percentCompelete)
			{

			},
			success:function(data)
			{
				$(EEForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{
					createmessagein(1, "Evacuation Center Successfully Edited!", EEForm.msgbox);
					EditEvac(EEForm.id.value, EEForm.name.value, EEForm.brgy.options[EEForm.brgy.selectedIndex].text, EEForm.address1.value);
				}
				else if(!isWhitespace(GetWarningMsg(server_message)))
				{
					createmessagein(2, GetWarningMsg(server_message), EEForm.msgbox);
				}
				else if(!isWhitespace(GetErrorMsg(server_message)))
				{
					createmessagein(3, GetErrorMsg(server_message), EEForm.msgbox);
				}
				else if(!isWhitespace(GetServerMsg(server_message)))
				{
					createmessagein(4, GetServerMsg(server_message), EEForm.msgbox);
				}
				else
				{
					createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', EEForm.msgbox);
				}
			}
		});
    };
	
	ULForm.form.onsubmit = function (e) {
        e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ULForm.submit).button('loading');
			},
			uploadProgress:function(event,position,total,percentCompelete)
			{

			},
			success:function(data)
			{
				$(ULForm.submit).button('reset');
				var server_message = data.trim();
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{
					createmessagein(1, GetSuccessMsg(server_message), ULForm.msgbox);
				}
				else if(!isWhitespace(GetWarningMsg(server_message)))
				{
					createmessagein(2, GetWarningMsg(server_message), ULForm.msgbox);
				}
				else if(!isWhitespace(GetErrorMsg(server_message)))
				{
					createmessagein(3, GetErrorMsg(server_message), ULForm.msgbox);
				}
				else if(!isWhitespace(GetServerMsg(server_message)))
				{
					createmessagein(4, GetServerMsg(server_message), ULForm.msgbox);
				}
				else
				{
					createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', ULForm.msgbox);
				}
			}
		});
    };
		
	//Fill disaster table
	<?php
	$list_sql = 'SELECT
					el.ID AS ID,
					b.NAME AS BARANGAY,
					el.EVACNAME AS EVACNAME,
					el.EVACADDRESS1 AS ADDRESS1,
					el.EVACADDRESS2 AS ADDRESS2,
					el.LAT AS LAT,
					el.LNG AS LNG
				FROM
                	(SELECT * FROM evacuation_list WHERE EVACNAME LIKE "%'. $search .'%") AS el 
				INNER JOIN
					barangay AS b ON b.ID = el.BARANGAY
				ORDER BY
					el.EVACNAME
				LIMIT 
					' .$limit. '
				OFFSET 
					'.$offset;
				
	$list_result = $db->connection->query($list_sql);
	$list_count = mysqli_num_rows($list_result);
	
	if($list_count < 1) {
		echo 'createmessage(4, "No results found.", false);';
	}
	else {
		while ($list_row = $list_result->fetch_assoc()) {
			$result_ID = htmlspecialchars($list_row['ID']);
			$result_BARANGAY = htmlspecialchars($list_row['BARANGAY']);
			$result_EVACNAME = htmlspecialchars($list_row['EVACNAME']);
			$result_ADDRESS1 = htmlspecialchars($list_row['ADDRESS1']);
			$result_ADDRESS2 = htmlspecialchars($list_row['ADDRESS2']);
			$result_LAT = htmlspecialchars($list_row['LAT']);
			$result_LNG = htmlspecialchars($list_row['LNG']);
	?>
	
	AddEvac(<?php echo $result_ID; ?>,'<?php echo $result_EVACNAME; ?>','<?php echo $result_BARANGAY; ?>','<?php echo $result_ADDRESS1; ?>','<?php echo $result_ADDRESS2; ?>',<?php echo $result_LAT; ?>,<?php echo $result_LNG; ?>);
	
	<?php
		}
	}
	?>
</script>
</body>
</html>