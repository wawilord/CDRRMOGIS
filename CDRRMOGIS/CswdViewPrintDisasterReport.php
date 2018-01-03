<?php
    include('library/form/connection.php');
    include('library/function/functions.php');
    $db = new db();
    $fromtime = $_GET['time'];
    $totime = '';

    $dummystart = substr($_GET['time'], 0, 11);
    $dummyhour = substr($_GET['time'], 11, 2);
    $dummyend = substr($_GET['time'], 13, 6);
    $dummyhour = (int)$dummyhour+1;
    $totime = $dummystart . $dummyhour . $dummyend;


    $sql = "SELECT * FROM disaster_type WHERE ID = " . $_GET['disaster'];
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    $row = $result->fetch_assoc();
    $disaster_name = $row['NAME'];

    /*
    $sql = "SELECT ID FROM disaster_declare WHERE DISASTER = " . $_GET['disaster'];
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    if($count > 0) {
        $declareid = '';
        while ($row = $result->fetch_assoc()) {
            $declareid .= 'DECLAREID = ' . $row['ID'] . ' OR ';
        }
        $declareid = substr($declareid, 0, strlen($declareid) - 3);


        $sql = 'SELECT DECLAREID FROM  (SELECT DECLAREID 
                                                FROM evacuation_report
                                                WHERE (' . $declareid . ')
                                                AND ISVERIFIED = 1
                                                AND DATEADDED >= "' . $fromtime . '"
                                                AND DATEADDED < "' . $totime . '"
                                            UNION
                                                SELECT DECLAREID 
                                                FROM disaster_reports
                                                WHERE (' . $declareid . ')
                                                AND ISVERIFIED = 1
                                                AND DATEADDED >= "' . $fromtime . '"
                                                AND DATEADDED < "' . $totime . '"
                                            UNION
                                                SELECT DECLAREID 
                                                FROM disaster_cost
                                                WHERE (' . $declareid . ')
                                                AND DATEADDED >= "' . $fromtime . '"
                                                AND DATEADDED < "' . $totime . '") a
        ';

        $mmdeclareid = '';
        $declarelist = array();
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while ($row = $result->fetch_assoc())
        {
            array_push($declarelist, $row['DECLAREID']);
        }

        $declareid = '';
        foreach ($declarelist as $declareidd){
            $declareid .= 'ID = ' . $declareidd . ' OR ';
            $mmdeclareid .= 'disaster_reports.DECLAREID = ' . $declareidd . ' OR ';
        }
        $declareid = substr($declareid, 0, strlen($declareid) - 3);
        $mmdeclareid = substr($mmdeclareid, 0, strlen($mmdeclareid) - 3);

        $brgylist = array();
        $sql = 'SELECT BRGY from disaster_declare WHERE ' . $declareid;
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while ($row = $result->fetch_assoc())
        {
            array_push($brgylist, $row['BRGY']);
        }

        $brgyid = '';
        foreach ($brgylist as $brgyidd){
            $brgyid .= 'ID = ' . $brgyidd . ' OR ';
        }
        $brgyid = substr($brgyid, 0, strlen($brgyid) - 3);

        $districtlist = array();
        $sql = 'SELECT DISTRICT from barangay WHERE ' . $brgyid . ' GROUP BY DISTRICT';
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while ($row = $result->fetch_assoc())
        {
            array_push($districtlist, $row['DISTRICT']);
        }

        $datalist = array();
        foreach ($districtlist as $districtid){
            $sql = 'SELECT NAME FROM district WHERE ID = ' . $districtid;
            $result = $db->connection->query($sql);
            $row = $result->fetch_assoc();
            array_push($datalist, array($districtid, $row['NAME'], array()));
        }

        for($i = 0; $i < sizeof($datalist); $i++){
            $sql = 'SELECT ID, NAME FROM barangay WHERE (' . $brgyid . ') AND DISTRICT = ' . $datalist[$i][0];
            $result = $db->connection->query($sql);
            while($row = $result->fetch_assoc()){
                array_push($datalist[$i][2], array($row['ID'], $row['NAME'], 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
            }
        }

        for($i = 0; $i < sizeof($datalist); $i++){
            for($x = 0; $x < sizeof($datalist[$i][2]); $x++){

            }
        }
    }
    */
