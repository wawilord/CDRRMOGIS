<?php
session_start();
include ('library/form/CdrrmoOnly.php');
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 10;

$total_sql = 'SELECT 
barangay.ID, 
barangay.NAME,
district.NAME AS DISTRICT, 
city.NAME AS CITY, 
barangay_info.MEN AS MEN, 
barangay_info.WOMEN AS WOMEN
FROM (SELECT * FROM barangay WHERE barangay.NAME LIKE "%'.$search.'%") AS barangay
LEFT JOIN district ON barangay.DISTRICT = district.ID
LEFT JOIN city ON district.CITY = city.ID
LEFT JOIN barangay_info ON barangay.ID = barangay_info.BARANGAY
GROUP BY barangay.ID';

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
    <meta name="description" content="th3515">
    <meta name="author" content="@pablongbuhaymo">

    <title>Manage Barangays | City Disaster Risk Management</title>    
    <link rel="icon" href="img/favicon.ico">

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/app.css" rel="stylesheet">

   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"
    integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="
   crossorigin=""/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body role="document">

<!-- Modals -->

<!-- Add Barangay Modal -->
<div class="modal fade" id="AddBarangayModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add New Barangay</h4>
			</div>
			
			<!-- Add Barangay Form -->
			<form id="AddBarangayForm" method="post" action="library/form/AddBarangayForm.php">
				<div class="modal-body">
					<div id="AB_msgbox" tabindex="0"></div>
					
					<!-- MAP SOON HERE-->
					
					
					<!-- Infomation -->
					<div class="panel panel-default">
						<div class="panel-heading">Information</div>
						<div class="panel-body">
						
							<!-- Name -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">Name: </span>
								<input type="text" id="AddBarangayForm_NAME" name="NAME" maxlength="50" class="form-control" required />
							</div>
							<br />
							
							<!-- District -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">District: </span>
								<select class="form-control" id="AddBarangayForm_DISTRICT" name="DISTRICT">
									<?php
									$district_sql = "SELECT * FROM district ORDER BY NAME";
									$district_result = $db->connection->query($district_sql);
									while($district_row = $district_result->fetch_assoc()) {
										?>
										<option value="<?php echo $district_row["ID"]; ?>" id="add_district_<?php echo $district_row["ID"]; ?>"><?php echo $district_row["NAME"]; ?></option>
										<?php
									}
									?>
								</select>
							</div>
							<br />
							
						</div>
					</div>
					
					<!-- Population -->
					<div class="panel panel-default">
						<div class="panel-heading">Details</div>
						<div class="panel-body">
						
							<!-- Men -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">Men: </span>
								<input type="number" id="AddBarangayForm_MEN" name="MEN" value=0 maxlength="5" class="form-control" />
							</div>
							<br />
							
							<!-- Women -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">Women: </span>
								<input type="number" id="AddBarangayForm_WOMEN" name="WOMEN" value=0 maxlength="5" class="form-control" />
							</div>
							<br />
							
							<!-- Minors -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">Minors: </span>
								<input type="number" id="AddBarangayForm_MINORS" name="MINORS" value=0 maxlength="5" class="form-control" />
							</div>
							<br />
							
							<!-- Adults -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">Adults: </span>
								<input type="number" id="AddBarangayForm_ADULTS" name="ADULTS" value=0 maxlength="5" class="form-control" />
							</div>
							<br />
						
							<!-- PWD -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">PWDs: </span>
								<input type="number" id="AddBarangayForm_PWD" name="PWD" value=0 maxlength="5" class="form-control" />
							</div>
							<br />

							<!-- Concrete Houses -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">Concrete: </span>
								<input type="number" id="AddBarangayForm_CONCRETE" name="CONCRETE" value=0 maxlength="5" class="form-control" />
							</div>
							<br />

							<!-- Light Houses -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">Light: </span>
								<input type="number" id="AddBarangayForm_LIGHT" name="LIGHT" value=0 maxlength="5" class="form-control" />
							</div>
							<br />

							<!-- Both Houses -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">Both: </span>
								<input type="number" id="AddBarangayForm_BOTH" name="BOTH" value=0 maxlength="5" class="form-control" />
							</div>
							<br />

							<!-- Area -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">Area: </span>
								<input type="number" id="AddBarangayForm_AREA" name="AREA" value=0 maxlength="5" step="0.01" class="form-control" />
							</div>
							<br />

						</div>
					</div>
					
				</div>
				
				<!-- Submission -->
				<div class="modal-footer">
					<button type="submit" id="AddBarangayForm_SUBMIT" data-loading-text="Adding Barangay..." class="btn btn-primary">Add Barangay</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
				
			</form>
			
		</div>
	</div>
