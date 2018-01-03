<?php
session_start();
include ('library/form/CdrrmoOnly.php');
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 10;

$total_sql = 'SELECT * FROM pcr_report WHERE PCRNUMBER LIKE "%'.$search.'%"';
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
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="css/map.css" rel="stylesheet">
	

</head>
<body role="document">

<!-- Modals -->

<!-- Content starts here -->

<?php include('library/html/navbar.php'); ?>

<div class="container">
    <div class="page-header">
		<a class="btn btn-primary pull-right" href="CdrrmoAddPCR.php">
			<span class="glyphicon glyphicon-plus"></span> Add PCR
		</a>
        <h1>PCR Management</h1>
    </div>
	
	<!-- PCR Search Bar -->
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
	
	<!-- PCR List -->
	<table class="table table-striped table-hover">
		<thead>
		<tr>
			<th>PCR No.</th>
			<th>Unit Name</th>
			<th>Date</th>
			<th>Uploader</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody id="PageComponent_PCRLIST">
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
        pcrlist: document.getElementById('PageComponent_PCRLIST')
    };
	
	//Add new pcr to pcr list
	function AddPCR(id, num, unit, date, uploader) {
        PageComponent.pcrlist.innerHTML = PageComponent.pcrlist.innerHTML +
            '<tr>' +
            '   <td id="PCR_PCRnum_' + id + '">' + num + '</td>'+
            '   <td id="PCR_Unit_' + id + '">' + unit + '</td>'+
            '   <td id="PCR_Date_' + id + '">' + date + '</td>'+
            '   <td id="PCR_Uploader_' + id + '">' + uploader + '</td>'+
			'   <td><a id="PCR_ViewBTN_' + id + '" value="' + id + '" class="btn btn-default" href="CdrrmoEditPCR.php?r=' + id + '"><span class="glyphicon glyphicon-share-alt"></span>&nbspView</a></td>'+
            '</tr>';
    }
	
//Fill account table with accounts
<?php
	$sql = 'SELECT * FROM pcr_report WHERE PCRNUMBER LIKE "%'.$search.'%" ORDER BY DATE DESC LIMIT '.$limit.' OFFSET '.$offset;
	
	$result = $db->connection->query($sql);
	$count = mysqli_num_rows($result);
	if($count < 1) {
		echo 'createmessage(4, "No results found.", false);';
	}
	else {
		while ($row = $result->fetch_assoc())
		{
			$result_ID = htmlspecialchars($row['PCRID']);
			$result_NO = htmlspecialchars($row['PCRNUMBER']);
			$result_UNITNAME = htmlspecialchars($row['UNITNAME']);
			$result_DATE = htmlspecialchars($row['DATE']);
			$result_UPLOADER = htmlspecialchars($row['POSTBY']);
    ?>
	AddPCR('<?php echo $result_ID; ?>', '<?php echo $result_NO; ?>', '<?php echo $result_UNITNAME; ?>', '<?php echo $result_DATE; ?>', '<?php echo $result_UPLOADER; ?>');
	<?php
		}
	}
?>
</script>
</body>
</html>