<?php
/*
 *__________________________________________________________________________________________________________
 *
 * TITLE: 			Add PCR Form
 * DESCRIPTION: 	Code for adding pcr information in 
 * 					a multitude to tables T_T
 *__________________________________________________________________________________________________________
 *
 * VARIABLES:
 *
 * $_POST['runInfo_UNITNAME'];
 * $_POST['runInfo_RESPONSE'];
 * $_POST['runInfo_PCRNO'];
 * $_POST['runInfo_LOCATION']; 
 * $_POST['runInfo_DATE']; 
 * $_POST['runInfo_DISPO']; 
 * $_POST['runInfo_ADDRESS'];
 * $_POST['runInfo_IMAGE'];
 *
 * $_POST['track_RECEIVED'];
 * $_POST['track_DEPART'];
 * $_POST['track_ENROUTE'];
 * $_POST['track_DEST'];
 * $_POST['track_SCENE'];
 * $_POST['track_SERVE'];
 * $_POST['track_PAT'];
 * $_POST['track_END'];
 * $_POST['track_BEGIN'];
 * $_POST['track_TOTAL'];
 *
 * $_POST['patInfo_NAME'];
 * $_POST['patInfo_AGE'];
 * $_POST['patInfo_GENDER'];
 * $_POST['patInfo_DOB'];
 * $_POST['patInfo_ADDRESS'];
 *
 * $_POST['resInfo_NAME'];
 * $_POST['resInfo_REL'];
 * $_POST['resInfo_CONTACT'];
 * $_POST['resInfo_ADDRESS'];
 *
 * $_POST['vit_COMP'];
 * $_POST['vit_NOI'];
 * $_POST['vit_BS_TIME'];
 * $_POST['vit_EN_TIME'];
 * $_POST['vit_BS_BP'];
 * $_POST['vit_EN_BP'];
 * $_POST['vit_BS_PULSE'];
 * $_POST['vit_EN_PULSE'];
 * $_POST['vit_PULSEQTY'];
 * $_POST['vit_BS_RES'];
 * $_POST['vit_EN_RES'];
 * $_POST['vit_RESQTY'];
 * $_POST['vit_BS_SPO'];
 * $_POST['vit_EN_SPO'];
 * $_POST['vit_BS_CBG'];
 * $_POST['vit_EN_CBG'];
 *
 * $_POST['pat_SKIN'];
 * $_POST['pat_LEYE'];
 * $_POST['pat_REYE'];
 * $_POST['pat_PPROV'];
 * $_POST['pat_PQTY'];
 * $_POST['pat_PRAD'];
 * $_POST['pat_PSEV'];
 * $_POST['pat_ON'];
 * $_POST['pat_O2GIVE'];
 * $_POST['pat_O2TYPE'];
 * $_POST['pat_O2RATE'];
 * $_POST['pat_LOC'];
 * $_POST['pat_BS_EYE'];
 * $_POST['pat_EN_EYE'];
 * $_POST['pat_BS_VERB'];
 * $_POST['pat_EN_VERB'];
 * $_POST['pat_BS_MOT'];
 * $_POST['pat_EN_MOT'];
 * $_POST['pat_BS_TOTAL'];
 * $_POST['pat_EN_TOTAL'];
 *
 * $_POST['his_ALGY'];
 * $_POST['his_MED'];
 * $_POST['his_HTY'];
 *
 * $_POST['phys_EXAM[]'];
 *
 * $_POST['int_INTER'];
 * $_POST['int_RESP'];
 *
 * $_POST['act_CPR'];
 * $_POST['act_TIME'];
 * $_POST['act_DEFIB'];
 * $_POST['act_PULSERET'];
 * $_POST['act_PULSERATE'];
 * $_POST['act_RESP'];
 * $_POST['act_RESPRATE'];
 *
 * $_POST['nar_NARRATIVE'];
 *
 * $_POST['endo_PREP'];
 * $_POST['endo_DIR'];
 * $_POST['endo_TO'];
 * $_POST['endo_LEVEL[]'];
 * $_POST['endo_NAME[]'];
 *
 *__________________________________________________________________________________________________________
 *
 */
session_start();
include('../form/connection.php');
include ('../function/functions.php');
$db = new db();