?>

<style>
    table {
        border-collapse: collapse;
    }

    table, th, td, tbody {
        border: 1px solid black;
    }

    table tbody td {
        text-align: right;
    }

    .none {
        border: 0 none black;
    }

    .none_right {
        border-right-style: none;
    }

    .none_left {
        border-left-style: none;
    }
</style>

<table style="margin: auto; width: 100%;" class="none">
    <thead>
    <tr class="none">
        <td colspan="14" style="text-align: center;" class="none">Republic of the Philippines</td>
    </tr>
    <tr class="none">
        <td colspan="14" style="text-align: center;" class="none"><b>CITY SOCIAL WELFARE AND DEVELOPMENT OFFICE</b></td>
    </tr>
    <tr class="none">
        <td colspan="14" style="text-align: center;" class="none">Iloilo City</td>
    </tr>
    <tr class="none">
        <td colspan="14" style="text-align: center;" class="none"><br /></td>
    </tr>
    <tr class="none">
        <td colspan="14" style="text-align: center;" class="none"><b>STATUS OF DISASTER OPERATIONS</b></td>
    </tr>
    <tr class="none">
        <td colspan="14" style="text-align: center;" class="none"><small><?php echo getdatestring($fromtime) ?> (Date)</small></td>
    </tr>
    <tr class="none">
        <td colspan="14" style="text-align: center;" class="none"><small><?php echo gettimestring($fromtime) . ' - ' . gettimestring($totime); ?> (Time)</small></td>
    </tr>
    <tr class="none">
        <td colspan="7" class="none"><small><b>Region:</b> 6</small></td>
        <td colspan="7" class="none"></td>
    </tr>
    <tr class="none">
        <td colspan="7" class="none"><small><b>Type of Disaster:</b> <?php echo $disaster_name; ?></small></td>
        <td colspan="7" class="none"></td>
    </tr>
    <tr class="none">
        <td colspan="7" class="none"><small><b>Date of Occurence:</b> ___-___-___</small></td>
        <td colspan="7" class="none"></td>
    </tr>
    <tr class="none">
        <td colspan="7" class="none"><br /></td>
        <td colspan="7" class="none"></td>
    </tr>
    <tr style="text-align: center;">
        <td colspan="2"><b>Affected Areas</b></td>
        <td colspan="9"><b>Number of</b></td>
        <td colspan="4"><b>Cost of Assistance</b></td>
    </tr>
    <tr style="text-align: center;">
        <td rowspan="2" colspan="2"><b>(Province/City/Municipality)</b></td>
        <td colspan="5"><b>Damaged Houses</b></td>
        <td colspan="3"><b>Casualties</b></td>
        <td rowspan="2"><b>Total</b></td>
        <td rowspan="2"><b>DSWD</b></td>
        <td rowspan="2"><b>LGUs</b></td>
        <td rowspan="2"><b>NGOs/Other GOs</b></td>
    </tr>

    <tr style="text-align: center;">
        <td colspan="2"><span><b>Total</b></span></td>
        <td><span><b>Totally</b></span></td>
        <td><span><b>Partially</b></span></td>
        <td><span><b>Evacuees/Flooded</b></span></td>
        <td><span><b>Dead</b></span></td>
        <td><span><b>Injured</b></span></td>
        <td><span><b>Missing</b></span></td>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = 'SELECT 	DMGTOTALLY,
		DMGPARTIALLY,
        CSLTDEAD,
        CSLTINJURED,
        CSLTMISSING,
        DSWD,
        LGU,
        NGO,
        SRVFAMILIES,
        SRVPERSONS,
        BARANGAY,
        DISTRICT,
        MostRecentDate
        
        FROM
        
