<!DOCTYPE html>
<?php
session_start();
include ('library/form/CdrrmoOnly.php');
include('library/form/connection.php');
$db = new db();

$pcr_id = isset($_GET['r']) ? $_GET['r'] : '';
$sql_pcr_report = "SELECT * FROM pcr_report WHERE PCRID = $pcr_id";
$result_pcr_report = $db->connection->query($sql_pcr_report);

while ($row = $result_pcr_report->fetch_assoc()) {
	$result_pcr_report_NUM = htmlspecialchars($row['PCRNUMBER']);
	$result_pcr_report_DATE = htmlspecialchars($row['DATE']);
	$result_pcr_report_UNIT = htmlspecialchars($row['UNITNAME']);
	$result_pcr_report_ADDRESS = htmlspecialchars($row['INADDRESS']);
	$result_pcr_report_LOC = htmlspecialchars($row['LOCTYPE']);
	$result_pcr_report_RES = htmlspecialchars($row['RESTYPE']);
	$result_pcr_report_DISPO = htmlspecialchars($row['DISPO']);
	$result_pcr_report_IMAGE = $row['IMAGE'];
}

$sql_pcr_track = "SELECT * FROM pcr_track WHERE PCRID = $pcr_id";
$result_pcr_track = $db->connection->query($sql_pcr_track);

while ($row = $result_pcr_track->fetch_assoc()) {
	$result_pcr_track_CALLRCVD = htmlspecialchars($row['CALLRCVD']);
	$result_pcr_track_ENROUTE = htmlspecialchars($row['ENROUTE']);
	$result_pcr_track_ATSCN = htmlspecialchars($row['ATSCN']);
	$result_pcr_track_ATPT = htmlspecialchars($row['ATPT']);
	$result_pcr_track_DEPSCN = htmlspecialchars($row['DEPSCN']);
	$result_pcr_track_ATDEST = htmlspecialchars($row['ATDEST']);
	$result_pcr_track_INSVC = htmlspecialchars($row['INSVC']);
	$result_pcr_track_END = htmlspecialchars($row['END']);
	$result_pcr_track_BEGIN = htmlspecialchars($row['BEGIN']);
	$result_pcr_track_TOTAL = htmlspecialchars($row['TOTAL']);
}

$sql_pcr_pat = "SELECT * FROM pcr_patient WHERE PCRNO = $pcr_id";
$result_pcr_pat = $db->connection->query($sql_pcr_pat);

while ($row = $result_pcr_pat->fetch_assoc()) {
	$result_pcr_pat_PTNTNAME = htmlspecialchars($row['PTNTNAME']);
	$result_pcr_pat_PTNTADDRESS = htmlspecialchars($row['PTNTADDRESS']);
	$result_pcr_pat_PTNTAGE = htmlspecialchars($row['PTNTAGE']);
	$result_pcr_pat_PTNTGENDER = htmlspecialchars($row['PTNTGENDER']);
	$result_pcr_pat_PTNTDOB = htmlspecialchars($row['PTNTDOB']);
	$result_pcr_pat_PTNTCOMPLAINT = htmlspecialchars($row['PTNTCOMPLAINT']);
	$result_pcr_pat_PTNTNOI = htmlspecialchars($row['PTNTNOI']);
}

$sql_pcr_rp = "SELECT * FROM pcr_rp WHERE PCRNO = $pcr_id";
$result_pcr_rp = $db->connection->query($sql_pcr_rp);

while ($row = $result_pcr_rp->fetch_assoc()) {
	$result_pcr_rp_RPNAME = htmlspecialchars($row['RPNAME']);
	$result_pcr_rp_RPADDRESS = htmlspecialchars($row['RPADDRESS']);
	$result_pcr_rp_RPREL = htmlspecialchars($row['RPREL']);
	$result_pcr_rp_RPCONTACT = htmlspecialchars($row['RPCONTACT']);
}

$sql_pcr_asmtbs = "SELECT * FROM pcr_asmtbaseline WHERE PCRNO = $pcr_id";
$result_pcr_asmtbs = $db->connection->query($sql_pcr_asmtbs);

while ($row = $result_pcr_asmtbs->fetch_assoc()) {
	$result_pcr_asmtbs_TIME = htmlspecialchars($row['TIME']);
	$result_pcr_asmtbs_BP = htmlspecialchars($row['BLOODPRESSURE']);
	$result_pcr_asmtbs_PULSE = htmlspecialchars($row['PULSE']);
	$result_pcr_asmtbs_PULSEQUALITY = htmlspecialchars($row['PULSEQUALITY']);
	$result_pcr_asmtbs_RESP = htmlspecialchars($row['RESP']);
	$result_pcr_asmtbs_RESPQUALITY = htmlspecialchars($row['RESPQUALITY']);
	$result_pcr_asmtbs_SPO2 = htmlspecialchars($row['SPO2']);
	$result_pcr_asmtbs_CBG = htmlspecialchars($row['CBG']);
}

$sql_pcr_asmten = "SELECT * FROM pcr_asmtenroute WHERE PCRNO = $pcr_id";
$result_pcr_asmten = $db->connection->query($sql_pcr_asmten);

while ($row = $result_pcr_asmten->fetch_assoc()) {
	$result_pcr_asmten_TIME = htmlspecialchars($row['TIME']);
	$result_pcr_asmten_BP = htmlspecialchars($row['BLOODPRESSURE']);
	$result_pcr_asmten_PULSE = htmlspecialchars($row['PULSE']);
	$result_pcr_asmten_RESP = htmlspecialchars($row['RESP']);
	$result_pcr_asmten_SPO2 = htmlspecialchars($row['SPO2']);
	$result_pcr_asmten_CBG = htmlspecialchars($row['CBG']);
}

$sql_pcr_asmt = "SELECT * FROM pcr_assessment WHERE PCRNO = $pcr_id";
$result_pcr_asmt = $db->connection->query($sql_pcr_asmt);

while ($row = $result_pcr_asmt->fetch_assoc()) {
	$result_pcr_asmt_SKIN = htmlspecialchars($row['SKIN']);
	$result_pcr_asmt_EYESL = htmlspecialchars($row['EYESL']);
	$result_pcr_asmt_EYESR = htmlspecialchars($row['EYESR']);
	$result_pcr_asmt_PAINPROVOKE = htmlspecialchars($row['PAINPROVOKE']);
	$result_pcr_asmt_PAINQUALITY = htmlspecialchars($row['PAINQUALITY']);
	$result_pcr_asmt_PAINRADIATE = htmlspecialchars($row['PAINRADIATE']);
	$result_pcr_asmt_PAINSEVERITY = htmlspecialchars($row['PAINSEVERITY']);
	$result_pcr_asmt_PAINONSET = htmlspecialchars($row['PAINONSET']);
	$result_pcr_asmt_O2GIVEN = htmlspecialchars($row['O2GIVEN']);
	$result_pcr_asmt_O2TYPE = htmlspecialchars($row['O2TYPE']);
	$result_pcr_asmt_O2RATE = htmlspecialchars($row['O2RATE']);
	$result_pcr_asmt_LOC = htmlspecialchars($row['LOC']);
}

$sql_pcr_gcs = "SELECT * FROM pcr_gcs WHERE PCRNO = $pcr_id";
$result_pcr_gcs = $db->connection->query($sql_pcr_gcs);

while ($row = $result_pcr_gcs->fetch_assoc()) {
	$result_pcr_gcs_BASEEYE = htmlspecialchars($row['BASEEYE']);
	$result_pcr_gcs_BASEVERBAL = htmlspecialchars($row['BASEVERBAL']);
	$result_pcr_gcs_BASEMOTOR = htmlspecialchars($row['BASEMOTOR']);
	$result_pcr_gcs_BASEGCS = htmlspecialchars($row['BASEGCS']);
	$result_pcr_gcs_ENROUTEEYE = htmlspecialchars($row['ENROUTEEYE']);
	$result_pcr_gcs_ENROUTEVERBAL = htmlspecialchars($row['ENROUTEVERBAL']);
	$result_pcr_gcs_ENROUTEMOTOR = htmlspecialchars($row['ENROUTEMOTOR']);
	$result_pcr_gcs_ENROUTEGCS = htmlspecialchars($row['ENROUTEGCS']);
}