$session_USER_USERNAME = $_SESSION['USER_USERNAME'];
$form_runInfo_UNITNAME = $db->connection->real_escape_string($_POST['runInfo_UNITNAME']);//
$form_runInfo_RESPONSE = $db->connection->real_escape_string($_POST['runInfo_RESPONSE']);//
$form_runInfo_PCRNO = $db->connection->real_escape_string($_POST['runInfo_PCRNO']);//
$form_runInfo_LOCATION = $db->connection->real_escape_string($_POST['runInfo_LOCATION']);//
$form_runInfo_DATE = $db->connection->real_escape_string($_POST['runInfo_DATE']);//
$form_runInfo_DISPO = $db->connection->real_escape_string($_POST['runInfo_DISPO']);//
$form_runInfo_ADDRESS = $db->connection->real_escape_string($_POST['runInfo_ADDRESS']);//
$form_runInfo_IMAGE = addslashes(file_get_contents($_FILES['runInfo_IMAGE']['tmp_name']));//

$form_track_RECEIVED = $db->connection->real_escape_string($_POST['track_RECEIVED']);//
$form_track_DEPART = $db->connection->real_escape_string($_POST['track_DEPART']);//
$form_track_ENROUTE = $db->connection->real_escape_string($_POST['track_ENROUTE']);//
$form_track_DEST = $db->connection->real_escape_string($_POST['track_DEST']);//
$form_track_SCENE = $db->connection->real_escape_string($_POST['track_SCENE']);//
$form_track_SERVE = $db->connection->real_escape_string($_POST['track_SERVE']);//
$form_track_PAT = $db->connection->real_escape_string($_POST['track_PAT']);//
$form_track_END = $db->connection->real_escape_string($_POST['track_END']);//
$form_track_BEGIN = $db->connection->real_escape_string($_POST['track_BEGIN']);//
$form_track_TOTAL = $db->connection->real_escape_string($_POST['track_TOTAL']);//

$form_patInfo_NAME = $db->connection->real_escape_string($_POST['patInfo_NAME']);//
$form_patInfo_AGE = $db->connection->real_escape_string($_POST['patInfo_AGE']);//
$form_patInfo_GENDER = $db->connection->real_escape_string($_POST['patInfo_GENDER']);//
$form_patInfo_DOB = $db->connection->real_escape_string($_POST['patInfo_DOB']);//
$form_patInfo_ADDRESS = $db->connection->real_escape_string($_POST['patInfo_ADDRESS']);//

$form_resInfo_NAME = $db->connection->real_escape_string($_POST['resInfo_NAME']);//
$form_resInfo_REL = $db->connection->real_escape_string($_POST['resInfo_REL']);//
$form_resInfo_CONTACT = $db->connection->real_escape_string($_POST['resInfo_CONTACT']);//
$form_resInfo_ADDRESS = $db->connection->real_escape_string($_POST['resInfo_ADDRESS']);//

$form_vit_COMP = $db->connection->real_escape_string($_POST['vit_COMP']);//
$form_vit_NOI = $db->connection->real_escape_string($_POST['vit_NOI']);//
$form_vit_BS_TIME = $db->connection->real_escape_string($_POST['vit_BS_TIME']);//
$form_vit_EN_TIME = $db->connection->real_escape_string($_POST['vit_EN_TIME']);//
$form_vit_BS_BP = $db->connection->real_escape_string($_POST['vit_BS_BP']);//
$form_vit_EN_BP = $db->connection->real_escape_string($_POST['vit_EN_BP']);//
$form_vit_BS_PULSE = $db->connection->real_escape_string($_POST['vit_BS_PULSE']);//
$form_vit_EN_PULSE = $db->connection->real_escape_string($_POST['vit_EN_PULSE']);//
$form_vit_PULSEQTY = $db->connection->real_escape_string($_POST['vit_PULSEQTY']);//
$form_vit_BS_RES = $db->connection->real_escape_string($_POST['vit_BS_RES']);//
$form_vit_EN_RES = $db->connection->real_escape_string($_POST['vit_EN_RES']);//
$form_vit_RESQTY = $db->connection->real_escape_string($_POST['vit_RESQTY']);//
$form_vit_BS_SPO = $db->connection->real_escape_string($_POST['vit_BS_SPO']);//
$form_vit_EN_SPO = $db->connection->real_escape_string($_POST['vit_EN_SPO']);//
$form_vit_BS_CBG = $db->connection->real_escape_string($_POST['vit_BS_CBG']);//
$form_vit_EN_CBG = $db->connection->real_escape_string($_POST['vit_EN_CBG']);//