(SELECT 	DMGTOTALLY,
		DMGPARTIALLY,
        CSLTDEAD,
        CSLTINJURED,
        CSLTMISSING,
        DSWD,
        LGU,
        NGO,
        SRVFAMILIES,
        SRVPERSONS,
        BARANGAY,
        DISTRICT,
        CASE
        	WHEN DRDATE >= DCDATE AND DRDATE >= ERDATE THEN DRDATE
        	WHEN DCDATE >= DRDATE AND DCDATE >= ERDATE THEN DCDATE
        	WHEN ERDATE >= DRDATE AND ERDATE >= DCDATE THEN ERDATE
        	ELSE                                        DRDATE
    	END AS MostRecentDate
        
        FROM
        
(SELECT  disaster_reports.DMGTOTALLY,
                    disaster_reports.DMGPARTIALLY,
                    disaster_reports.CSLTDEAD,
                    disaster_reports.CSLTINJURED,
                    disaster_reports.CSLTMISSING,
                    disaster_cost.DSWD,
                    disaster_cost.LGU,
                    disaster_cost.NGO,
                    evacuation_report.SRVFAMILIES,
                    evacuation_report.SRVPERSONS,
                    barangay.NAME AS BARANGAY,
                    district.NAME AS DISTRICT,
                    disaster_reports.DATEADDED AS DRDATE,
                    disaster_cost.DATEADDED AS DCDATE,
                    evacuation_report.DATEADDED AS ERDATE
            
            FROM    disaster_reports, 
                    disaster_cost,
                    evacuation_report,
                    barangay,
                    district,
                    disaster_declare,
                    disaster_type
            
            WHERE   disaster_reports.DECLAREID = disaster_declare.ID
            
            AND		disaster_cost.DECLAREID = disaster_declare.ID
            
            AND		evacuation_report.DECLAREID = disaster_declare.ID
            
            AND		disaster_declare.BRGY = barangay.ID
            
            AND		barangay.DISTRICT = district.ID
            
            AND		disaster_declare.DISASTER = ' . $_GET["disaster"] . '
            
            GROUP BY 	disaster_reports.ID
            
            UNION
            
            SELECT  disaster_reports.DMGTOTALLY,
                    disaster_reports.DMGPARTIALLY,
                    disaster_reports.CSLTDEAD,
                    disaster_reports.CSLTINJURED,
                    disaster_reports.CSLTMISSING,
                    disaster_cost.DSWD,
                    disaster_cost.LGU,
                    disaster_cost.NGO,
                    evacuation_report.SRVFAMILIES,
                    evacuation_report.SRVPERSONS,
                    barangay.NAME AS BARANGAY,
                    district.NAME AS DISTRICT,
                    disaster_reports.DATEADDED AS DRDATE,
                    disaster_cost.DATEADDED AS DCDATE,
                    evacuation_report.DATEADDED AS ERDATE
            
            FROM    disaster_reports, 
                    disaster_cost,
                    evacuation_report,
                    barangay,
                    district,
                    disaster_declare,
                    disaster_type
            
            WHERE   disaster_reports.DECLAREID = disaster_declare.ID
            
            AND		disaster_cost.DECLAREID = disaster_declare.ID
            
            AND		evacuation_report.DECLAREID = disaster_declare.ID
            
            AND		disaster_declare.BRGY = barangay.ID
            
            AND		barangay.DISTRICT = district.ID
            
            AND		disaster_declare.DISASTER = ' . $_GET["disaster"] . '
            
            GROUP BY 	disaster_cost.ID
            
            UNION
            
            SELECT  disaster_reports.DMGTOTALLY,
                    disaster_reports.DMGPARTIALLY,
                    disaster_reports.CSLTDEAD,
                    disaster_reports.CSLTINJURED,
                    disaster_reports.CSLTMISSING,
                    disaster_cost.DSWD,
                    disaster_cost.LGU,
                    disaster_cost.NGO,
                    evacuation_report.SRVFAMILIES,
                    evacuation_report.SRVPERSONS,
                    barangay.NAME AS BARANGAY,
                    district.NAME AS DISTRICT,
                    disaster_reports.DATEADDED AS DRDATE,
                    disaster_cost.DATEADDED AS DCDATE,
                    evacuation_report.DATEADDED AS ERDATE
            
            FROM    disaster_reports, 
                    disaster_cost,
                    evacuation_report,
                    barangay,
                    district,
                    disaster_declare,
                    disaster_type
            
            WHERE   disaster_reports.DECLAREID = disaster_declare.ID
            
            AND		disaster_cost.DECLAREID = disaster_declare.ID
            
            AND		evacuation_report.DECLAREID = disaster_declare.ID
            
            AND		disaster_declare.BRGY = barangay.ID
            
            AND		barangay.DISTRICT = district.ID
            
            AND		disaster_declare.DISASTER = ' . $_GET["disaster"] . '
            
            GROUP BY 	evacuation_report.ID) a
            
ORDER BY MostRecentDate DESC) b

