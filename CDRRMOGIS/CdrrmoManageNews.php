<?php
session_start();
include ('library/form/CdrrmoOnly.php');
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();
$session_USER_USERNAME = $_SESSION['USER_USERNAME'];
$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 10;

$total_sql = "SELECT * FROM newsfeed WHERE POSTBY = '$session_USER_USERNAME'";
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
    <title>Newsfeed Management</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="css/map.css" rel="stylesheet">
	

</head>
<body role="document">
<!-- Content starts here -->

<?php include('library/html/navbar.php'); ?>

<div class="container">
    <div class="page-header">
        <h1>Newsfeed</h1>
    </div>
	
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div id="NF_msgbox" tabindex="0"></div>
			<form id="AddNewsForm" method="post" action="library/form/CdrrmoAddNews.php">
				<textarea class="form-control" placeholder="What's happening?" id="AddNewsForm_CONTENT" name="CONTENT" maxlength="200" style="resize:none; margin-bottom:8px;" autofocus></textarea>
				<span style="color:gray;">Post as <b><?php echo $session_USER_USERNAME; ?></b></span>
				<button class="btn btn-info pull-right" type="submit" id="AddNewsForm_SUBMIT" data-loading-text="Sending..">Post <span class="glyphicon glyphicon-send" aria-hidden="true"></span></button><span class="pull-right" id="text-counter" style="margin-top:8px;margin-right:8px;color:gray;">200</span>
			</form>
		</div>
	</div>
	<br />
	
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h2 class="panel-title">Your News</h2>
				</div>
				
				<!-- Latest News -->
				<ul class="list-group" id="news-list">
				
				</ul>
			</div>
		</div>
	</div>
</div>

<!-- 
	<li class="list-group-item">
		<div class="media">
			<div class="media-body">
				<h5><b>cdrrmo</b> <small>March 2, 2016</small></h5>
				Lorem Ipsum dolor sit ames er ti depreticat
			</div>
		</div>
	</li>
-->

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
	var maxlength = 200;
	$('#AddNewsForm_CONTENT').on('keyup keydown', function(e){
		var length = $(this).val().length;
		$('#text-counter').text(maxlength - length);
	});
	
	var PageComponent = {
        nflist: document.getElementById('news-list')
    };
	
	var NFForm = { 
        form: document.getElementById('AddNewsForm'),
		content: document.getElementById('AddNewsForm_CONTENT'),
        submit : '#AddNewsForm_SUBMIT',
        msgbox: 'NF_msgbox'
    };
	
	//Add post to list
	function AddPost(id, username, content, timestamp) {
		PageComponent.nflist.innerHTML += '<li class="list-group-item" id="post_'+ id +'">' +
											'<div class="media"><div class="media-body">' +
											'<h5><b>'+ username +'</b> <small>'+ timestamp +'</small></h5>' +
											content + '</div></div></li>';
	}
	
	//Add post to list inverted
	function AddPostI(id, username, content, timestamp) {
		PageComponent.nflist.innerHTML = '<li class="list-group-item" id="post_'+ id +'">' +
											'<div class="media"><div class="media-body">' +
											'<h5><b>'+ username +'</b> <small>'+ timestamp +'</small></h5>' +
											content + '</div></div></li>' + PageComponent.nflist.innerHTML;
	}
	
	//Async New Post Submit 
	NFForm.form.onsubmit = function(e) {
		e.preventDefault();
		
		$(this).ajaxSubmit({
			beforeSend:function()
			{
				$(NFForm.submit).button('loading');
			},
			uploadProgress:function(event,position,total,percentCompelete)
			{

			},
			success:function(data)
			{
				$(NFForm.submit).button('reset');
				var server_message = data.trim();
				
				if(!isWhitespace(GetSuccessMsg(server_message)))
				{
					createmessagein(1, 'Successfully Posted!', NFForm.msgbox);
					var info = JSON.parse(GetSuccessMsg(server_message));
					AddPostI(info.id, info.postby, info.content, info.timestamp);
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
	
//Fill post list with posts
<?php
	$sql = "SELECT * FROM newsfeed WHERE POSTBY = '$session_USER_USERNAME' ORDER BY POSTDATE DESC LIMIT $limit OFFSET $offset";
	$result = $db->connection->query($sql);
	$count = mysqli_num_rows($result);
	
	if($count < 1) {
		
	}
	else {
		while ($row = $result->fetch_assoc())
		{
			$result_ID = htmlspecialchars($row['ID']);
			$result_CONTENT = htmlspecialchars($row['CONTENT']);
			$result_POSTDATE = htmlspecialchars($row['POSTDATE']);
			$result_POSTBY = htmlspecialchars($row['POSTBY']);
			$result_POSTDATE = strtotime($result_POSTDATE);
?>
			AddPost(<?php echo $result_ID; ?>, '<?php echo $result_POSTBY; ?>', "<?php echo $result_CONTENT; ?>", '<?php echo date('M d, Y H:i', $result_POSTDATE); ?>');
<?php
		}
	}
?>
</script>
</body>
</html>