$sql_pcr_physex = "SELECT * FROM pcr_physex WHERE PCRNO = $pcr_id";
$result_pcr_physex = $db->connection->query($sql_pcr_physex);
$result_pcr_physex_entry = array();

while ($row = $result_pcr_physex->fetch_assoc()) {
	array_push($result_pcr_physex_entry, htmlspecialchars($row['AREA']) . "_" . htmlspecialchars($row['AFFLICTION']));
}

$sql_pcr_asmtallergies = "SELECT * FROM pcr_asmtallergies WHERE PCRNO = $pcr_id";
$result_pcr_asmtallergies = $db->connection->query($sql_pcr_asmtallergies);
$result_pcr_asmtallergies_string = "";

while ($row = $result_pcr_asmtallergies->fetch_assoc()) {
	$result_pcr_asmtallergies_string .= htmlspecialchars($row['ALLERGY']) . ", ";
}
$result_pcr_asmtallergies_string = substr($result_pcr_asmtallergies_string, 0, -2);

$sql_pcr_asmtmed = "SELECT * FROM pcr_asmtmed WHERE PCRNO = $pcr_id";
$result_pcr_asmtmed = $db->connection->query($sql_pcr_asmtmed);
$result_pcr_asmtmed_string = "";

while ($row = $result_pcr_asmtmed->fetch_assoc()) {
	$result_pcr_asmtmed_string .= htmlspecialchars($row['MEDICATIONS']) . ", ";
}
$result_pcr_asmtmed_string = substr($result_pcr_asmtmed_string, 0, -2);

$sql_pcr_asmtpmh = "SELECT * FROM pcr_asmtpmh WHERE PCRNO = $pcr_id";
$result_pcr_asmtpmh = $db->connection->query($sql_pcr_asmtpmh);
$result_pcr_asmtpmh_string = "";

while ($row = $result_pcr_asmtpmh->fetch_assoc()) {
	$result_pcr_asmtpmh_string .= htmlspecialchars($row['PASTMEDHTY']) . ", ";
}
$result_pcr_asmtpmh_string = substr($result_pcr_asmtpmh_string, 0, -2);

$sql_pcr_treatment = "SELECT * FROM pcr_treatment WHERE PCRNO = $pcr_id";
$result_pcr_treatment = $db->connection->query($sql_pcr_treatment);

while ($row = $result_pcr_treatment->fetch_assoc()) {
	$result_pcr_treatment_INTERVENTIONS = htmlspecialchars($row['INTERVENTIONS']);
	$result_pcr_treatment_RESPONSE = htmlspecialchars($row['RESPONSE']);
	$result_pcr_treatment_CPR = htmlspecialchars($row['CPR']);
	$result_pcr_treatment_CPRTIME = htmlspecialchars($row['CPRTIME']);
	$result_pcr_treatment_DEFIB = htmlspecialchars($row['DEFIB']);
	$result_pcr_treatment_RETRNPULSE = htmlspecialchars($row['RETRNPULSE']);
	$result_pcr_treatment_RTRNPULSERATE = htmlspecialchars($row['RTRNPULSERATE']);
	$result_pcr_treatment_RESP = htmlspecialchars($row['RESP']);
	$result_pcr_treatment_RESPRATE = htmlspecialchars($row['RESPRATE']);
	$result_pcr_treatment_NARRATIVE = htmlspecialchars($row['NARRATIVE']);
}

$sql_pcr_crew = "SELECT * FROM pcr_crew WHERE PCRNO = $pcr_id";
$result_pcr_crew = $db->connection->query($sql_pcr_crew);
$result_pcr_crew_NAME = array();
$result_pcr_crew_LEVEL = array();

while ($row = $result_pcr_crew->fetch_assoc()) {
	array_push($result_pcr_crew_NAME, htmlspecialchars($row['NAME']));
	array_push($result_pcr_crew_LEVEL, htmlspecialchars($row['LEVEL']));
}

$sql_pcr_endorse = "SELECT * FROM pcr_endorse WHERE PCRNO = $pcr_id";
$result_pcr_endorse = $db->connection->query($sql_pcr_endorse);

while ($row = $result_pcr_endorse->fetch_assoc()) {
	$result_pcr_endorse_PREPAREDBY = htmlspecialchars($row['PREPAREDBY']);
	$result_pcr_endorse_MEDDIR = htmlspecialchars($row['MEDDIR']);
	$result_pcr_endorse_ENDORSEDTO = htmlspecialchars($row['ENDORSEDTO']);
}
?>
<!--This Page is for the CDRRMO only -->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>View PCR</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="css/map.css" rel="stylesheet">
	<link href="css/jquery.datetimepicker.css" rel="stylesheet">