GROUP BY BARANGAY
            ';

    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    $currentdistrict = '';

    $_sub_brgynum = 0;
    $_sub_totally = 0;
    $_sub_partially = 0;
    $_sub_evacuees = 0;
    $_sub_dead = 0;
    $_sub_injured = 0;
    $_sub_missing = 0;
    $_sub_total = 0;
    $_sub_dswd = 0;
    $_sub_lgu = 0;
    $_sub_ngo = 0;
    while($row = $result->fetch_assoc()){

        if($currentdistrict != $row['DISTRICT']) {
            
        }

        $_sub_brgynum += 1;
        $_sub_totally += $row['DMGTOTALLY'];
        $_sub_partially += $row['DMGPARTIALLY'];
        $_sub_evacuees += $row['SRVPERSONS'];
        $_sub_dead += $row['CSLTDEAD'];
        $_sub_injured += $row['CSLTINJURED'];
        $_sub_missing += $row['CSLTMISSING'];
        $_sub_total += $row['DSWD'] + $row['LGU'] + $row['NGO'];
        $_sub_dswd += $row['DSWD'];
        $_sub_lgu += $row['LGU'];
        $_sub_ngo += $row['NGO'];

        ?>
            <tr style="text-align: center;">
                <td class="none_right" style="text-align: left;"><?php if($currentdistrict != $row['DISTRICT']) { echo $row['DISTRICT']; } ?></td>
                <td class="none_left" style="text-align: left;"><?php echo $row['BARANGAY']; ?></td>
                <td></td>
                <td></td>
                <td><?php echo $row['DMGTOTALLY']; ?></td>
                <td><?php echo $row['DMGPARTIALLY']; ?></td>
                <td><?php echo $row['SRVPERSONS']; ?></td>
                <td><?php echo $row['CSLTDEAD']; ?></td>
                <td><?php echo $row['CSLTINJURED']; ?></td>
                <td><?php echo $row['CSLTMISSING']; ?></td>
                <td><?php echo number_format($row['DSWD'] + $row['LGU'] + $row['NGO']); ?></td>
                <td><?php echo number_format($row['DSWD']); ?></td>
                <td><?php echo number_format($row['LGU']); ?></td>
                <td><?php echo number_format($row['NGO']); ?></td>
            </tr>
        <?php
    }

    ?>

    <tr style="text-align: center;">
        <td class="none_right" style="text-align: left;"><b>Sub-Total: </b></td>
        <td class="none_left">--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
    </tr>

    <tr style="text-align: center;">
        <td class="none_right" style="text-align: left;"><b>Total: </b></td>
        <td class="none_left">--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
        <td>--</td>
    </tr>
    </tbody>

    <tfoot>
    <tr>
        <td colspan="14" style="text-align: right;" class="none"><small><b>This report was generated by the system. Time Generation: </b> May 16, 2016, 10:21 am</small></td>
    </tr>
    <tr>
        <td colspan="14" class="none">Certified Correct: </td>
    </tr>
    <tr>
        <td colspan="1" class="none"></td>
        <td colspan="5" style="text-align: center;" class="none"><b>ALFREDO A. VILLANUEVA</b><br />City Social Welfare & Dev't Officer</td>
        <td colspan="8" class="none"></td>
    </tr>
    </tfoot>
</table>