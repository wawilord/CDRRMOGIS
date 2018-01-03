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

        $sql = 'SELECT          disaster_declare.ID,
                                disaster_declare.BRGY AS BARANGAYID,
                                barangay.NAME AS BARANGAYNAME,
                                disaster_declare.POSTBY AS POSTBYID,
                                user_accounts.FIRSTNAME,
                                user_accounts.MIDDLENAME,
                                user_accounts.LASTNAME,
                                disaster_declare.DISASTER AS DISASTERID,
                                disaster_type.NAME AS DISASTERNAME,
                                disaster_type.COLOR,
                                disaster_declare.NICKNAME,
                                disaster_declare.STARTED,
                                disaster_declare.ENDED,
                                disaster_declare.COMMENT,
                                disaster_declare.LAT,
                                disaster_declare.LNG,
                                disaster_declare.RADIUS,
                                disaster_declare.ISVERIFIED
                FROM            disaster_declare,
                                barangay,
                                disaster_type,
                                user_accounts
                WHERE           disaster_declare.DISASTER = disaster_type.ID
                AND             disaster_declare.BRGY = barangay.ID
                AND             disaster_declare.POSTBY = user_accounts.USERNAME
                AND             disaster_declare.ID = ' . $_GET['id'];
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
        <?php
            if($DisasterInfo['ENDED'] == ''){
            ?>
                <h1 class="red_alert">
                    On-Going Disaster
                </h1>
            <?php
            }
            else{
                ?>
                <h1>Disaster Details
                <span class = "pull-right">
<div class="btn-group"><button id="Barangay_BTN_' + id + '" value="' + id + '" class="btn btn-secondary" data-toggle="modal" data-target="#UpdateBarangayModal" onclick="location.href='viewPreviousDisaster.php'"><span class="glyphicon glyphicon-menu-left"></span> Return to Disaster Profile</button><input type="hidden" class="btn" /></div>
</span></h1>
            <?php
            }
        ?>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <table class="table">
                    <tr style="font-size: 20px;">
                        <th>Disaster:</th>
                        <td>
                            <span class="glyphicon glyphicon-stop" style="color: <?php echo $DisasterInfo['COLOR']; ?>;"></span>
                            <?php echo $DisasterInfo['DISASTERNAME']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Barangay:</th>
                        <td><?php echo $DisasterInfo['BARANGAYNAME']; ?></td>
                    </tr>
                    <tr>
                        <th>Alias:</th>
                        <td><?php echo $DisasterInfo['NICKNAME']; ?></td>
                    </tr>
                    <tr>
                        <th>Started:</th>
                        <td><?php echo converttoformaldatetimestring($DisasterInfo['STARTED']); ?></td>
                    </tr>
                    <?php
                        if($DisasterInfo['ENDED'] != ''){
                            ?>
                                <tr>
                                    <th>Ended:</th>
                                    <td><?php echo converttoformaldatetimestring($DisasterInfo['ENDED']); ?></td>
                                </tr>
                            <?php
                        }
                    ?>
                    <tr>
                        <th>Radius:</th>
                        <td><?php echo $DisasterInfo['RADIUS']; ?> meters</td>
                    </tr>
                    <tr>
                        <th>Coordinate:</th>
                        <td><?php echo $DisasterInfo['LAT']; ?>, <?php echo $DisasterInfo['LNG']; ?></td>
                    </tr>
                    <tr>
                        <th>Declared By:</th>
                        <td><?php echo $DisasterInfo['FIRSTNAME'] . ' ' . $DisasterInfo['MIDDLENAME'] . ' ' . $DisasterInfo['LASTNAME']; ?></td>
                    </tr>
                    <?php
                        if($DisasterInfo['COMMENT'] != ''){
                            ?>
                                <tr>
                                    <th>Comment:</th>
                                    <td><?php echo $DisasterInfo['COMMENT']; ?></td>
                                </tr>
                            <?php
                        }
                    ?>
                </table>
            </div>
            <div class="col-lg-6">
                <div id="map" style="height: 70vh; width: 100%;" tabindex="0"> </div>
            </div>
        </div>
        <div class="row">
            <h4>Accepted Disaster Reports By CSWD</h4>
            <div class="container-fluid">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Dead</th>
                        <th>Injured</th>
                        <th>Missing</th>
                        <th>Totally Damaged Houses</th>
                        <th>Partially Damaged Houses</th>
                        <th>Reported By</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $DisasterReports = array();
                    $sql = 'SELECT              disaster_reports.DMGTOTALLY,
                                                disaster_reports.DMGPARTIALLY,
                                                disaster_reports.CSLTDEAD,
                                                disaster_reports.CSLTINJURED,
                                                disaster_reports.CSLTMISSING,
                                                disaster_reports.DATEADDED,
                                                user_accounts.FIRSTNAME,
                                                user_accounts.MIDDLENAME,
                                                user_accounts.LASTNAME
                            FROM                disaster_reports,
                                                user_accounts
                            WHERE               user_accounts.USERNAME = disaster_reports.UPLOADER
                            AND                 disaster_reports.DECLAREID = ' . $DisasterInfo['ID'];
                    $result = $db->connection->query($sql);
                    $count = mysqli_num_rows($result);
                    while($row = $result->fetch_assoc()){
                        $DisasterReports[] = $row;
                    }

                    foreach ($DisasterReports as $report){
                        ?>
                        <tr>
                            <td><?php echo converttoformaldatetimestring($report['DATEADDED']); ?></td>
                            <td><?php echo $report['CSLTDEAD']; ?></td>
                            <td><?php echo $report['CSLTINJURED']; ?></td>
                            <td><?php echo $report['CSLTMISSING']; ?></td>
                            <td><?php echo $report['DMGTOTALLY']; ?></td>
                            <td><?php echo $report['DMGPARTIALLY']; ?></td></td>
                            <td><?php echo $report['FIRSTNAME'] . ' ' . $report['MIDDLENAME'] . ' ' . $report['LASTNAME']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php
            $Evacuations = array();
            $sql = 'SELECT              evacuation_list.ID,
                                        evacuation_list.EVACNAME
                    FROM                evacuation_list,
                                        evacuation_report
                    WHERE               evacuation_report.EVACID = evacuation_list.ID
                    AND                 evacuation_report.DECLAREID = ' . $DisasterInfo['ID'] . '
                    GROUP BY            evacuation_list.ID';
            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);
            while($row = $result->fetch_assoc()){
                $row['Reports'] = array();
                $sql2 = 'SELECT      evacuation_report.*,
                            user_accounts.FIRSTNAME,
                            user_accounts.MIDDLENAME,
                            user_accounts.LASTNAME
                FROM        evacuation_report,
                            user_accounts
                WHERE       evacuation_report.UPLOADER = user_accounts.USERNAME
                AND         evacuation_report.DECLAREID = ' . $DisasterInfo["ID"] . '
                AND         evacuation_report.EVACID = ' . $row["ID"] . '
                AND         evacuation_report.ISVERIFIED = 1
                GROUP BY    evacuation_report.ID
                ORDER BY    evacuation_report.DATEADDED ASC';
                $result2 = $db->connection->query($sql2);
                while ($row2 = $result2->fetch_assoc()){
                    $row['Reports'][] = $row2;
                }
                $Evacuations[] = $row;
            }
            if($count > 0){
                ?>
                <hr />
                <h4>Accpeted Evacuation Reports by CSWD</h4>
                <div class="container-fluid">
                    <div class="container-fluid">
                        <?php
                            foreach ($Evacuations as $EC){
                                if(sizeof($EC['Reports']) > 0){
                                    ?>
                                    <h4>- <?php echo $EC['EVACNAME']; ?></h4>
                                    <div class="container-fluid">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Reported by</th>
                                                <th>Date Time</th>
                                                <th>Persons Served</th>
                                                <th>Families Served</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($EC['Reports'] as $report){
                                                ?>
                                                <tr>
                                                    <td><?php echo $report['FIRSTNAME'] . ' ' . $report['MIDDLENAME'] . ' ' . $report['LASTNAME']; ?></td>
                                                    <td><?php echo converttoformaldatetimestring($report['DATEADDED']); ?></td>
                                                    <td><?php echo $report['SRVPERSONS']; ?></td>
                                                    <td><?php echo $report['SRVFAMILIES']; ?></td>
                                                    <td>
                                                        <?php
                                                        switch ($report['ISVERIFIED']){
                                                            case 0:
                                                                echo 'Pending';
                                                                break;
                                                            case 1:
                                                                echo 'Accepted';
                                                                break;
                                                            case 2:
                                                                echo 'Declined';
                                                                break;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if(!($report['ISVERIFIED'] != 0 || $report['UPLOADER'] != $_SESSION['USER_USERNAME'])){
                                                            //DisasterReportModal
                                                            ?>
                                                            <button class="btn btn-primary" onclick="ModifyEvacuationReport(<?php echo $report['ID']; ?>, <?php echo $report['SRVPERSONS']; ?>, <?php echo $report['SRVFAMILIES']; ?>, '<?php echo str_replace('-', '/', $report['DATEADDED']); ?>');return false;">Modify</button>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <br />
                                    <?php
                                }
                            }
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
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
    var map = null;
    function initAutocomplete() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: <?php echo $DisasterInfo['LAT']; ?>, lng: <?php echo $DisasterInfo['LNG']; ?>},
            zoom: 16,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [{"featureType": "poi", "stylers": [{"visibility": "off"}]}] //Remove Labels
        });

        new google.maps.Circle({
            strokeColor: "<?php echo $DisasterInfo['COLOR']; ?>",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "<?php echo $DisasterInfo['COLOR']; ?>",
            fillOpacity: 0.35,
            map: map,
            center: {lat: <?php echo $DisasterInfo['LAT']; ?>, lng: <?php echo $DisasterInfo['LNG']; ?>},
            radius: 120,
            clickable: false
        });

        new google.maps.Marker({
            position: {lat: <?php echo $DisasterInfo['LAT']; ?>, lng: <?php echo $DisasterInfo['LNG']; ?>},
            map: map,
            title: 'Disaster',
            animation: google.maps.Animation.BOUNCE
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&key=AIzaSyAuX4dJw6AKx5rbomKRek679WKUACmI9eE" async defer></script>
</body>
</html>



