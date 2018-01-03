<?php
session_start();
include ('library/form/AdminOnly.php');
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 10;

$total_sql = 'SELECT * FROM disaster_type WHERE NAME LIKE "%'.$search.'%"';
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

<!-- New Disaster Type Modal -->
<div class="modal fade" id="AddDisasterTypeModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add New Disaster Type</h4>
			</div>
			
			<!-- New Disaster Type Form -->
			<form id="AddDisasterTypeForm" method="post" action="library/form/AddDisasterTypeForm.php">
				<div class="modal-body">
					<div id="DT_msgbox" tabindex="0"></div>
					
					<h4>Information</h4>
					<!-- Name -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Name: </span>
						<input type="text" id="AddDisasterTypeForm_NAME" name="NAME" maxlength="50" class="form-control" required />
					</div>
					<br />
					
					<!-- Color -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Color: </span>
						<input type="color" id="AddDisasterTypeForm_COLOR" name="COLOR" value="#8C8C8C" class="form-control" required />
					</div>
					<br />
					<hr />
					
					
					<h4>Properties &nbsp<input type="button" class="btn btn-default" value="Add" id="AddDisasterFactor_BTN" /></h4>
					
					<!-- Factors -->
					<div id="factor-group">
						<div class="input-group">
							<span class="input-group-addon" id="sizing-addon1">Name: </span>
							<input type="text" name="FACTORS[]" value="" class="form-control" required />
						</div>	
					</div>
					

					
				</div>
				
				<!-- Submit and Cancel Buttons -->
				<div class="modal-footer">
					<button type="submit" id="AddDisasterTypeForm_SUBMIT" data-loading-text="Adding Disaster Type..." class="btn btn-primary">Add Disaster Type</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
				
			</form>
			
		</div>
	</div>
</div>

<!-- Update Disaster Type Modal -->
<div class="modal fade" id="UpdateDisasterTypeModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Update Disaster Type</h4>
			</div>
			
			<!-- Update Disaster Type Form -->
			<form id="UpdateDisasterTypeForm" method="post" action="library/form/UpdateDisasterTypeForm.php">
				<div class="modal-body">
					<div id="UD_msgbox" tabindex="0"></div>
					
					<!-- Hidden ID -->
					<div class="input-group input-group" style="display:none">
						<span class="input-group-addon" id="sizing-addon1">ID: </span>
						<input type="text" id="UpdateDisasterTypeForm_ID" name="ID" maxlength="50" class="form-control" />
					</div>
					<br />
					
					<!-- Name -->
					<div class="input-group input-group">
						<span class="input-group-addon" id="sizing-addon1">Name: </span>
						<input type="text" id="UpdateDisasterTypeForm_NAME" name="NAME" maxlength="50" class="form-control" required />
					</div>
					<br />
					
					<!-- Color -->
					<div class="input-group input-group">
						<span class="input-group-addon" id="sizing-addon1">Color: </span>
						<input type="color" id="UpdateDisasterTypeForm_COLOR" name="COLOR" class="form-control" required />
					</div>
					<br />
					
					<!-- Status -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Status: </span>
						<select class="form-control" id="UpdateDisasterTypeForm_STATUS" name="STATUS" required>
							<option value="1">Enabled</option>
							<option value="0">Disabled</option>
						</select>
					</div>
					<br />
					
				</div>
			
				<!-- Submit and Cancel Buttons -->
				<div class="modal-footer">
					<button type="submit" id="UpdateDisasterTypeForm_SUBMIT" data-loading-text="Updating Disaster Type..." class="btn btn-primary">Update Disaster Type</button>
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
		<button class="btn btn-secondary pull-right" data-toggle="modal" data-target="#AddDisasterTypeModal">
			<span class="glyphicon glyphicon-plus"></span> Add New Disaster Type
		</button>
        <h3>Disaster Type Management</h3>
    </div>
	
	<!-- Disaster Type Search Bar -->
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
			<th>Name</th>
			<th>Color</th>
			<th>Enabled</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody id="PageComponent_DTLIST">
		</tbody>
	</table>
	<div id="pagemessagebox" tabindex="0"></div>
	
	<!-- Pagination -->
	<ul class="pager">
		<li style="<?php echo $bottom_page; ?>">
			<a href="DisasterTypeManage.php?search=<?php echo $search; ?>&page=1" style=""><span class="glyphicon glyphicon-menu-left"></span>&nbsp 1</a>
		</li>
		<li class="<?php echo $disable_previous; ?>">
			<a href="DisasterTypeManage.php?search=<?php echo $search; ?>&page=<?php echo $page - 1; ?>" style="<?php echo $disable_previous2; ?>"><span class="glyphicon glyphicon-menu-left"></span>&nbsp Previous</a>
		</li>
		<li class="disabled">
			<span><h4 style="margin-top: 0.3rem; margin-bottom: 0.3rem;">Page <?php echo $page; ?></h4></span>
		</li>
		<li class="<?php echo $disable_next; ?>">
			<a href="DisasterTypeManage.php?search=<?php echo $search; ?>&page=<?php echo $page + 1; ?>" style="<?php echo $disable_next2; ?>">Next&nbsp <span class="glyphicon glyphicon-menu-right"></span></a>
		</li>
		<li style="<?php echo $top_page; ?>">
			<a href="DisasterTypeManage.php?search=<?php echo $search; ?>&page=<?php echo $total_page; ?>" style=""><?php echo $total_page; ?>&nbsp <span class="glyphicon glyphicon-menu-right"></span></a>
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