$form_pat_SKIN = $db->connection->real_escape_string($_POST['pat_SKIN']);//
$form_pat_LEYE = $db->connection->real_escape_string($_POST['pat_LEYE']);//
$form_pat_REYE = $db->connection->real_escape_string($_POST['pat_REYE']);//
$form_pat_PPROV = $db->connection->real_escape_string($_POST['pat_PPROV']);//
$form_pat_PQTY = $db->connection->real_escape_string($_POST['pat_PQTY']);//
$form_pat_PRAD = $db->connection->real_escape_string($_POST['pat_PRAD']);//
$form_pat_PSEV = $db->connection->real_escape_string($_POST['pat_PSEV']);//
$form_pat_ON = $db->connection->real_escape_string($_POST['pat_ON']);//
$form_pat_O2GIVE = $db->connection->real_escape_string($_POST['pat_O2GIVE']);//
$form_pat_O2TYPE = $db->connection->real_escape_string($_POST['pat_O2TYPE']);//
$form_pat_O2RATE = $db->connection->real_escape_string($_POST['pat_O2RATE']);//
$form_pat_LOC = $db->connection->real_escape_string($_POST['pat_LOC']);//
$form_pat_BS_EYE = $db->connection->real_escape_string($_POST['pat_BS_EYE']);//
$form_pat_EN_EYE = $db->connection->real_escape_string($_POST['pat_EN_EYE']);//
$form_pat_BS_VERB = $db->connection->real_escape_string($_POST['pat_BS_VERB']);//
$form_pat_EN_VERB = $db->connection->real_escape_string($_POST['pat_EN_VERB']);//
$form_pat_BS_MOT = $db->connection->real_escape_string($_POST['pat_BS_MOT']);//
$form_pat_EN_MOT = $db->connection->real_escape_string($_POST['pat_EN_MOT']);//
$form_pat_BS_TOTAL = $db->connection->real_escape_string($_POST['pat_BS_TOTAL']);//
$form_pat_EN_TOTAL = $db->connection->real_escape_string($_POST['pat_EN_TOTAL']);//

if(!empty($_POST['his_ALGY'])) {
	$form_his_ALGY = $db->connection->real_escape_string($_POST['his_ALGY']);//
	$form_his_ALGY2 = explode(", ", $form_his_ALGY);
}
else {
	$form_his_ALGY2 = array();
}
if(!empty($_POST['his_MED'])) {
	$form_his_MED = $db->connection->real_escape_string($_POST['his_MED']);//
	$form_his_MED2 = explode(", ", $form_his_MED);
}
else {
	$form_his_MED2 = array();
}
if(!empty($_POST['his_MED'])) {
	$form_his_HTY = $db->connection->real_escape_string($_POST['his_HTY']);//
	$form_his_HTY2 = explode(", ", $form_his_HTY);
}
else {
	$form_his_HTY2 = array();
}

$form_phys_EXAM = !empty($_POST['phys_EXAM']) ? $_POST['phys_EXAM'] : array();//

$form_int_INTER = $db->connection->real_escape_string($_POST['int_INTER']);//
$form_int_RESP = $db->connection->real_escape_string($_POST['int_RESP']);//

$form_act_CPR = $db->connection->real_escape_string($_POST['act_CPR']);//
$form_act_TIME = $db->connection->real_escape_string($_POST['act_TIME']);//
$form_act_DEFIB = $db->connection->real_escape_string($_POST['act_DEFIB']);//
$form_act_PULSERET = $db->connection->real_escape_string($_POST['act_PULSERET']);//
$form_act_PULSERATE = $db->connection->real_escape_string($_POST['act_PULSERATE']);//
$form_act_RESP = $db->connection->real_escape_string($_POST['act_RESP']);//
$form_act_RESPRATE = $db->connection->real_escape_string($_POST['act_RESPRATE']);//

$form_nar_NARRATIVE = $db->connection->real_escape_string($_POST['nar_NARRATIVE']);//

