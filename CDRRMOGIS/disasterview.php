<?php
    include('library/form/connection.php');
    include('library/function/functions.php');
    $db = new db();
    session_start();


    $sql = 'SELECT * FROM disaster_type WHERE ID =' . $_GET['id'];
    $result = $db->connection->query($sql);
    $info = $result->fetch_assoc();
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
  <style>
    .progress-bar{
      background-image: none;
    }
  </style>
</head>
<body role="document">

<!--LOGIN MODAL HERE-->
<?php include('library/html/loginmodal.php'); ?>
<!--NAVBAR HERE-->
<?php include('library/html/navbar.php'); ?>
  <div class="container"> <!--Content starts here-->
      <div class="page-header" style="background: linear-gradient(178deg, white, white, white, <?php echo hex2rgba($info['COLOR'], .5); ?>);">
          <h1>Disasters <small>- <?php echo $info['NAME']; ?></small>
          <span class = "pull-right">
              <button class="btn btn-basic" onclick="location.href='disaster.php'"><span class="glyphicon glyphicon-menu-left"></span> Return to Disaster Statistics</button>
          </span>
          </h1>
      </div>
      <div class="row">
          <div class="col-lg-6">
              <h4>Info:</h4>
              <?php
              $sql = 'SELECT ID FROM disaster_declare WHERE DISASTER = ' . $info['ID'] . ' AND ISVERIFIED = 1';
              $result = $db->connection->query($sql);
              $count = mysqli_num_rows($result);
              if($count > 0) {
                  $declares = array();
                  while ($row = $result->fetch_assoc()) {
                      $declares[] = $row;
                  }
                  $death = 0;
                  $totally = 0;
                  $partially = 0;
                  $missing = 0;
                  $injured = 0;

                  foreach ($declares as $declare) {
                      $sql = 'SELECT * 
                            FROM disaster_reports 
                            WHERE DECLAREID = ' . $declare['ID'] . ' 
                            AND ISVERIFIED = 1
                            ORDER BY DATEADDED DESC
                            LIMIT 0, 1';
                      $result = $db->connection->query($sql);
                      while ($row = $result->fetch_assoc()) {
                          $death += $row['CSLTDEAD'];
                          $totally += $row['DMGTOTALLY'];
                          $partially += $row['DMGPARTIALLY'];
                          $missing += $row['CSLTMISSING'];
                          $injured += $row['CSLTINJURED'];
                      }
                  }

                  $totally /= $count;
                  $partially /= $count;
                  $missing /= $count;
                  $injured /= $count;

                  $totally = round($totally);
                  $partially = round($partially);
                  $missing = round($missing);
                  $injured = round($injured);
                  ?>
                  <table class="table">
                      <thead>
                      <tr>
                          <th>Number of</th>
                          <th>Value</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr>
                          <td>Occurrence</td>
                          <td><?php echo $count; ?></td>
                      </tr>
                      <tr>
                          <td>Total Deaths</td>
                          <td><?php echo $death; ?> (Average: <?php echo round($death/$count); ?>)</td>
                      </tr>
                      <tr>
                          <td>Average Missing During Occurrence</td>
                          <td><?php echo $missing; ?></td>
                      </tr>
                      <tr>
                          <td>Average Injured During Occurrence</td>
                          <td><?php echo $injured; ?></td>
                      </tr>
                      <tr>
                          <td>Average Totally Damaged Houses During Occurrence</td>
                          <td><?php echo $totally; ?></td>
                      </tr>
                      <tr>
                          <td>Average Partially Damaged Houses During Occurrence</td>
                          <td><?php echo $partially; ?></td>
                      </tr>
                      </tbody>
                  </table>
                  <?php
              }
              else{
                  echo '<p>NOT AVAILABLE</p>';
              }
              ?>
          </div>
          <div class="col-lg-6">
              <h4>Barangays Recently Affected:</h4>
              <div class="list-group">
                  <?php
                        $sql = 'SELECT 	barangay.NAME, 
                                        barangay.ID,
                                        disaster_declare.STARTED
                                FROM 	barangay,
                                        disaster_declare
                                WHERE	disaster_declare.BRGY = barangay.ID
                                AND		disaster_declare.DISASTER = ' . $info['ID'] . '
                                AND     disaster_declare.ISVERIFIED = 1
                                GROUP BY barangay.ID
                                ORDER BY disaster_declare.STARTED ASC
                                LIMIT   0, 10';
                        $result = $db->connection->query($sql);
                        while ($row = $result->fetch_assoc()){
                            ?>
                                <a href="barangayview.php?id=<?php echo $row['ID']; ?>" class="list-group-item"><?php echo $row['NAME']; ?> <small class="pull-right">[<?php echo converttoformaldatetimestring($row['STARTED']); ?>]</small></a>
                            <?php
                        }

                        if(mysqli_num_rows($result) < 1){
                            echo '<p>NONE</p>';
                        }
                  ?>
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
<script src="js/app/bargraph.js"></script>
<script>

</script>

</body>
</html>