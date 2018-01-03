<?php
session_start();
include ('library/form/AdminOnly.php');
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();

$barangayID = $_GET['b'];
$b_sql = "SELECT NAME FROM barangay
            WHERE ID = $barangayID";
$b_result = $db->connection->query($b_sql);
$b_row = $b_result->fetch_assoc();
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title>Barangay Info | City Disaster Risk Management</title>    
    <link rel="icon" href="img/favicon.ico">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="css/map.css" rel="stylesheet">
	

</head>
<body role="document">

<!-- Content starts here -->
<?php include('library/html/navbar.php'); ?>

<div class="container">
    <div class="page-header">
        <h2>Brgy. <?php echo $b_row['NAME']; ?> Info
        <span class = "pull-right">
            <button class="btn btn-basic" onclick="location.href='barangayManage.php'"><span class="glyphicon glyphicon-menu-left"></span> Return to View Barangay Data</button>
        </span>
        </h2>
    </div>

    <!-- Info Table -->
    <table class="table table-striped table-hover">
		<thead>
            <tr>
                <th>Date</th>
                <th>Men</th>
                <th>Women</th>
                <th>Minors</th>
                <th>Adults</th>
                <th>PWD</th>
                <th>Light</th>
                <th>Concrete</th>
                <th>Both</th>
                <th>Area</th>
            </tr>
		</thead>
		<tbody id="PageComponent_IFLIST">
		</tbody>
	</table>
    <div id="pagemessagebox" tabindex="0"></div>

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

<!-- Page Script -->
<script>

$(".dbSync").click(function(){
    $.ajax({url: "library/form/BrgyInfoUpdate.php", success: function(result){
        alert('Succesfly sync from Barangay Database');
        location.reload();
    }});
});

    var PageComponent = {
        iflist: document.getElementById('PageComponent_IFLIST')
    };

    function addRow(id, date, men, women, minors, adults, pwd, light, concrete, both, area) {
        var text = `<tr>
                        <td id="info_date_${id}">${date}</td>
                        <td id="info_men_${id}">${men}</td>
                        <td id="info_women_${id}">${women}</td>
                        <td id="info_minors_${id}">${minors}</td>
                        <td id="info_adults_${id}">${adults}</td>
                        <td id="info_pwd_${id}">${pwd}</td>
                        <td id="info_light_${id}">${light}</td>
                        <td id="info_concrete_${id}">${concrete}</td>
                        <td id="info_both_${id}">${both}</td>
                        <td id="info_area_${id}">${area}</td>
                    </tr>`;
        PageComponent.iflist.insertAdjacentHTML('afterbegin', text);
    }

    <?php
    $info_sql = "SELECT * FROM barangay_info
                WHERE BARANGAY = $barangayID";
    $info_result = $db->connection->query($info_sql);
	$info_count = mysqli_num_rows($info_result);
    if($info_count < 1) {
		echo 'createmessage(4, "No results found.", false);';
	}
	else {
        while($info_row = $info_result->fetch_assoc()) {
            $result_ID = htmlspecialchars($info_row['ID']);
            $result_DATE = htmlspecialchars($info_row['DATEADDED']);
            $result_MEN = htmlspecialchars($info_row['MEN']);
            $result_WOMEN = htmlspecialchars($info_row['WOMEN']);
            $result_MINORS = htmlspecialchars($info_row['MINORS']);
            $result_ADULTS = htmlspecialchars($info_row['ADULTS']);
            $result_PWD = htmlspecialchars($info_row['PWD']);
            $result_LIGHT = htmlspecialchars($info_row['L_HOUSES']);
            $result_CONCRETE = htmlspecialchars($info_row['C_HOUSES']);
            $result_BOTH = htmlspecialchars($info_row['CL_HOUSES']);
            $result_AREA = htmlspecialchars($info_row['Area']);

            ?>

            addRow(<?php echo $result_ID; ?>,
                    '<?php echo converttoformaldatetimestring($result_DATE); ?>',
                    <?php echo $result_MEN; ?>,
                    <?php echo $result_WOMEN; ?>,
                    <?php echo $result_MINORS; ?>,
                    <?php echo $result_ADULTS; ?>,
                    <?php echo $result_PWD; ?>,
                    <?php echo $result_LIGHT; ?>,
                    <?php echo $result_CONCRETE; ?>,
                    <?php echo $result_BOTH; ?>,
                    <?php echo $result_AREA; ?>);

            <?php
        }
    }
    ?>

</script>
</body>
</html>