$form_endo_PREP = $db->connection->real_escape_string($_POST['endo_PREP']);//
$form_endo_DIR = $db->connection->real_escape_string($_POST['endo_DIR']);//
$form_endo_TO = $db->connection->real_escape_string($_POST['endo_TO']);//

$form_endo_LEVEL = $_POST['endo_LEVEL'];
$form_endo_NAME = $_POST['endo_NAME'];
$form_endo_LEVEL = array_filter($form_endo_LEVEL, 'strlen');
$form_endo_NAME = array_filter($form_endo_NAME, 'strlen');

$sql_pcr_report = "INSERT INTO pcr_report(
	PCRNUMBER, UNITNAME,
	DATE, INADDRESS,
	LOCTYPE, RESTYPE,
	DISPO, POSTBY,
	IMAGE) 
	VALUES (
	$form_runInfo_PCRNO, '$form_runInfo_UNITNAME',
	'$form_runInfo_DATE', '$form_runInfo_ADDRESS',
	'$form_runInfo_LOCATION', '$form_runInfo_RESPONSE',
	'$form_runInfo_DISPO', '$session_USER_USERNAME',
	'$form_runInfo_IMAGE')";

if($db->connection->query($sql_pcr_report)) {
	$pcr_ID = $db->connection->insert_id;
	
	$sql_pcr_track = "INSERT INTO pcr_track(
	PCRID, CALLRCVD,
	ENROUTE, ATSCN,
	ATPT, DEPSCN,
	ATDEST, INSVC,
	END, BEGIN,
	TOTAL) 
	VALUES (
	$pcr_ID, '$form_track_RECEIVED',
	'$form_track_ENROUTE', '$form_track_SCENE',
	'$form_track_PAT', '$form_track_DEPART',
	'$form_track_DEST', '$form_track_SERVE',
	$form_track_END, $form_track_BEGIN,
	$form_track_TOTAL)";
	$db->connection->query($sql_pcr_track);
	
	$sql_pcr_patient = "INSERT INTO pcr_patient(
	PCRNO, PTNTNAME,
	PTNTADDRESS, PTNTAGE,
	PTNTGENDER, PTNTDOB,
	PTNTCOMPLAINT, PTNTNOI) 
	VALUES (
	$pcr_ID, '$form_patInfo_NAME',
	'$form_patInfo_ADDRESS', $form_patInfo_AGE,
	'$form_patInfo_GENDER', '$form_patInfo_DOB',
	'$form_vit_COMP', '$form_vit_NOI')";
	$db->connection->query($sql_pcr_patient);
	
	$sql_pcr_rp = "INSERT INTO pcr_rp(
	PCRNO, RPNAME,
	RPADDRESS, RPREL,
	RPCONTACT) 
	VALUES (
	$pcr_ID, '$form_resInfo_NAME',
	'$form_resInfo_ADDRESS', '$form_resInfo_REL',
	'$form_resInfo_CONTACT')";
	$db->connection->query($sql_pcr_rp);
	
	
	$sql_pcr_asmtbaseline = "INSERT INTO pcr_asmtbaseline(
	PCRNO, TIME,
	BLOODPRESSURE, PULSE,
	PULSEQUALITY, RESP,
	RESPQUALITY, SPO2,
	CBG) 
	VALUES (
	$pcr_ID, '$form_vit_BS_TIME',
	$form_vit_BS_BP, $form_vit_BS_PULSE,
	'$form_vit_PULSEQTY', $form_vit_BS_RES,
	'$form_vit_RESQTY', $form_vit_BS_SPO,
	$form_vit_BS_CBG)";
	$db->connection->query($sql_pcr_asmtbaseline);
	
	$sql_pcr_asmtenroute = "INSERT INTO pcr_asmtenroute(
	PCRNO, TIME,
	BLOODPRESSURE, PULSE,
	RESP, SPO2,
	CBG) 
	VALUES (
	$pcr_ID, '$form_vit_EN_TIME',
	$form_vit_EN_BP, $form_vit_EN_PULSE,
	$form_vit_EN_RES, $form_vit_EN_SPO,
	$form_vit_EN_CBG)";
	$db->connection->query($sql_pcr_asmtenroute);
	
	$sql_pcr_assessment = "INSERT INTO pcr_assessment(
	PCRNO, SKIN,
	EYESL, EYESR, PAINPROVOKE,
	PAINQUALITY, PAINRADIATE,
	PAINSEVERITY, PAINONSET,
	O2GIVEN, O2TYPE, 
	O2RATE, LOC) 
	VALUES (
	$pcr_ID, '$form_pat_SKIN',
	'$form_pat_LEYE', '$form_pat_REYE', '$form_pat_PPROV',
	'$form_pat_PQTY', $form_pat_PRAD,
	$form_pat_PSEV, '$form_pat_ON',
	$form_pat_O2GIVE, '$form_pat_O2TYPE',
	$form_pat_O2RATE, '$form_pat_LOC')";
	$db->connection->query($sql_pcr_assessment);
	
	$sql_pcr_gcs = "INSERT INTO pcr_gcs(
	PCRNO, BASEEYE,
	BASEVERBAL, BASEMOTOR,
	BASEGCS, ENROUTEEYE,
	ENROUTEVERBAL, ENROUTEMOTOR,
	ENROUTEGCS) 
	VALUES (
	$pcr_ID, $form_pat_BS_EYE,
	$form_pat_BS_VERB, $form_pat_BS_MOT,
	$form_pat_BS_TOTAL, $form_pat_EN_EYE,
	$form_pat_EN_VERB, $form_pat_EN_MOT,
	$form_pat_EN_TOTAL)";
	$db->connection->query($sql_pcr_gcs);
	
	$sql_pcr_treatment = "INSERT INTO pcr_treatment(
	PCRNO, INTERVENTIONS,
	RESPONSE, CPR,
	CPRTIME, DEFIB,
	RETRNPULSE, RTRNPULSERATE,
	RESP, RESPRATE,
	NARRATIVE) 
	VALUES (
	$pcr_ID, '$form_int_INTER',
	'$form_int_RESP', $form_act_CPR,
	'$form_act_TIME', $form_act_DEFIB,
	$form_act_PULSERET, $form_act_PULSERATE,
	$form_act_RESP, $form_act_RESPRATE,
	'$form_nar_NARRATIVE')";
	$db->connection->query($sql_pcr_treatment);
	
	$sql_pcr_endorse = "INSERT INTO pcr_endorse(
	PCRNO, PREPAREDBY,
	MEDDIR, ENDORSEDTO) 
	VALUES (
	$pcr_ID, '$form_endo_PREP',
	'$form_endo_DIR', '$form_endo_TO')";
	$db->connection->query($sql_pcr_endorse);
	
	foreach($form_phys_EXAM as $value) {
		$temp = explode("_", $value);
		$sql_pcr_physex = "INSERT INTO pcr_physex(
		PCRNO, AREA, AFFLICTION)
		VALUES(
		$pcr_ID, '$temp[0]', '$temp[1]')";
		
		if($db->connection->query($sql_pcr_physex)) {
		}
	}

	foreach($form_his_ALGY2 as $value) {
		$sql_pcr_asmtallergies = "INSERT INTO pcr_asmtallergies(
		PCRNO, ALLERGY)
		VALUES(
		$pcr_ID, '$value')";
		
		if($db->connection->query($sql_pcr_asmtallergies)) {
				
		}
	}
	
	foreach($form_his_MED2 as $value) {
		$sql_pcr_asmtmed = "INSERT INTO pcr_asmtmed(
		PCRNO, MEDICATIONS)
		VALUES(
		$pcr_ID, '$value')";
		
		if($db->connection->query($sql_pcr_asmtmed)) {
				
		}
	}
	
	foreach($form_his_HTY2 as $value) {
		$sql_pcr_asmtpmh = "INSERT INTO pcr_asmtpmh(
		PCRNO, PASTMEDHTY)
		VALUES(
		$pcr_ID, '$value')";
		
		if($db->connection->query($sql_pcr_asmtpmh)) {
				
		}
	}

	foreach($form_endo_NAME as $key => $value) {
		$sql_pcr_crew = "INSERT INTO pcr_crew(
		PCRNO, NAME, LEVEL)
		VALUES(
		$pcr_ID, '$value', '$form_endo_LEVEL[$key]')";
		
		if($db->connection->query($sql_pcr_crew)) {
			
		}
	}
	
	header("Location: /web/thesis/CdrrmoManagePCR.php");
	exit();
}
else {
	echo($db->connection->error);
}

?>