</div>

<!-- Update Barrangay Modal -->
<div class="modal fade" id="UpdateBarangayModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Update Barangay</h4>
			</div>

			<!-- Update Barangay Form -->
			<form id="UpdateBarangayForm" method="post" action="library/form/UpdateBarangayForm.php">
				<div class="modal-body">
					<div id="UB_msgbox" tabindex="0"></div>
					
					<input type="hidden" id="UpdateBarangayForm_ID" name="ID" required />
					<!-- Infomation -->
					<div class="panel panel-default">
						<div class="panel-heading">Information</div>
						<div class="panel-body">
						
							<!-- Name -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">Name: </span>
								<input type="text" id="UpdateBarangayForm_NAME" name="NAME" maxlength="50" class="form-control" required />
							</div>
							<br />
							
							<!-- District -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">District: </span>
								<select class="form-control" id="UpdateBarangayForm_DISTRICT" name="DISTRICT">
									<?php
									$district_sql = "SELECT * FROM district ORDER BY NAME";
									$district_result = $db->connection->query($district_sql);
									while($district_row = $district_result->fetch_assoc()) {
										?>
										<option value="<?php echo $district_row["ID"]; ?>" id="add_district_<?php echo $district_row["ID"]; ?>"><?php echo $district_row["NAME"]; ?></option>
										<?php
									}
									?>
								</select>
							</div>
							<br />
						</div>
					</div>
						
				</div>
					
				<!-- Submission -->
				<div class="modal-footer">
					<button type="submit" id="UpdateBarangayForm_SUBMIT" data-loading-text="Updating Barangay..." class="btn btn-primary">Update Barangay</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Update Barrangay Info Modal -->
<div class="modal fade" id="UpdateBarangayModal2" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Update Barangay Information</h4>
			</div>
				
			<!-- Update Barangay Form 2-->
			<form id="UpdateBarangayForm2" method="post" action="library/form/UpdateBarangayForm2.php">
				<div class="modal-body">
					<div id="UB_msgbox2" tabindex="0"></div>
					
					<!-- ID -->
					<input type="hidden" id="UpdateBarangayForm2_ID" name="ID" required />					
					
					<!-- Men -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Men: </span>
						<input type="number" id="UpdateBarangayForm_MEN" name="MEN" value=0 maxlength="5" class="form-control" />
					</div>
					<br />
					
					<!-- Women -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Women: </span>
						<input type="number" id="UpdateBarangayForm_WOMEN" name="WOMEN" value=0 maxlength="5" class="form-control" />
					</div>
					<br />
					
					<!-- Minors -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Minors: </span>
						<input type="number" id="UpdateBarangayForm_MINORS" name="MINORS" value=0 maxlength="5" class="form-control" />
					</div>
					<br />
					
					<!-- Adults -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Adults: </span>
						<input type="number" id="UpdateBarangayForm_ADULTS" name="ADULTS" value=0 maxlength="5" class="form-control" />
					</div>
					<br />
				
					<!-- PWD -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">PWDs: </span>
						<input type="number" id="UpdateBarangayForm_PWD" name="PWD" value=0 maxlength="5" class="form-control" />
					</div>
					<br />
							
					<!-- Concrete Houses -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Concrete: </span>
						<input type="number" id="UpdateBarangayForm_CONCRETE" name="CONCRETE" value=0 maxlength="5" class="form-control" />
					</div>
					<br />

					<!-- Light Houses -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Light: </span>
						<input type="number" id="UpdateBarangayForm_LIGHT" name="LIGHT" value=0 maxlength="5" class="form-control" />
					</div>
					<br />

					<!-- Both Houses -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Both: </span>
						<input type="number" id="UpdateBarangayForm_BOTH" name="BOTH" value=0 maxlength="5" class="form-control" />
					</div>
					<br />

					<!-- Area -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Area: </span>
						<input type="number" id="UpdateBarangayForm_AREA" name="AREA" value=0 maxlength="5" step="0.01" class="form-control" />
					</div>
					<br />
				</div>
				
				<!-- Submission -->
				<div class="modal-footer">
					<button type="submit" id="UpdateBarangayForm_SUBMIT2" data-loading-text="Updating Barangay..." class="btn btn-primary">Update Barangay</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			
			</form>
		</div>
	</div>
