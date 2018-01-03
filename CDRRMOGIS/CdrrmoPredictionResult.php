<!DOCTYPE html>
<?php
    error_reporting(E_ERROR);
    session_start();
    include('library/form/connection.php');
    include ('library/function/functions.php');
    include('Regression/Matrix.php');
    include ('Regression/Regression.php');
    $db = new db();
    date_default_timezone_set('Asia/Manila');
    $G_Time = new DateTime();
    $G_Time->modify('-5 Year');
    $BackTrackTime = $G_Time->format('Y-m-d H:i:s');
    $CurrentYear = new DateTime();
    $CurrentYear = $CurrentYear->format('Y');
    $Results = array();
    for($i = $CurrentYear; $i < $_GET["Year"]+1; $i++){
        $Results[] = array(
            "Year"=>$i,
            "DEAD"=>0,
            "MISSING"=>0,
            "INJURED"=>0,
            "TOTALLY"=>0,
            "PARTIALLY"=>0,
            "DATAINPUTS"=>array()
        );
    }

    $sql = 'SELECT * FROM disaster_type WHERE ID = ' . $_GET["disaster"];
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    $DisasterInfo = $result->fetch_assoc();
    $DisasterInfo["FACTORS"] = array();

    $sql = 'SELECT * FROM disaster_factors WHERE TYPE_ID = ' . $_GET["disaster"];
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while($row = $result->fetch_assoc()){
        $DisasterInfo["FACTORS"][] = $row;
    }


    $PlaceName = 'Iloilo City';
    if($_GET["District"] > 0){
        $sql = 'SELECT NAME FROM district WHERE ID = ' . $_GET["District"];
        $result = $db->connection->query($sql);
        $row= $result->fetch_assoc();
        $PlaceName = $row["NAME"] . ', ' . $PlaceName;
    }
    if($_GET['Barangay'] > 0){
        $sql = 'SELECT NAME FROM barangay WHERE ID = ' . $_GET['Barangay'];
        $result = $db->connection->query($sql);
        $row= $result->fetch_assoc();
        $PlaceName = $row["NAME"] . ', ' . $PlaceName;
    }

    $Disasters = array();
    $sql = 'SELECT          ID,
                            STARTED,
                            ENDED,
                            RADIUS,
                            JSON_FACTORS
            FROM            disaster_declare 
            WHERE           DISASTER = ' . $DisasterInfo["ID"] . '
            AND             STARTED > "' . $BackTrackTime . '"
            AND             ISVERIFIED = 1';
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while($row = $result->fetch_assoc()){
        $factor_json = json_decode($row["JSON_FACTORS"], true);
        unset($row["JSON_FACTORS"]);
        foreach ($DisasterInfo["FACTORS"] as $factor){
            $row["FACTOR_" . $factor["ID"]] = Get0IfNull($factor_json["FACTOR_" . $factor["ID"]]);
        }
        $dummy_start = new DateTime($row["STARTED"]);
        $dummy_end = new DateTime($row["ENDED"]);
        $row["DURATION"] = date_diff($dummy_end, $dummy_start)->format('%a');
        $row["DURATION"] += date_diff($dummy_end, $dummy_start)->format('%h')/24;
        $row["DURATION"] += (date_diff($dummy_end, $dummy_start)->format('%i')/60)/24;
        unset($row["STARTED"]);
        unset($row["ENDED"]);
        $Disasters[] = $row;
    }
    for($j = 0; $j < sizeof($Disasters); $j++){
        $sql = 'SELECT 				* 
                        FROM 				disaster_reports
                        WHERE				ISVERIFIED = 1
                        AND                 DECLAREID = ' . $Disasters[$j]["ID"] . '
                        ORDER BY			DATEADDED DESC
                        LIMIT				0,1';
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        $row = $result->fetch_assoc();
        $Disasters[$j]["DEAD"] = Get0IfNull($row["CSLTDEAD"]);
        $Disasters[$j]["MISSING"] = Get0IfNull($row["CSLTMISSING"]);
        $Disasters[$j]["INJURED"] = Get0IfNull($row["CSLTINJURED"]);
        $Disasters[$j]["TOTALLY"] = Get0IfNull($row["DMGTOTALLY"]);
        $Disasters[$j]["PARTIALLY"] = Get0IfNull($row["DMGPARTIALLY"]);
        $Disasters[$j]["ReportTime"] = $row["DATEADDED"];

        $sql = 'SELECT 				        barangay_info.MEN,
                                            barangay_info.WOMEN,
                                            barangay_info.MINORS,
                                            barangay_info.ADULTS,
                                            barangay_info.PWD,
                                            barangay_info.T_HOUSES,
                                            barangay_info.C_HOUSES,
                                            barangay_info.L_HOUSES,
                                            barangay_info.CL_HOUSES,
                                            barangay_info.Area,
                                            barangay_info.isFloodProne,
                                            barangay.isCoastal
                    FROM 					barangay_info,
                                            disaster_declare,
                                            barangay
                    WHERE 					disaster_declare.BRGY = barangay_info.BARANGAY
                    AND                     barangay.ID = disaster_declare.BRGY
                    AND						disaster_declare.ID = ' . $Disasters[$j]["ID"] . '
                    AND                     barangay_info.DATEADDED < "' . $Disasters[$j]["ReportTime"] . '"
                    ORDER BY				barangay_info.DATEADDED DESC
                    LIMIT					0,1';
        unset($Disasters[$j]["ReportTime"]);

        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        $row = $result->fetch_assoc();
        $Disasters[$j]["C_HOUSES"] = $row["C_HOUSES"]/$row["T_HOUSES"];
        $Disasters[$j]["L_HOUSES"] = $row["L_HOUSES"]/$row["T_HOUSES"];
        $Disasters[$j]["CL_HOUSES"] = $row["CL_HOUSES"]/$row["T_HOUSES"];
        $Disasters[$j]["T_HOUSES"] = $row["T_HOUSES"];
        $Disasters[$j]["FLOODPRONE"] = $row["isFloodProne"];
        $Disasters[$j]["ISCOASTAL"] = $row["isCoastal"];
        $Disasters[$j]["Area"] = $row["Area"];
        $Disasters[$j]["MEN"] = $row["MEN"]/($row["MEN"] + $row["WOMEN"]);
        $Disasters[$j]["WOMEN"] = $row["WOMEN"]/($row["MEN"] + $row["WOMEN"]);
        $Disasters[$j]["MINORS"] = $row["MINORS"]/($row["MEN"] + $row["WOMEN"]);
        $Disasters[$j]["ADULTS"] = $row["ADULTS"]/($row["MEN"] + $row["WOMEN"]);
        $Disasters[$j]["PWD"] = $row["PWD"]/($row["MEN"] + $row["WOMEN"]);
        $Disasters[$j]["TOTALPOPULATION"] = $row["MEN"] + $row["WOMEN"];
        $Disasters[$j]["DENSITY"] = ($row["MEN"] + $row["WOMEN"])/$row["Area"];
    }
    $RegVar = array(
        "X"=>array(),
        "Y"=>array()
    );

    $dummy_list = array();
    $YMaxPerc = array(0, 0, 0, 0, 0);

    foreach ($Disasters as $disaster){
        $dummy_list[] = array(
            (1 * (double)$disaster["DEAD"])/$disaster["TOTALPOPULATION"],
            (1 * (double)$disaster["MISSING"])/$disaster["TOTALPOPULATION"],
            (1 * (double)$disaster["INJURED"])/$disaster["TOTALPOPULATION"],
            (1 * (double)$disaster["TOTALLY"])/$disaster["T_HOUSES"],
            (1 * (double)$disaster["PARTIALLY"])/$disaster["T_HOUSES"]
        );
    }
    foreach ($dummy_list as $item){
        for($i = 0; $i < sizeof($YMaxPerc); $i++){
            if($item[$i] > $YMaxPerc[$i]){
                $YMaxPerc[$i] = $item[$i];
            }
        }
    }



    foreach ($Disasters as $disaster){
        $dummy_X = array();
        array_push($dummy_X, (double)$disaster["RADIUS"]);//+++++++++
        array_push($dummy_X, (double)$disaster["DURATION"]);//+++++++++
        /*array_push($dummy_X, (double)$disaster["C_HOUSES"]);
        array_push($dummy_X, (double)$disaster["L_HOUSES"]);
        array_push($dummy_X, (double)$disaster["CL_HOUSES"]);*/
        array_push($dummy_X, (double)$disaster["T_HOUSES"]);//+++++++++
        //array_push($dummy_X, (double)$disaster["FLOODPRONE"]);
        /*array_push($dummy_X, (double)$disaster["ISCOASTAL"]);*/
        array_push($dummy_X, (double)$disaster["Area"]);//+++++++++
        /*array_push($dummy_X, (double)$disaster["MEN"]);
        array_push($dummy_X, (double)$disaster["WOMEN"]);
        array_push($dummy_X, (double)$disaster["MINORS"]);
        array_push($dummy_X, (double)$disaster["ADULTS"]);
        array_push($dummy_X, (double)$disaster["PWD"]);*/
        array_push($dummy_X, (double)$disaster["TOTALPOPULATION"]);//+++++++++
        foreach ($DisasterInfo["FACTORS"] as $factor){
            array_push($dummy_X, (double)$disaster["FACTOR_" . $factor["ID"]]);
        }
        $RegVar["X"][] = $dummy_X;

        $dummy_Y = array();
        array_push($dummy_Y, (double)$disaster["DEAD"]);
        array_push($dummy_Y, (double)$disaster["MISSING"]);
        array_push($dummy_Y, (double)$disaster["INJURED"]);
        array_push($dummy_Y, (double)$disaster["TOTALLY"]);
        array_push($dummy_Y, (double)$disaster["PARTIALLY"]);
        $RegVar["Y"][] = $dummy_Y;
    }
    //Testing_________________________-
    /*
    for($i = 0; $i < sizeof($RegVar["X"]); $i++){
        echo json_encode($RegVar["X"][$i]) . " = " . json_encode($RegVar["Y"][$i]) . "\n"; 
    }*/

    //____________________________
    function RemoveDuplicates($Data){
        $NewData = array();
        for($i = 0; $i < sizeof($Data); $i++){
            $Redundant = false;
            for($j = ($i+1); $j < sizeof($Data); $j++){
                if(json_encode($Data[$i]) == json_encode($Data[$j]))
                    $Redundant = true;
            }
            if(!$Redundant)
                array_push($NewData, $Data[$i]);
        }
        return $NewData;
    }
    function GetResultAverage($Factors, $Data){
        $results = array();
        $n = 0;
        for($i = 0; $i < sizeof($Data[0]["Results"]); $i++)
            array_push($results, 0);
        foreach ($Data as $data){
            if(json_encode($Factors) == json_encode($data["Factors"])){
                for($j = 0; $j < sizeof($Data[0]["Results"]); $j++)
                    $results[$j] += $data["Results"][$j];
                $n++;
            }
        }
        for($j = 0; $j < sizeof($Data[0]["Results"]); $j++)
            $results[$j] = round($results[$j]/$n);
        return $results;
    }
    function RemoveRedundantFactors($Data){
        $NewData = array();
        for($i = 0; $i < sizeof($Data); $i++){
            array_push($NewData, $Data[$i]);
            $NewData[$i]["Results"] = GetResultAverage($Data[$i]["Factors"], $Data);
        }
        return RemoveDuplicates($NewData);
    }
    function RemoveNull($Data){
        $NewData = array();
        foreach ($Data as $data) {
            $Ok = true;
            foreach ($data["Factors"] as $factors)
                if(is_null($factors))
                    $Ok = false;
            foreach ($data["Results"] as $results)
                if(is_null($results))
                    $Ok = false;
            if($Ok)
                array_push($NewData, $data);
        }
        return $NewData;
    }
    function RemoveOverPopulation($Data){
        $NewData = array();
        foreach ($Data as $data) {
                $current_total_houses = $data["Factors"][2];
                $current_total_population = $data["Factors"][4];
                $affected = $data["Results"][0] + $data["Results"][1] + $data["Results"][2];
                $damages = $data["Results"][3] + $data["Results"][4];
                if(($current_total_houses >= $damages) && ($current_total_population >= $affected))
                    array_push($NewData, $data);
        }
        return $NewData;
    }
    function DisplayData($Data){
        foreach ($Data as $data){
            echo json_encode($data) . "\n";
        }
        echo "\n\n";
    }

    $Data = array();
    for($i = 0; $i < sizeof($RegVar["X"]); $i++)
        array_push($Data, array("Factors"=>$RegVar["X"][$i], "Results"=>$RegVar["Y"][$i]));

    //DisplayData($Data);
    $Data = RemoveNull($Data);
    $Data = RemoveDuplicates($Data);
    $Data = RemoveOverPopulation($Data);
    $Data = RemoveRedundantFactors($Data);
    //DisplayData($Data);
    //exit;

    $XData = array();
    $YData = array();
    for($i = 0; $i < sizeof($Data); $i++){
        array_push($XData, $Data[$i]["Factors"]);
        array_push($YData, $Data[$i]["Results"]);
    }

    //____________________________

    $Reg = new \Regression\Regression();
    $Reg->setX(new \Regression\Matrix($XData));
    $Reg->setY(new \Regression\Matrix($YData));
    $Reg->exec();

    $Barangays = array();
    if($_GET['Barangay'] > 0){
        $sql = 'SELECT ID, NAME, isCoastal FROM barangay WHERE ID = ' . $_GET['Barangay'];
    }
    else if($_GET["District"] > 0){
        $sql = 'SELECT ID, NAME, isCoastal FROM barangay WHERE DISTRICT = ' . $_GET['District'];
    }
    else{
        $sql = 'SELECT ID, NAME, isCoastal FROM barangay';
    }
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while ($row = $result->fetch_assoc()){
        $row["INFOS"] = array();
        $row["DISASTERS"] = array();
        $Barangays[] = $row;
    }
    for($i = 0; $i < sizeof($Barangays); $i++){
        $sql = 'SELECT * FROM   (SELECT * FROM   (SELECT          * 
                                                FROM            barangay_info 
                                                WHERE           BARANGAY = ' . $Barangays[$i]["ID"] . '
                                                AND             DATEADDED > "' . $BackTrackTime . '"
                                                ORDER BY        DATEADDED DESC) a
                                GROUP BY        YEAR(DATEADDED)) b
                ORDER BY        DATEADDED ASC';
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while($row = $result->fetch_assoc()){
            $Barangays[$i]["INFOS"][] = $row;
        }

        $sql = 'SELECT          ID,
                                STARTED,
                                ENDED,
                                RADIUS,
                                JSON_FACTORS
                FROM            disaster_declare 
                WHERE           DISASTER = ' . $DisasterInfo["ID"] . '
                AND             BRGY = ' . $Barangays[$i]["ID"] . '
                AND             STARTED > "' . $BackTrackTime . '"
                AND             ISVERIFIED = 1';
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while($row = $result->fetch_assoc()){
            $factor_json = json_decode($row["JSON_FACTORS"], true);
            unset($row["JSON_FACTORS"]);
            foreach ($DisasterInfo["FACTORS"] as $factor){
                $row["FACTOR_" . $factor["ID"]] = Get0IfNull($factor_json["FACTOR_" . $factor["ID"]]);
            }
            $dummy_start = new DateTime($row["STARTED"]);
            $dummy_end = new DateTime($row["ENDED"]);
            $row["DURATION"] = date_diff($dummy_end, $dummy_start)->format('%a');
            $row["DURATION"] += date_diff($dummy_end, $dummy_start)->format('%h')/24;
            $row["DURATION"] += (date_diff($dummy_end, $dummy_start)->format('%i')/60)/24;
            unset($row["STARTED"]);
            unset($row["ENDED"]);
            $Barangays[$i]["DISASTERS"][] = $row;
        }
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
    <style>
        .morris-hover{position:absolute;z-index:1000}.morris-hover.morris-default-style{border-radius:10px;padding:6px;color:#666;background:rgba(255,255,255,0.8);border:solid 2px rgba(230,230,230,0.8);font-family:sans-serif;font-size:12px;text-align:center}.morris-hover.morris-default-style .morris-hover-row-label{font-weight:bold;margin:0.25em 0}
        .morris-hover.morris-default-style .morris-hover-point{white-space:nowrap;margin:0.1em 0}
    </style>
</head>
<body role="document">
    <!--NAVBAR HERE-->
    <?php include('library/html/navbar.php'); ?>
    <div class="container"> <!--Content starts here-->
        <div class="page-header">
            <h1>Forecasting <small> of Casualties of <?php echo $DisasterInfo["NAME"]; ?> in
                    <br /><?php echo $PlaceName; ?> (<?php echo $_GET["Year"]; ?>)</small>
            </h1>
        </div>
        <div class="container">
            <div class="row">
                <?php

                    $goodtogo = true;
                    if(sizeof($Disasters) < 5){
                        echo '<h3>There are not enough data to forecast casualties of a ' . $DisasterInfo["NAME"] . '.</h3>';
                        $goodtogo = false;
                    }
                    foreach ($Barangays as $barangay){
                        if(sizeof($barangay["INFOS"]) < 4){
                            echo '<h4>Brgy. ' . $barangay["NAME"] . ' doesn\'t have enough population data to forecast casualties of a ' . $DisasterInfo["NAME"] . '.</h4>';
                            $goodtogo = false;
                        }
                        if(sizeof($barangay["DISASTERS"]) < 5){
                            echo '<h4>Brgy. ' . $barangay["NAME"] . ' doesn\'t have enough disaster history data to forecast casualties of a ' . $DisasterInfo["NAME"] . '.</h4>';
                            $goodtogo = false;
                        }
                    }
                    //__________________________________________

                    function AddOneYear($newset, $year){
                        $AP = array(
                            "C_HOUSES"=>0,
                            "L_HOUSES"=>0,
                            "CL_HOUSES"=>0,
                            "T_HOUSES"=>0,
                            "Area"=>0,
                            "MEN"=>0,
                            "WOMEN"=>0,
                            "MINORS"=>0,
                            "ADULTS"=>0,
                            "PWD"=>0
                        );
                        for($i = 1; $i < sizeof($newset); $i++){
                            $AP["C_HOUSES"] += $newset[$i]["C_HOUSES_INCREASE"];
                            $AP["L_HOUSES"] += $newset[$i]["L_HOUSES_INCREASE"];
                            $AP["CL_HOUSES"] += $newset[$i]["CL_HOUSES_INCREASE"];
                            $AP["T_HOUSES"] += $newset[$i]["T_HOUSES_INCREASE"];
                            $AP["Area"] += $newset[$i]["Area_INCREASE"];
                            $AP["MEN"] += $newset[$i]["MEN_INCREASE"];
                            $AP["WOMEN"] += $newset[$i]["WOMEN_INCREASE"];
                            $AP["MINORS"] += $newset[$i]["MINORS_INCREASE"];
                            $AP["ADULTS"] += $newset[$i]["ADULTS_INCREASE"];
                            $AP["PWD"] += $newset[$i]["PWD_INCREASE"];
                        }
                        $AP["C_HOUSES"] /= (sizeof($newset) - 1);
                        $AP["L_HOUSES"] /= (sizeof($newset) - 1);
                        $AP["CL_HOUSES"] /= (sizeof($newset) - 1);
                        $AP["T_HOUSES"] /= (sizeof($newset) - 1);
                        $AP["Area"] /= (sizeof($newset) - 1);
                        $AP["MEN"] /= (sizeof($newset) - 1);
                        $AP["WOMEN"] /= (sizeof($newset) - 1);
                        $AP["MINORS"] /= (sizeof($newset) - 1);
                        $AP["ADULTS"] /= (sizeof($newset) - 1);
                        $AP["PWD"] /= (sizeof($newset) - 1);

                        array_push($newset, $newset[sizeof($newset) - 1]);

                        $newset[sizeof($newset)-1]["C_HOUSES"] = ($AP["C_HOUSES"] * $newset[sizeof($newset)-1]["C_HOUSES"]) + $newset[sizeof($newset)-1]["C_HOUSES"];
                        $newset[sizeof($newset)-1]["L_HOUSES"] = ($AP["L_HOUSES"] * $newset[sizeof($newset)-1]["L_HOUSES"]) + $newset[sizeof($newset)-1]["L_HOUSES"];
                        $newset[sizeof($newset)-1]["CL_HOUSES"] = ($AP["CL_HOUSES"] * $newset[sizeof($newset)-1]["CL_HOUSES"]) + $newset[sizeof($newset)-1]["CL_HOUSES"];
                        $newset[sizeof($newset)-1]["T_HOUSES"] = ($AP["T_HOUSES"] * $newset[sizeof($newset)-1]["T_HOUSES"]) + $newset[sizeof($newset)-1]["T_HOUSES"];
                        $newset[sizeof($newset)-1]["Area"] = ($AP["Area"] * $newset[sizeof($newset)-1]["Area"]) + $newset[sizeof($newset)-1]["Area"];
                        $newset[sizeof($newset)-1]["MEN"] = ($AP["MEN"] * $newset[sizeof($newset)-1]["MEN"]) + $newset[sizeof($newset)-1]["MEN"];
                        $newset[sizeof($newset)-1]["WOMEN"] = ($AP["WOMEN"] * $newset[sizeof($newset)-1]["WOMEN"]) + $newset[sizeof($newset)-1]["WOMEN"];
                        $newset[sizeof($newset)-1]["MINORS"] = ($AP["MINORS"] * $newset[sizeof($newset)-1]["MINORS"]) + $newset[sizeof($newset)-1]["MINORS"];
                        $newset[sizeof($newset)-1]["ADULTS"] = ($AP["ADULTS"] * $newset[sizeof($newset)-1]["ADULTS"]) + $newset[sizeof($newset)-1]["ADULTS"];
                        $newset[sizeof($newset)-1]["PWD"] = ($AP["PWD"] * $newset[sizeof($newset)-1]["PWD"]) + $newset[sizeof($newset)-1]["PWD"];

                        $newset[sizeof($newset)-1]["C_HOUSES"] = round($newset[sizeof($newset)-1]["C_HOUSES"]);
                        $newset[sizeof($newset)-1]["L_HOUSES"] = round($newset[sizeof($newset)-1]["L_HOUSES"]);
                        $newset[sizeof($newset)-1]["CL_HOUSES"] = round($newset[sizeof($newset)-1]["CL_HOUSES"]);
                        $newset[sizeof($newset)-1]["T_HOUSES"] = round($newset[sizeof($newset)-1]["T_HOUSES"]);
                        $newset[sizeof($newset)-1]["MEN"] = round($newset[sizeof($newset)-1]["MEN"]);
                        $newset[sizeof($newset)-1]["WOMEN"] = round($newset[sizeof($newset)-1]["WOMEN"]);
                        $newset[sizeof($newset)-1]["MINORS"] = round($newset[sizeof($newset)-1]["MINORS"]);
                        $newset[sizeof($newset)-1]["ADULTS"] = round($newset[sizeof($newset)-1]["ADULTS"]);
                        $newset[sizeof($newset)-1]["PWD"] = round($newset[sizeof($newset)-1]["PWD"]);

                        $newset[sizeof($newset)-1]["DATEADDED"] = $year . '-' . explode('-', $newset[sizeof($newset)-1]["DATEADDED"])[1] . '-' . explode('-', $newset[sizeof($newset)-1]["DATEADDED"])[2];

                        for($i = 1; $i < sizeof($newset); $i++){
                            $newset[$i]["C_HOUSES_INCREASE"] = ($newset[$i]["C_HOUSES"] - $newset[$i-1]["C_HOUSES"])/$newset[$i-1]["C_HOUSES"];
                            $newset[$i]["L_HOUSES_INCREASE"] = ($newset[$i]["L_HOUSES"] - $newset[$i-1]["L_HOUSES"])/$newset[$i-1]["L_HOUSES"];
                            $newset[$i]["CL_HOUSES_INCREASE"] = ($newset[$i]["CL_HOUSES"] - $newset[$i-1]["CL_HOUSES"])/$newset[$i-1]["CL_HOUSES"];
                            $newset[$i]["T_HOUSES_INCREASE"] = ($newset[$i]["T_HOUSES"] - $newset[$i-1]["T_HOUSES"])/$newset[$i-1]["T_HOUSES"];
                            $newset[$i]["Area_INCREASE"] = ($newset[$i]["Area"] - $newset[$i-1]["Area"])/$newset[$i-1]["Area"];
                            $newset[$i]["MEN_INCREASE"] = ($newset[$i]["MEN"] - $newset[$i-1]["MEN"])/$newset[$i-1]["MEN"];
                            $newset[$i]["WOMEN_INCREASE"] = ($newset[$i]["WOMEN"] - $newset[$i-1]["WOMEN"])/$newset[$i-1]["WOMEN"];
                            $newset[$i]["MINORS_INCREASE"] = ($newset[$i]["MINORS"] - $newset[$i-1]["MINORS"])/$newset[$i-1]["MINORS"];
                            $newset[$i]["ADULTS_INCREASE"] = ($newset[$i]["ADULTS"] - $newset[$i-1]["ADULTS"])/$newset[$i-1]["ADULTS"];
                            $newset[$i]["PWD_INCREASE"] = ($newset[$i]["PWD"] - $newset[$i-1]["PWD"])/$newset[$i-1]["PWD"];
                        }

                        return $newset;
                    }
                    function GetDA($item, $DisasterInfo){
                        $average = array(
                            "RADIUS"=>0,
                            "DURATION"=>0
                        );
                        foreach ($DisasterInfo["FACTORS"] as $factor){
                            $average["FACTOR_" . $factor["ID"]] = 0;
                        }
                        foreach ($item as $ittem){
                            $average["RADIUS"] += $ittem["RADIUS"];
                            $average["DURATION"] += $ittem["DURATION"];
                            foreach ($DisasterInfo["FACTORS"] as $factor){
                                $average["FACTOR_" . $factor["ID"]] += $ittem["FACTOR_" . $factor["ID"]];
                            }
                        }
                        $average["RADIUS"] /= sizeof($item);
                        $average["DURATION"] /= sizeof($item);
                        foreach ($DisasterInfo["FACTORS"] as $factor){
                            $average["FACTOR_" . $factor["ID"]] /= sizeof($item);
                        }
                        return $average;
                    }
                    function GetPopProj($items, $year){
                        $yearstart = (int)explode('-', $items[0]["DATEADDED"])[0];
                        $ys = (int)explode('-', $items[0]["DATEADDED"])[0];
                        $yearend = new DateTime();
                        $yearend = $yearend->format('Y');
                        $newset = array();
                        $returnset = array();

                        for($i = 0; $i < (((int)$yearend-(int)$ys)+1); $i++){
                            if($yearstart == (int)explode('-', $items[$i]["DATEADDED"])[0]){
                                array_push($newset, $items[$i]);
                            }
                            else{
                                $items[$i-1]["DATEADDED"] = $yearstart . '-' . explode('-', $items[$i-1]["DATEADDED"])[1] . '-' . explode('-', $items[$i-1]["DATEADDED"])[2];
                                array_push($newset, $items[$i-1]);
                                $i--;
                            }
                            $yearstart++;
                        }
                        for($i = 1; $i < sizeof($newset); $i++){
                            $newset[$i]["C_HOUSES_INCREASE"] = ($newset[$i]["C_HOUSES"] - $newset[$i-1]["C_HOUSES"])/$newset[$i-1]["C_HOUSES"];
                            $newset[$i]["L_HOUSES_INCREASE"] = ($newset[$i]["L_HOUSES"] - $newset[$i-1]["L_HOUSES"])/$newset[$i-1]["L_HOUSES"];
                            $newset[$i]["CL_HOUSES_INCREASE"] = ($newset[$i]["CL_HOUSES"] - $newset[$i-1]["CL_HOUSES"])/$newset[$i-1]["CL_HOUSES"];
                            $newset[$i]["T_HOUSES_INCREASE"] = ($newset[$i]["T_HOUSES"] - $newset[$i-1]["T_HOUSES"])/$newset[$i-1]["T_HOUSES"];
                            $newset[$i]["Area_INCREASE"] = ($newset[$i]["Area"] - $newset[$i-1]["Area"])/$newset[$i-1]["Area"];
                            $newset[$i]["MEN_INCREASE"] = ($newset[$i]["MEN"] - $newset[$i-1]["MEN"])/$newset[$i-1]["MEN"];
                            $newset[$i]["WOMEN_INCREASE"] = ($newset[$i]["WOMEN"] - $newset[$i-1]["WOMEN"])/$newset[$i-1]["WOMEN"];
                            $newset[$i]["MINORS_INCREASE"] = ($newset[$i]["MINORS"] - $newset[$i-1]["MINORS"])/$newset[$i-1]["MINORS"];
                            $newset[$i]["ADULTS_INCREASE"] = ($newset[$i]["ADULTS"] - $newset[$i-1]["ADULTS"])/$newset[$i-1]["ADULTS"];
                            $newset[$i]["PWD_INCREASE"] = ($newset[$i]["PWD"] - $newset[$i-1]["PWD"])/$newset[$i-1]["PWD"];
                        }
                        array_push($returnset, $newset[sizeof($newset)-1]);
                        for($i = 0; $i < $year-$yearend; $i++){
                            $yyy = (int)$yearend + $i + 1;
                            $newset = AddOneYear($newset, $yyy);
                            array_push($returnset, $newset[sizeof($newset)-1]);
                        }

                        for($i = 0; $i < sizeof($returnset); $i++){
                            unset($returnset[$i]["C_HOUSES_INCREASE"]);
                            unset($returnset[$i]["L_HOUSES_INCREASE"]);
                            unset($returnset[$i]["CL_HOUSES_INCREASE"]);
                            unset($returnset[$i]["T_HOUSES_INCREASE"]);
                            unset($returnset[$i]["Area_INCREASE"]);
                            unset($returnset[$i]["MEN_INCREASE"]);
                            unset($returnset[$i]["WOMEN_INCREASE"]);
                            unset($returnset[$i]["MINORS_INCREASE"]);
                            unset($returnset[$i]["ADULTS_INCREASE"]);
                            unset($returnset[$i]["PWD_INCREASE"]);
                        }
                        return $returnset;
                    }
                    function converttowholenumber($num){
                        if($num < 0){
                            return 0;
                        }
                        return floor((double)$num);
                    }

                    //__________________________________________
                    if($goodtogo){
                        foreach ($Barangays as $barangay){
                            $DA_DATA = GetDA($barangay["DISASTERS"], $DisasterInfo);
                            $YearPopulationProjections = GetPopProj($barangay["INFOS"], $_GET['Year']);
                            for($i = 0; $i < sizeof($YearPopulationProjections); $i++){
                                $MyInputData = array();
                                array_push($MyInputData, (double)$DA_DATA["RADIUS"]);
                                array_push($MyInputData, (double)$DA_DATA["DURATION"]);
                                /*array_push($MyInputData, (double)($YearPopulationProjections[$i]["C_HOUSES"]/$YearPopulationProjections[$i]["T_HOUSES"]));
                                array_push($MyInputData, (double)($YearPopulationProjections[$i]["L_HOUSES"]/$YearPopulationProjections[$i]["T_HOUSES"]));
                                array_push($MyInputData, (double)($YearPopulationProjections[$i]["CL_HOUSES"]/$YearPopulationProjections[$i]["T_HOUSES"]));*/
                                array_push($MyInputData, (double)$YearPopulationProjections[$i]["T_HOUSES"]);
                                //array_push($MyInputData, (double)$YearPopulationProjections[$i]["isFloodProne"]);
                                /*array_push($MyInputData, (double)$barangay["isCoastal"]);*/
                                array_push($MyInputData, (double)$YearPopulationProjections[$i]["Area"]);
                                /*array_push($MyInputData, (double)($YearPopulationProjections[$i]["MEN"]/($YearPopulationProjections[$i]["MEN"] + $YearPopulationProjections[$i]["WOMEN"])));
                                array_push($MyInputData, (double)($YearPopulationProjections[$i]["WOMEN"]/($YearPopulationProjections[$i]["MEN"] + $YearPopulationProjections[$i]["WOMEN"])));
                                array_push($MyInputData, (double)($YearPopulationProjections[$i]["MINORS"]/($YearPopulationProjections[$i]["MEN"] + $YearPopulationProjections[$i]["WOMEN"])));
                                array_push($MyInputData, (double)($YearPopulationProjections[$i]["ADULTS"]/($YearPopulationProjections[$i]["MEN"] + $YearPopulationProjections[$i]["WOMEN"])));
                                array_push($MyInputData, (double)($YearPopulationProjections[$i]["PWD"]/($YearPopulationProjections[$i]["MEN"] + $YearPopulationProjections[$i]["WOMEN"])));*/
                                array_push($MyInputData, (double)($YearPopulationProjections[$i]["MEN"] + $YearPopulationProjections[$i]["WOMEN"]));
                                foreach ($DisasterInfo["FACTORS"] as $factor){
                                    array_push($MyInputData, (double)$DA_DATA["FACTOR_" . $factor["ID"]]);
                                }
                                $Forecast = $Reg->predict(new \Regression\Matrix(array($MyInputData)));
                                //echo json_encode($Forecast->getData()) . '<br /><hr /><br />';
                                $PPop = (double)($YearPopulationProjections[$i]["MEN"] + $YearPopulationProjections[$i]["WOMEN"]);
                                $HPop = (double)$YearPopulationProjections[$i]["T_HOUSES"];
                                $res = array(
                                    converttowholenumber(round($Forecast->getData()[0][0])),
                                    converttowholenumber(round($Forecast->getData()[0][1])),
                                    converttowholenumber(round($Forecast->getData()[0][2])),
                                    converttowholenumber(round($Forecast->getData()[0][3])),
                                    converttowholenumber(round($Forecast->getData()[0][4]))
                                );


                                if(($res[0]/$PPop) > $YMaxPerc[0]){
                                    $res[0] = round($YMaxPerc[0] * $PPop);
                                    $YMaxPerc[0] *= 1.05;
                                }
                                if(($res[1]/$PPop) > $YMaxPerc[1]){
                                    $res[1] = round($YMaxPerc[1] * $PPop);
                                    $YMaxPerc[1] *= 1.05;
                                }
                                if(($res[2]/$PPop) > $YMaxPerc[2]){
                                    $res[2] = round($YMaxPerc[2] * $PPop);
                                    $YMaxPerc[2] *= 1.05;
                                }
                                if(($res[3]/$HPop) > $YMaxPerc[4]){
                                    $res[3] = round($YMaxPerc[3] * $HPop);
                                    $YMaxPerc[3] *= 1.05;
                                }
                                if(($res[4]/$HPop) > $YMaxPerc[4]){
                                    $res[4] = round($YMaxPerc[4] * $HPop);
                                    $YMaxPerc[4] *= 1.05;
                                }


                                $Results[$i]["DEAD"] += $res[0];
                                $Results[$i]["MISSING"] += $res[1];
                                $Results[$i]["INJURED"] += $res[2];
                                $Results[$i]["TOTALLY"] += $res[3];
                                $Results[$i]["PARTIALLY"] += $res[4];
                                $Results[$i]["POPULATION"] += (double)($YearPopulationProjections[$i]["MEN"] + $YearPopulationProjections[$i]["WOMEN"]);
                                $Results[$i]["HOUSES"] += (double)$YearPopulationProjections[$i]["T_HOUSES"];
                            }
                        }
                        ?>
                        <div class="row">
                            <div class="col-lg-8">
                                <h3>Graph of Possible Affected People by Year</h3>
                                <div class="container-fluid">
                                    <div id="Affected_Graph"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <h4>Possible Affected People in <?php echo $_GET["Year"]; ?>:</h4>
                                <h1><?php echo number_format(($Results[sizeof($Results)-1]["DEAD"]+$Results[sizeof($Results)-1]["MISSING"]+$Results[sizeof($Results)-1]["INJURED"])); ?> of <?php echo number_format($Results[sizeof($Results)-1]["POPULATION"]); ?></h1>
                                <div id="Affected_Donut" style="height: 250px;"></div>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-lg-8">
                                <h3>Graph of Possible Damaged Houses/Buildings</h3>
                                <div class="container-fluid">
                                    <div id="Damages_Graph"></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <h4>Possible Damaged Houses/Buildings in <?php echo $_GET["Year"]; ?>:</h4>
                                <h1><?php echo number_format(($Results[sizeof($Results)-1]["TOTALLY"]+$Results[sizeof($Results)-1]["PARTIALLY"])); ?> of <?php echo number_format($Results[sizeof($Results)-1]["HOUSES"]); ?></h1>
                                <div id="Damaged_Donut" style="height: 250px;"></div>
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
        var dir = document.URL.substr(0,document.URL.lastIndexOf('/'));
        $(document).ready(function() {
            //Morris charts snippet - js
            $.getScript(dir + '/js/app/raphael-min.js',function(){
                $.getScript(dir + '/js/app/morris.min.js',function(){
                    /*
                     "Year"=>$i,
                     "DEAD"=>0,
                     "MISSING"=>0,
                     "INJURED"=>0,
                     "TOTALLY"=>0,
                     "PARTIALLY"=>0,
                     "DATAINPUTS"=>array()
                     */
                    Morris.Line({
                        element: 'Affected_Graph',
                        data: [
                            <?php
                                $txt = "";
                                foreach ($Results as $result){
                                    $txt .= "\n{ y: '" . $result['Year'] .  "', a: " . ($result["DEAD"]+$result["MISSING"]+$result["INJURED"]) . "},";
                                }
                                $txt = substr($txt, 0, strlen($txt)-1);
                            echo $txt;
                            ?>
                        ],
                        xkey: 'y',
                        ykeys: ['a'],
                        labels: ['Affected'],
                        ymin: 'auto'
                    });

                    Morris.Line({
                        element: 'Damages_Graph',
                        data: [
                            <?php
                            $txt = "";
                            foreach ($Results as $result){
                                $txt .= "\n{ y: '" . $result['Year'] .  "', a: " . ($result["TOTALLY"]+$result["PARTIALLY"]) . "},";
                            }
                            $txt = substr($txt, 0, strlen($txt)-1);
                            echo $txt;
                            ?>
                        ],
                        xkey: 'y',
                        ykeys: ['a'],
                        labels: ['Damaged Houses/Buildings'],
                        ymin: 'auto'
                    });

                    Morris.Donut({
                        element: 'Affected_Donut',
                        data: [
                            {label: "Affected", value: <?php echo ($Results[sizeof($Results)-1]["DEAD"]+$Results[sizeof($Results)-1]["MISSING"]+$Results[sizeof($Results)-1]["INJURED"]); ?>},
                            {label: "Not Affected", value: <?php echo $Results[sizeof($Results)-1]["POPULATION"] - ($Results[sizeof($Results)-1]["DEAD"]+$Results[sizeof($Results)-1]["MISSING"]+$Results[sizeof($Results)-1]["INJURED"]); ?>}              ]
                    });

                    Morris.Donut({
                        element: 'Damaged_Donut',
                        data: [
                            {label: "Damaged", value: <?php echo($Results[sizeof($Results)-1]["TOTALLY"]+$Results[sizeof($Results)-1]["PARTIALLY"]); ?>},
                            {label: "Not Damaged", value: <?php echo $Results[sizeof($Results)-1]["HOUSES"] - ($Results[sizeof($Results)-1]["TOTALLY"]+$Results[sizeof($Results)-1]["PARTIALLY"]); ?>}                ]
                    });

                });
            });
        });

    </script>

</body>
</html>



