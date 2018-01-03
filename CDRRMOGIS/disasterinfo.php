<?php
    include('library/form/connection.php');
    include('library/function/functions.php');
    $db = new db();
    session_start();

    $total_population = 0;
    $affected = 0;

    $sql = 'SELECT 	disaster_declare.NICKNAME,
                    disaster_declare.STARTED,
                    disaster_declare.ENDED,
                    disaster_declare.LAT,
                    disaster_declare.LNG,
                    disaster_declare.RADIUS,
                    disaster_declare.ISVERIFIED,
                    barangay.ID AS BRGYID,
                    barangay.NAME AS BRGYNAME,
                    district.NAME AS DISTRICTNAME,
                    disaster_type.NAME AS DISASTER,
                    disaster_type.ID AS DISASTERID,
                    disaster_type.COLOR
                    
            FROM 	disaster_declare,
                    barangay,
                    district,
                    disaster_type
            
            WHERE	disaster_declare.DISASTER = disaster_type.ID
            AND		disaster_declare.BRGY = barangay.ID
            AND		barangay.DISTRICT = district.ID
            AND     disaster_declare.ID = ' . $_GET['id'] . '
            GROUP BY	disaster_declare.ID';
    $result = $db->connection->query($sql);
    $info = $result->fetch_assoc();

    $sql = "SELECT 			MEN,
                            WOMEN 
            FROM 			barangay_info
            WHERE 			BARANGAY = " . $info['BRGYID'] . "
            ORDER BY		DATEADDED DESC
            LIMIT 			0,1";
    $result = $db->connection->query($sql);
    $row = $result->fetch_assoc();
    $total_population += (int)$row['MEN'];
    $total_population += (int)$row['WOMEN'];

    $brgypath = array();
    $sql = 'SELECT * FROM barangay_coordinates WHERE BARANGAY=' . $info['BRGYID'];
    $result = $db->connection->query($sql);
    while($row = $result->fetch_assoc()){
        $brgypath[] = array(
            "lat"=>(double)$row['LAT'],
            "lng"=>(double)$row['LNG']
        );
    }

    $sql = "SELECT 			* 
            FROM 			disaster_reports 
            WHERE 			DECLAREID = " . $_GET['id'] . "
            AND				ISVERIFIED = 1
            ORDER BY		DATEADDED DESC
            LIMIT			0,1";
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    $report = null;
    if($count > 0){
        $report = $result->fetch_assoc();
        $affected += (int)$report['CSLTDEAD'];
        $affected += (int)$report['CSLTINJURED'];
        $affected += (int)$report['CSLTMISSING'];
    }

    $evaclist = array();
    $sql = "SELECT 			evacuation_report.*,
                            evacuation_list.EVACNAME,
                            evacuation_list.EVACADDRESS1
            
            FROM 			evacuation_report,
                            evacuation_list
            
            WHERE 			evacuation_report.EVACID = evacuation_list.ID
            AND				evacuation_report.ISVERIFIED = 1
            AND             evacuation_report.DECLAREID = " . $_GET['id'] . "
                            
            GROUP BY		evacuation_report.EVACID";
    $result = $db->connection->query($sql);
    while($row = $result->fetch_assoc()){
        $evaclist[] = array(
            "ID"=>$row['EVACID'],
            "NAME"=>$row['EVACNAME'],
            "ADDRESS"=>$row['EVACADDRESS1'],
            "REPORTS"=>array()
        );
    }
    $i = 0;
    foreach ($evaclist as $evac){
        $sql = "SELECT 			*
                FROM 			evacuation_report
                WHERE 			EVACID = ". $evac['ID'] ."
                AND				ISVERIFIED = 1
                AND             DECLAREID = " . $_GET['id'];
        $result = $db->connection->query($sql);
        while($row = $result->fetch_assoc()){
            $evaclist[$i]['REPORTS'][] = $row;
        }
        $i++;
    }

?>
<!DOCTYPE html>
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

