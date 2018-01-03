<!DOCTYPE html>
<?php
session_start();
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();

$session_USER_USERNAME = $_SESSION['USER_USERNAME'];
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
</head>
<body role="document">
<!-- Content starts here -->
<?php include('library/html/navbar.php'); ?>

<div class="container">
    <div class="page-header">
        <h3>
            Update Account
            <small> for account <i><?php echo $session_USER_USERNAME ?></i></small>
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div id="UP_msgbox" tabindex="0"></div>
            <form id="UpdatePassForm" method="post" action="library/form/UpdatePassForm.php">
                <!-- Old Password -->
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">Old Password: </span>
                    <input type="password" id="UpdatePassForm_OLDPASS" name="OLDPASS" maxlength="50" class="form-control" required />
                </div>
                <br />

                <!-- New Password -->
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">New Password: </span>
                    <input type="password" id="UpdatePassForm_NEWPASS1" name="NEWPASS1" maxlength="50" class="form-control" required />
                </div>
                <br />

                <!-- New Password 2 -->
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1">Retype New Password: </span>
                    <input type="password" id="UpdatePassForm_NEWPASS2" name="NEWPASS2" maxlength="50" class="form-control" required />
                </div>
                <br />

                <!-- Submission -->
                <button type="submit" id="UpdatePassForm_SUBMIT" data-loading-text="Update Account..." class="btn btn-primary pull-right">Submit</button>
            </form>
        </div>
    </div>
</div>

<br>

<!--FOOTER HERE-->
<?php include('library/html/footer.php'); ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/1.11.3_jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/app/mylibrary.js"></script>
<script src="js/app/messagealert.js"></script>
<script src="js/jquery.form.min.js"></script>

<script>
    //Update Account Form
	var UPForm = { 
        form: document.getElementById('UpdatePassForm'),
		oldpass: document.getElementById('UpdatePassForm_OLDPASS'),
        newpass1: document.getElementById('UpdatePassForm_NEWPASS1'),
        newpass2: document.getElementById('UpdatePassForm_NEWPASS2'),
        submit : '#UpdatePassFormForm_SUBMIT',
        msgbox: 'UP_msgbox'
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
					createmessagein(1, GetSuccessMsg(server_message), UPForm.msgbox);
                    UPForm.form.reset();
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
			}
		});
	};
</script>

</body>
</html>