</head>
<body role="document">
	<!--LOGIN MODAL HERE-->
	<?php include('library/html/loginmodal.php'); ?>
	<!-- Content starts here -->
	<?php include('library/html/navbar.php'); ?>
	
	<!-- Image Modal -->
	<div id="imgModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			  </div>
			  <div class="modal-body">
				<?php echo '<img src="data:image/jpeg;base64,'.base64_encode( $result_pcr_report_IMAGE ).'" style="width: 100%; height: auto;" />'; ?>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  </div>
			</div>
		</div>
	</div>
	
	<div class="container">
		<div class="page-header">
			<h1>Person Care Report #<?php echo $result_pcr_report_NUM; ?></h1>
		</div>
		
			<div class="row">
				<div class="panel-group" id="accordion">
					<!-- Run Information -->
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title text-center">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
								<span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> Run Information</a>
							</h4>
						</div>
						<div id="collapse1" class="panel-collapse collapse">
							<div class="panel-body">
							
								<!-- Run Info -->
								<h4>
									Run Info &nbsp
									<span><a class="btn btn-info btn-sm" data-toggle="collapse" data-target="#run-section">Show</a></span>
								</h4>
								
								<div class="collapse" id="run-section">
									<hr />
									<div class="row">
										<div class="col-lg-4">
											<!-- Unit Name -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Unit Name</span>
												<input id="runInfo_UNITNAME" name="runInfo_UNITNAME" type="text" class="form-control" value="<?php echo $result_pcr_report_UNIT; ?>" required>
											</div>
											
											<br />
											
											<!-- Response Type -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Response Type</span>
												<select id="runInfo_RESPONSE" name="runInfo_RESPONSE" class="form-control">
													<option selected value=""> -- </option>
													<option value="Intercept" <?php if($result_pcr_report_RES == "Intercept") echo 'selected';?>>Intercept</option>
													<option value="Response to Scene" <?php if($result_pcr_report_RES == "Response to Scene") echo 'selected';?>>Response to Scene</option>
													<option value="Scheduled Interfacility Transfer" <?php if($result_pcr_report_RES == "Scheduled Interfacility Transfer") echo 'selected';?>>Scheduled Interfacility Transfer</option>
													<option value="Unscheduled Interfacility Transfer" <?php if($result_pcr_report_RES == "Unscheduled Interfacility Transfer") echo 'selected';?>>Unscheduled Interfacility Transfer</option>
													<option value="Standby" <?php if($result_pcr_report_RES == "Standby") echo 'selected';?>>Standby</option>
													<option value="Unknown" <?php if($result_pcr_report_RES == "Unknown") echo 'selected';?>>Unknown</option>
													<option value="N/A" <?php if($result_pcr_report_RES == "N/A") echo 'selected';?>>N/A</option>
												</select>
											</div>
										</div>
										
										<div class="col-lg-4">
											<!-- PCR No. -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">PCR No.</span>
												<input id="runInfo_PCRNO" name="runInfo_PCRNO" type="number" class="form-control" value="<?php echo $result_pcr_report_NUM; ?>">
											</div>
											
											<br />
											
											<!-- Location Type -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Location Type</span>
												<select id="runInfo_LOCATION" name="runInfo_LOCATION" class="form-control">
													<option selected value=""> -- </option>
													<option value="Airport" <?php if($result_pcr_report_LOC == "Airport") echo 'selected';?>>Airport</option>
													<option value="Clinic/Medical" <?php if($result_pcr_report_LOC == "Clinic/Medical") echo 'selected';?>>Clinic Medical</option>
													<option value="Educational" <?php if($result_pcr_report_LOC == "Educational") echo 'selected';?>>Educational</option>
													<option value="Farm" <?php if($result_pcr_report_LOC == "Farm") echo 'selected';?>>Farm</option>
													<option value="Highway/Street" <?php if($result_pcr_report_LOC == "Highway/Street") echo 'selected';?>>Highway/Street</option>
													<option value="Home/Residence" <?php if($result_pcr_report_LOC == "Home/Residence") echo 'selected';?>>Home/Residence</option>
													<option value="Hospital" <?php if($result_pcr_report_LOC == "Hospital") echo 'selected';?>>Hospital</option>
													<option value="Industrial" <?php if($result_pcr_report_LOC == "Industrial") echo 'selected';?>>Industrial</option>
													<option value="Mine/Quarry" <?php if($result_pcr_report_LOC == "Mine/Quarry") echo 'selected';?>>Mine/Quarry</option>
													<option value="Nursing Home" <?php if($result_pcr_report_LOC == "Nursing Home") echo 'selected';?>>Nursing Home</option>
													<option value="Public Building" <?php if($result_pcr_report_LOC == "Public Building") echo 'selected';?>>Public Building</option>
													<option value="Public Outdoors" <?php if($result_pcr_report_LOC == "Public Outdoors") echo 'selected';?>>Public Outdoors</option>
													<option value="Recreational" <?php if($result_pcr_report_LOC == "Recreational") echo 'selected';?>>Recreational</option>
													<option value="Residential Institution" <?php if($result_pcr_report_LOC == "Residential Institution") echo 'selected';?>>Residential Institution</option>
													<option value="Restaurant/Bar" <?php if($result_pcr_report_LOC == "Restaurant/Bar") echo 'selected';?>>Restaurant/Bar</option>
													<option value="Waterway" <?php if($result_pcr_report_LOC == "Waterway") echo 'selected';?>>Waterway</option>
													<option value="Unspecified" <?php if($result_pcr_report_LOC == "Unspecified") echo 'selected';?>>Unspecified</option>
													<option value="N/A" <?php if($result_pcr_report_LOC == "N/A") echo 'selected';?>>N/A</option>
												</select>
											</div>
										</div>
										
										<div class="col-lg-4">
											<!-- Date -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Date</span>
												<input id="runInfo_DATE" name="runInfo_DATE" type="text" class="form-control date-only" value="<?php echo $result_pcr_report_DATE; ?>">
											</div>
											
											<br />
											
											<!-- Patient Disposition -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Patient Disposition</span>
												<select id="runInfo_DISPO" name="runInfo_DISPO" class="form-control">
													<option selected value=""> -- </option>
													<option value="Treat/Transport(EMS)" <?php if($result_pcr_report_DISPO == "Treat/Transport(EMS)") echo 'selected';?>>Treat/Transport(EMS)</option>
													<option value="Treat/Transport(Private)" <?php if($result_pcr_report_DISPO == "Treat/Transport(Private)") echo 'selected';?>>Treat/Transport(Private)</option>
													<option value="No Treatment Required" <?php if($result_pcr_report_DISPO == "No Treatment Required") echo 'selected';?>>No Treatment Required</option>
													<option value="Dead at Scene" <?php if($result_pcr_report_DISPO == "Dead at Scene") echo 'selected';?>>Dead at Scene</option>
													<option value="No Patient Found" <?php if($result_pcr_report_DISPO == "No Patient Found") echo 'selected';?>>No Patient Found</option>
													<option value="Treat/Transfer Care" <?php if($result_pcr_report_DISPO == "Treat/Transfer Care") echo 'selected';?>>Treat/Transfer Care</option>
													<option value="Treat/Release" <?php if($result_pcr_report_DISPO == "Treat/Release") echo 'selected';?>>Treat/Release</option>
													<option value="Refused Care" <?php if($result_pcr_report_DISPO == "Refused Care") echo 'selected';?>>Refused Care</option>
													<option value="Cancelled" <?php if($result_pcr_report_DISPO == "Cancelled") echo 'selected';?>>Cancelled</option>
												</select>
											</div>
										</div>
									</div>
									
									<br />
									
									<div class="row">
										<div class="col-lg-8">
											<!-- Incident Address -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Incident Address</span>
												<input id="runInfo_ADDRESS" name="runInfo_ADDRESS" type="text" class="form-control" value="<?php echo $result_pcr_report_ADDRESS; ?>">
											</div>
										</div>
										
										<div class="col-lg-4">
											<!-- Image -->
											<a class="btn btn-default" data-toggle="modal" data-target="#imgModal">View Scanned Image</a>
										</div>
									</div>
								</div>
								<br />
								
								<!-- Tracking -->
								<h4>
									Tracking &nbsp
									<span><a class="btn btn-info btn-sm" data-toggle="collapse" data-target="#other-section">Show</a></span>
								</h4>
								
								<div class="collapse" id="other-section">
									<hr />
									
									<h4>Event Times</h4>
									<div class="row">
										<div class="col-lg-3">
											<!-- Call Received -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Call Received</span>
												<input id="track_RECEIVED" name="track_RECEIVED" type="text" class="form-control time-only" value="<?php echo $result_pcr_track_CALLRCVD; ?>">
											</div>
											<br />
											
											<!-- Depart Scene -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Departed Scene</span>
												<input id="track_DEPART" name="track_DEPART" type="text" class="form-control time-only" value="<?php echo $result_pcr_track_DEPSCN; ?>">
											</div>
										</div>
										
										<div class="col-lg-3">
											<!-- Enroute -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Enroute</span>
												<input id="track_ENROUTE" name="track_ENROUTE" type="text" class="form-control time-only" value="<?php echo $result_pcr_track_ENROUTE; ?>">
											</div>
											<br />
											
											<!-- At Dest -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">At Destination</span>
												<input id="track_DEST" name="track_DEST" type="text" class="form-control time-only" value="<?php echo $result_pcr_track_ATDEST; ?>">
											</div>
										</div>
										
										<div class="col-lg-3">
											<!-- At Scene -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">At Scene</span>
												<input id="track_SCENE" name="track_SCENE" type="text" class="form-control time-only" value="<?php echo $result_pcr_track_ATSCN; ?>">
											</div>
											<br />
											
											<!-- In Service -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">In Service</span>
												<input id="track_SERVE" name="track_SERVE" type="text" class="form-control time-only" value="<?php echo $result_pcr_track_INSVC; ?>">
											</div>
										</div>
										
										<div class="col-lg-3">
											<!-- At Patient -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">At Patient</span>
												<input id="track_PAT" name="track_PAT" type="text" class="form-control time-only" value="<?php echo $result_pcr_track_ATPT; ?>">
											</div>
											
										</div>
										
									</div>
									<br />
									
									<h4>Mileage Readings</h4>
									<div class="row">
										<div class="col-lg-3">
											<!-- END -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">End</span>
												<input id="track_END" name="track_END" type="number" class="form-control" value="<?php echo $result_pcr_track_END; ?>">
											</div>
										</div>
										
										<div class="col-lg-3">
											<!-- BEGIN -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Begin</span>
												<input id="track_BEGIN" name="track_BEGIN" type="number" class="form-control" value="<?php echo $result_pcr_track_BEGIN; ?>">
											</div>
										</div>
										
										<div class="col-lg-3">
											<!-- TOTAL -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Total</span>
												<input id="track_TOTAL" name="track_TOTAL" type="number" class="form-control" value="<?php echo $result_pcr_track_TOTAL; ?>">
											</div>
										</div>
									</div>
									
								</div>
								
							</div>
						</div>
					</div>
					
					<!-- Patient Info -->
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title text-center">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
								<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Patient Info</a>
							</h4>
						</div>
						<div id="collapse2" class="panel-collapse collapse">
							<div class="panel-body">
							
							
								<!-- Patient Info -->
								<h4>
									Patient Info&nbsp
									<span><a class="btn btn-info btn-sm" data-toggle="collapse" data-target="#patient-info-section">Show</a></span>
								</h4>
								
								<div class="collapse" id="patient-info-section">
									<hr />
								
									<div class="row">
										<div class="col-lg-4">
											<!-- Patient Name -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Name</span>
												<input id="patInfo_NAME" name="patInfo_NAME" type="text" class="form-control" value="<?php echo $result_pcr_pat_PTNTNAME; ?>">
											</div>
										</div>
										
										<div class="col-lg-2">
											<!-- Age -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Age</span>
												<input id="patInfo_AGE" name="patInfo_AGE" type="number" min="1" max="120" class="form-control" value="<?php echo $result_pcr_pat_PTNTAGE; ?>">
											</div>
										</div>
										
										<div class="col-lg-2">
											<!-- Gender -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Gender</span>
												<select id="patInfo_GENDER" name="patInfo_GENDER" class="form-control">
													<option value="Male" <?php if($result_pcr_pat_PTNTGENDER == "Male") echo 'selected'; ?>>Male</option>
													<option value="Female" <?php if($result_pcr_pat_PTNTGENDER == "Female") echo 'selected'; ?>>Female</option>
												</select>
											</div>
										</div>
										
										<div class="col-lg-4">
											<!-- Date of Birth -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Date of Birth</span>
												<input id="patInfo_DOB" name="patInfo_DOB" type="text" class="form-control date-only" value="<?php echo $result_pcr_pat_PTNTDOB; ?>">
											</div>
										</div>
									</div>
									
									<br />
									
									<div class="row">
										<div class="col-lg-6">
											<!-- Patient Address -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Address</span>
												<input id="patInfo_ADDRESS" name="patInfo_ADDRESS" type="text" class="form-control" value="<?php echo $result_pcr_pat_PTNTADDRESS; ?>">
											</div>
										</div>
									</div>
									
								</div>
								<br />
								
								<!-- Responsible Person Info -->
								<h4>
									Responsible Person Info &nbsp
									<span><a class="btn btn-info btn-sm" data-toggle="collapse" data-target="#rp-section">Show</a></span>
								</h4>
								
								<div class="collapse" id="rp-section">
									<hr />
								
									<div class="row">
										<div class="col-lg-5">
											<!-- Responsible Person's Name -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Name</span>
												<input id="resInfo_NAME" name="resInfo_NAME" type="text" class="form-control" value="<?php echo $result_pcr_rp_RPNAME; ?>">
											</div>
										</div>
										
										<div class="col-lg-3">
											<!-- Relationship -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Relationship</span>
												<input id="resInfo_REL" name="resInfo_REL" type="text" class="form-control" value="<?php echo $result_pcr_rp_RPREL; ?>">
											</div>
										</div>
										
										<div class="col-lg-4">
											<!-- Contact -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Contact</span>
												<input id="resInfo_CONTACT" name="resInfo_CONTACT" type="text" class="form-control" value="<?php echo $result_pcr_rp_RPCONTACT; ?>">
											</div>
										</div>
									</div>
									
									<br />
									
									<div class="row">
										<div class="col-lg-6">
											<!-- Responsible Person Address -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Address</span>
												<input id="resInfo_ADDRESS" name="resInfo_ADDRESS" type="text" class="form-control" value="<?php echo $result_pcr_rp_RPADDRESS; ?>">
											</div>
										</div>
									</div>
								
								</div>
								
							</div>
						</div>
					</div>
					
					<!-- Assessment -->
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title text-center">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
								<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Assessment</a>
							</h4>
						</div>
						<div id="collapse3" class="panel-collapse collapse">
							<div class="panel-body">
								<h4>
									Vitalities &nbsp
									<span><a class="btn btn-info btn-sm" data-toggle="collapse" data-target="#vitalities-section">Show</a></span>
								</h4>
								
								<div class="collapse" id="vitalities-section">
									<hr />
									<div class="row">
										<div class="col-lg-6">
											<!-- Chief Complaint -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Chief Complaint</span>
												<input id="vit_COMP" name="vit_COMP" type="text" class="form-control" value="<?php echo $result_pcr_pat_PTNTCOMPLAINT; ?>">
											</div> 
										</div>
										<div class="col-lg-6">
											<!-- NOI/MOI -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">NOI/MOI</span>
												<input id="vit_NOI" name="vit_NOI" type="text" class="form-control" value="<?php echo $result_pcr_pat_PTNTNOI; ?>">
											</div>
										</div>
									</div> <br />
									
									<div class="row">
										<div class="col-lg-1">
											<br /><br />
											<p>Baseline</p>
											<hr />
											<p>Enroute</p>
										</div>
										<!-- Time -->
										<div class="col-lg-1">
											<h5>Time</h5>
											<input id="vit_BS_TIME" name="vit_BS_TIME" type="text" class="form-control time-only" value="<?php echo $result_pcr_asmtbs_TIME; ?>">
											<br />
											<input id="vit_EN_TIME" name="vit_EN_TIME" type="text" class="form-control time-only" value="<?php echo $result_pcr_asmten_TIME; ?>">
										</div>
										<!-- B/P -->
										<div class="col-lg-1">
											<h5><abbr title="blood pressure">B/P</abbr></h5>
											<input id="vit_BS_BP" name="vit_BS_BP" type="number" min="1" class="form-control" value="<?php echo $result_pcr_asmtbs_BP; ?>">
											<br />
											<input id="vit_EN_BP" name="vit_EN_BP" type="number" min="1" class="form-control" value="<?php echo $result_pcr_asmten_BP; ?>">
										</div>
										<!-- Pulse -->
										<div class="col-lg-1">
											<h5>Pulse</h5>
											<input id="vit_BS_PULSE" name="vit_BS_PULSE" type="number" min="1" class="form-control" value="<?php echo $result_pcr_asmtbs_PULSE; ?>">
											<br />
											<input id="vit_EN_PULSE" name="vit_EN_PULSE" type="number" min="1" class="form-control" value="<?php echo $result_pcr_asmten_PULSE; ?>">
										</div>
										<!-- Pulse Quality -->
										<div class="col-lg-2">
											<h5>Pulse Quality</h5>
											<br />
											<select id="vit_PULSEQTY" name="vit_PULSEQTY" class="form-control">
												<option selected value=""> -- </option>
												<option value="Regular" <?php if($result_pcr_asmtbs_PULSEQUALITY == "Regular") echo 'selected'; ?>>Regular</option>
												<option value="Irregular" <?php if($result_pcr_asmtbs_PULSEQUALITY == "Irregular") echo 'selected'; ?>>Irregular</option>
												<option value="N/A" <?php if($result_pcr_asmtbs_PULSEQUALITY == "N/A") echo 'selected'; ?>>N/A</option>
											</select>
										</div>
										<!-- Respiration -->
										<div class="col-lg-1">
											<h5>Respiration</h5>
											<input id="vit_BS_RES" name="vit_BS_RES" type="number" min="1" class="form-control" value="<?php echo $result_pcr_asmtbs_RESP; ?>">
											<br />
											<input id="vit_EN_RES" name="vit_EN_RES" type="number" min="1" class="form-control" value="<?php echo $result_pcr_asmten_RESP; ?>">
										</div>
										<!-- Respiration Quality -->
										<div class="col-lg-2">
											<h5>Respiration Quality</h5>
											<br />
											<select id="vit_RESQTY" name="vit_RESQTY" class="form-control">
												<option selected value=""> -- </option>
												<option value="Regular" <?php if($result_pcr_asmtbs_RESPQUALITY == "Regular") echo 'selected'; ?>>Regular</option>
												<option value="Shallow" <?php if($result_pcr_asmtbs_RESPQUALITY == "Shallow") echo 'selected'; ?>>Shallow</option>
												<option value="Labored" <?php if($result_pcr_asmtbs_RESPQUALITY == "Labored") echo 'selected'; ?>>Labored</option>
												<option value="N/A" <?php if($result_pcr_asmtbs_RESPQUALITY == "N/A") echo 'selected'; ?>>N/A</option>
											</select>
										</div>
										<!-- SPO2 -->
										<div class="col-lg-1">
											<h5><abbr title="peripheral capillary oxygen saturation">SPO<sub>2</sub></abbr></h5>
											<input id="vit_BS_SPO" name="vit_BS_SPO" type="number" min="1" class="form-control" value="<?php echo $result_pcr_asmtbs_SPO2; ?>">
											<br />
											<input id="vit_EN_SPO" name="vit_EN_SPO" type="number" min="1" class="form-control" value="<?php echo $result_pcr_asmten_SPO2; ?>">
										</div>
										<!-- CBG -->
										<div class="col-lg-1">
											<h5><abbr title="capillary blood glucose">CBG</abbr></h5>
											<input id="vit_BS_CBG" name="vit_BS_CBG" type="number" min="1" class="form-control" value="<?php echo $result_pcr_asmtbs_CBG; ?>">
											<br />
											<input id="vit_EN_CBG" name="vit_EN_CBG" type="number" min="1" class="form-control" value="<?php echo $result_pcr_asmten_CBG; ?>">
										</div>
									</div>

								</div>
								
								<br />
								
								<!-- Patient -->
								<h4>
									Patient &nbsp
									<span><a class="btn btn-info btn-sm" data-toggle="collapse" data-target="#patient-section">Show</a></span>
								</h4>
								
								<div class="collapse" id="patient-section">
									<hr />
									
									<h4>Physical</h4>
									<div class="row">
										<div class="col-lg-3">
											<!-- Skin -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Skin</span>
												<select id="pat_SKIN" name="pat_SKIN" class="form-control">
													<option selected value=""> -- </option>
													<option value="Warm" <?php if($result_pcr_asmt_SKIN == "Warm") echo 'selected'; ?>>Warm</option>
													<option value="Cool/Cold" <?php if($result_pcr_asmt_SKIN == "Cool/Cold") echo 'selected'; ?>>Cool/Cold</option>
													<option value="Warm/Hot" <?php if($result_pcr_asmt_SKIN == "Warm/Hot") echo 'selected'; ?>>Warm/Hot</option>
													<option value="Normal" <?php if($result_pcr_asmt_SKIN == "Normal") echo 'selected'; ?>>Normal</option>
													<option value="Dry" <?php if($result_pcr_asmt_SKIN == "Dry") echo 'selected'; ?>>Dry</option>
													<option value="Moist" <?php if($result_pcr_asmt_SKIN == "Moist") echo 'selected'; ?>>Moist</option>
													<option value="Diaphonetic" <?php if($result_pcr_asmt_SKIN == "Diaphonetic") echo 'selected'; ?>>Diaphonetic</option>
													<option value="Jaundiced" <?php if($result_pcr_asmt_SKIN == "Jaundiced") echo 'selected'; ?>>Jaundiced</option>
													<option value="Cyanotic" <?php if($result_pcr_asmt_SKIN == "Cyanotic") echo 'selected'; ?>>Cyanotic</option>
													<option value="Pale/Ashen" <?php if($result_pcr_asmt_SKIN == "Pale/Ashen") echo 'selected'; ?>>Pale/Ashen</option>
													<option value="Cherry" <?php if($result_pcr_asmt_SKIN == "Cherry") echo 'selected'; ?>>Cherry</option>
													<option value="Flushed" <?php if($result_pcr_asmt_SKIN == "Flushed") echo 'selected'; ?>>Flushed</option>
													<option value="N/A" <?php if($result_pcr_asmt_SKIN == "N/A") echo 'selected'; ?>>N/A</option>
												</select>
											</div> 
										</div>
										
										<div class="col-lg-3">
											<!-- Eyes Left -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Eyes (Left)</span>
												<select id="pat_LEYE" name="pat_LEYE" class="form-control">
													<option selected value=""> -- </option>
													<option value="PERRL"  <?php if($result_pcr_asmt_EYESL == "PERRL") echo 'selected'; ?>>PERRL</option>
													<option value="Reactive"  <?php if($result_pcr_asmt_EYESL == "Reactive") echo 'selected'; ?>>Reactive</option>
													<option value="Nonreactive"  <?php if($result_pcr_asmt_EYESL == "Nonreactive") echo 'selected'; ?>>Nonreactive</option>
													<option value="Constricted"  <?php if($result_pcr_asmt_EYESL == "Constricted") echo 'selected'; ?>>Constricted</option>
													<option value="Dilated"  <?php if($result_pcr_asmt_EYESL == "Dilated") echo 'selected'; ?>>Dilated</option>
													<option value="Blind"  <?php if($result_pcr_asmt_EYESL == "Blind") echo 'selected'; ?>>Blind</option>
													<option value="Cataract"  <?php if($result_pcr_asmt_EYESL == "Cataract") echo 'selected'; ?>>Cataract</option>
													<option value="Glaucoma"  <?php if($result_pcr_asmt_EYESL == "Glaucoma") echo 'selected'; ?>>Glaucoma</option>
												</select>
											</div>
										</div>
										
										<div class="col-lg-3">
											<!-- Eyes Right -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Eyes (Right)</span>
												<select id="pat_REYE" name="pat_REYE" class="form-control">
													<option selected value=""> -- </option>
													<option value="PERRL" <?php if($result_pcr_asmt_EYESR == "PERRL") echo 'selected'; ?>>PERRL</option>
													<option value="Reactive" <?php if($result_pcr_asmt_EYESR == "Reactive") echo 'selected'; ?>>Reactive</option>
													<option value="Nonreactive" <?php if($result_pcr_asmt_EYESR == "Nonreactive") echo 'selected'; ?>>Nonreactive</option>
													<option value="Constricted" <?php if($result_pcr_asmt_EYESR == "Constricted") echo 'selected'; ?>>Constricted</option>
													<option value="Dilated" <?php if($result_pcr_asmt_EYESR == "Dilated") echo 'selected'; ?>>Dilated</option>
													<option value="Blind" <?php if($result_pcr_asmt_EYESR == "Blind") echo 'selected'; ?>>Blind</option>
													<option value="Cataract" <?php if($result_pcr_asmt_EYESR == "Cataract") echo 'selected'; ?>>Cataract</option>
													<option value="Glaucoma" <?php if($result_pcr_asmt_EYESR == "Glaucoma") echo 'selected'; ?>>Glaucoma</option>
												</select>
											</div>
										</div>
									</div>
									
									
									<!-- Pain -->
									<h4>Pain</h4>
									<div class="row">
										<div class="col-lg-3">
											<!-- Provoke -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Provoke</span>
												<input id="pat_PPROV" name="pat_PPROV" type="text" class="form-control" value="<?php echo $result_pcr_asmt_PAINPROVOKE; ?>">
											</div>
										</div>
										
										<div class="col-lg-3">
											<!-- Quality -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Quality</span>
												<select id="pat_PQTY" name="pat_PQTY" class="form-control">
													<option selected value=""> -- </option>
													<option value="Sharp" <?php if($result_pcr_asmt_PAINQUALITY == "Sharp") echo 'selected'; ?>>Sharp</option>
													<option value="Dull" <?php if($result_pcr_asmt_PAINQUALITY == "Dull") echo 'selected'; ?>>Dull</option>
													<option value="Cramp" <?php if($result_pcr_asmt_PAINQUALITY == "Cramp") echo 'selected'; ?>>Cramp</option>
													<option value="Crushing" <?php if($result_pcr_asmt_PAINQUALITY == "Crushing") echo 'selected'; ?>>Crushing</option>
													<option value="Constant" <?php if($result_pcr_asmt_PAINQUALITY == "Constant") echo 'selected'; ?>>Constant</option>
												</select>
											</div>
										</div>
										
										<div class="col-lg-2">
											<!-- Radiate -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Radiate</span>
												<select id="pat_PRAD" name="pat_PRAD" class="form-control">
													<option selected value=""> -- </option>
													<option value="0" <?php if($result_pcr_asmt_PAINRADIATE == 0) echo 'selected'; ?>>No</option>
													<option value="1" <?php if($result_pcr_asmt_PAINRADIATE == 1) echo 'selected'; ?>>Yes</option>
												</select>
											</div>
										</div>
										
										<div class="col-lg-2">
											<!-- Severity -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Severity</span>
												<input id="pat_PSEV" name="pat_PSEV" type="number" min="1" max="10" class="form-control" value="<?php echo $result_pcr_asmt_PAINSEVERITY; ?>">
											</div>
										</div>
										
										<div class="col-lg-2">
											<!-- Onset -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Onset</span>
												<select id="pat_ON" name="pat_ON" class="form-control">
													<option selected value=""> -- </option>
													<option value="0-15 minutes" <?php if($result_pcr_asmt_PAINONSET == "0-15 minutes") echo 'selected'; ?>>0-15 minutes</option>
													<option value="15-80 minutes" <?php if($result_pcr_asmt_PAINONSET == "15-80 minutes") echo 'selected'; ?>>15-80 minutes</option>
													<option value="1-12 hours" <?php if($result_pcr_asmt_PAINONSET == "1-12 hours") echo 'selected'; ?>>1-12 hours</option>
													<option value="12-24 minutes" <?php if($result_pcr_asmt_PAINONSET == "12-24 minutes") echo 'selected'; ?>>12-24 minutes</option>
												</select>
											</div>
										</div>
										
									</div>
									
									<!-- O2 and LOC -->
									<div class="row">
										<div class="col-lg-6">
											<h4>O<sub>2</sub></h4>
										</div>
										<div class="col-lg-6">
											<h4><abbr title="Level of Consciousness">LoC</abbr></h4>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-2">
											<!-- Given -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Given</span>
												<select id="pat_O2GIVE" name="pat_O2GIVE" class="form-control">
													<option selected value=""> -- </option>
													<option value="0" <?php if($result_pcr_asmt_O2GIVEN == 0) echo 'selected'; ?>>No</option>
													<option value="1" <?php if($result_pcr_asmt_O2GIVEN == 1) echo 'selected'; ?>>Yes</option>
												</select>
											</div>
										</div>

										<div class="col-lg-2">
											<!-- Type -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Type</span>
												<select id="pat_O2TYPE" name="pat_O2TYPE" class="form-control">
													<option selected value=""> -- </option>
													<option value="BVM" <?php if($result_pcr_asmt_O2TYPE == "BVM") echo 'selected'; ?>>BVM</option>
													<option value="Cannula" <?php if($result_pcr_asmt_O2TYPE == "Cannula") echo 'selected'; ?>>Cannula</option>
													<option value="Mask" <?php if($result_pcr_asmt_O2TYPE == "Mask") echo 'selected'; ?>>Mask</option>
												</select>
											</div>
										</div>
										
										<div class="col-lg-2">
											<!-- Rate -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Rate</span>
												<input id="pat_O2RATE" name="pat_O2RATE" type="number" min="1" class="form-control" value="<?php echo $result_pcr_asmt_O2RATE; ?>">
											</div>
										</div>
										
										<div class="col-lg-3">
											<!-- LoC -->
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">LoC</span>
												<select id="pat_LOC" name="pat_LOC" class="form-control">
													<option selected value=""> -- </option>
													<option value="Alert 1" <?php if($result_pcr_asmt_LOC == "Alert 1") echo 'selected'; ?>>Alert 1</option>
													<option value="Alert 2" <?php if($result_pcr_asmt_LOC == "Alert 2") echo 'selected'; ?>>Alert 2</option>
													<option value="Alert 3" <?php if($result_pcr_asmt_LOC == "Alert 3") echo 'selected'; ?>>Alert 3</option>
													<option value="Responds to verbal" <?php if($result_pcr_asmt_LOC == "Responds to verbal") echo 'selected'; ?>>Responds to verbal</option>
													<option value="Responds to pain" <?php if($result_pcr_asmt_LOC == "Responds to pain") echo 'selected'; ?>>Responds to pain</option>
													<option value="Unresponsive" <?php if($result_pcr_asmt_LOC == "Unresponsive") echo 'selected'; ?>>Unresponsive</option>
												</select>
											</div>
										</div>
										
									</div>
									<br />
									
									<!-- GCS -->
									<h4><abbr title="Glasgow Coma Scale">GCS</abbr></h4>
									<div class="row">
										<div class="col-lg-1">
											<br /><br/><br />
											<p>Baseline</p>
											<br />
											<p>Enroute</p>
										</div>
										
										<!-- Eye Opening -->
										<div class="col-lg-1">
											<h5>Eye Opening</h5>
											<input id="pat_BS_EYE" name="pat_BS_EYE" type="number" min="1" max="4" value="<?php echo $result_pcr_gcs_BASEEYE; ?>" class="form-control gcs-bs">
											<br />
											<input id="pat_EN_EYE" name="pat_EN_EYE" type="number" min="1" max="4" value="<?php echo $result_pcr_gcs_ENROUTEEYE; ?>" class="form-control gcs-en">
										</div>
										
										<!-- Verbal Response -->
										<div class="col-lg-1">
											<h5>Verbal Response</h5>
											<input id="pat_BS_VERB" name="pat_BS_VERB" type="number" min="1" max="5" value="<?php echo $result_pcr_gcs_BASEVERBAL; ?>" class="form-control gcs-bs">
											<br />
											<input id="pat_EN_VERB" name="pat_EN_VERB" type="number" min="1" max="5" value="<?php echo $result_pcr_gcs_ENROUTEVERBAL; ?>" class="form-control gcs-en">
										</div>
										
										<!-- Motor Response -->
										<div class="col-lg-1">
											<h5>Motor Response</h5>
											<input id="pat_BS_MOT" name="pat_BS_MOT" type="number" min="1" max="6" value="<?php echo $result_pcr_gcs_BASEMOTOR; ?>" class="form-control gcs-bs">
											<br />
											<input id="pat_EN_MOT" name="pat_EN_MOT" type="number" min="1" max="6" value="<?php echo $result_pcr_gcs_ENROUTEMOTOR; ?>" class="form-control gcs-en">
										</div>
										
										<!-- Total -->
										<div class="col-lg-1">
											<h5>Total &nbsp&nbsp GCS</h5>
											<input id="pat_BS_TOTAL" name="pat_BS_TOTAL" type="text" class="form-control" value="<?php echo $result_pcr_gcs_BASEGCS; ?>" readonly>
											<br />
											<input id="pat_EN_TOTAL" name="pat_EN_TOTAL" type="text" class="form-control" value="<?php echo $result_pcr_gcs_ENROUTEGCS; ?>" readonly>
										</div>
										
									</div>
									
								</div>
								
								<br />
								
								<!-- Physical Examination -->
								<h4>
									Physical Examination &nbsp
									<span><a class="btn btn-info btn-sm" data-toggle="collapse" data-target="#physical-section">Show</a></span>
								</h4>
								
								<div class="collapse" id="physical-section">
									<hr />
									
									<div class="row">
										<div class="col-lg-12">
											<table class="table table-striped" style="table-layout: fixed;">
												<thead>
													<tr>
														<th></th>
														<th>Pain</th>
														<th>Blunt Trauma</th>
														<th>Dislocation/ Fracture</th>
														<th>Gunshot</th>
														<th>Laceration/ Abrasion</th>
														<th>Puncture/ Stab</th>
														<th>Soft Tissue Swelling</th>
														<th>Burn</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Head/Face</td>
														<td><input name="phys_EXAM[]" value="Head/Face_Pain" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Head/Face_Pain") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Head/Face_Blunt Trauma" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Head/Face_Blunt Trauma") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Head/Face_Dislocation/Fracture" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Head/Face_Dislocation/Fracture") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Head/Face_Gunshot" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Head/Face_Gunshot") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Head/Face_Laceration/Abrasion" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Head/Face_Laceration/Abrasion") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Head/Face_Puncture/Stab" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Head/Face_Puncture/Stab") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Head/Face_Soft Tissue Swelling" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Head/Face_Soft Tissue Swelling") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Head/Face_Burn" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Head/Face_Burn") {echo 'checked';} } ?>/></td>
													</tr>
													
													<tr>
														<td>Neck</td>
														<td><input name="phys_EXAM[]" value="Neck_Pain" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Neck_Pain") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Neck_Blunt Trauma" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Neck_Blunt Trauma") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Neck_Dislocation/Fracture" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Neck_Dislocation/Fracture") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Neck_Gunshot" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Neck_Gunshot") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Neck_Laceration/Abrasion" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Neck_Laceration/Abrasion") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Neck_Puncture/Stab" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Neck_Puncture/Stab") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Neck_Soft Tissue Swelling" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Neck_Soft Tissue Swelling") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Neck_Burn" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Neck_Burn") {echo 'checked';} } ?>/></td>
													</tr>
													
													<tr>
														<td>Chest/Axilla</td>
														<td><input name="phys_EXAM[]" value="Chest/Axilla_Pain" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Chest/Axilla_Pain") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Chest/Axilla_Blunt Trauma" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Chest/Axilla_Blunt Trauma") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Chest/Axilla_Dislocation/Fracture" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Chest/Axilla_Dislocation/Fracture") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Chest/Axilla_Gunshot" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Chest/Axilla_Gunshot") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Chest/Axilla_Laceration/Abrasion" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Chest/Axilla_Laceration/Abrasion") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Chest/Axilla_Puncture/Stab" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Chest/Axilla_Puncture/Stab") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Chest/Axilla_Soft Tissue Swelling" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Chest/Axilla_Soft Tissue Swelling") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Chest/Axilla_Burn" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Chest/Axilla_Burn") {echo 'checked';} } ?>/></td>
													</tr>
													
													<tr>
														<td>Abdomen</td>
														<td><input name="phys_EXAM[]" value="Abdomen_Pain" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Abdomen_Pain") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Abdomen_Blunt Trauma" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Abdomen_Blunt Trauma") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Abdomen_Dislocation/Fracture" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Abdomen_Dislocation/Fracture") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Abdomen_Gunshot" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Abdomen_Gunshot") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Abdomen_Laceration/Abrasion" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Abdomen_Laceration/Abrasion") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Abdomen_Puncture/Stab" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Abdomen_Puncture/Stab") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Abdomen_Soft Tissue Swelling" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Abdomen_Soft Tissue Swelling") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Abdomen_Burn" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Abdomen_Burn") {echo 'checked';} } ?>/></td>
													</tr>
													
													<tr>
														<td>Back/Flank</td>
														<td><input name="phys_EXAM[]" value="Back/Flank_Pain" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Back/Flank_Pain") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Back/Flank_Blunt Trauma" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Back/Flank_Blunt Trauma") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Back/Flank_Dislocation/Fracture" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Back/Flank_Dislocation/Fracture") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Back/Flank_Gunshot" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Back/Flank_Gunshot") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Back/Flank_Laceration/Abrasion" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Back/Flank_Laceration/Abrasion") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Back/Flank_Puncture/Stab" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Back/Flank_Puncture/Stab") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Back/Flank_Soft Tissue Swelling" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Back/Flank_Soft Tissue Swelling") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Back/Flank_Burn" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Back/Flank_Burn") {echo 'checked';} } ?>/></td>
													</tr>
													
													<tr>
														<td>Pelvis/Hip</td>
														<td><input name="phys_EXAM[]" value="Pelvis/Hip_Pain" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Pelvis/Hip_Pain") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Pelvis/Hip_Blunt Trauma" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Pelvis/Hip_Blunt Trauma") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Pelvis/Hip_Dislocation/Fracture" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Pelvis/Hip_Dislocation/Fracture") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Pelvis/Hip_Gunshot" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Pelvis/Hip_Gunshot") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Pelvis/Hip_Laceration/Abrasion" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Pelvis/Hip_Laceration/Abrasion") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Pelvis/Hip_Puncture/Stab" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Pelvis/Hip_Puncture/Stab") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Pelvis/Hip_Soft Tissue Swelling" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Pelvis/Hip_Soft Tissue Swelling") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Pelvis/Hip_Burn" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Pelvis/Hip_Burn") {echo 'checked';} } ?>/></td>
													</tr>
													
													<tr>
														<td>Left Arm</td>
														<td><input name="phys_EXAM[]" value="Left Arm_Pain" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Arm_Pain") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Arm_Blunt Trauma" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Arm_Blunt Trauma") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Arm_Dislocation/Fracture" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Arm_Dislocation/Fracture") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Arm_Gunshot" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Arm_Gunshot") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Arm_Laceration/Abrasion" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Arm_Laceration/Abrasion") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Arm_Puncture/Stab" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Arm_Puncture/Stab") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Arm_Soft Tissue Swelling" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Arm_Soft Tissue Swelling") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Arm_Burn" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Arm_Burn") {echo 'checked';} } ?>/></td>
													</tr>
													
													<tr>
														<td>Right Arm</td>
														<td><input name="phys_EXAM[]" value="Right Arm_Pain" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Arm_Pain") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Arm_Blunt Trauma" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Arm_Blunt Trauma") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Arm_Dislocation/Fracture" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Arm_Dislocation/Fracture") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Arm_Gunshot" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Arm_Gunshot") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Arm_Laceration/Abrasion" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Arm_Laceration/Abrasion") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Arm_Puncture/Stab" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Arm_Puncture/Stab") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Arm_Soft Tissue Swelling" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Arm_Soft Tissue Swelling") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Arm_Burn" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Arm_Burn") {echo 'checked';} } ?>/></td>
													</tr>
													
													<tr>
														<td>Left Leg</td>
														<td><input name="phys_EXAM[]" value="Left Leg_Pain" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Leg_Pain") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Leg_Blunt Trauma" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Leg_Blunt Trauma") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Leg_Dislocation/Fracture" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Leg_Dislocation/Fracture") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Leg_Gunshot" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Leg_Gunshot") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Leg_Laceration/Abrasion" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Leg_Laceration/Abrasion") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Leg_Puncture/Stab" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Leg_Puncture/Stab") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Leg_Soft Tissue Swelling" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Leg_Soft Tissue Swelling") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Left Leg_Burn" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Left Leg_Burn") {echo 'checked';} } ?>/></td>
													</tr>
													
													<tr>
														<td>Right Leg</td>
														<td><input name="phys_EXAM[]" value="Right Leg_Pain" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Leg_Pain") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Leg_Blunt Trauma" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Leg_Blunt Trauma") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Leg_Dislocation/Fracture" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Leg_Dislocation/Fracture") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Leg_Gunshot" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Leg_Gunshot") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Leg_Laceration/Abrasion" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Leg_Laceration/Abrasion") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Leg_Puncture/Stab" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Leg_Puncture/Stab") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Leg_Soft Tissue Swelling" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Leg_Soft Tissue Swelling") {echo 'checked';} } ?>/></td>
														<td><input name="phys_EXAM[]" value="Right Leg_Burn" type="checkbox" <?php foreach($result_pcr_physex_entry as $value) { if($value == "Right Leg_Burn") {echo 'checked';} } ?>/></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								
								<br />
								
								<!-- History -->
								<h4>
									History &nbsp
									<span><a class="btn btn-info btn-sm" data-toggle="collapse" data-target="#history-section">Show</a></span>
								</h4>
								
								<div class="collapse" id="history-section">
									<hr />
									<p class="bg-warning text-center">Separate each entry with a comma.</p>
									
									<div class="row">
										<!-- Allergies -->
										<div class="col-lg-4">
											<h4>Allergies</h4>
											<textarea id="his_ALGY" name="his_ALGY" class="form-control" rows="3"><?php echo $result_pcr_asmtallergies_string; ?></textarea>
										</div>
										
										<!-- Medications -->
										<div class="col-lg-4">
											<h4>Medications</h4>
											<textarea id="his_MED" name="his_MED" class="form-control" rows="3"><?php echo $result_pcr_asmtmed_string; ?></textarea>
										</div>
										
										<!-- Past Medical History -->
										<div class="col-lg-4">
											<h4>Past Medical History</h4>
											<textarea id="his_HTY" name="his_HTY" class="form-control" rows="3"><?php echo $result_pcr_asmtpmh_string; ?></textarea>
										</div>
										
									</div>
								</div>
								
							</div>
						</div>
					</div>
					
					<!-- Treatment -->
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title text-center">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
								<span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span> Treatment</a>
							</h4>
						</div>
						<div id="collapse4" class="panel-collapse collapse">
							<div class="panel-body">
							
								<!-- Interventions -->
								<h4>
									Interventions &nbsp
									<span><a class="btn btn-info btn-sm" data-toggle="collapse" data-target="#interventions-section">Show</a></span>
								</h4>
								
								<div class="collapse" id="interventions-section">
									<hr />
									<div class="row">
										<!-- Interventions -->
										<div class="col-lg-6">
											<h4>Interventions</h4>
											<textarea id="int_INTER" name="int_INTER" class="form-control" rows="3"><?php echo $result_pcr_treatment_INTERVENTIONS; ?></textarea>
										</div>
										
										<!-- Response -->
										<div class="col-lg-6">
											<h4>Response to Treatment</h4>
											<textarea id="int_RESP" name="int_RESP" class="form-control" rows="3"><?php echo $result_pcr_treatment_RESPONSE; ?></textarea>
										</div>
									</div>
								</div>
								<br />
								
								<!-- Actions -->
								<h4>
									Actions &nbsp
									<span><a class="btn btn-info btn-sm" data-toggle="collapse" data-target="#actions-section">Show</a></span>
								</h4>
								
								<div class="collapse" id="actions-section">
									<hr />
				
									<div class="row">
										<!-- CPR -->
										<div class="col-lg-2">
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">CPR</span>
												<select id="act_CPR" name="act_CPR" class="form-control">
													<option selected value=""> -- </option>
													<option value="1" <?php if($result_pcr_treatment_CPR == 1) echo 'selected'; ?>>Yes</option>
													<option value="0" <?php if($result_pcr_treatment_CPR == 0) echo 'selected'; ?>>No</option>
												</select>
											</div>
										</div>
										
										<!-- CPR Time -->
										<div class="col-lg-2">
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">CPR Time</span>
												<input id="act_TIME" name="act_TIME" type="text" class="form-control time-only" value="<?php echo $result_pcr_treatment_CPRTIME; ?>" />
											</div>
										</div>
										
										<!-- Defib -->
										<div class="col-lg-3">
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Defibrillator</span>
												<select id="act_DEFIB" name="act_DEFIB" class="form-control">
													<option selected value=""> -- </option>
													<option value="1" <?php if($result_pcr_treatment_DEFIB == 1) echo 'selected'; ?>>Yes</option>
													<option value="0"<?php if($result_pcr_treatment_DEFIB == 0) echo 'selected'; ?>>No</option>
												</select>
											</div>
										</div>
										
										<!-- Return of Pulse -->
										<div class="col-lg-3">
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Return of Pulse</span>
												<select id="act_PULSERET" name="act_PULSERET" class="form-control">
													<option selected value=""> -- </option>
													<option value="1" <?php if($result_pcr_treatment_RETRNPULSE == 1) echo 'selected'; ?>>Yes</option>
													<option value="0" <?php if($result_pcr_treatment_RETRNPULSE == 0) echo 'selected'; ?>>No</option>
												</select>
											</div>
										</div>
										
										<!-- Rate -->
										<div class="col-lg-2">
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Rate</span>
												<input id="act_PULSERATE" name="act_PULSERATE" type="number" min="1" class="form-control" value="<?php echo $result_pcr_treatment_RTRNPULSERATE; ?>" />
											</div>
										</div>
									</div>
									<br />
									
									<div class="row">
										<!-- Respirations -->
										<div class="col-lg-3">
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Respirations</span>
												<select id="act_RESP" name="act_RESP" class="form-control">
													<option selected value=""> -- </option>
													<option value="1" <?php if($result_pcr_treatment_RESP == 1) echo 'selected'; ?>>Yes</option>
													<option value="0" <?php if($result_pcr_treatment_RESP == 0) echo 'selected'; ?>>No</option>
												</select>
											</div>
										</div>
										
										<!-- Rate -->
										<div class="col-lg-2">
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Rate</span>
												<input id="act_RESPRATE" name="act_RESPRATE" type="number" min="1" class="form-control" value="<?php echo $result_pcr_treatment_RESPRATE; ?>" />
											</div>
										</div>
									</div>
								</div>
								<br />
								
								<!-- Narrative -->
								<h4>
									Narrative &nbsp
									<span><a class="btn btn-info btn-sm" data-toggle="collapse" data-target="#narrative-section">Show</a></span>
								</h4>
								
								<div class="collapse" id="narrative-section">
									<hr />
									<div class="row">
										<!-- Narrative -->
										<div class="col-lg-12">
											<textarea id="nar_NARRATIVE" name="nar_NARRATIVE" class="form-control" rows="3"><?php echo $result_pcr_treatment_NARRATIVE; ?></textarea>
										</div>
									</div>
								</div>
								<br />
								
								<!-- Endorsement -->
								<h4>
									Endorsement &nbsp
									<span><a class="btn btn-info btn-sm" data-toggle="collapse" data-target="#endo-section">Show</a></span>
								</h4>
								
								<div class="collapse" id="endo-section">
									<hr />
									
									<div class="row">
										<!-- Crew Box -->
										<div class="col-lg-4">
											<h4>Crew Box</h4>
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Name</span>
												<input id="endo_NAME1" name="endo_NAME[]" type="text" class="form-control" value="<?php echo $result_pcr_crew_NAME[0]; ?>" />
											</div> <br />
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Name</span>
												<input id="endo_NAME2" name="endo_NAME[]" type="text" class="form-control" value="<?php echo $result_pcr_crew_NAME[1]; ?>" />
											</div> <br />
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Name</span>
												<input id="endo_NAME3" name="endo_NAME[]" type="text" class="form-control" value="<?php echo $result_pcr_crew_NAME[2]; ?>" />
											</div> <br />
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Name</span>
												<input id="endo_NAME4" name="endo_NAME[]" type="text" class="form-control" value="<?php echo $result_pcr_crew_NAME[3]; ?>" />
											</div> <br />
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Name</span>
												<input id="endo_NAME5" name="endo_NAME[]" type="text" class="form-control" value="<?php echo $result_pcr_crew_NAME[4]; ?>" />
											</div>
										</div>
										<!-- Level Crew -->
										<div class="col-lg-3">
											<br /><br />
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Level</span>
												<input id="endo_LEVEL1" name="endo_LEVEL[]" type="text" class="form-control" value="<?php echo $result_pcr_crew_LEVEL[0]; ?>" />
											</div> <br />
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Level</span>
												<input id="endo_LEVEL2" name="endo_LEVEL[]" type="text" class="form-control" value="<?php echo $result_pcr_crew_LEVEL[1]; ?>" />
											</div> <br />
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Level</span>
												<input id="endo_LEVEL3" name="endo_LEVEL[]" type="text" class="form-control" value="<?php echo $result_pcr_crew_LEVEL[2]; ?>" />
											</div> <br />
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Level</span>
												<input id="endo_LEVEL4" name="endo_LEVEL[]" type="text" class="form-control" value="<?php echo $result_pcr_crew_LEVEL[3]; ?>" />
											</div> <br />
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Level</span>
												<input id="endo_LEVEL5" name="endo_LEVEL[]" type="text" class="form-control" value="<?php echo $result_pcr_crew_LEVEL[4]; ?>" />
											</div>
										</div>
										
										<!-- Prepared By, Med Dir, Endo -->
										<div class="col-lg-5">
											<br /><br />
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Prepared By</span>
												<input id="endo_PREP" name="endo_PREP" type="text" class="form-control" value="<?php echo $result_pcr_endorse_PREPAREDBY; ?>" />
											</div> <br />

											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Medical Director</span>
												<input id="endo_DIR" name="endo_DIR" type="text" class="form-control" value="<?php echo $result_pcr_endorse_MEDDIR; ?>" />
											</div> <br />
											
											<div class="input-group">
												<span class="input-group-addon" id="basic-addon1">Endorsed To</span>
												<input id="endo_TO" name="endo_TO" type="text" class="form-control" value="<?php echo $result_pcr_endorse_ENDORSEDTO; ?>" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<a type="button" class="btn btn-default pull-right" href="CdrrmoManagePCR.php"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Back</a>
			</div>
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
		jQuery('.date-only').datetimepicker({
			timepicker:false,
			format: 'Y-m-d',
			maxDate: '0'
		});
		
		jQuery('.time-only').datetimepicker({
			datepicker:false,
			format:'H:i'
		});
	</script>
</body>
</html>