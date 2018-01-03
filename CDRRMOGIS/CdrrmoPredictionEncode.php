<!DOCTYPE html>
    <?php
    session_start();
    include('library/form/connection.php');
    include ('library/function/functions.php');
    $db = new db();
    $session_USER_FIRSTNAME = htmlspecialchars($_SESSION['USER_FIRSTNAME']);
    $session_USER_MIDDLENAME = htmlspecialchars($_SESSION['USER_MIDDLENAME']);
    $session_USER_LASTNAME = htmlspecialchars($_SESSION['USER_LASTNAME']);
    date_default_timezone_set('Asia/Hong_Kong');
    $time = time();

    $sql = 'SELECT * FROM disaster_type WHERE ID = ' . $_GET["disaster"];
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    $DisasterInfo = $result->fetch_assoc();

    ?>
<!--This Page is for the admin only -->
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
    <link href="css/jquery.datetimepicker.css" rel="stylesheet">
</head>
<body role="document">
<!--NAVBAR HERE-->
<?php include('library/html/navbar.php'); ?>
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1>Forecasting <small> / Forecast Possible Casualties</small></h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h2>
                    <small>Forecast</small><br />
                    <span style="color: <?php echo $DisasterInfo['COLOR']; ?>;" class="glyphicon glyphicon-stop"></span>
                    <?php echo $DisasterInfo['NAME']; ?>
                    <small>in</small>
                </h2>
                <br />
                <form method="get" action="CdrrmoPredictionResult.php">
                    <input style="display: none;" name="disaster" type="text" value="<?php echo $DisasterInfo['ID']; ?>" />
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">District</span>
                        <select id="District" name="District" class="form-control">
                        </select>
                    </div>
                    <br />
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">Barangay</span>
                        <select id="Barangay" name="Barangay" class="form-control">
                        </select>
                    </div>
                    <br />
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">Year</span>
                        <select id="Year" name="Year" class="form-control">
                        </select>
                    </div>
                    <br />
                    <button type="submit" class="btn btn-primary pull-right">Forecast <span class="glyphicon glyphicon-play"></span></button>
                    <div style="clear: both;"></div>
                </form>
            </div>
        </div>
    </div>

</div> <!--Content ends here-->
<!--FOOTER HERE-->
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
    for (var i = new Date().getFullYear(); i < new Date().getFullYear() + 10; i++)
        $('#Year').append($('<option />').val(i).html(i));
    <?php
        $Districts = array();

        $sql = 'SELECT    district.ID,
                          district.NAME 
                FROM      district,
                          barangay
                WHERE     barangay.DISTRICT = district.ID
                GROUP BY  district.ID';
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while($row = $result->fetch_assoc()){
            $row["Barangays"] = array();
            $Districts[] = $row;
        }
        for($i = 0; $i < sizeof($Districts); $i++){
            $sql = 'SELECT      ID,
                                NAME
                    FROM        barangay
                    WHERE       DISTRICT = ' . $Districts[$i]["ID"];
            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);
            while($row = $result->fetch_assoc()){
                $Districts[$i]["Barangays"][] = $row;
            }
        }
    ?>
    var Districts = JSON.parse('<?php echo json_encode($Districts); ?>');
    function FillDistricts() {
        var object = document.getElementById('District');
        object.innerHTML = '<option value="-99">All</option>';
        Districts.forEach(function (district) {
            object.innerHTML += '<option value="' + district.ID + '">' + district.NAME + '</option>';
        });
        object.onchange = function () {
            FillBarangays(object.value);
        };
    }
    function FillBarangays(s_district) {
        var object = document.getElementById('Barangay');
        object.innerHTML = '<option value="-99">All</option>';

        Districts.forEach(function (district) {
            if(district.ID == s_district){
                district.Barangays.forEach(function (barangay) {
                    object.innerHTML += '<option value="' + barangay.ID + '">' + barangay.NAME + '</option>';
                });
            }
        });
    }
    FillDistricts();
    FillBarangays(-99);
</script>
</body>
</html>