</div>

<!-- View Barrangay Info Modal -->
<div class="modal fade" id="ViewBarangayModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Barangay Information Overview</h4>
			</div>
				
			<!-- View Barangay Modal-->
			<div class="modal-body">
					<!-- ID -->
					<input type="hidden" id="UpdateBarangayForm2_ID" name="ID" required />					
					<h1 class = "text-center" id = "viewModalBrgy"></h1>
					<h5 class = "text-center greyFont" style = "font-size:18px;" id = "viewModalArea"></h5>	
					<br />

						<span class = "col-lg-4">
						<h4> Gender</h4><hr>
					   <canvas id="cassualtiesChart" width="55" height="100%"></canvas>
						<h6 id = "viewModalPopulation"></h6>
						</span>					
											
						<span class = "col-lg-4">
							<h4> Age Group</h4><hr>
					   <canvas id="ageDifference" width="55" height="100%"></canvas>
						<h6 id = "viewModalPWD"></h6>
						</span>					
											
						<span class = "col-lg-4">
						<h4> House Type </h4><hr>
					   <canvas id="houseType" width="55" height="100%"></canvas>
						<h6 id = "viewModalHouseTotal"></h6>
						<br />
						</span>					

				</div>
				
				<!-- Submission -->
				<div class="modal-footer">
					<a href="overview.php" class="btn btn-success">Location</a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			
			</form>
		</div>
	</div>
</div>



<!-- Content starts here -->
<?php include('library/html/navbar.php'); ?>

<div class="container">
    <div class="page-header">
		<button class="btn btn-secondary pull-right" data-toggle="modal" data-target="#AddBarangayModal">
			<span class="glyphicon glyphicon-plus"></span> Add New Barangay
		</button>
        <h3>Barangay Management</h3>
    </div>
	
	<!-- Barangay Search Bar -->
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
	
	<!-- Barangay List -->
	<table class="table table-striped table-hover">
		<thead>
		<tr>
			<th>Name</th>
			<th>District</th>
			<th>City</th>
			<th>Area</th>
			<th>Overview</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody id="PageComponent_BRLIST">
		</tbody>
	</table>
	<div id="pagemessagebox" tabindex="0"></div>
	
	<!-- Pagination -->
	<ul class="pager">
		<li style="<?php echo $bottom_page; ?>">
			<a href="BarangayManagement.php?search=<?php echo $search; ?>&page=1" style=""><span class="glyphicon glyphicon-menu-left"></span>&nbsp 1</a>
		</li>
		<li class="<?php echo $disable_previous; ?>">
			<a href="BarangayManagement.php?search=<?php echo $search; ?>&page=<?php echo $page - 1; ?>" style="<?php echo $disable_previous2; ?>"><span class="glyphicon glyphicon-menu-left"></span>&nbsp Previous</a>
		</li>
		<li class="disabled">
			<span><h4 style="margin-top: 0.3rem; margin-bottom: 0.3rem;">Page <?php echo $page; ?></h4></span>
		</li>
		<li class="<?php echo $disable_next; ?>">
			<a href="BarangayManagement.php?search=<?php echo $search; ?>&page=<?php echo $page + 1; ?>" style="<?php echo $disable_next2; ?>">Next&nbsp <span class="glyphicon glyphicon-menu-right"></span></a>
		</li>
		<li style="<?php echo $top_page; ?>">
			<a href="BarangayManagement.php?search=<?php echo $search; ?>&page=<?php echo $total_page; ?>" style=""><?php echo $total_page; ?>&nbsp <span class="glyphicon glyphicon-menu-right"></span></a>
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
<script src="js/chart.bundle.min.js"></script>


