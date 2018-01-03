<!DOCTYPE html>
    <?php
    session_start();
    include ('library/form/BrgyOnly.php');
    include('library/form/connection.php');
    include ('library/function/functions.php');
    $db = new db();
    $sql = 'SELECT      *
            FROM        disaster_declare
            WHERE       ID = ' . $_GET['id'];
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    $DeclareInfo = $result->fetch_assoc();
    $DisasterReports = array();
    $sql = 'SELECT 				disaster_reports.*,
                                user_accounts.FIRSTNAME,
                                user_accounts.MIDDLENAME,
                                user_accounts.LASTNAME
            FROM 				disaster_reports,
                                user_accounts
            WHERE 				disaster_reports.UPLOADER = user_accounts.USERNAME
            AND                 disaster_reports.DECLAREID = ' . $DeclareInfo["ID"] . '
            GROUP BY			disaster_reports.ID
            ORDER BY            disaster_reports.DATEADDED ASC';
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while($row = $result->fetch_assoc()){
        $DisasterReports[] = $row;
    }


    $EvacuationCenters = array();
    $sql = 'SELECT      evacuation_list.ID, 
                        evacuation_list.EVACNAME
            FROM        evacuation_list,
                        evacuation_report
            WHERE       evacuation_list.ID = evacuation_report.EVACID
            AND         evacuation_report.DECLAREID = ' . $DeclareInfo["ID"] . '
            GROUP BY    evacuation_list.ID';
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
                AND         evacuation_report.DECLAREID = ' . $DeclareInfo["ID"] . '
                AND         evacuation_report.EVACID = ' . $row["ID"] . '
                GROUP BY    evacuation_report.ID
                ORDER BY    evacuation_report.DATEADDED ASC';
        $result2 = $db->connection->query($sql2);
        while ($row2 = $result2->fetch_assoc()){
            $row['Reports'][] = $row2;
        }
        $EvacuationCenters[] = $row;
    }
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

<div class="modal fade" id="DisasterReportModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modify Data</h4>
            </div>
            <form id="DisasterReportForm" method="post" action="library/form/EditDisasterReportForm.php">
                <div class="modal-body">
                    <div id="DR_msgbox" tabindex="0"></div>
                    <input type="hidden" id="DisasterReportForm_ID" name="ID" />
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">Date Time</span>
                        <input type="text" id="DisasterReportForm_TIME" name="TIME" class="form-control" aria-describedby="sizing-addon1">
                    </div>
                    <br />
                    <div class="panel panel-default">
                        <div class="panel-heading">Casualties</div>
                        <div class="panel-body">
                            <div class="input-group input-group">
                                <span class="input-group-addon" id="sizing-addon1">Dead</span>
                                <input type="number" id="DisasterReportForm_DEAD" name="DEAD" value="0" min="0" class="form-control" aria-describedby="sizing-addon1">
                            </div>

                            <br />

                            <div class="input-group input-group">
                                <span class="input-group-addon" id="sizing-addon1">Injured</span>
                                <input type="number" id="DisasterReportForm_INJURED" name="INJURED" value="0" min="0" class="form-control" aria-describedby="sizing-addon1">
                            </div>

                            <br />

                            <div class="input-group input-group">
                                <span class="input-group-addon" id="sizing-addon1">Missing</span>
                                <input type="number" id="DisasterReportForm_MISSING" name="MISSING" value="0" min="0" class="form-control" aria-describedby="sizing-addon1">
                            </div>
                        </div>
                    </div>

                    <br />

                    <div class="panel panel-default">
                        <div class="panel-heading">Damages</div>
                        <div class="panel-body">
                            <div class="input-group input-group">
                                <span class="input-group-addon" id="sizing-addon1">Totally</span>
                                <input type="number" id="DisasterReportForm_TOTALLY" name="TOTALLY" value="0" min="0" class="form-control" aria-describedby="sizing-addon1">
                            </div>

                            <br />

                            <div class="input-group input-group">
                                <span class="input-group-addon" id="sizing-addon1">Partially</span>
                                <input type="number" id="DisasterReportForm_PARTIALLY" name="PARTIALLY" value="0" min="0" class="form-control" aria-describedby="sizing-addon1">
                            </div>

                            <br />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="DisasterReportForm_SUBMIT" data-loading-text="Sending Report" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="UpdateEvacuationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Modify Evacuation Report</h4>
            </div>
            <form id="EvacuationReportForm" method="post" action="library/form/EditEvacuationReportForm.php">
                <div class="modal-body">
                    <div id="ER_msgbox" tabindex="0"></div>
                    <input type="hidden" id="EvacuationReportForm_ID" name="ID" class="form-control">
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">Persons Served: </span>
                        <input type="text" id="EvacuationReportForm_TIME" name="TIME" class="form-control">
                    </div>
                    <br />
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">Persons Served: </span>
                        <input type="number" id="EvacuationReportForm_PERSONS" name="PERSONS" value="0" min="0" class="form-control">
                    </div>
                    <br />
                    <div class="input-group input-group">
                        <span class="input-group-addon" id="sizing-addon1">Families Served: </span>
                        <input type="number" id="EvacuationReportForm_FAMILIES" name="FAMILIES" value="0" min="0" class="form-control">
                    </div>
                    <br />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="EvacuationReportForm_SUBMIT" data-loading-text="Sending Report..." class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--NAVBAR HERE-->
