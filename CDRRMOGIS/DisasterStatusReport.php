<?php
    session_start();
    include('library/form/connection.php');
    include('library/function/functions.php');
    $db = new db();

    //Retrieve User Info
    $session_USER_FIRSTNAME = htmlspecialchars($_SESSION['USER_FIRSTNAME']);
    $session_USER_MIDDLENAME = htmlspecialchars($_SESSION['USER_MIDDLENAME']);
    $session_USER_LASTNAME = htmlspecialchars($_SESSION['USER_LASTNAME']);
    //_____________________________________________________

    //Parsing Time
    $Time = array(
        "Start"=>array(
            "Date"=>new DateTime($_GET['time']),
            "StrDate"=>null
        ),
        "End"=>array(
            "Date"=>new DateTime($_GET['time']),
            "StrDate"=>null
        )
    );
    $Time["End"]["Date"]->modify('+1 hour');
    $Time["Start"]["StrDate"] = $Time["Start"]["Date"]->format('Y-m-d H:i:s');
    $Time["End"]["StrDate"] = $Time["End"]["Date"]->format('Y-m-d H:i:s');
    //_____________________________________________________

    //Retrieve Report Profile Info
    $sql='SELECT                disaster_profile.ID,
                                disaster_profile.NAME,
                                disaster_profile.TYPE AS DISASTERID,
                                disaster_type.NAME AS DISASTERNAME,
                                disaster_profile.DATESTART
            FROM                disaster_profile,
                                disaster_type
            WHERE               disaster_type.ID = disaster_profile.TYPE
            AND                 disaster_profile.ID = ' . $_GET["id"] . '
            GROUP BY            disaster_profile.ID';
    $result = $db->connection->query($sql);
    $ReportProfileInfo = $result->fetch_assoc();
    $ReportProfileInfo['DECLARELIST'] = array();
    $ReportProfileInfo['SQL_arg_string'] = '';
    $sql = 'SELECT          DECLAREID
            FROM            disaster_declarelist
            WHERE           PROFILEID = ' . $ReportProfileInfo['ID'];
    $result = $db->connection->query($sql);
    while ($row = $result->fetch_assoc()){
        $ReportProfileInfo['DECLARELIST'][] = $row['DECLAREID'];
    }
    foreach ($ReportProfileInfo['DECLARELIST'] as $id){
        $ReportProfileInfo['SQL_arg_string'] .= $id . ', ';
    }
    $ReportProfileInfo['SQL_arg_string'] = substr($ReportProfileInfo['SQL_arg_string'], 0, strlen($ReportProfileInfo['SQL_arg_string']) - 2);
    //_____________________________________________________

    //Retrieve Data
    $Data = array();
    $sql = 'SELECT * FROM(SELECT                district.ID AS DISTRICTID,
                                                district.NAME AS DISTRICTNAME,
                                                disaster_declare.BRGY as BARANGAYID,
                                                barangay.NAME AS BARANGAYNAME
                            FROM                disaster_declare,
                                                district,
                                                barangay
                            WHERE               district.ID = barangay.DISTRICT
                            AND                 disaster_declare.BRGY = barangay.ID
                            AND                 disaster_declare.ID IN (' . $ReportProfileInfo['SQL_arg_string'] . ')
                            GROUP BY            disaster_declare.BRGY) a
            ORDER BY        DISTRICTID ASC';
    $result = $db->connection->query($sql);
    while ($row = $result->fetch_assoc()){
        $Data[] = $row;
    }
    for($i = 0; $i < sizeof($Data); $i++){

        //Get from disaster reports
        $sql = 'SELECT      SUM(TOTALLY) AS TOTALLY,
                            SUM(PARTIALLY) AS PARTIALLY,
                            SUM(DEAD) AS DEAD,
                            SUM(INJURED) AS INJURED,
                            SUM(MISSING) AS MISSING
                FROM        (SELECT * FROM  (SELECT         disaster_reports.DMGTOTALLY AS TOTALLY,
                                                            disaster_reports.DMGPARTIALLY AS PARTIALLY,
                                                            disaster_reports.CSLTDEAD AS DEAD,
                                                            disaster_reports.CSLTINJURED AS INJURED,
                                                            disaster_reports.CSLTMISSING AS MISSING,
                                                            disaster_declare.BRGY,
                                                            disaster_declare.ID AS DECLAREID
                                            FROM            disaster_reports,
                                                            disaster_declare
                                            WHERE           disaster_reports.DECLAREID = disaster_declare.ID
                                            AND             disaster_reports.ISVERIFIED = 1
                                            AND             disaster_reports.DECLAREID IN (' . $ReportProfileInfo['SQL_arg_string'] . ')
                                            AND             disaster_declare.BRGY = ' . $Data[$i]["BARANGAYID"] . '
                                            AND             disaster_reports.DATEADDED <= "' . $Time["End"]["StrDate"] . '"
                                            ORDER BY        disaster_reports.DATEADDED DESC)a
                            GROUP BY DECLAREID) b';
        $result = $db->connection->query($sql);
        $row = $result->fetch_assoc();

        $Data[$i]["TOTALLY"] = Get0IfNull($row["TOTALLY"]);
        $Data[$i]["PARTIALLY"] = Get0IfNull($row["PARTIALLY"]);
        $Data[$i]["DEAD"] = Get0IfNull($row["DEAD"]);
        $Data[$i]["INJURED"] = Get0IfNull($row["INJURED"]);
        $Data[$i]["MISSING"] = Get0IfNull($row["MISSING"]);

        //Get from disaster cost
        $sql = 'SELECT          SUM(DSWD) AS DSWD,
                                SUM(LGU) AS LGU,
                                SUM(NGO) AS NGO
                FROM            (SELECT * FROM   (SELECT            disaster_cost.DSWD,
                                                                    disaster_cost.LGU,
                                                                    disaster_cost.NGO,
                                                                    disaster_declare.BRGY,
                                                                    disaster_declare.ID AS DECLAREID
                                                FROM                disaster_cost,
                                                                    disaster_declare
                                                WHERE               disaster_cost.DECLAREID = disaster_declare.ID
                                                AND                 disaster_cost.DECLAREID IN (' . $ReportProfileInfo['SQL_arg_string'] . ')
                                                AND                 disaster_declare.BRGY = ' . $Data[$i]["BARANGAYID"] . '
                                                AND                 disaster_cost.DATEADDED <= "' . $Time["End"]["StrDate"] . '"
                                                ORDER BY            disaster_cost.DATEADDED DESC) a
                                GROUP BY DECLAREID) b';
        $result = $db->connection->query($sql);
        $row = $result->fetch_assoc();

        $Data[$i]["DSWD"] = Get0IfNull($row["DSWD"]);
        $Data[$i]["LGU"] = Get0IfNull($row["LGU"]);
        $Data[$i]["NGO"] = Get0IfNull($row["NGO"]);

        //Get from evacuation report
        $sql = 'SELECT          SUM(FAMILIES) AS FAMILIES,
                                SUM(PERSONS) AS PERSONS
                FROM            (SELECT * FROM (SELECT              evacuation_report.EVACID,
                                                                    evacuation_report.DECLAREID,
                                                                    evacuation_report.SRVFAMILIES AS FAMILIES,
                                                                    evacuation_report.SRVPERSONS AS PERSONS,
                                                                    disaster_declare.BRGY
                                                FROM                evacuation_report,
                                                                    disaster_declare
                                                WHERE               evacuation_report.DECLAREID = disaster_declare.ID
                                                AND                 evacuation_report.ISVERIFIED = 1
                                                AND                 evacuation_report.DECLAREID IN(' . $ReportProfileInfo['SQL_arg_string'] . ')
                                                AND                 disaster_declare.BRGY = ' . $Data[$i]["BARANGAYID"] . '
                                                AND                 evacuation_report.DATEADDED <= "' . $Time["End"]["StrDate"] . '"
                                                ORDER BY            evacuation_report.DATEADDED DESC)a
                                GROUP BY EVACID, DECLAREID)a';
        $result = $db->connection->query($sql);
        $row = $result->fetch_assoc();

        $Data[$i]["FAMILIES"] = Get0IfNull($row["FAMILIES"]);
        $Data[$i]["PERSONS"] = Get0IfNull($row["PERSONS"]);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Disaster Status Report</title>
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
    </head>
    <body>
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
                    <td colspan="14" style="text-align: center;" class="none"><small><?php echo getdatestring($Time["Start"]["StrDate"]); ?> (Date)</small></td>
                </tr>
                <tr class="none">
                    <td colspan="14" style="text-align: center;" class="none">
                        <small>
                            <?php
                                $d_st = gettimestring($Time["Start"]["StrDate"]);
                                $d_en = gettimestring($Time["End"]["StrDate"]);
                                echo $d_st . ' - ' . $d_en;
                            ?> (Time)
                        </small>
                    </td>
                </tr>
                <tr class="none">
                    <td colspan="7" class="none"><small><b>Region:</b> 6</small></td>
                    <td colspan="7" class="none"></td>
                </tr>
                <tr class="none">
                    <td colspan="7" class="none"><small><b>Type of Disaster:</b> <?php echo $ReportProfileInfo["DISASTERNAME"]; ?></small></td>
                    <td colspan="7" class="none"></td>
                </tr>
                <tr class="none">
                    <td colspan="7" class="none"><small><b>Date of Occurence:</b> <?php echo getdatestring($ReportProfileInfo["DATESTART"]); ?></small></td>
                    <td colspan="7" class="none"></td>
                </tr>
                <tr class="none">
                    <td colspan="7" class="none"><br /></td>
                    <td colspan="7" class="none"></td>
                </tr>
                <tr style="text-align: center;">
                    <td colspan="2"><b>Affected Areas</b></td>
                    <td colspan="8"><b>Number of</b></td>
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
                    $TOTAL = array(
                        "BARANGAY"=>0,
                        "TOTALLY"=>0,
                        "PARTIALLY"=>0,
                        "PERSONS"=>0,
                        "DEAD"=>0,
                        "INJURED"=>0,
                        "MISSING"=>0,
                        "DSWD"=>0,
                        "LGU"=>0,
                        "NGO"=>0
                    );
                    $SUBTOTAL = array(
                    "BARANGAY"=>0,
                    "TOTALLY"=>0,
                    "PARTIALLY"=>0,
                    "PERSONS"=>0,
                    "DEAD"=>0,
                    "INJURED"=>0,
                    "MISSING"=>0,
                    "DSWD"=>0,
                    "LGU"=>0,
                    "NGO"=>0
                );
                    $current_district = null;
                    $CanPrintSubTotal = false;
                    foreach ($Data as $_Data){
                            if($current_district != $_Data["DISTRICTID"] && $CanPrintSubTotal){
                                ?>
                                    <tr style="text-align: center;">
                                        <td class="none_right" style="text-align: left;"><b>Sub-Total: </b></td>
                                        <td class="none_left"><b><?php echo number_format($SUBTOTAL["BARANGAY"]); ?></b></td>
                                        <td><b></b></td>
                                        <td><b></b></td>
                                        <td><b><?php echo number_format($SUBTOTAL["TOTALLY"]); ?></b></td>
                                        <td><b><?php echo number_format($SUBTOTAL["PARTIALLY"]); ?></b></td>
                                        <td><b><?php echo number_format($SUBTOTAL["PERSONS"]); ?></b></td>
                                        <td><b><?php echo number_format($SUBTOTAL["DEAD"]); ?></b></td>
                                        <td><b><?php echo number_format($SUBTOTAL["INJURED"]); ?></b></td>
                                        <td><b><?php echo number_format($SUBTOTAL["MISSING"]); ?></b></td>
                                        <td><b><?php echo number_format($SUBTOTAL["DSWD"] + $SUBTOTAL["LGU"] + $SUBTOTAL["NGO"]); ?></b></td>
                                        <td><b><?php echo number_format($SUBTOTAL["DSWD"]); ?></b></td>
                                        <td><b><?php echo number_format($SUBTOTAL["LGU"]); ?></b></td>
                                        <td><b><?php echo number_format($SUBTOTAL["NGO"]); ?></b></td>
                                    </tr>
                                <?php
                                $TOTAL["BARANGAY"] += $SUBTOTAL["BARANGAY"];
                                $TOTAL["TOTALLY"] += $SUBTOTAL["TOTALLY"];
                                $TOTAL["PARTIALLY"] += $SUBTOTAL["PARTIALLY"];
                                $TOTAL["PERSONS"] += $SUBTOTAL["PERSONS"];
                                $TOTAL["DEAD"] += $SUBTOTAL["DEAD"];
                                $TOTAL["INJURED"] += $SUBTOTAL["INJURED"];
                                $TOTAL["MISSING"] += $SUBTOTAL["MISSING"];
                                $TOTAL["DSWD"] += $SUBTOTAL["DSWD"];
                                $TOTAL["LGU"] += $SUBTOTAL["LGU"];
                                $TOTAL["NGO"] += $SUBTOTAL["NGO"];
                                $SUBTOTAL = array(
                                    "BARANGAY"=>0,
                                    "TOTALLY"=>0,
                                    "PARTIALLY"=>0,
                                    "PERSONS"=>0,
                                    "DEAD"=>0,
                                    "INJURED"=>0,
                                    "MISSING"=>0,
                                    "DSWD"=>0,
                                    "LGU"=>0,
                                    "NGO"=>0
                                );
                            }
                        $CanPrintSubTotal = true;
                        ?>
                            <tr style="text-align: center;">
                                <td class="none_right" style="text-align: center;">
                                    <?php
                                        if($current_district != $_Data["DISTRICTID"]){
                                            echo $_Data["DISTRICTNAME"];
                                            $current_district = $_Data["DISTRICTID"];
                                        }
                                    ?>
                                </td>
                                <td class="none_left" style="text-align: left;"><?php echo $_Data["BARANGAYNAME"]; ?></td>
                                <td></td>
                                <td></td>
                                <td><?php echo number_format($_Data["TOTALLY"]); ?></td>
                                <td><?php echo number_format($_Data["PARTIALLY"]); ?></td>
                                <td><?php echo number_format($_Data["PERSONS"]); ?></td>
                                <td><?php echo number_format($_Data["DEAD"]); ?></td>
                                <td><?php echo number_format($_Data["INJURED"]); ?></td>
                                <td><?php echo number_format($_Data["MISSING"]); ?></td>
                                <td><?php echo number_format($_Data["DSWD"] + $_Data["LGU"] + $_Data["NGO"]); ?></td>
                                <td><?php echo number_format($_Data["DSWD"]); ?></td>
                                <td><?php echo number_format($_Data["LGU"]); ?></td>
                                <td><?php echo number_format($_Data["NGO"]); ?></td>
                            </tr>
                        <?php
                        $SUBTOTAL["BARANGAY"] += 1;
                        $SUBTOTAL["TOTALLY"] += $_Data["TOTALLY"];
                        $SUBTOTAL["PARTIALLY"] += $_Data["PARTIALLY"];
                        $SUBTOTAL["PERSONS"] += $_Data["PERSONS"];
                        $SUBTOTAL["DEAD"] += $_Data["DEAD"];
                        $SUBTOTAL["INJURED"] += $_Data["INJURED"];
                        $SUBTOTAL["MISSING"] += $_Data["MISSING"];
                        $SUBTOTAL["DSWD"] += $_Data["DSWD"];
                        $SUBTOTAL["LGU"] += $_Data["LGU"];
                        $SUBTOTAL["NGO"] += $_Data["NGO"];
                    }
                ?>
                <tr style="text-align: center;">
                    <td class="none_right" style="text-align: left;"><b>Sub-Total: </b></td>
                    <td class="none_left"><b><?php echo number_format($SUBTOTAL["BARANGAY"]); ?></b></td>
                    <td><b></b></td>
                    <td><b></b></td>
                    <td><b><?php echo number_format($SUBTOTAL["TOTALLY"]); ?></b></td>
                    <td><b><?php echo number_format($SUBTOTAL["PARTIALLY"]); ?></b></td>
                    <td><b><?php echo number_format($SUBTOTAL["PERSONS"]); ?></b></td>
                    <td><b><?php echo number_format($SUBTOTAL["DEAD"]); ?></b></td>
                    <td><b><?php echo number_format($SUBTOTAL["INJURED"]); ?></b></td>
                    <td><b><?php echo number_format($SUBTOTAL["MISSING"]); ?></b></td>
                    <td><b><?php echo number_format($SUBTOTAL["DSWD"] + $SUBTOTAL["LGU"] + $SUBTOTAL["NGO"]); ?></b></td>
                    <td><b><?php echo number_format($SUBTOTAL["DSWD"]); ?></b></td>
                    <td><b><?php echo number_format($SUBTOTAL["LGU"]); ?></b></td>
                    <td><b><?php echo number_format($SUBTOTAL["NGO"]); ?></b></td>
                </tr>
                <?php
                    $TOTAL["BARANGAY"] += $SUBTOTAL["BARANGAY"];
                    $TOTAL["TOTALLY"] += $SUBTOTAL["TOTALLY"];
                    $TOTAL["PARTIALLY"] += $SUBTOTAL["PARTIALLY"];
                    $TOTAL["PERSONS"] += $SUBTOTAL["PERSONS"];
                    $TOTAL["DEAD"] += $SUBTOTAL["DEAD"];
                    $TOTAL["INJURED"] += $SUBTOTAL["INJURED"];
                    $TOTAL["MISSING"] += $SUBTOTAL["MISSING"];
                    $TOTAL["DSWD"] += $SUBTOTAL["DSWD"];
                    $TOTAL["LGU"] += $SUBTOTAL["LGU"];
                    $TOTAL["NGO"] += $SUBTOTAL["NGO"];
                    $SUBTOTAL = array(
                        "BARANGAY"=>0,
                        "TOTALLY"=>0,
                        "PARTIALLY"=>0,
                        "PERSONS"=>0,
                        "DEAD"=>0,
                        "INJURED"=>0,
                        "MISSING"=>0,
                        "DSWD"=>0,
                        "LGU"=>0,
                        "NGO"=>0
                    );
                ?>
                <tr>
                    <td class="none_right">&nbsp;</td>
                    <td class="none_left"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr style="text-align: center;">
                    <td class="none_right" style="text-align: left;"><b>Total: </b></td>
                    <td class="none_left"><b><?php echo number_format($TOTAL["BARANGAY"]); ?></b></td>
                    <td><b></b></td>
                    <td><b></b></td>
                    <td><b><?php echo number_format($TOTAL["TOTALLY"]); ?></b></td>
                    <td><b><?php echo number_format($TOTAL["PARTIALLY"]); ?></b></td>
                    <td><b><?php echo number_format($TOTAL["PERSONS"]); ?></b></td>
                    <td><b><?php echo number_format($TOTAL["DEAD"]); ?></b></td>
                    <td><b><?php echo number_format($TOTAL["INJURED"]); ?></b></td>
                    <td><b><?php echo number_format($TOTAL["MISSING"]); ?></b></td>
                    <td><b><?php echo number_format($TOTAL["DSWD"] + $TOTAL["LGU"] + $TOTAL["NGO"]); ?></b></td>
                    <td><b><?php echo number_format($TOTAL["DSWD"]); ?></b></td>
                    <td><b><?php echo number_format($TOTAL["LGU"]); ?></b></td>
                    <td><b><?php echo number_format($TOTAL["NGO"]); ?></b></td>
                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <?php
                        date_default_timezone_set('Asia/Manila');
                        $mytime = new DateTime();
                    ?>
                    <td colspan="14" style="text-align: right;" class="none"><small><b>Report generated as of: </b> <?php echo converttoformaldatetimestring($mytime->format('Y-m-d H:i:s')); ?></small></td>
                </tr>
                <tr>
                    <td colspan="14" class="none">Certified Correct: </td>
                </tr>
                <tr>
                    <td colspan="11" style="text-align: right;" class="none"><br/></td>
                </tr>
                <tr>
                    <td colspan="11" style="text-align: right;" class="none"><br/></td>
                </tr>
                <tr>
                    <td colspan="1" class="none"></td>
                    <td colspan="5" style="text-align: center;" class="none"><b><?php echo $session_USER_FIRSTNAME . ' ' . substr($session_USER_MIDDLENAME, 0, 1) . '. ' . $session_USER_LASTNAME; ?></b><br />City Social Welfare & Dev't Officer</td>
                    <td colspan="8" class="none"></td>
                </tr>
            </tfoot>
        </table>
    </body>
</html>