<script>
    var PageComponent = {
        dtlist: document.getElementById('PageComponent_DTLIST')
    };
	
	//Add Disaster Type Form
	var DTForm = {
		form: document.getElementById('AddDisasterTypeForm'),
		name: document.getElementById('AddDisasterTypeForm_NAME'),
		color: document.getElementById('AddDisasterTypeForm_COLOR'),
		submit : '#AddDisasterTypeForm_SUBMIT',
		modal: '#AddDisasterTypeModal',
        msgbox: 'DT_msgbox'
	};
	
	//Update Disaster Type Form
	var UDForm = {
		form: document.getElementById('UpdateDisasterTypeForm'),
		id: document.getElementById('UpdateDisasterTypeForm_ID'),
		name: document.getElementById('UpdateDisasterTypeForm_NAME'),
		color: document.getElementById('UpdateDisasterTypeForm_COLOR'),
		status: document.getElementById('UpdateDisasterTypeForm_STATUS'),
		submit : '#UpdateDisasterTypeForm_SUBMIT',
		modal: '#UpdateDisasterTypeModal',
        msgbox: 'UD_msgbox'
	};

	//Add New Factor
	$("#AddDisasterFactor_BTN").on("click", function () {
		var content = '<div class="input-group fac-add">' + 
					  '<span class="input-group-addon" id="sizing-addon1">Name: </span>' +
					  '<input type="text" name="FACTORS[]" value="" class="form-control" required />' +
					  '<span class="input-group-btn"><input type="button" class="btn btn-default fac-remove" value="Remove" /></span></div>';
		$("#factor-group").append(content);
	});

	$(document).on("click", ".fac-remove", function () {	
		$(this).parents('.fac-add').remove();
	});
	
	//Reset New Disaster Type Form
	function ResetDTForm() {
        DTForm.name.value = '';
		DTForm.color.value = '';
	}
	
	//Add disaster type to list
	function AddDisasterType(id, name, color, status) {
        PageComponent.dtlist.innerHTML = PageComponent.dtlist.innerHTML +
            '<tr>' +
            '   <td id="DisasterType_name_' + id + '">' + name + '</td>'+
            '   <td><span id="DisasterType_color_' + id + '" class="glyphicon glyphicon-stop" style="color:' + color + '"></span></td>'+
            '   <td id="DisasterType_status_' + id + '">' + status + '</td>'+
			'   <td><button id="DisasterType_BTN_' + id + '" value="' + id + '" class="btn btn-default" onclick="UpdateFill(\'' + id + '\')" data-toggle="modal" data-target="#UpdateDisasterTypeModal"><span class="glyphicon glyphicon-pencil"></span>&nbspEdit</button></td>'+
            '</tr>';
    }
	
	//Updates disaster type on list
	function UpdateDisasterType(id, name, color, status) {
		document.getElementById('DisasterType_name_' + id).innerHTML = name;
		document.getElementById('DisasterType_color_' + id).style.color = color;
		document.getElementById('DisasterType_status_' + id).innerHTML = status;
	}
	
	//Converts RBG color format to hex
	function rgb2hex(rgb) {
		rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
		function hex(x) {
			return ("0" + parseInt(x).toString(16)).slice(-2);
		}
		return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
	}
	
	//Fill fields when edit is clicked
	function UpdateFill(id) {
		var name = document.getElementById('DisasterType_name_' + id).innerHTML;
		var color = document.getElementById('DisasterType_color_' + id).style.color;
		var status = document.getElementById('DisasterType_status_' + id).innerHTML;
		switch(status) {
			case 'Enabled':
				UDForm.status.selectedIndex = 0;
				break;
			case 'Disabled':
				UDForm.status.selectedIndex = 1;
				break;
		}
		UDForm.id.value = id;
		UDForm.name.value = name;
		UDForm.color.value = rgb2hex(color);
	}
	
	//Async New Disaster Type Submit
	DTForm.form.onsubmit = function(e) {
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(DTForm.submit).button('loading');
			},
			uploadProgress:function(event,position,total,percentCompelete)
			{

			},
			success:function(data)
			{
				$(DTForm.submit).button('reset');
				var server_message = data.trim();
				
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{
					createmessagein(1, 'Disaster type successfully added!', DTForm.msgbox);
					
					AddDisasterType(GetSuccessMsg(server_message), DTForm.name.value, DTForm.color.value, 'Enabled');
					ResetDTForm();
				}
				else if(!isWhitespace(GetWarningMsg(server_message)))
				{
					createmessagein(2, GetWarningMsg(server_message), DTForm.msgbox);
				}
				else if(!isWhitespace(GetErrorMsg(server_message)))
				{
					createmessagein(3, GetErrorMsg(server_message), DTForm.msgbox);
				}
				else if(!isWhitespace(GetServerMsg(server_message)))
				{
					createmessagein(4, GetServerMsg(server_message), DTForm.msgbox);
				}
				else
				{
					createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', DTForm.msgbox);
				}
			}
		});
	};
	
	//Async Update Disaster Type Submit
	UDForm.form.onsubmit = function(e) {
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(UDForm.submit).button('loading');
			},
			uploadProgress:function(event,position,total,percentCompelete)
			{

			},
			success:function(data)
			{
				$(UDForm.submit).button('reset');
				var server_message = data.trim();
				
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{
					createmessagein(1, 'Disaster type successfully updated!', UDForm.msgbox);
					
					UpdateDisasterType(GetSuccessMsg(server_message), UDForm.name.value, UDForm.color.value, UDForm.status.options[UDForm.status.selectedIndex].text);
				}
				else if(!isWhitespace(GetWarningMsg(server_message)))
				{
					createmessagein(2, GetWarningMsg(server_message), UDForm.msgbox);
				}
				else if(!isWhitespace(GetErrorMsg(server_message)))
				{
					createmessagein(3, GetErrorMsg(server_message), UDForm.msgbox);
				}
				else if(!isWhitespace(GetServerMsg(server_message)))
				{
					createmessagein(4, GetServerMsg(server_message), UDForm.msgbox);
				}
				else
				{
					createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', UDForm.msgbox);
				}
			}
		});
	};
	
	//Fill disaster type table
	<?php
	$list_sql = 'SELECT * FROM disaster_type WHERE NAME LIKE "%'.$search.'%" ORDER BY NAME LIMIT ' .$limit. ' OFFSET '.$offset;
	$list_result = $db->connection->query($list_sql);
	$list_count = mysqli_num_rows($list_result);
	
	if($list_count < 1) {
		echo 'createmessage(4, "No results found.", false);';
	}
	else {
		while ($list_row = $list_result->fetch_assoc()) {
			$result_ID = htmlspecialchars($list_row['ID']);
			$result_NAME = htmlspecialchars($list_row['NAME']);
			$result_COLOR = htmlspecialchars($list_row['COLOR']);
			$result_ENABLED = htmlspecialchars($list_row['ENABLED']);
			
			if($result_ENABLED == 1) {$result_ENABLED = "Enabled";}
			else {$result_ENABLED = "Disabled";}
	?>
	
	AddDisasterType(<?php echo $result_ID; ?>,'<?php echo $result_NAME; ?>','<?php echo $result_COLOR; ?>','<?php echo $result_ENABLED; ?>');
	
	<?php
		}
	}
	?>
</script>
</body>
</html>