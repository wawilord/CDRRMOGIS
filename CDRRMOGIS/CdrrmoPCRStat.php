<?php
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();
session_start();
include ('library/form/CdrrmoOnly.php');

$getDate = (isset($_GET["d"])) ? $_GET["d"] : "P1M";
$interval = new DateInterval($getDate);
$curDate = new DateTime();
$curDate->sub($interval);

$sql = "SELECT PCRID
FROM pcr_report
WHERE DATE between '". $curDate->format('Y-m-d') ."' AND DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
$result = $db->connection->query($sql);
$count = mysqli_num_rows($result);
if($count < 1) 
	$script = "document.getElementById('content').innerHTML = '<div class=\"alert alert-info\" role=\"alert\">No records to show.</div>'";
else
	$script = "";
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
      .morris-hover{position:absolute;z-index:1000}.morris-hover.morris-default-style{border-radius:10px;padding:6px;color:#666;background:rgba(255,255,255,0.8);border:solid 2px rgba(230,230,230,0.8);font-family:sans-serif;font-size:12px;text-align:center}.morris-hover.morris-default-style .morris-hover-row-label{font-weight:bold;margin:0.25em 0}
      .morris-hover.morris-default-style .morris-hover-point{white-space:nowrap;margin:0.1em 0}
  </style>
</head>
<body role="document">
<?php include('library/html/loginmodal.php'); ?>
<?php include('library/html/navbar.php'); ?>

<!--Content starts here-->
<div class="container">
    <div class="page-header">
		<div class="form-inline pull-right">
			<select class="form-control" onchange="window.location.href=this.value;">
				<option>Date Filter</option>
				<option value="CdrrmoPCRStat.php?d=P1M">1 Month Ago</option>
				<option value="CdrrmoPCRStat.php?d=P3M">3 Months Ago</option>
				<option value="CdrrmoPCRStat.php?d=P6M">6 Months Ago</option>
				<option value="CdrrmoPCRStat.php?d=P12M">12 Months Ago</option>
				<option value="CdrrmoPCRStat.php?d=P20Y">All-time</option>
			</select>
		</div>
		
        <h1>PCR Statistics <small>since <?php echo $curDate->format('M d, Y'); ?></small></h1>
    </div>
	
	<div id="content">
		<!-- PCR Graph -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Daily PCR Counts</h3>
					</div>
					<div class="panel-body">
						<div id="PCR-count"></div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-4">
				<div class="panel panel-default">
					<!-- Location Type -->
					<div class="panel-heading">Location Type</div>
					<ul class="list-group">
						<?php
							$loc_sql = "SELECT LOCTYPE
							FROM pcr_report
							WHERE DATE between '". $curDate->format('Y-m-d') ."' AND DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
							$loc_result = $db->connection->query($loc_sql);
							$air_count = 0;
							$cli_count = 0;
							$edu_count = 0;
							$frm_count = 0;
							$hwy_count = 0;
							$hme_count = 0;
							$hpl_count = 0;
							$idl_count = 0;
							$mne_count = 0;
							$nrs_count = 0;
							$pbg_count = 0;
							$pod_count = 0;
							$rec_count = 0;
							$rei_count = 0;
							$reb_count = 0;
							$wtw_count = 0;
							$uns_count = 0;
							$ntt_count = 0;
							while ($loc_row = $loc_result->fetch_assoc()){
								switch ($loc_row["LOCTYPE"]) {
									case 'Airport':
										$air_count++;
										break;
									case 'Clinic/Medical':
										$cli_count++;
										break;
									case 'Educational':
										$edu_count++;
										break;
									case 'Farm':
										$frm_count++;
										break;
									case 'Highway/Street':
										$hwy_count++;
										break;
									case 'Home/Residence':
										$hme_count++;
										break;
									case 'Hospital':
										$hpl_count++;
										break;
									case 'Industrial':
										$idl_count++;
										break;
									case 'Mine/Quarry':
										$mne_count++;
										break;
									case 'Nursing Home':
										$nrs_count++;
										break;
									case 'Public Building':
										$pbg_count++;
										break;
									case 'Public Outdoors':
										$pod_count++;
										break;
									case 'Recreational':
										$rec_count++;
										break;
									case 'Residential Institution':
										$rei_count++;
										break;
									case 'Restaurant/Bar':
										$reb_count++;
										break;
									case 'Waterway':
										$wtw_count++;
										break;
									case 'Unspecified':
										$uns_count++;
										break;
									case 'N/A':
										$ntt_count++;
										break;
								}
							}
						?>

						<li class="list-group-item"><span class="badge"><?php echo $air_count; ?></span>Airport</li>
						<li class="list-group-item"><span class="badge"><?php echo $cli_count; ?></span>Clinic/Medical</li>
						<li class="list-group-item"><span class="badge"><?php echo $edu_count; ?></span>Educational</li>
						<li class="list-group-item"><span class="badge"><?php echo $frm_count; ?></span>Farm</li>
						<li class="list-group-item"><span class="badge"><?php echo $hwy_count; ?></span>Highway/Street</li>
						<li class="list-group-item"><span class="badge"><?php echo $hme_count; ?></span>Home/Residence</li>
						<li class="list-group-item"><span class="badge"><?php echo $hpl_count; ?></span>Hospital</li>
						<li class="list-group-item"><span class="badge"><?php echo $idl_count; ?></span>Industrial</li>
						<li class="list-group-item"><span class="badge"><?php echo $mne_count; ?></span>Mine/Quarry</li>
						<li class="list-group-item"><span class="badge"><?php echo $nrs_count; ?></span>Nursing Home</li>
						<li class="list-group-item"><span class="badge"><?php echo $pbg_count; ?></span>Public Building</li>
						<li class="list-group-item"><span class="badge"><?php echo $pod_count; ?></span>Public Outdoors</li>
						<li class="list-group-item"><span class="badge"><?php echo $rec_count; ?></span>Recreational</li>
						<li class="list-group-item"><span class="badge"><?php echo $rei_count; ?></span>Residential Institution</li>
						<li class="list-group-item"><span class="badge"><?php echo $reb_count; ?></span>Restaurant/Bar</li>
						<li class="list-group-item"><span class="badge"><?php echo $wtw_count; ?></span>Waterway</li>
						<li class="list-group-item"><span class="badge"><?php echo $uns_count; ?></span>Unspecified</li>
						<li class="list-group-item"><span class="badge"><?php echo $ntt_count; ?></span>N/A</li>
					</ul>
				</div>
			</div>
			
			<div class="col-lg-4">
				<div class="panel panel-default">
					
					<!-- Patient Info -->
					<div class="panel-heading">Patient Info</div>
					<ul class="list-group">
						<?php
							$pat_sql = "SELECT pat.PTNTAGE, pat.PTNTGENDER
										FROM pcr_patient as pat
										LEFT JOIN pcr_report as rep
										ON rep.PCRID = pat.PCRNO
										WHERE rep.DATE between '". $curDate->format('Y-m-d') ."' AND DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
							$pat_result = $db->connection->query($pat_sql);
							$age_arr = array();
							$o18_count = 0;
							$u18_count = 0;
							$age_ave = 0;
							$man_count = 0;
							$wmn_count = 0;
							$ppl_count = 0;
							while ($pat_row = $pat_result->fetch_assoc()){
								if($pat_row["PTNTAGE"] >= 18) {
									$o18_count++;
									array_push($age_arr, $pat_row["PTNTAGE"]);
								}
								else {
									$u18_count++;
									array_push($age_arr, $pat_row["PTNTAGE"]);
								}
								switch($pat_row["PTNTGENDER"]) {
									case 'Female':
										$wmn_count++;
										break;
									case 'Male':
										$man_count++;
										break;
								}
							}
							$age_ave = array_sum($age_arr) / count($age_arr);
							$ppl_count = $man_count + $wmn_count;
						?>

						<li class="list-group-item"><span class="badge"><?php echo $ppl_count; ?></span>Patients Recorded</li>
						<li class="list-group-item"><span class="badge"><?php echo $man_count; ?></span>Male Patients</li>
						<li class="list-group-item"><span class="badge"><?php echo $wmn_count; ?></span>Female Patients</li>
						<li class="list-group-item"><span class="badge"><?php echo $o18_count; ?></span>Patients 18 and Above</li>
						<li class="list-group-item"><span class="badge"><?php echo $u18_count; ?></span>Patients Below 18</li>
						<li class="list-group-item"><span class="badge"><?php echo $age_ave; ?></span>Average Patient Age</li>
					</ul>
				</div>

				<div class="panel panel-default">
					
					<!-- Patient Disposition -->
					<div class="panel-heading">Patient Disposition</div>
					<ul class="list-group">
						<?php 
							$res_sql = "SELECT DISPO
							FROM pcr_report
							WHERE DATE between '". $curDate->format('Y-m-d') ."' AND DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
							$res_result = $db->connection->query($res_sql);
							$ems_count = 0;
							$pvt_count = 0;
							$not_count = 0;
							$ded_count = 0;
							$nop_count = 0;
							$ttc_count = 0;
							$trr_count = 0;
							$ref_count = 0;
							$can_count = 0;
							while ($res_row = $res_result->fetch_assoc()){
								switch ($res_row["DISPO"]) {
									case 'Treat/Transport(EMS)':
										$ems_count++;
										break;
									case 'Treat/Transport(Private)':
										$pvt_count++;
										break;
									case 'No Treatment Required':
										$not_count++;
										break;
									case 'Dead at Scene':
										$ded_count++;
										break;
									case 'No Patient Found':
										$nop_count++;
										break;
									case 'Treat/Transfer Care':
										$ttc_count++;
										break;
									case 'Treat/Release':
										$trr_count++;
										break;
									case 'Refused Care':
										$ref_count++;
										break;
									case 'Cancelled':
										$can_count++;
										break;
								}
							}
						?>
						<li class="list-group-item"><span class="badge"><?php echo $ems_count; ?></span>Treat/Transport(EMS)</li>
						<li class="list-group-item"><span class="badge"><?php echo $pvt_count; ?></span>Treat/Transport(Private)</li>
						<li class="list-group-item"><span class="badge"><?php echo $not_count; ?></span>No Treatment Required</li>
						<li class="list-group-item"><span class="badge"><?php echo $ded_count; ?></span>Dead at Scene</li>
						<li class="list-group-item"><span class="badge"><?php echo $nop_count; ?></span>No Patient Found</li>
						<li class="list-group-item"><span class="badge"><?php echo $ttc_count; ?></span>Treat/Transfer Care</li>
						<li class="list-group-item"><span class="badge"><?php echo $trr_count; ?></span>Treat/Release</li>
						<li class="list-group-item"><span class="badge"><?php echo $ref_count; ?></span>Refused Care</li>
						<li class="list-group-item"><span class="badge"><?php echo $can_count; ?></span>Cancelled</li>
					</ul>
				</div>

			</div>

			<div class="col-lg-4">
				<div class="panel panel-default">
					
					<!-- Response Types -->
					<div class="panel-heading">Response Types</div>
					<ul class="list-group">
						<?php 
							$res_sql = "SELECT RESTYPE
							FROM pcr_report
							WHERE DATE between '". $curDate->format('Y-m-d') ."' AND DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
							$res_result = $db->connection->query($res_sql);
							$int_count = 0;
							$rts_count = 0;
							$sit_count = 0;
							$uit_count = 0;
							$sby_count = 0;
							$unk_count = 0;
							$noa_count = 0;
							while ($res_row = $res_result->fetch_assoc()){
								switch ($res_row["RESTYPE"]) {
									case 'Intercept':
										$int_count++;
										break;
									case 'Response to Scene':
										$rts_count++;
										break;
									case 'Scheduled Interfacility Transfer':
										$sit_count++;
										break;
									case 'Unscheduled Interfacility Transfer':
										$uit_count++;
										break;
									case 'Standby':
										$sby_count++;
										break;
									case 'Unknown':
										$unk_count++;
										break;
									case 'N/A':
										$noa_count++;
										break;
								}
							}
						?>
						<li class="list-group-item"><span class="badge"><?php echo $int_count; ?></span>Intercept</li>
						<li class="list-group-item"><span class="badge"><?php echo $rts_count; ?></span>Response to Scene</li>
						<li class="list-group-item"><span class="badge"><?php echo $sit_count; ?></span>Scheduled Interfacility Transfer</li>
						<li class="list-group-item"><span class="badge"><?php echo $uit_count; ?></span>Unscheduled Interfacility Transfer</li>
						<li class="list-group-item"><span class="badge"><?php echo $sby_count; ?></span>Standby</li>
						<li class="list-group-item"><span class="badge"><?php echo $unk_count; ?></span>Unknown</li>
						<li class="list-group-item"><span class="badge"><?php echo $noa_count; ?></span>N/A</li>
						
					</ul>
				</div>

				<div class="panel panel-default">
					<!-- Treatment -->
					<div class="panel-heading">Treatment</div>
					<ul class="list-group">
						<?php 
							$trt_sql = "SELECT p.DATE, t.CPR, t.DEFIB, t.RETRNPULSE, t.RESP
							FROM pcr_treatment as t
							INNER JOIN pcr_report as p
							ON t.PCRNO = p.PCRID
							WHERE p.DATE between '". $curDate->format('Y-m-d') ."' AND DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
							$trt_result = $db->connection->query($trt_sql);
							$trt_CPR = 0;
							$trt_DEF = 0;
							$trt_PLS = 0;
							$trt_RSP = 0;
							while ($trt_row = $trt_result->fetch_assoc()){
								if($trt_row["CPR"] == "1") 
									$trt_CPR++;
								if($trt_row["DEFIB"] == "1") 
									$trt_DEF++;
								if($trt_row["RETRNPULSE"] == "1") 
									$trt_PLS++;
								if($trt_row["RESP"] == "1") 
									$trt_RSP++;
							}
						?>
						<li class="list-group-item"><span class="badge"><?php echo $trt_CPR; ?></span>CPRs Performed</li>
						<li class="list-group-item"><span class="badge"><?php echo $trt_DEF; ?></span>Defibrillators Used</li>
						<li class="list-group-item"><span class="badge"><?php echo $trt_PLS; ?></span>Pulses Returned</li>
						<li class="list-group-item"><span class="badge"><?php echo $trt_RSP; ?></span>Respirators Used</li>
					</ul>
				</div>

				<div class="panel panel-default">
					<!-- Tracking -->
					<div class="panel-heading">Run Information</div>
					<ul class="list-group">
						<?php 
							$tck_sql = "SELECT p.DATE, TIMEDIFF(t.INSVC, t.CALLRCVD) as RUN, t.TOTAL
							FROM pcr_track as t
							INNER JOIN pcr_report as p
							ON t.PCRID = p.PCRID
							WHERE p.DATE between '". $curDate->format('Y-m-d') ."' AND DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
							$tck_result = $db->connection->query($tck_sql);
							$tck_run = [];
							$tck_mil = [];
							while ($tck_row = $tck_result->fetch_assoc()){
								array_push($tck_run, $tck_row["RUN"]);
								array_push($tck_mil, $tck_row["TOTAL"]);
							}
							$tck_run_t = array_sum(array_map('strtotime', $tck_run));
							$tck_run_f = date('H:i:s', $tck_run_t / count($tck_run));
							$tck_mil_t = array_sum($tck_mil);
							$tck_mil_f = $tck_mil_t / count($tck_mil);
						?>
						<li class="list-group-item"><span class="badge"><?php echo $tck_run_f; ?></span>Average Run Time</li>
						<li class="list-group-item"><span class="badge"><?php echo date('H:i:s', $tck_run_t); ?></span>Total Run Time</li>
						<li class="list-group-item"><span class="badge"><?php echo $tck_mil_f; ?></span>Average Mileage</li>
						<li class="list-group-item"><span class="badge"><?php echo $tck_mil_t; ?></span>Total Mileage</li>
					</ul>
				</div>

			</div>

		</div>
	
	</div>
	
</div>


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
    var dir = document.URL.substr(0,document.URL.lastIndexOf('/'));
    $(document).ready(function() {
        //Morris charts snippet - js
        $.getScript(dir + '/js/app/raphael-min.js',function(){
            $.getScript(dir + '/js/app/morris.min.js',function(){
				
				
				// PCR Count //
                Morris.Line({
                    element: 'PCR-count',
                    data: [
                        <?php
                            $sql = "SELECT DATE, COUNT(*) as NUM 
                            FROM pcr_report
							WHERE DATE between '". $curDate->format('Y-m-d') ."' AND DATE_ADD(CURDATE(), INTERVAL 1 DAY)
							GROUP BY DATE
                            ORDER BY DATE ASC";
                            $result = $db->connection->query($sql);
                            $arg = [];
							
                            while ($row = $result->fetch_assoc()){
								array_push($arg, '{ y: \'' . $row["DATE"] .'\', a: ' . $row["NUM"] . '}');

                            }
							$arg_string = implode(",", $arg);
                            echo $arg_string;
                        ?>
                    ],
                    xkey: 'y',
                    ykeys: ['a'],
                    labels: ['No. of PCR'],
					ymin: 'auto',
					dateFormat: function (x) { return new Date(x).toDateString(); }
                    //lineColors: ['#0b62a4'],
                });
				
            });
        });
    });
	
	<?php echo $script; ?>
</script>

</body>
</html>