<?php include('library/html/navbar.php'); ?>
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1>
            Modify Pending Sent Reports<br />
            <small><?php echo $DeclareInfo['NICKNAME']; ?></small>
        </h1>
    </div>

  <div class="container-fluid">
      <h4>Disaster Reports</h4>
      <div class="container-fluid">
          <table class="table">
              <tr>
                  <th>Uploader</th>
                  <th>Date Time</th>
                  <th>Dead</th>
                  <th>Injured</th>
                  <th>Missing</th>
                  <th>Totally Damaged Houses</th>
                  <th>Partially Damaged Houses</th>
                  <th>Status</th>
                  <th>Action</th>
              </tr>
              <?php
                foreach ($DisasterReports as $report){
                    ?>
                    <tr>
                        <td><?php echo $report['FIRSTNAME'] . ' ' . $report['MIDDLENAME'] . ' ' . $report['LASTNAME']; ?></td>
                        <td><?php echo converttoformaldatetimestring($report['DATEADDED']); ?></td>
                        <td><?php echo $report['CSLTDEAD']; ?></td>
                        <td><?php echo $report['CSLTINJURED']; ?></td>
                        <td><?php echo $report['CSLTMISSING']; ?></td>
                        <td><?php echo $report['DMGTOTALLY']; ?></td>
                        <td><?php echo $report['DMGPARTIALLY']; ?></td>
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
                                        <button class="btn btn-primary" onclick="ModifyDisasterReport(<?php echo $report['ID']; ?>, <?php echo $report['CSLTDEAD']; ?>, <?php echo $report['CSLTINJURED']; ?>, <?php echo $report['CSLTMISSING']; ?>, <?php echo $report['DMGTOTALLY']; ?>, <?php echo $report['DMGPARTIALLY']; ?>, '<?php echo str_replace('-', '/', $report['DATEADDED']); ?>');return false;">Modify</button>
                                    <?php
                                }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
              ?>
          </table>
      </div>

      <br />
      <h4>Evacutaion Center Reports</h4>
      <div class="container-fluid">
          <div class="container-fluid">
              <?php foreach ($EvacuationCenters as $EC){
                  ?>
                  <h4>- <?php echo $EC['EVACNAME']; ?></h4>
                  <div class="container-fluid">
                      <table class="table">
                          <tr>
                              <th>Uploader</th>
                              <th>Date Time</th>
                              <th>Persons Served</th>
                              <th>Families Served</th>
                              <th>Status</th>
                              <th>Action</th>
                          </tr>
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
                      </table>
                  </div>
                  <?php
              } ?>
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
    //DisasterReportForm_TIME
    $('#DisasterReportForm_TIME').datetimepicker({
        mask:'9999/19/39 29:59'
    });
    //EvacuationReportForm_TIME
    $('#EvacuationReportForm_TIME').datetimepicker({
        mask:'9999/19/39 29:59'
    });

    function ModifyDisasterReport(id, dead, injured, missing, totally, partially, time) {
        $('#DisasterReportModal').modal('show');
        $('#DisasterReportForm_ID').val(id);
        $('#DisasterReportForm_TIME').val(time);
        $('#DisasterReportForm_DEAD').val(dead);
        $('#DisasterReportForm_INJURED').val(injured);
        $('#DisasterReportForm_MISSING').val(missing);
        $('#DisasterReportForm_TOTALLY').val(totally);
        $('#DisasterReportForm_PARTIALLY').val(partially);
    }
    $('#DisasterReportForm').on('submit', function (e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            success: function (data) {
                DisplayMsg(data, 'DR_msgbox', function (SuccessMsg) {
                    alert('Success!');
                    window.location.reload();
                });
            }
        });
    });

    function ModifyEvacuationReport(id, persons, families, time){
        //alert(id + ' ' + persons + ' ' + families + ' ' + time);
        $('#UpdateEvacuationModal').modal('show');
        $('#EvacuationReportForm_ID').val(id);
        $('#EvacuationReportForm_TIME').val(time);
        $('#EvacuationReportForm_PERSONS').val(persons);
        $('#EvacuationReportForm_FAMILIES').val(families);
    }
    $('#EvacuationReportForm').on('submit', function (e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            success: function (data) {
                DisplayMsg(data, 'ER_msgbox', function (SuccessMsg) {
                    alert('Success!');
                    window.location.reload();
                });
            }
        });
    });
</script>

</body>
</html>