<!-- Page Script -->
<script>
    var PageComponent = {
        brlist: document.getElementById('PageComponent_BRLIST')
    };
	
	var ABForm = { 
        form: document.getElementById('AddBarangayForm'),
		name: document.getElementById('AddBarangayForm_NAME'),
		district: document.getElementById('AddBarangayForm_DISTRICT'),
		men: document.getElementById('AddBarangayForm_MEN'),
		women: document.getElementById('AddBarangayForm_WOMEN'),
		minors: document.getElementById('AddBarangayForm_MINORS'),
		adults: document.getElementById('AddBarangayForm_ADULTS'),
		pwd: document.getElementById('AddBarangayForm_PWD'),
		light: document.getElementById('AddBarangayForm_LIGHT'),
		concrete: document.getElementById('AddBarangayForm_CONCRETE'),
		both: document.getElementById('AddBarangayForm_BOTH'),
		area: document.getElementById('AddBarangayForm_AREA'),
        submit : '#AddBarangayForm_SUBMIT',
        modal: '#AddBarangayModal',
        msgbox: 'AB_msgbox'
    };
		
	function ResetABForm() {
		ABForm.name.value = '';
		ABForm.district.selectedIndex = 0;
		ABForm.men.value = 0;
		ABForm.women.value = 0;
		ABForm.minors.value = 0;
		ABForm.adults.value = 0;
		ABForm.pwd.value = 0;
	}

	//Async New Barangay Submit 
	ABForm.form.onsubmit = function(e) {
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ABForm.submit).button('loading');
			},
			uploadProgress:function(event,position,total,percentCompelete)
			{

			},
			success:function(data)
			{
				$(ABForm.submit).button('reset');
				var server_message = data.trim();
				
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{
					createmessagein(1, 'Successfully added barangay!', ABForm.msgbox);
					
					AddBarangay(server_message, ABForm.name.value, ABForm.district.options[ABForm.district.selectedIndex].text, 'Iloilo City', ABFORM.area);
					ResetABForm();
				}
				else if(!isWhitespace(GetWarningMsg(server_message)))
				{
					createmessagein(2, GetWarningMsg(server_message), ABForm.msgbox);
				}
				else if(!isWhitespace(GetErrorMsg(server_message)))
				{
					createmessagein(3, GetErrorMsg(server_message), ABForm.msgbox);
				}
				else if(!isWhitespace(GetServerMsg(server_message)))
				{
					createmessagein(4, GetServerMsg(server_message), ABForm.msgbox);
				}
				else
				{
					createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', ABForm.msgbox);
				}
			}
		});
	};


	
	//Add barangay to list. 

	// *** i added aditional area2 value to successfully pass value in update form 2. notice barangay_area2
	
	function AddBarangay(id, name, district, city, men, women, population, minors, adults, pwd, t_houses, c_houses, l_houses, cl_houses, area) {
        PageComponent.brlist.innerHTML = PageComponent.brlist.innerHTML +
            '<tr>' +
            '   <td id="Barangay_name_' + id + '">' + name + '</td>'+
			'   <td id="Barangay_district_' + id + '">' + district + '</td>'+
			'   <td id="Barangay_city_' + id + '">' + city + '</td>'+
			'	<td class = "hidden" id="Barangay_men_' + id + '">' + men + '</td>'+
			'	<td class = "hidden" id="Barangay_women_' + id + '">' + women + '</td>'+
			'	<td class = "hidden" id="Barangay_population_' + id + '">' + population + '</td>'+
			'	<td class = "hidden" id="Barangay_minors_' + id + '">' + minors + '</td>'+
			'	<td class = "hidden" id="Barangay_adults_' + id + '">' + adults + '</td>'+
			'	<td class = "hidden" id="Barangay_pwd_' + id + '">' + pwd + '</td>'+
			'	<td class = "hidden" id="Barangay_t_houses_' + id + '">' + t_houses + '</td>'+
			'	<td class = "hidden" id="Barangay_c_houses_' + id + '">' + c_houses + '</td>'+
			'	<td class = "hidden" id="Barangay_l_houses_' + id + '">' + l_houses + '</td>'+
			'	<td class = "hidden" id="Barangay_cl_houses_' + id + '">' + cl_houses + '</td>'+
			'	<td class = "hidden" id="Barangay_area2_' + id + '">' + area + '</td>'+ 			
			'   <td id="Barangay_area_' + id + '">' + area + ' sqm</td>'+
			'   <td><div class="btn-group"><button id="Barangay_BTN_' + id + '" value="' + id + '" class="btn btn-default" data-toggle="modal" data-target="#ViewBarangayModal" onclick="viewChart(\'' + id + '\')"><span class="glyphicon glyphicon-eye-open"></span>&nbspView</button><input type="hidden" class="btn" /></div></td>'+
			'   <td><div class="btn-group"><button id="Barangay_BTN_' + id + '" value="' + id + '" class="btn btn-default" data-toggle="modal" data-target="#UpdateBarangayModal" onclick="UpdateFill(\'' + id + '\')"><span class="glyphicon glyphicon-pencil"></span>&nbspEdit</button><input type="hidden" class="btn" /></div>'+
			'   <div class="btn-group"><input type="hidden" class="btn" /><button id="Barangay_BTN_' + id + '2" value="' + id + '" class="btn btn-default" data-toggle="modal" data-target="#UpdateBarangayModal2" onclick="UpdateFill2(\'' + id + '\')"><span class="glyphicon glyphicon-list-alt"></span>&nbspUpdate Info</button><input type="hidden" class="btn" /></div>'+
			'  	<form style="margin:0px" class="btn-group" id="UpdateBarangayMapForm" method="post" action="#"><input type="hidden" class="btn" name="BARANGAY" value="'+id+'" /><button type="submit" value="'+id+'" id="UpdateBarangayMapForm_SUBMIT" class="btn btn-default"><span class="glyphicon glyphicon-map-marker"></span>&nbspUpdate Map</button></form></td>'+
            '</tr>';
    }

	
		
	//Fill disaster type table
	<?php
	$list_sql='SELECT 
    disaster_reports.DMGTOTALLY,
    disaster_reports.DMGPARTIALLY,
    disaster_reports.CSLTDEAD,
    disaster_reports.CSLTINJURED,
    disaster_reports.CSLTMISSING,
    disaster_reports.DATEADDED,
    disaster_reports.ISVERIFIED,
    disaster_declare.NICKNAME,
    disaster_declare.STARTED,
    disaster_declare.ENDED,
    disaster_declare.COMMENT,
    disaster_declare.DATECREATED,
    disaster_declare.LAT,
    disaster_declare.LNG,
    disaster_declare.RADIUS,
    disaster_declare.ISVERIFIED,
    disaster_declare.ACCEPTED,
    disaster_type.NAME,
    disaster_type.COLOR
    
    FROM disaster_reports
    left JOIN disaster_declare
    ON disaster_reports.DECLAREID = disaster_declare.ID
    left JOIN disaster_type
    ON disaster_declare.DISASTER = disaster_type.ID
	GROUP BY disaster_declare.ID
	ORDER BY disaster_declare.NAME
	LIMIT '.$limit.' OFFSET '.$offset;
	
	$list_result = $db->connection->query($list_sql);
	$list_count = mysqli_num_rows($list_result);
	
	if($list_count < 1) {
		echo 'createmessage(4, "No results found.", false);';
	}
	else {
		while ($list_row = $list_result->fetch_assoc()) {
			$result_ID = htmlspecialchars($list_row['DMGTOTALLY']);
			$result_NAME = htmlspecialchars($list_row['DMGPARTIALLY']);
			$result_DISTRICT = htmlspecialchars($list_row['CSLTDEAD']);
			$result_CITY = htmlspecialchars($list_row['CSLTINJURED']);
			$result_MEN = htmlspecialchars($list_row['NICKNAME']);
			$result_WOMEN = htmlspecialchars($list_row['DATEADDED']);
			$result_AREA = htmlspecialchars($list_row['ISVERIFIED']);
			$result_MINORS = htmlspecialchars($list_row['MINORS']);
			$result_ADULTS = htmlspecialchars($list_row['ADULTS']);
			$result_PWD = htmlspecialchars($list_row['NICKNAME']);
			$result_T_HOUSES = htmlspecialchars($list_row['STARTED']);
			$result_C_HOUSES = htmlspecialchars($list_row['COMMENT']);
			$result_C_HOUSES = htmlspecialchars($list_row['DATECREATED']);
			$result_C_HOUSES = htmlspecialchars($list_row['LAT']);
			$result_C_HOUSES = htmlspecialchars($list_row['LNG']);
			$result_C_HOUSES = htmlspecialchars($list_row['RADIUS']);
			$result_C_HOUSES = htmlspecialchars($list_row['ISVERIFIED']);
			$result_L_HOUSES = htmlspecialchars($list_row['ACCEPTED']);
			$result_CL_HOUSES = htmlspecialchars($list_row['NAME']);
			$result_CL_HOUSES = htmlspecialchars($list_row['COLOR']);

	?>
	AddBarangay(<?php echo $result_ID; ?>, '<?php echo $result_NAME; ?>', '<?php echo $result_DISTRICT; ?>', '<?php echo $result_CITY; ?>', '<?php echo $result_MEN; ?>', '<?php echo $result_WOMEN; ?>', '<?php echo $result_POPULATION; ?>', '<?php echo $result_MINORS; ?>', '<?php echo $result_ADULTS; ?>', '<?php echo $result_PWD; ?>', '<?php echo $result_T_HOUSES; ?>', '<?php echo $result_C_HOUSES; ?>', '<?php echo $result_L_HOUSES; ?>', '<?php echo $result_CL_HOUSES; ?>', <?php echo $result_AREA; ?>);
	<?php
		}
	}
	?>
