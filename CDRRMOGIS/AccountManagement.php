<?php
session_start();
include ('library/form/AdminOnly.php');
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 10;

$total_sql = 'SELECT * FROM barangay AS brgy RIGHT JOIN (SELECT * FROM user_accounts WHERE USERNAME LIKE "%'.$search.'%") AS user ON user.BRGY = brgy.ID';
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
    <title>Account Management</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="css/map.css" rel="stylesheet">
	

</head>
<body role="document">

<!-- Modals -->

<!-- New Account Modal -->
<div class="modal fade" id="AddAccountModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="b">Add New Account</h4>
			</div>
			
			<!-- Add Account Form -->
			<form id="AddAccountForm" method="post" action="library/form/AddAccountForm.php">
				<div class="modal-body">
					<div id="AC_msgbox" tabindex="0"></div>
					
					<!-- Username -->
					<div class="input-group input-group">
						<span class="input-group-addon" id="sizing-addon1">Username: </span>
						<input type="text" id="AddAccountForm_USERNAME" name="USERNAME" maxlength="50" class="form-control" required />
					</div>
					<br />
					
					<!-- Password -->
					<div class="input-group input-group">
						<span class="input-group-addon" id="sizing-addon1">Password: </span>
						<input type="password" id="AddAccountForm_PASSWORD" name="PASSWORD" maxlength="50" class="form-control" required />
					</div>
					<br />
					
					<!-- Account Type -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Account Type: </span>
						<select class="form-control" id="AddAccountForm_TYPE" onchange="AddAccount_TypeChange()" name="TYPE" required>
							<option value="A">Administrator</option>
							<option value="B">CDRRMO</option>
							<option value="B">CSWD</option>
						</select>
					</div>
					<br />
					
					<!-- First Name -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">First Name: </span>
						<input type="text" id="AddAccountForm_FIRSTNAME" name="FIRSTNAME" maxlength="50" class="form-control" required>
					</div>
					<br />
					
					<!-- Middle Name -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Middle Name: </span>
						<input type="text" id="AddAccountForm_MIDDLENAME" name="MIDDLENAME" maxlength="50" class="form-control" required>
					</div>
					<br />
					
					<!-- Last Name -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Last Name: </span>
						<input type="text" id="AddAccountForm_LASTNAME" name="LASTNAME" maxlength="50" class="form-control" required>
					</div>
					<br />
					
					<!-- Barangay -->
					<div class="input-group" id="AddAccountContainer_BARANGAY" style="display: none;">
						<span class="input-group-addon" id="sizing-addon1">Barangay: </span>
						<select class="form-control" id="AddAccountForm_BARANGAY" name="BARANGAY">
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
				</div>
				
				<!-- Submission -->
				<div class="modal-footer">
				<button type="submit" id="AddAccountForm_SUBMIT" data-loading-text="Adding Account..." class="btn btn-primary">Add Account</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Update Account Modal -->