<!--LOGIN MODAL HERE-->
<?php include('library/html/loginmodal.php'); ?>
<!--NAVBAR HERE-->
<?php include('library/html/navbar.php'); ?>
  <div class="container"> <!--Content starts here-->
      <div class="page-header">
          <h1><span class="glyphicon glyphicon-stop" style="color: <?php echo $info['COLOR']; ?>;"></span> <?php echo $info['DISASTER']; ?>
              <small>
                  in <?php echo 'Brgy. ' . $info['BRGYNAME'] . ', ' . $info['DISTRICTNAME']; ?>
              </small>
          </h1>
      </div>
      <div class="row">
          <div class="col-lg-6">
              <div id="map" style="height: 70vh; width: 100%;" tabindex="0"> </div>
          </div>
          <div class="col-lg-6">
              <h2><small>Disaster:</small> <a href="disasterview.php?id=<?php echo $info['DISASTERID']; ?>"><?php echo $info['DISASTER']; ?></a></h2>
              <h3><small>Location:</small> <a href="barangayview.php?id=<?php echo $info['BRGYID']; ?>" ><?php echo 'Brgy. ' . $info['BRGYNAME'] . ', ' . $info['DISTRICTNAME']; ?></a></h3>
              <h4><small>Started:</small> <?php echo converttoformaldatetimestring($info['STARTED']); ?></h4>
              <h4><small>Radius:</small> <?php echo round($info['RADIUS'], 2); ?> meters</h4>
              <h4>
                  <small>Disaster Status:</small>
                  <?php
                        if($info['ENDED'] == ''){
                            echo 'On-going';
                        }
                        else {
                            echo 'Ended (' . converttoformaldatetimestring($info['ENDED']) . ')';
                        }
                  ?>
              </h4>
              <h4>
                  <small>Available Report/s:</small>
                  <?php
                  if($info['ISVERIFIED'] == '0' || $info['ISVERIFIED'] == 0){
                      echo 'Partial <small>(Not available for public)</small>';
                  }
                  else {
                      echo 'Complete <small>(Available for public)</small>';
                  }
                  ?>
              </h4>
              <h4><small>Affected people:</small> <?php echo round($affected/$total_population, 6); ?> % of <?php echo $total_population ?></h4>
              <div id="Donut-Chart" style="height: 20vw; padding: 0; margin: 0;"></div>
          </div>
      </div>
      <hr />
      <div class="row">
          <div class="col-lg-6">
              <h3>Final Report</h3>
              <?php
                  if($report != null && ($info['ISVERIFIED'] == 1 || $info['ISVERIFIED'] == '1')){
                      ?>
                          <div class="container-fluid">
                              <h4>Casualties:</h4>
                              <div class="container-fluid">
                                  <table class="table">
                                      <tr>
                                          <td>Dead:</td>
                                          <td><?php echo $report['CSLTDEAD']; ?></td>
                                      </tr>
                                      <tr>
                                          <td>Injured:</td>
                                          <td><?php echo $report['CSLTINJURED']; ?></td>
                                      </tr>
                                      <tr>
                                          <td>Missing:</td>
                                          <td><?php echo $report['CSLTMISSING']; ?></td>
                                      </tr>
                                  </table>
                              </div>
                              <h4>House/Building Damages:</h4>
                              <div class="container-fluid">
                                  <table class="table">
                                      <tr>
                                          <td>Totally:</td>
                                          <td><?php echo $report['DMGTOTALLY']; ?></td>
                                      </tr>
                                      <tr>
                                          <td>Partially:</td>
                                          <td><?php echo $report['DMGPARTIALLY']; ?></td>
                                      </tr>
                                  </table>
                              </div>
                          </div>
                      <?php
                  }
                  else{
                      ?>
                          <p>Not Available.</p>
                      <?php
                  }
              ?>
          </div>
          <div class="col-lg-6">
              <h3>Evcuation Center Timeline Status:</h3>
              <?php
                    if(sizeof($evaclist) < 1){
                        echo '<p>No Evacuation Center being used.</p>';
                    }
              ?>
              <div class="container-fluid">
                  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                      <?php
                            $first = true;
                           foreach ($evaclist as $evac){
                               ?>
                                    <div class="panel panel-default">

                                   <div class="panel-heading" role="tab">
                                       <h4 class="panel-title">
                                           <a role="button" data-toggle="collapse" data-parent="#accordion" href="#Evac_<?php echo $evac['ID']; ?>" aria-expanded="true">
                                               <?php echo $evac['NAME']; ?> - <small><?php echo $evac['ADDRESS']; ?></small>
                                           </a>
                                       </h4>
                                   </div>

                                   <div id="Evac_<?php echo $evac['ID']; ?>" class="panel-collapse collapse<?php if($first){ echo ' in'; $first = false; } ?>" role="tabpanel">
                                       <div class="panel-body">
                                           <table class="table">
                                               <tr>
                                                   <th>Time</th>
                                                   <th>Served Persons</th>
                                                   <th>Served Families</th>
                                               </tr>
                                               <?php
                                                    foreach ($evac['REPORTS'] as $report){
                                                    ?>
                                                        <tr>
                                                            <td><?php echo converttoformaldatetimestring($report['DATEADDED']); ?></td>
                                                            <td><?php echo $report['SRVPERSONS']; ?></td>
                                                            <td><?php echo $report['SRVFAMILIES']; ?></td>
                                                        </tr>
                                                    <?php
                                               }
                                               ?>
                                           </table>
                                       </div>
                                   </div>
                               </div>
                                <?php
                           }
                      ?>

                  </div>
              </div>
          </div>
      </div>
  </div>