</script>



<!-- UPDATE BARANGAY -->

<script>

	var UBForm = {
        form: document.getElementById('UpdateBarangayForm'),
		id: document.getElementById('UpdateBarangayForm_ID'),
		name: document.getElementById('UpdateBarangayForm_NAME'),
		district: document.getElementById('UpdateBarangayForm_DISTRICT'),
        submit : '#UpdateBarangayForm_SUBMIT',
        modal: '#UpdateBarangayModal',
        msgbox: 'UB_msgbox'
    };

	var UBForm2 = {
		form: document.getElementById('UpdateBarangayForm2'),
		id: document.getElementById('UpdateBarangayForm2_ID'),
		men: document.getElementById('UpdateBarangayForm_MEN'),
		women: document.getElementById('UpdateBarangayForm_WOMEN'),
		minors: document.getElementById('UpdateBarangayForm_MINORS'),
		adults: document.getElementById('UpdateBarangayForm_ADULTS'),
		pwd: document.getElementById('UpdateBarangayForm_PWD'),
		light: document.getElementById('UpdateBarangayForm_LIGHT'),
		concrete: document.getElementById('UpdateBarangayForm_CONCRETE'),
		both: document.getElementById('UpdateBarangayForm_BOTH'),
		area: document.getElementById('UpdateBarangayForm_AREA'),
		infoBtn: document.getElementById('UpdateBarangayForm_INFO'),
		submit : '#UpdateBarangayForm_SUBMIT2',
        modal: '#UpdateBarangayModal2',
        msgbox: 'UB_msgbox2'
	};


	function UpdateBarangay(id, name, district) {
		document.getElementById('Barangay_name_' + id).innerHTML = name;
		document.getElementById('Barangay_district_' + id).innerHTML = district;
	}

	//Fills forms when edit buttons are clicked
	function UpdateFill(id) {
		var name = document.getElementById('Barangay_name_' + id).innerHTML;
		var district = document.getElementById('Barangay_district_' + id).innerHTML;
		for(var i = 0; i < UBForm.district.options.length; i++) {
			if(UBForm.district.options[i].text == district) {
				UBForm.district.selectedIndex = i;
			}
		}
		UBForm.name.value = name;
		UBForm.id.value = id;
	}

	// UBFORM2 HERE


	// Fills Form of UBPForm2. notice area2 
	function UpdateFill2(id) {
		var currentBarangay = document.getElementById('Barangay_name_' + id).innerHTML;
		var currentDistrict = document.getElementById('Barangay_district_' + id).innerHTML;
		var currentCity = document.getElementById('Barangay_city_' + id).innerHTML;
		var currentMen = document.getElementById('Barangay_men_' + id).innerHTML;
		var currentWomen = document.getElementById('Barangay_women_' + id).innerHTML;
		var currentArea = document.getElementById('Barangay_area2_' + id).innerHTML;
		var currentPopulation = document.getElementById('Barangay_population_' + id).innerHTML;
		var currentMinors = document.getElementById('Barangay_minors_' + id).innerHTML;
		var currentAdults = document.getElementById('Barangay_adults_' + id).innerHTML;
		var currentPWD = document.getElementById('Barangay_pwd_' + id).innerHTML;
		var currentT_houses = document.getElementById('Barangay_t_houses_' + id).innerHTML;
		var currentC_houses = document.getElementById('Barangay_c_houses_' + id).innerHTML;
		var currentL_houses = document.getElementById('Barangay_l_houses_' + id).innerHTML;
		var currentCL_houses = document.getElementById('Barangay_cl_houses_' + id).innerHTML;

		UBForm2.id.value = id;
		UBForm2.men.value = currentMen;
		UBForm2.women.value = currentWomen;
		UBForm2.minors.value = currentMinors;
		UBForm2.adults.value = currentAdults;
		UBForm2.pwd.value = currentPWD;
		UBForm2.light.value = currentL_houses;
		UBForm2.concrete.value = currentC_houses;
		UBForm2.both.value = currentCL_houses;
		UBForm2.area.value = currentArea;
	}



		//Async Update Barangay Submit 
	UBForm.form.onsubmit = function(e) {
		e.preventDefault();
		
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(UBForm.submit).button('loading');
			},
			uploadProgress:function(event,position,total,percentCompelete)
			{

			},
			success:function(data)
			{
				$(UBForm.submit).button('reset');
				var server_message = data.trim();
				
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{
					createmessagein(1, 'Successfully updated barangay!', UBForm.msgbox);
					
					UpdateBarangay(GetSuccessMsg(server_message), UBForm.name.value, UBForm.district.options[UBForm.district.selectedIndex].text);
				}
				else if(!isWhitespace(GetWarningMsg(server_message)))
				{
					createmessagein(2, GetWarningMsg(server_message), UBForm.msgbox);
				}
				else if(!isWhitespace(GetErrorMsg(server_message)))
				{
					createmessagein(3, GetErrorMsg(server_message), UBForm.msgbox);
				}
				else if(!isWhitespace(GetServerMsg(server_message)))
				{
					createmessagein(4, GetServerMsg(server_message), UBForm.msgbox);
				}
				else
				{
					createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', UBForm.msgbox);
				}
			}
		});
	};

		//Async Update Barangay 2 Submit 
	UBForm2.form.onsubmit = function(e) {
		e.preventDefault();
		
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(UBForm2.submit).button('loading');
			},
			uploadProgress:function(event,position,total,percentCompelete)
			{

			},
			success:function(data)
			{
				$(UBForm2.submit).button('reset');
				var server_message = data.trim();
				
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{
					createmessagein(1, 'Successfully updated barangay info!', UBForm2.msgbox);
					UBForm2.form.reset();
					UpdateBarangay2(GetSuccessMsg(server_message), parseInt(UBForm2.men.value) + parseInt(UBForm2.women.value));
				}
				else if(!isWhitespace(GetWarningMsg(server_message)))
				{
					createmessagein(2, GetWarningMsg(server_message), UBForm2.msgbox);
				}
				else if(!isWhitespace(GetErrorMsg(server_message)))
				{
					createmessagein(3, GetErrorMsg(server_message), UBForm2.msgbox);
				}
				else if(!isWhitespace(GetServerMsg(server_message)))
				{
					createmessagein(4, GetServerMsg(server_message), UBForm2.msgbox);
				}
				else
				{
					createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', UBForm2.msgbox);
				}
			}
		});
	};