<div class="modal fade" id="UpdateAccountModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Update Account</h4>
			</div>
			
			<!-- Update Account Form -->
			<form id="UpdateAccountForm" method="post" action="library/form/UpdateAccountForm.php">
				<div class="modal-body">
					<div id="UP_msgbox" tabindex="0"></div>
					
					<!-- Username -->
					<div class="input-group input-group" style="display: none;">
						<span class="input-group-addon" id="sizing-addon1">Username: </span>
						<input type="text" id="UpdateAccountForm_USERNAME" name="USERNAME" maxlength="50" class="form-control" required />
					</div>
					<br />
					
					<!-- New Password -->
					<div class="input-group input-group">
						<span class="input-group-addon" id="sizing-addon1">New Password: </span>
						<input type="password" id="UpdateAccountForm_PASSWORD" name="PASSWORD" maxlength="50" class="form-control" placeholder="Input new password" />
					</div>
					<br />
					
					<!-- Account Type -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Account Type: </span>
						<select class="form-control" id="UpdateAccountForm_TYPE" onchange="UpdateAccount_TypeChange()" name="TYPE" required>
							<option value="A">Administrator</option>
							<option value="B">CDRRMO</option>
							<option value="C">CSWD</option>
						</select>
					</div>
					<br />
					
					<!-- First Name -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">First Name: </span>
						<input type="text" id="UpdateAccountForm_FIRSTNAME" name="FIRSTNAME" maxlength="50" class="form-control">
					</div>
					<br />
					
					<!-- Middle Name -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Middle Name: </span>
						<input type="text" id="UpdateAccountForm_MIDDLENAME" name="MIDDLENAME" maxlength="50" class="form-control">
					</div>
					<br />
					
					<!-- Last Name -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Last Name: </span>
						<input type="text" id="UpdateAccountForm_LASTNAME" name="LASTNAME" maxlength="50" class="form-control">
					</div>
					<br />
					
					<!-- Status -->
					<div class="input-group">
						<span class="input-group-addon" id="sizing-addon1">Status: </span>
						<select class="form-control" id="UpdateAccountForm_STATUS" name="STATUS" required>
							<option value="1">Enabled</option>
							<option value="0">Disabled</option>
						</select>
					</div>
					<br />
					
					<!-- Barangay -->
					<div class="input-group" id="UpdateAccountContainer_BARANGAY" style="display: none;">
						<span class="input-group-addon" id="sizing-addon1">Barangay: </span>
						<select class="form-control" id="UpdateAccountForm_BARANGAY" name="BARANGAY">
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
				</div>
				
				<!-- Submission -->
				<div class="modal-footer">
					<button type="submit" id="UpdateAccountForm_SUBMIT" data-loading-text="Updating Account..." class="btn btn-primary">Update Account</button>
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
		<button class="btn btn-secondary pull-right" data-toggle="modal" data-target="#AddAccountModal">
			<span class="glyphicon glyphicon-plus"></span> Add New Account
		</button>
        <h3>Account Management</h3>
    </div>
	
	<!-- Account Search Bar -->
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<form id="SearchForm" method="get">
				<div class="input-group">
					<input id="SearchInput" name="search" type="text" value="<?php echo $search; ?>" class="form-control" placeholder="Search.." />
					<span class="input-group-btn">
						<button id="SearchSubmit" class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span>&nbsp Search</button>
					</span>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Account List -->
	<table class="table table-striped table-hover">
		<thead>
		<tr>
			<th>Username</th>
			<th>Type</th>
			<th>Name</th>
			<th>Status</th>
			<th>Barangay</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody id="PageComponent_ACLIST">
		</tbody>
	</table>
	<div id="pagemessagebox" tabindex="0"></div>
	
	<!-- Pagination -->
	<ul class="pager">
		<li style="<?php echo $bottom_page; ?>">
			<a href="AccountManagement.php?search=<?php echo $search; ?>&page=1" style=""><span class="glyphicon glyphicon-menu-left"></span>&nbsp 1</a>
		</li>
		<li class="<?php echo $disable_previous; ?>">
			<a href="AccountManagement.php?search=<?php echo $search; ?>&page=<?php echo $page - 1; ?>" style="<?php echo $disable_previous2; ?>"><span class="glyphicon glyphicon-menu-left"></span>&nbsp Previous</a>
		</li>
		<li class="disabled">
			<span><h4 style="margin-top: 0.3rem; margin-bottom: 0.3rem;">Page <?php echo $page; ?></h4></span>
		</li>
		<li class="<?php echo $disable_next; ?>">
			<a href="AccountManagement.php?search=<?php echo $search; ?>&page=<?php echo $page + 1; ?>" style="<?php echo $disable_next2; ?>">Next&nbsp <span class="glyphicon glyphicon-menu-right"></span></a>
		</li>
		<li style="<?php echo $top_page; ?>">
			<a href="AccountManagement.php?search=<?php echo $search; ?>&page=<?php echo $total_page; ?>" style=""><?php echo $total_page; ?>&nbsp <span class="glyphicon glyphicon-menu-right"></span></a>
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
        aclist: document.getElementById('PageComponent_ACLIST')
    };
	
	//Add Account Form
	var ACForm = { 
        form: document.getElementById('AddAccountForm'),
		username: document.getElementById('AddAccountForm_USERNAME'),
		password: document.getElementById('AddAccountForm_PASSWORD'),
		type: document.getElementById('AddAccountForm_TYPE'),
		firstname: document.getElementById('AddAccountForm_FIRSTNAME'),
		middlename: document.getElementById('AddAccountForm_MIDDLENAME'),
		lastname: document.getElementById('AddAccountForm_LASTNAME'),
		barangay: document.getElementById('AddAccountForm_BARANGAY'),
        submit : '#AddAccountForm_SUBMIT',
        modal: '#AddAccountModal',
        msgbox: 'AC_msgbox'
    };
	
	//Update Account Form
	var UPForm = { 
        form: document.getElementById('UpdateAccountForm'),
		username: document.getElementById('UpdateAccountForm_USERNAME'),
		password: document.getElementById('UpdateAccountForm_PASSWORD'),
		type: document.getElementById('UpdateAccountForm_TYPE'),
		firstname: document.getElementById('UpdateAccountForm_FIRSTNAME'),
		middlename: document.getElementById('UpdateAccountForm_MIDDLENAME'),
		lastname: document.getElementById('UpdateAccountForm_LASTNAME'),
		status: document.getElementById('UpdateAccountForm_STATUS'),
		barangay: document.getElementById('UpdateAccountForm_BARANGAY'),
        submit : '#UpdateAccountForm_SUBMIT',
        modal: '#UpdateAccountModal',
        msgbox: 'UP_msgbox'
    };
	
	//Reset New Account Form
	function ResetACForm() {
        ACForm.username.value = '';
		ACForm.password.value = '';
		ACForm.type.selectedIndex = 0;
		ACForm.firstname.value = '';
		ACForm.middlename.value = '';
		ACForm.lastname.value = '';
		ACForm.barangay.selectedIndex = 0;
		document.getElementById("AddAccountContainer_BARANGAY").style.display = "none";
    }
	
	//New Account Form: Barangay select is not displayed until Barangay in account type is selected.
	function AddAccount_TypeChange() {
		document.getElementById("AddAccountContainer_BARANGAY").style.display = "none";
		if(ACForm.type.selectedIndex == 3) {
			document.getElementById("AddAccountContainer_BARANGAY").style.display = "inline-table";
		}
	}
	
	//Update Account Form: Barangay select is not displayed until Barangay in account type is selected.
	function UpdateAccount_TypeChange() {
		document.getElementById("UpdateAccountContainer_BARANGAY").style.display = "none";
		if(UPForm.type.selectedIndex == 3) {
			document.getElementById("UpdateAccountContainer_BARANGAY").style.display = "inline-table";
		}
	}
	
	//Add new account to account list
	function AddAccount(username, type, name, status, barangay) {
        PageComponent.aclist.innerHTML = PageComponent.aclist.innerHTML +
            '<tr>' +
            '   <td id="UpdateAccount_username_' + username + '">' + username + '</td>'+
            '   <td id="UpdateAccount_type_' + username + '">' + type + '</td>'+
            '   <td id="UpdateAccount_name_' + username + '">' + name + '</td>'+
            '   <td id="UpdateAccount_status_' + username + '">' + status + '</td>'+
            '   <td id="UpdateAccount_barangay_' + username + '">' + barangay + '</td>'+
			'   <td><button id="UpdateAccount_BTN_' + username + '" value="' + username + '" class="btn btn-default" onclick="UpdateFill(\'' + username + '\')" data-toggle="modal" data-target="#UpdateAccountModal"><span class="glyphicon glyphicon-pencil"></span>&nbspEdit</button></td>'+
            '</tr>';
    }
	
	//Updates account on account list
	function UpdateAccount(username, type, name, status, barangay) {
		document.getElementById('UpdateAccount_type_' + username).innerHTML = type;
		document.getElementById('UpdateAccount_name_' + username).innerHTML = name;
		document.getElementById('UpdateAccount_status_' + username).innerHTML = status;
		document.getElementById('UpdateAccount_barangay_' + username).innerHTML = barangay;
	}
	
	//Fill fields when edit is clicked
	function UpdateFill(username) {
		var fullname = document.getElementById('UpdateAccount_name_' + username).innerHTML;
		var namesplit = fullname.split(", ");
		var lastname = namesplit[0];
		var firstname = namesplit[1];
		var middlename = namesplit[2];
		var type = document.getElementById('UpdateAccount_type_' + username).innerHTML;
		var status = document.getElementById('UpdateAccount_status_' + username).innerHTML;
		var barangay = document.getElementById('UpdateAccount_barangay_' + username).innerHTML;
		switch(type) {
			case 'Administrator':
				UPForm.type.selectedIndex = 0;
				break;
			case 'CDRRMO':
				UPForm.type.selectedIndex = 1;
				break;
			case 'CSWD':
				UPForm.type.selectedIndex = 2;
				break;
			case 'Barangay':
				UPForm.type.selectedIndex = 3;
			break;
		}
		switch(status) {
			case 'Enabled':
				UPForm.status.selectedIndex = 0;
				break;
			case 'Disabled':
				UPForm.status.selectedIndex = 1;
				break;
		}
		UPForm.username.value = username;
		UPForm.firstname.value = firstname;
		UPForm.middlename.value = middlename;
		UPForm.lastname.value = lastname;
		if(barangay != 'None') {
			for(var i = 0; i < UPForm.barangay.options.length; i++) {
				if(UPForm.barangay.options[i].text == barangay) {
					UPForm.barangay.selectedIndex = i;
				}
			}
			document.getElementById("UpdateAccountContainer_BARANGAY").style.display = "inline-table";
		}
		else {
			document.getElementById("UpdateAccountContainer_BARANGAY").style.display = "none";
		}
		
		if(username == '<?php echo $_SESSION['USER_USERNAME']; ?>') {
			UPForm.type.disabled = true;
			UPForm.status.disabled = true;
		}
		else {
			UPForm.type.disabled = false;
			UPForm.status.disabled = false;
		}
	}
	
	//Async New Account Submit 
	ACForm.form.onsubmit = function(e) {
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(ACForm.submit).button('loading');
			},
			uploadProgress:function(event,position,total,percentCompelete)
			{

			},
			success:function(data)
			{
				$(ACForm.submit).button('reset');
				var server_message = data.trim();
				
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{
					var send;
					
					createmessagein(1, GetSuccessMsg(server_message), ACForm.msgbox);
					
					if(ACForm.type.selectedIndex == 3) {
						send = ACForm.barangay.options[ACForm.barangay.selectedIndex].text;
					}
					else {
						send = 'None';
					}
					
					AddAccount(ACForm.username.value, ACForm.type.options[ACForm.type.selectedIndex].text, ACForm.lastname.value + ', ' + ACForm.firstname.value + ', ' + ACForm.middlename.value, 'Enabled', send);
					ResetACForm();
				}
				else if(!isWhitespace(GetWarningMsg(server_message)))
				{
					createmessagein(2, GetWarningMsg(server_message), ACForm.msgbox);
				}
				else if(!isWhitespace(GetErrorMsg(server_message)))
				{
					createmessagein(3, GetErrorMsg(server_message), ACForm.msgbox);
				}
				else if(!isWhitespace(GetServerMsg(server_message)))
				{
					createmessagein(4, GetServerMsg(server_message), ACForm.msgbox);
				}
				else
				{
					createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', ACForm.msgbox);
				}
				ACForm.type.onchange = function() {
					AddAccount_TypeChange();
				};
			}
		});
	};
	
	//Async Update Account Submit
	UPForm.form.onsubmit = function(e) {
		e.preventDefault();
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(UPForm.submit).button('loading');
			},
			uploadProgress:function(event,position,total,percentCompelete)
			{

			},
			success:function(data)
			{
				$(UPForm.submit).button('reset');
				var server_message = data.trim();
				
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{
					var foo;
					
					createmessagein(1, GetSuccessMsg(server_message), UPForm.msgbox);
					
					if(UPForm.type.selectedIndex == 3) {
						foo = UPForm.barangay.options[UPForm.barangay.selectedIndex].text;
					}
					else {
						foo = 'None';
					}
					
					UpdateAccount(UPForm.username.value, UPForm.type.options[UPForm.type.selectedIndex].text, UPForm.lastname.value + ', ' + UPForm.firstname.value + ', ' + UPForm.middlename.value, UPForm.status.options[UPForm.status.selectedIndex].text, foo);
				}
				else if(!isWhitespace(GetWarningMsg(server_message)))
				{
					createmessagein(2, GetWarningMsg(server_message), UPForm.msgbox);
				}
				else if(!isWhitespace(GetErrorMsg(server_message)))
				{
					createmessagein(3, GetErrorMsg(server_message), UPForm.msgbox);
				}
				else if(!isWhitespace(GetServerMsg(server_message)))
				{
					createmessagein(4, GetServerMsg(server_message), UPForm.msgbox);
				}
				else
				{
					createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', UPForm.msgbox);
				}
				UPForm.type.onchange = function() {
					UpdateAccount_TypeChange();
				};
			}
		});
	};
	

