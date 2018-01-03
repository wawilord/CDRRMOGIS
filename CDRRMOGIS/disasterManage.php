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

    <title>Manage Confirmed Disaster | City Disaster Risk Management</title>    
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
<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

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


<div class="modal fade" id="dummy" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="UpdateEvac_Title">DUMMY</h4>
			</div>
			<div class="modal-body">
				<div id="UL_msgbox" tabindex="0"></div>
				
				<h1> DUMMY </h1>
			</div>
			<div class ="modal-footer">
				<form id="UpdateEvacForm" method="post" action="library/form/CdrrmoEvacLocUpdate.php">
					<input type="hidden" name="U_ID" id="UpdateEvac_ID" />
					<input type="hidden" name="U_LAT" id="UpdateEvac_LAT" />
					<input type="hidden" name="U_LNG" id="UpdateEvac_LNG" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
			<form id="AddEvacuationForm" method="post" action="library/form/addEvacForm.php">
				<div class="modal-body">
					<div id="AE_msgbox" tabindex="0"></div>
					
					<!-- Evacuation Name -->
					<div class="input-group input-group">
						<span class="input-group-addon">Evacuation Name</span>
						<input id="AddEvacuationForm_NAME" name="NAME" type="text" class="form-control">
					</div>
					<br />
					
					<!-- Complete Address -->
					<div class="input-group input-group">
						<span class="input-group-addon">Complete Address</span>
						<input id="AddEvacuationForm_ADDRESS" name="ADDRESS" type="text" class="form-control">
					</div>
					<br />
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" id="AddEvacuationForm_SUBMIT" data-loading-text="Adding Evacuation..." class="btn btn-primary">Add</button>
				</div>
			</form>	
		</div>
	</div>
</div>
<!-- Content starts here -->
<?php include('library/html/navbar.php'); ?>

<div class="container">
    <div class="page-header">
		<button class="btn btn-basic pull-right" data-toggle="modal" data-target="#AddEvacuationModal">
			<span class="glyphicon glyphicon-plus"></span> Add Confirmed Disaster
		</button>
        <h3>Manage Confirmed Disasters</h3>
    </div>
	
	<!-- Evacuation Center Search Bar -->
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<form id="SearchForm" method="get">
				<div class="input-group">
					<input id="SearchInput" name="search" type="text" class="form-control" placeholder="Search.." value="" />
					<span class="input-group-btn">
						<button id="SearchSubmit" class="btn btn-basic" type="submit"><span class="glyphicon glyphicon-search"></span>&nbsp Search</button>
					</span>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Disaster Type List -->
	<table class="table table-striped table-hover">
		<thead>
		<tr>
			<th>Disaster Name</th>
			<th>Type</th>
			<th>Started</th>
			<th>Ended</th>
			<th>Report</th>
			<th>Location</th>
			<th>Images</th>
			<th>Comment</th>
			<th>Action</th>
		</tr>
		</thead>
		<tbody id="PageComponent_ECLIST">
		<tr>
            <td id="Evac_name_' + id + '">Yolanda</td>
            <td id="Evac_barangay_' + id + '">Typhoon</td>
            <td>14:00 5/26/17</td>
            <td>10f:00 5/27/17</td>
            <td><button class="btn btn-default" data-toggle="modal" data-target="#dummy"><span class="glyphicon glyphicon-eye-open"></span> View</button></td>
            <td><button class="btn btn-default" data-toggle="modal" data-target="#dummy"><span class="glyphicon glyphicon-map-marker"></span> Location</button></td>
            <td><button class="btn btn-default" data-toggle="modal" data-target="#dummy"><span class="glyphicon glyphicon-eye-open"></span> View</button></td>
            <td><button class="btn btn-default" data-toggle="modal" data-target="#dummy"><span class="glyphicon glyphicon-eye-open"></span> View</button></td>
            <td><button class="btn btn-default" data-toggle="modal" data-target="#dummy"><span class="glyphicon glyphicon-pencil"></span> Edit</button></td>
          </tr>

          <tr>
			 <td id="Evac_name_' + id + '">Frank</td>
            <td id="Evac_barangay_' + id + '">Typhoon</td>
            <td>14:00 5/26/17</td>
            <td>10f:00 5/27/17</td>
            <td><button class="btn btn-default" data-toggle="modal" data-target="#dummy"><span class="glyphicon glyphicon-eye-open"></span> View</button></td>
            <td><button class="btn btn-default" data-toggle="modal" data-target="#dummy"><span class="glyphicon glyphicon-map-marker"></span> Location</button></td>
            <td><button class="btn btn-default" data-toggle="modal" data-target="#dummy"><span class="glyphicon glyphicon-eye-open"></span> View</button></td>
            <td><button class="btn btn-default" data-toggle="modal" data-target="#dummy"><span class="glyphicon glyphicon-eye-open"></span> View</button></td>
            <td><button class="btn btn-default" data-toggle="modal" data-target="#dummy"><span class="glyphicon glyphicon-pencil"></span> Edit</button></td>
          </tr>

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

<!-- Map Script -->


<script>
  var PageComponent = {
        eclist: document.getElementById('PageComponent_ECLIST')
    };
	
 var AEForm = {  //Add Evacuation Form
        form: document.getElementById('AddEvacuationForm'),
        name: document.getElementById('AddEvacuationForm_NAME'),
        address: document.getElementById('AddEvacuationForm_ADDRESS'),
        submit: '#AddEvacuationForm_SUBMIT',
        modal: '#AddEvacuationModal',
        msgbox: 'AE_msgbox'
    };

	function ResetAEForm() {
        AEForm.name.value = '';
        AEForm.address.value = '';
    }
	
	AEForm.form.onsubmit = function (e) {
        e.preventDefault();
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
					createmessagein(1, "Evacuation Center Successfully Added!", AEForm.msgbox);
					AddEvac(GetSuccessMsg(server_message), AEForm.name.value, AEForm.address.value);
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

    // ADD EVAC
    function AddEvac(id, name, barangay, address) {
        PageComponent.eclist.innerHTML = PageComponent.eclist.innerHTML +
   //          '<tr>' +
   //          '   <td id="Evac_name_' + id + '">' + name + '</td>'+
   //          '   <td id="Evac_barangay_' + id + '">' + barangay + '</td>'+
   //          '   <td id="Evac_address_' + id + '">' + address + '</td>'+
			// '   <td><button id="EditEvac_BTN' + id + '" value="' + id + '" class="btn btn-default" onclick="EditFill(' + id + ')" data-toggle="modal" data-target="#EditEvacuationModal"><span class="glyphicon glyphicon-pencil"></span>&nbspEdit</button></td>'+
   //          '</tr>';
			//onclick="UpdateFill(\'' + id + '\')"
    }


    <?php
	$list_sql = 'SELECT
					ID,
					BARANGAY,
					EVACNAME,
					EVACADDRESS,
					LAT,
					LANG
				FROM
					evacuation_list
				ORDER BY
					EVACNAME';

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
			$result_ADDRESS = htmlspecialchars($list_row['EVACADDRESS']);
	?>
	
	AddEvac(<?php echo $result_ID; ?>,'<?php echo $result_EVACNAME; ?>','<?php echo $result_BARANGAY; ?>','<?php echo $result_ADDRESS; ?>');
	
	<?php
		}
	}
	?>

</script>

</body>
</html>