</script>



	function AddBarangay(id, name, district, city, population, minors, adults, pwd, t_houses, c_houses, l_houses, 

<!-- OVERVIEW BARANGAY AND CHART -->
<script>


function viewChart(id) {

		var currentBarangay = document.getElementById('Barangay_name_' + id).innerHTML;
		var currentDistrict = document.getElementById('Barangay_district_' + id).innerHTML;
		var currentCity = document.getElementById('Barangay_city_' + id).innerHTML;
		var currentMen = document.getElementById('Barangay_men_' + id).innerHTML;
		var currentWomen = document.getElementById('Barangay_women_' + id).innerHTML;
		var currentArea = document.getElementById('Barangay_area_' + id).innerHTML;
		var currentPopulation = document.getElementById('Barangay_population_' + id).innerHTML;
		var currentMinors = document.getElementById('Barangay_minors_' + id).innerHTML;
		var currentAdults = document.getElementById('Barangay_adults_' + id).innerHTML;
		var currentPWD = document.getElementById('Barangay_pwd_' + id).innerHTML;
		var currentT_houses = document.getElementById('Barangay_t_houses_' + id).innerHTML;
		var currentC_houses = document.getElementById('Barangay_c_houses_' + id).innerHTML;
		var currentL_houses = document.getElementById('Barangay_l_houses_' + id).innerHTML;
		var currentCL_houses = document.getElementById('Barangay_cl_houses_' + id).innerHTML;



		document.getElementById("viewModalBrgy").innerHTML = currentBarangay + ', <small>' + currentDistrict+' '+currentCity+'</small>';
		document.getElementById("viewModalArea").innerHTML = currentArea;		
		document.getElementById("viewModalPopulation").innerHTML = 'Total Population: '+currentPopulation;		
		document.getElementById("viewModalPWD").innerHTML = 'Persons With Disabilities: '+ currentPWD;		
		document.getElementById("viewModalHouseTotal").innerHTML = 'Total Houses: '+ currentT_houses;		

		chartResponse(currentMen, currentWomen, currentMinors, currentAdults, currentT_houses, currentC_houses, currentL_houses, currentCL_houses);
}


// PROBABLY MUST BE EVENT 

function chartResponse(men,women,minors,adults,thouse,chouse,lhouse,clhouse)
{
		var ctx = document.getElementById("cassualtiesChart");
		var myChart = new Chart(ctx, {
		    type: 'pie',
		    data: {
		        labels: ["Men", "Women"],
		        datasets: [{
		            label: 'Gender',
		            data: [men,women],
		            backgroundColor: [
		            'rgba(237,153,91,0.8)',
		            'rgba(245,79,85,0.8)'
		           ],
		            borderColor: [
		            'rgba(237,153,91,1)',
		            'rgba(245,79,85,1)',
		            ],
		            borderWidth: 1
		        }]
		    },
		    options: {
		                animation:{
		                    animateScale:true
		                }
		            }
		});


	
		var ctx = document.getElementById("houseType");
		var myChart = new Chart(ctx, {
		    type: 'pie',
		    data: {
		        labels: ["Light", "Concrete", "Both"],
		        datasets: [{
		            label: 'House Type',
		            data: [lhouse, chouse,clhouse],
		            backgroundColor: [
		                'rgba(203,80,80,0.8)',
			            'rgba(237,153,91,0.8)',
			            'rgba(245,79,85,0.8)'
		  ],
		            borderColor: [
		                'rgba(203,80,80,1)',
			            'rgba(237,153,91,1)',
			            'rgba(245,79,85,1)'
			
			  ],
		            borderWidth: 1
		        }]
		    },
		    options: {
		                animation:{
		                    animateScale:true
		                }
		            }
		});

		var ctx = document.getElementById("ageDifference");
		var myChart = new Chart(ctx, {
		    type: 'pie',
		    data: {
		        labels: ["Minor", "Adults"],
		        datasets: [{
		            label: 'Age Difference',
		            data: [minors,adults],
		            backgroundColor: [
		                'rgba(203,80,80,0.8)',
			            'rgba(237,153,91,0.8)'

		     ],
		            borderColor: [
		                'rgba(203,80,80,1)',
			            'rgba(237,153,91,1)'

		            ],
		            borderWidth: 1
		        }]
		    },
		    options: {
		                animation:{
		                    animateScale:true
		                }
		            }
		});

	}




</script>

</body>
</html>