<!--FOOTER HERE-->
<?php include('library/html/footer.php'); ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/1.11.3_jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/app/mylibrary.js"></script>
<script src="js/app/messagealert.js"></script>
<script src="js/app/loginscript.js"></script>
<script>
    var map = null;
    var polygon = null;
    var DisasterCircle = null;
    var DisasterDot = null;
    var marker = null;
    var infowindow = null;
    function initAutocomplete() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 10.7149629, lng: 122.5476471},
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [{"featureType": "poi", "stylers": [{ "visibility": "off" }]}] //Remove Labels
        });
        polygon = new google.maps.Polygon({
            paths: JSON.parse('<?php echo json_encode($brgypath); ?>'),
            strokeColor: 'black',
            strokeOpacity: 0.2,
            strokeWeight: 2,
            fillColor: 'black',
            fillOpacity: .2,
            clickable: false
        });
        polygon.setMap(map);
        DisasterCircle = new google.maps.Circle({
            strokeColor: '<?php echo $info["COLOR"]; ?>',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '<?php echo $info["COLOR"]; ?>',
            fillOpacity: 0.35,
            map: map,
            center: {lat:<?php echo $info['LAT']; ?>, lng: <?php echo $info['LNG']; ?>},
            radius: <?php echo $info['RADIUS'] ?>
        });
        DisasterCircle.addListener('click', function () {
            infowindow.open(map, marker);
            map.fitBounds(MyBounds);
            map.setCenter(marker.getPosition());
        });
        DisasterDot = new google.maps.Circle({
            strokeColor: '<?php echo $info["COLOR"]; ?>',
            strokeOpacity: 1,
            strokeWeight: 5,
            fillColor: 'white',
            fillOpacity: 1,
            map: map,
            center: {lat:<?php echo $info['LAT']; ?>, lng: <?php echo $info['LNG']; ?>},
            radius: 0
        });
        marker = new google.maps.Marker({
            position: {lat:<?php echo $info['LAT']; ?>, lng: <?php echo $info['LNG']; ?>},
            map: map,
            visible: false
        });
        infowindow = new google.maps.InfoWindow({
            content: 'Location of <?php echo $info["DISASTER"]; ?> <span class="glyphicon glyphicon-stop" style="color: <?php echo $info['COLOR']; ?>;"></span>'
        });
        infowindow.open(map, marker);
        var MyBounds = new google.maps.LatLngBounds();
        var paths = polygon.getPaths();
        paths.forEach(function(path) {
            var ar = path.getArray();
            for(var i = 0, l = ar.length;i < l; i++) {
                MyBounds.extend(ar[i]);
            }
        });
        map.fitBounds(MyBounds);
    }

</script>

<script>
    var dir = document.URL.substr(0,document.URL.lastIndexOf('/'));
    $(document).ready(function() {
        //Morris charts snippet - js
        $.getScript(dir + '/js/app/raphael-min.js',function(){
            $.getScript(dir + '/js/app/morris.min.js',function(){
                Morris.Donut({
                    element: 'Donut-Chart',
                    data: [
                        {label: "Affected", value: <?php echo $affected; ?>},
                        {label: "Unaffected", value: <?php echo $total_population - $affected; ?>}         ]
                });
            });
        });
    });
</script>

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&key=AIzaSyAuX4dJw6AKx5rbomKRek679WKUACmI9eE" async defer></script>
</body>
</html>