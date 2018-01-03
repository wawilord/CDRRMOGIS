<!-- pass not updated. area probably still returns value @ php -->
<!-- i added only location reload == lazy -->

<?php
session_start();
include ('library/form/AdminOnly.php');
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
barangay_info.AREA AS AREA
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
				<h4 class="modal-title">Enable a Barangay</h4>
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
							
							<!-- NAME -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">Barangay: </span>
								<select class="form-control" id="AddBarangayForm_NAME" name="NAME">
									<?php
									$barangay_sql = "SELECT * FROM barangay ORDER BY NAME";
									$barangay_result = $db->connection->query($barangay_sql);
									while($barangay_row = $barangay_result->fetch_assoc()) {
										?>
										<option value="<?php echo $barangay_row["ID"]; ?>" id="add_district_<?php echo $district_row["ID"]; ?>"><?php echo $barangay_row["NAME"]; ?></option>
										<?php
									}
									?>
								</select>
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

							<!-- City -->
							<div class="input-group">
								<span class="input-group-addon" id="sizing-addon1">District: </span>
								<select class="form-control" id="AddBarangayForm_CITY" name="CITY">
									<?php
									$city_sql = "SELECT * FROM city ORDER BY NAME";
									$city_result = $db->connection->query($city_sql);
									while($city_row = $city_result->fetch_assoc()) {
										?>
										<option value="<?php echo $city_row["ID"]; ?>" id="add_district_<?php echo $district_row["ID"]; ?>"><?php echo $city_row["NAME"]; ?></option>
										<?php
									}
									?>
								</select>
							</div>
							<br />
							
						</div>

					</div>


					 <div class="panel panel-default">
                        <div class="panel-heading">Location</div>
                        <div class="panel-body">
                                    
                    <div class="input-group">
                        <span class="input-group-addon" id="basicasdsaasdon1"><span class="glyphicon glyphicon-pushpin"></span></span>
                        <div class="form-control" style="width: 100%; height:300px;">
                            <div id="map"></div>
                        </div>
                    </div>

                        </div>
                    </div>


					<small> *Demographics are provided after enabling the barangay </small>
					
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
						<input type="double" id="UpdateBarangayForm_AREA" name="AREA" value=0 maxlength="30"  class="form-control" />
					</div>
					<br />
				</div>
				
				<!-- Submission -->
				<div class="modal-footer">
					<button type="submit" id="UpdateBarangayForm_SUBMIT2" data-loading-text="Updating Barangay..." class="btn btn-primary">Update Barangay</button>
					<a href="#" class="btn btn-default" id="UpdateBarangayForm_INFO">View Data</a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			
			</form>
		</div>
	</div>
</div>


<!-- Delete Barangay Modal -->

<div class="modal fade" id="deleteBarangayModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Delete Barangay</h4>
            </div>
            <form id="deleteBarangayForm" method="post" action="library/form/deleteBarangay.php">
                <div class="modal-body">
                    <div id="deleteBarangayForm_msgbox" tabindex="0"></div>
                    <div><input type="text" id="deleteBarangayForm_ID" name="ID" style="display: none;"></div>
                    <h3 id = "deleteBarangayFormDetails"></h3>
                    <hr>
                    <a href="#" class="btn btn-default" id="deleteBarangayForm_VIEWDATA"><span class="glyphicon glyphicon-new-window"></span> View Data</a>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="deleteBarangayForm_submit" class="btn btn-danger" data-loading-text="Deleting..."><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- Content starts here -->
<?php include('library/html/navbar.php'); ?>

<div class="container">
    <div class="page-header">
		<button class="btn btn-basic pull-right" data-toggle="modal" data-target="#AddBarangayModal">
			<span class="glyphicon glyphicon-menu-left"></span> Return to Barangay Management
		</button>
        <h3>Disabled Barangay Management</h3>
        <small>The following data are sourced-out from the Barangay database</small>
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
			<th>ID</th>
			<th>Name</th>
			<th>View Data</th>
			<th>Status</th>
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
<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
   integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
   crossorigin=""></script>