//Fill account table with accounts
<?php
	$sql = 'SELECT * FROM barangay AS brgy RIGHT JOIN (SELECT * FROM user_accounts WHERE USERNAME LIKE "%'.$search.'%") AS user ON user.BRGY = brgy.ID ORDER BY user.USERNAME LIMIT '.$limit.' OFFSET '.$offset;
	
	$result = $db->connection->query($sql);
	$count = mysqli_num_rows($result);
	if($count < 1) {
		echo 'createmessage(4, "No results found.", false);';
	}
	else {
		while ($row = $result->fetch_assoc())
		{
			$result_USERNAME = htmlspecialchars($row['USERNAME']);
			$result_TYPE = htmlspecialchars($row['TYPE']);
			$result_NAME = htmlspecialchars($row['LASTNAME'] . ", " . $row['FIRSTNAME'] . ", " . $row['MIDDLENAME']);
			$result_ENABLED = htmlspecialchars($row['ENABLED']);
			$result_BARANGAY = htmlspecialchars($row['NAME']);
			
			switch ($result_TYPE)
			{
				case 'A':
					$result_TYPE = "Administrator";
					break;
				case 'B':
					$result_TYPE = "CDRRMO";
					break;
				case 'C':
					$result_TYPE = "CSWD";
					break;
				case 'D':
					$result_TYPE = "Barangay";
					break;
				Default:
					$result_TYPE = 'Unknown';
					exit;
					break;
			}
			
			if($result_ENABLED == 1) {$result_ENABLED = "Enabled";}
			else {$result_ENABLED = "Disabled";}
			if($result_BARANGAY == "") {$result_BARANGAY = "None";}
			
    ?>
	AddAccount('<?php echo $result_USERNAME; ?>', '<?php echo $result_TYPE; ?>', '<?php echo $result_NAME; ?>', '<?php echo $result_ENABLED; ?>', '<?php echo $result_BARANGAY; ?>');
	<?php
		}
	}
?>

</script>
</body>
</html>