<script type="text/javascript" src="city.js"></script>


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
					
					location.reload();
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

	function AddBarangay(id, name, district, city, men, women, population, minors, adults, pwd, t_houses, c_houses, l_houses, cl_houses, area) {
        PageComponent.brlist.innerHTML = PageComponent.brlist.innerHTML +
            '<tr>' +
            '  <td id="Barangay_name_' + id + '">' + id + '</td>'+  
            '   <td id="Barangay_name_' + id + '">' + name + '</td>'+
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
			'   <td class = "hidden" id="Barangay_area_' + id + '" value="' + id + '">' + area +'</td>'+
			'   <td><div class="btn-group"><button id="Barangay_BTN_' + id + '" value="' + id + '" class="btn btn-default" onclick="viewData(\'' + id + '\')"><span class="glyphicon glyphicon glyphicon-new-window"></span>&nbspView</button><input type="hidden" class="btn" /></div></td>'+
			'   <td><button class="btn btn-default" data-toggle="modal" data-target="#AddBarangayModal">Enable this Barangay</button><td>'+
            '</tr>';
    }


		
	//Fill disaster type table
	<?php
	$list_sql='SELECT 
	barangay.ID, 
	barangay.NAME,
	district.NAME AS DISTRICT, 
	city.NAME AS CITY, 
	barangay_info.MEN AS MEN, 
	barangay_info.WOMEN AS WOMEN,
	barangay_info.AREA AS AREA,
	barangay_info.MINORS AS MINORS,
	barangay_info.ADULTS AS ADULTS,
	barangay_info.PWD AS PWD,
	barangay_info.T_HOUSES AS T_HOUSES,
	barangay_info.C_HOUSES AS C_HOUSES,
	barangay_info.L_HOUSES AS L_HOUSES,
	barangay_info.CL_HOUSES AS CL_HOUSES
	FROM (SELECT * FROM barangay WHERE barangay.NAME LIKE "%'.$search.'%") AS barangay
	LEFT JOIN barangay_info ON barangay_info.BARANGAY = barangay.ID
	LEFT JOIN district ON barangay.DISTRICT = district.ID 
	LEFT JOIN city ON district.CITY = city.ID
	WHERE barangay.ENABLED = 0
	GROUP BY barangay.ID
	ORDER BY barangay.ID
	LIMIT '.$limit.' OFFSET '.$offset;
	
	$list_result = $db->connection->query($list_sql);
	$list_count = mysqli_num_rows($list_result);
	
	if($list_count < 1) {
		echo 'createmessage(4, "No results found.", false);';
	}
	else {
		while ($list_row = $list_result->fetch_assoc()) {
			$result_ID = htmlspecialchars($list_row['ID']);
			$result_NAME = htmlspecialchars($list_row['NAME']);
			$result_DISTRICT = htmlspecialchars($list_row['DISTRICT']);
			$result_CITY = htmlspecialchars($list_row['CITY']);
			$result_MEN = htmlspecialchars($list_row['MEN']);
			$result_WOMEN = htmlspecialchars($list_row['WOMEN']);
			$result_POPULATION = $result_MEN + $result_WOMEN;
			$result_AREA = htmlspecialchars($list_row['AREA']);
			$result_MINORS = htmlspecialchars($list_row['MINORS']);
			$result_ADULTS = htmlspecialchars($list_row['ADULTS']);
			$result_PWD = htmlspecialchars($list_row['PWD']);
			$result_T_HOUSES = htmlspecialchars($list_row['T_HOUSES']);
			$result_C_HOUSES = htmlspecialchars($list_row['C_HOUSES']);
			$result_L_HOUSES = htmlspecialchars($list_row['L_HOUSES']);
			$result_CL_HOUSES = htmlspecialchars($list_row['CL_HOUSES']);
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


	function UpdateFill2(id) {
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

		UBForm2.men.value = currentMen;
		UBForm2.women.value = currentWomen;
		UBForm2.minors.value = currentMinors;
		UBForm2.adults.value = currentAdults;
		UBForm2.pwd.value = currentPWD;
		UBForm2.light.value = currentL_houses;
		UBForm2.concrete.value = currentC_houses;
		UBForm2.both.value = currentCL_houses;
		UBForm2.area.value = currentArea;
		UBForm2.id.value = id;
		UBForm2.infoBtn.href = "brgyinfo.php?b=" + id;
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


	function viewData(id){

		location.href='brgyinfo.php?b='+id;

	}
	


</script>



<!-- DELETE BARANGAY -->
<script>

var dbForm = {
        form: document.getElementById('deleteBarangayForm'),
        modal: document.getElementById('deleteBarangayModal'),
        id: document.getElementById('deleteBarangayForm_ID'),
        details: document.getElementById('deleteBarangayFormDetails'),
        deleteViewData: document.getElementById('deleteBarangayForm_VIEWDATA'),
        msgbox: 'deleteBarangayForm_msgbox',
        submit: document.getElementById('deleteBarangayForm_submit')
    };

function deleteFill(id) {
		var name = document.getElementById('Barangay_name_' + id).innerHTML;
		var city = document.getElementById('Barangay_city_' + id).innerHTML;
		var district = document.getElementById('Barangay_district_'+id).innerHTML;
		dbForm.deleteViewData.href = "brgyinfo.php?b=" + id;
		document.getElementById("deleteBarangayFormDetails").innerHTML = city + ', <small>' + district +' '+city+'</small>';
		dbForm.id.value = id;
		}



 $(dbForm.form).on('submit', function (e) {
        var id = dbForm.id.value;

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(dbForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                $(dbForm.submit).button('reset');
                DisplayMsg(data, dbForm.msgbox, function (SuccessMsg) {
                    location.reload();
                    dbForm.form.reset();
                    $(dbForm.modal).modal('hide');
                });
            }
        });
    });


</script>
</body>
</html>