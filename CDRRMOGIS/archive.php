<?php
session_start();
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="th3515">
    <meta name="author" content="@pablongbuhaymo">

    <title>Archive | City Disaster Risk Management</title>    
    <link rel="icon" href="img/favicon.ico">

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/app.css" rel="stylesheet">

   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"
    integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="
   crossorigin=""/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<!-- The #page-top ID is part of the scrolling feature - the data-spy and data-target are part of the built-in Bootstrap scrollspy function -->

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

<?php include('library/html/navbar.php');
       include('library/html/loginmodal.php');
    ?>

    <!--Content starts here-->

    <div class="container fluid"> 
    
    <div class = "row no-pad">
        <div class="page-header">
            <div style="margin: auto; text-align: center;" class="pull-right">
                <div class="btn-group" role="group" aria-label="...">
                    <a href="archive.php" class="btn btn-basic active">Disaster Archive</a>
                    <a href="disaster.php" class="btn btn-basic">Disaster Statistics</a>  
                </div>
            </div>
            <h3>Disaster Archive <small>of Iloilo City</small></h3>
         </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="map" style="height: 70vh; width: 100%;" tabindex="0"> </div>
                </div>

      </div>
      </div>

<br>

  

<?php include('library/html/footer.php'); ?>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/app/mylibrary.js"></script>
    <script src="js/app/loginscript.js"></script>

    <script src="js/chart.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
   integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
   crossorigin=""></script>
   <script type="text/javascript" src="city.js"></script>
    
</body>

</html>



<script>
  
     var map = L.map('map').setView([10.719950067615137, 122.554175308317468], 13);
            mapLink = 
                '<a href="http://openstreetmap.org">OpenStreetMap</a>';
            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                maxZoom: 18,
                id: 'mapbox.light'
                }).addTo(map);


  var layerGroup = L.layerGroup().addTo(map);

        var disasterName = [
                    
                <?php
                    $sql ='SELECT 

                        disaster_declare.ID, disaster_declare.BRGY, disaster_declare.LAT, disaster_declare.LNG, disaster_declare.STARTED, disaster_declare.RADIUS, disaster_declare.JSON_FACTORS, disaster_type.COLOR, disaster_declare.NICKNAME, disaster_declare.ENDED, disaster_type.NAME AS TYPENAME 

                        FROM disaster_declare
                        INNER JOIN disaster_type
                        ON disaster_declare.DISASTER = disaster_type.ID
                        WHERE disaster_declare.ACCEPTED = 1
                        AND disaster_declare.ISVERIFIED = 1
                        AND STARTED >= now() - INTERVAL 1 DAY';

                    $result = $db->connection->query($sql);
                    $count = mysqli_num_rows($result);

                    while($row = $result->fetch_array())
                    {

                        ?> ['<?php echo $row['ID']; ?>','<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>','<?php echo $row['RADIUS']; ?>','<?php echo $row['COLOR']; ?>','<?php echo $row['STARTED']; ?>','<?php echo $row['JSON_FACTORS']; ?>','<?php echo $row['NICKNAME']; ?>','<?php echo $row['ENDED']; ?>','<?php echo $row['TYPENAME']; ?>'],
                        <?php
                    }    
                    ?>

                       ];
                       
                         changeMap(); 

 
 function centerLeafletMapOnMarker(map, marker) {
      var latLngs = [ marker.getLatLng() ];
      var markerBounds = L.latLngBounds(latLngs);
      map.fitBounds(markerBounds);
    }

    var info = L.control({position: 'bottomright'});

    info.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info');
        this.update();
        return this._div;
    };

    info.update = function (props) {
        this._div.innerHTML = '<h5> Disaster Types: '
                            <?php
                                $sql = 'SELECT * FROM disaster_type';
                                $result = $db->connection->query($sql);
                                while ($row = $result->fetch_assoc()){
                                    ?>
                                    +'<a href = "#" class="glyphicon glyphicon-stop " title = " <?php echo $row['NAME']; ?>" style="color: <?php echo $row['COLOR']; ?>; text-decoration: none;"></a>'
                                    <?php
                                }
                                +'</h5>'?>;
    };

    info.addTo(map);


   var dateSelect = L.control();

    dateSelect.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info');
        this.update();
        return this._div;
    };

    dateSelect.update = function (props) {
        this._div.innerHTML =    
        '<select class="form-control" id = "dateSelectHTML" name = "dateSelectHTML">'+
        '   <option value="A" >past 24 hours</option>'+
        '   <option value="B">past week</option>'+
        '   <option value="C">past month</option>'+
        '   <option value="D">past year</option>'+
        '   <option value="E">all time</option>'+
        '   </select>';    };

    dateSelect.addTo(map);



$(document).change(function(){
        switch($('#dateSelectHTML option:selected').val()) { 

            case "E":

               disasterName = [
            
        <?php
           $sql ='SELECT 

                disaster_declare.ID, disaster_declare.BRGY, disaster_declare.LAT, disaster_declare.LNG, disaster_declare.STARTED, disaster_declare.RADIUS, disaster_declare.JSON_FACTORS, disaster_type.COLOR, disaster_declare.NICKNAME, disaster_declare.ENDED, disaster_type.NAME AS TYPENAME 

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ACCEPTED = 1
                AND disaster_declare.ISVERIFIED = 1';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                 $sql2 ='SELECT barangay_info.MEN + barangay_info.WOMEN AS TOTAL, barangay_info.L_HOUSES + barangay_info.C_HOUSES + barangay_info.CL_HOUSES AS TOTALHOUSES FROM barangay_info WHERE barangay_info.BARANGAY = '.$row['BRGY'].'
                        ORDER BY barangay_info.DATEADDED DESC
                        LIMIT 1';
                $result2 = $db->connection->query($sql2);
                $count2 = mysqli_num_rows($result2);
                $totalpopulation = 0;
                $totalhouses = 0;
                $totalpercentile = 0;
                $totaldead = 0;
                $totalmissing = 0;
                $totalinjured = 0;
                $totalpartialdmg = 0;
                $totaltotaldmg = 0;

                while($row2 = $result2->fetch_array()){
                    $totalpopulation = $row2['TOTAL'];
                    $totalhouses = $row2['TOTALHOUSES'];
                }

                $sql3 ="SELECT * FROM disaster_reports WHERE DECLAREID = ".$row['ID']." ORDER BY disaster_reports.DATEADDED DESC LIMIT 1";

                $result3 = $db->connection->query($sql3);
                $count3 = mysqli_num_rows($result3);

                while($row3 = $result3->fetch_array()){
                    $totaldead += $row3['CSLTDEAD'];
                    $totalmissing += $row3['CSLTMISSING'];
                    $totalinjured += $row3['CSLTINJURED'];
                    $totalpartialdmg += $row3['DMGPARTIALLY'];
                    $totaltotaldmg += $row3['DMGTOTALLY'];
                }

                $totalcasualty = $totaldead + $totalmissing + $totalinjured;
                $categorypopulation = $totalcasualty / $totalpopulation;
                $scorepopulation = 0;

                if($categorypopulation > 0.2)
                {
                    $scorepopulation = 1;
                }
                else if($categorypopulation > 0.1 && $categorypopulation <= 0.2)
                {
                    $scorepopulation = 0.75;
                }
                else if($categorypopulation > 0.05 && $categorypopulation <= 0.1)
                {
                    $scorepopulation = 0.5;
                }
                else if($categorypopulation < 0.05)
                {
                    $scorepopulation = 0.25;
                }


                $totalpropertiesdmg = $totaltotaldmg + $totalpartialdmg;
                $categoryproperties = $totalpropertiesdmg / $totalhouses;
                $scoreproperties;

                if($categoryproperties > 0.2)
                {
                    $scoreproperties = 1;
                }
                else if($categoryproperties > 0.1 && $categorypopulation <= 0.2)
                {
                    $scoreproperties = 0.75;
                }
                else if($categoryproperties > 0.05 && $categorypopulation <= 0.1)
                {
                    $scoreproperties = 0.5;
                }
                else if($categoryproperties < 0.05)
                {
                    $scoreproperties = 0.25;
                }

                $totalscore = ($scorepopulation * 0.7) + ($scoreproperties * 0.3);
        
                ?> ['<?php echo $row['ID']; ?>','<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>','<?php echo $row['RADIUS']; ?>','<?php echo $row['COLOR']; ?>','<?php echo $row['STARTED']; ?>','<?php echo $row['JSON_FACTORS']; ?>','<?php echo $row['NICKNAME']; ?>','<?php echo $row['ENDED']; ?>','<?php echo $row['TYPENAME']; ?>','<?php echo $totalscore; ?>'],
                <?php
            }    
            ?>

               ];
               

            changeMap();         

            break;

            case "A":

               disasterName = [
       
        <?php
             $sql ='SELECT 

                disaster_declare.ID, disaster_declare.BRGY, disaster_declare.LAT, disaster_declare.LNG, disaster_declare.STARTED, disaster_declare.RADIUS, disaster_declare.JSON_FACTORS, disaster_type.COLOR, disaster_declare.NICKNAME, disaster_declare.ENDED, disaster_type.NAME AS TYPENAME 

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ACCEPTED = 1
                AND disaster_declare.ISVERIFIED = 1
                AND STARTED >= now() - INTERVAL 1 DAY';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                 $sql2 ='SELECT barangay_info.MEN + barangay_info.WOMEN AS TOTAL, barangay_info.L_HOUSES + barangay_info.C_HOUSES + barangay_info.CL_HOUSES AS TOTALHOUSES FROM barangay_info WHERE barangay_info.BARANGAY = '.$row['BRGY'].'
                        ORDER BY barangay_info.DATEADDED DESC
                        LIMIT 1';
                $result2 = $db->connection->query($sql2);
                $count2 = mysqli_num_rows($result2);
                $totalpopulation = 0;
                $totalhouses = 0;
                $totalpercentile = 0;
                $totaldead = 0;
                $totalmissing = 0;
                $totalinjured = 0;
                $totalpartialdmg = 0;
                $totaltotaldmg = 0;

                while($row2 = $result2->fetch_array()){
                    $totalpopulation = $row2['TOTAL'];
                    $totalhouses = $row2['TOTALHOUSES'];
                }

                $sql3 ="SELECT * FROM disaster_reports WHERE DECLAREID = ".$row['ID']." ORDER BY disaster_reports.DATEADDED DESC LIMIT 1";

                $result3 = $db->connection->query($sql3);
                $count3 = mysqli_num_rows($result3);

                while($row3 = $result3->fetch_array()){
                    $totaldead += $row3['CSLTDEAD'];
                    $totalmissing += $row3['CSLTMISSING'];
                    $totalinjured += $row3['CSLTINJURED'];
                    $totalpartialdmg += $row3['DMGPARTIALLY'];
                    $totaltotaldmg += $row3['DMGTOTALLY'];
                }


                $totalcasualty = $totaldead + $totalmissing + $totalinjured;
                $categorypopulation = $totalcasualty / $totalpopulation;
                $scorepopulation = 0;

                if($categorypopulation > 0.2)
                {
                    $scorepopulation = 1;
                }
                else if($categorypopulation > 0.1 && $categorypopulation <= 0.2)
                {
                    $scorepopulation = 0.75;
                }
                else if($categorypopulation > 0.05 && $categorypopulation <= 0.1)
                {
                    $scorepopulation = 0.5;
                }
                else if($categorypopulation < 0.05)
                {
                    $scorepopulation = 0.25;
                }


                $totalpropertiesdmg = $totaltotaldmg + $totalpartialdmg;
                $categoryproperties = $totalpropertiesdmg / $totalhouses;
                $scoreproperties;

                if($categoryproperties > 0.2)
                {
                    $scoreproperties = 1;
                }
                else if($categoryproperties > 0.1 && $categorypopulation <= 0.2)
                {
                    $scoreproperties = 0.75;
                }
                else if($categoryproperties > 0.05 && $categorypopulation <= 0.1)
                {
                    $scoreproperties = 0.5;
                }
                else if($categoryproperties < 0.05)
                {
                    $scoreproperties = 0.25;
                }

                $totalscore = ($scorepopulation * 0.7) + ($scoreproperties * 0.3);
        
                ?> ['<?php echo $row['ID']; ?>','<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>','<?php echo $row['RADIUS']; ?>','<?php echo $row['COLOR']; ?>','<?php echo $row['STARTED']; ?>','<?php echo $row['JSON_FACTORS']; ?>','<?php echo $row['NICKNAME']; ?>','<?php echo $row['ENDED']; ?>','<?php echo $row['TYPENAME']; ?>','<?php echo $totalscore; ?>'],
                <?php
            }    
            ?>

               ];
               

            changeMap();         

            break;

            case "B":

               disasterName = [
       
        <?php
            $sql ='SELECT 

                disaster_declare.ID, disaster_declare.BRGY, disaster_declare.LAT, disaster_declare.LNG, disaster_declare.STARTED, disaster_declare.RADIUS, disaster_declare.JSON_FACTORS, disaster_type.COLOR, disaster_declare.NICKNAME, disaster_declare.ENDED, disaster_type.NAME AS TYPENAME 

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ACCEPTED = 1
                AND disaster_declare.ISVERIFIED = 1
                AND STARTED >= now() - INTERVAL 1 WEEK';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                 $sql2 ='SELECT barangay_info.MEN + barangay_info.WOMEN AS TOTAL, barangay_info.L_HOUSES + barangay_info.C_HOUSES + barangay_info.CL_HOUSES AS TOTALHOUSES FROM barangay_info WHERE barangay_info.BARANGAY = '.$row['BRGY'].'
                        ORDER BY barangay_info.DATEADDED DESC
                        LIMIT 1';
                $result2 = $db->connection->query($sql2);
                $count2 = mysqli_num_rows($result2);
                $totalpopulation = 0;
                $totalhouses = 0;
                $totalpercentile = 0;
                $totaldead = 0;
                $totalmissing = 0;
                $totalinjured = 0;
                $totalpartialdmg = 0;
                $totaltotaldmg = 0;

                while($row2 = $result2->fetch_array()){
                    $totalpopulation = $row2['TOTAL'];
                    $totalhouses = $row2['TOTALHOUSES'];
                }

                $sql3 ="SELECT * FROM disaster_reports WHERE DECLAREID = ".$row['ID']." ORDER BY disaster_reports.DATEADDED DESC LIMIT 1";

                $result3 = $db->connection->query($sql3);
                $count3 = mysqli_num_rows($result3);

                while($row3 = $result3->fetch_array()){
                    $totaldead += $row3['CSLTDEAD'];
                    $totalmissing += $row3['CSLTMISSING'];
                    $totalinjured += $row3['CSLTINJURED'];
                    $totalpartialdmg += $row3['DMGPARTIALLY'];
                    $totaltotaldmg += $row3['DMGTOTALLY'];
                }

  $totalcasualty = $totaldead + $totalmissing + $totalinjured;
                $categorypopulation = $totalcasualty / $totalpopulation;
                $scorepopulation = 0;

                if($categorypopulation > 0.2)
                {
                    $scorepopulation = 1;
                }
                else if($categorypopulation > 0.1 && $categorypopulation <= 0.2)
                {
                    $scorepopulation = 0.75;
                }
                else if($categorypopulation > 0.05 && $categorypopulation <= 0.1)
                {
                    $scorepopulation = 0.5;
                }
                else if($categorypopulation < 0.05)
                {
                    $scorepopulation = 0.25;
                }


                $totalpropertiesdmg = $totaltotaldmg + $totalpartialdmg;
                $categoryproperties = $totalpropertiesdmg / $totalhouses;
                $scoreproperties;

                if($categoryproperties > 0.2)
                {
                    $scoreproperties = 1;
                }
                else if($categoryproperties > 0.1 && $categorypopulation <= 0.2)
                {
                    $scoreproperties = 0.75;
                }
                else if($categoryproperties > 0.05 && $categorypopulation <= 0.1)
                {
                    $scoreproperties = 0.5;
                }
                else if($categoryproperties < 0.05)
                {
                    $scoreproperties = 0.25;
                }

                $totalscore = ($scorepopulation * 0.7) + ($scoreproperties * 0.3);
        
                ?> ['<?php echo $row['ID']; ?>','<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>','<?php echo $row['RADIUS']; ?>','<?php echo $row['COLOR']; ?>','<?php echo $row['STARTED']; ?>','<?php echo $row['JSON_FACTORS']; ?>','<?php echo $row['NICKNAME']; ?>','<?php echo $row['ENDED']; ?>','<?php echo $row['TYPENAME']; ?>','<?php echo $totalscore; ?>'],
                <?php
            }    
            ?>
               ];
               

            changeMap();         

            case "C":

               disasterName = [
       
        <?php
            $sql ='SELECT 

                disaster_declare.ID, disaster_declare.BRGY, disaster_declare.LAT, disaster_declare.LNG, disaster_declare.STARTED, disaster_declare.RADIUS, disaster_declare.JSON_FACTORS, disaster_type.COLOR, disaster_declare.NICKNAME, disaster_declare.ENDED, disaster_type.NAME AS TYPENAME 

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ACCEPTED = 1
                AND disaster_declare.ISVERIFIED = 1
                AND STARTED >= now() - INTERVAL 1 MONTH';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                 $sql2 ='SELECT barangay_info.MEN + barangay_info.WOMEN AS TOTAL, barangay_info.L_HOUSES + barangay_info.C_HOUSES + barangay_info.CL_HOUSES AS TOTALHOUSES FROM barangay_info WHERE barangay_info.BARANGAY = '.$row['BRGY'].'
                        ORDER BY barangay_info.DATEADDED DESC
                        LIMIT 1';
                $result2 = $db->connection->query($sql2);
                $count2 = mysqli_num_rows($result2);
                $totalpopulation = 0;
                $totalhouses = 0;
                $totalpercentile = 0;
                $totaldead = 0;
                $totalmissing = 0;
                $totalinjured = 0;
                $totalpartialdmg = 0;
                $totaltotaldmg = 0;

                while($row2 = $result2->fetch_array()){
                    $totalpopulation = $row2['TOTAL'];
                    $totalhouses = $row2['TOTALHOUSES'];
                }

                $sql3 ="SELECT * FROM disaster_reports WHERE DECLAREID = ".$row['ID']." ORDER BY disaster_reports.DATEADDED DESC LIMIT 1";

                $result3 = $db->connection->query($sql3);
                $count3 = mysqli_num_rows($result3);

                while($row3 = $result3->fetch_array()){
                    $totaldead += $row3['CSLTDEAD'];
                    $totalmissing += $row3['CSLTMISSING'];
                    $totalinjured += $row3['CSLTINJURED'];
                    $totalpartialdmg += $row3['DMGPARTIALLY'];
                    $totaltotaldmg += $row3['DMGTOTALLY'];
                }


  $totalcasualty = $totaldead + $totalmissing + $totalinjured;
                $categorypopulation = $totalcasualty / $totalpopulation;
                $scorepopulation = 0;

                if($categorypopulation > 0.2)
                {
                    $scorepopulation = 1;
                }
                else if($categorypopulation > 0.1 && $categorypopulation <= 0.2)
                {
                    $scorepopulation = 0.75;
                }
                else if($categorypopulation > 0.05 && $categorypopulation <= 0.1)
                {
                    $scorepopulation = 0.5;
                }
                else if($categorypopulation < 0.05)
                {
                    $scorepopulation = 0.25;
                }


                $totalpropertiesdmg = $totaltotaldmg + $totalpartialdmg;
                $categoryproperties = $totalpropertiesdmg / $totalhouses;
                $scoreproperties;

                if($categoryproperties > 0.2)
                {
                    $scoreproperties = 1;
                }
                else if($categoryproperties > 0.1 && $categorypopulation <= 0.2)
                {
                    $scoreproperties = 0.75;
                }
                else if($categoryproperties > 0.05 && $categorypopulation <= 0.1)
                {
                    $scoreproperties = 0.5;
                }
                else if($categoryproperties < 0.05)
                {
                    $scoreproperties = 0.25;
                }



                $totalscore = ($scorepopulation * 0.7) + ($scoreproperties * 0.3);
        
                ?> ['<?php echo $row['ID']; ?>','<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>','<?php echo $row['RADIUS']; ?>','<?php echo $row['COLOR']; ?>','<?php echo $row['STARTED']; ?>','<?php echo $row['JSON_FACTORS']; ?>','<?php echo $row['NICKNAME']; ?>','<?php echo $row['ENDED']; ?>','<?php echo $row['TYPENAME']; ?>','<?php echo $totalscore; ?>'],
                <?php
            }    
            ?>
               ];
               

            changeMap();         

            break;


            case "D":

               disasterName = [
       
        <?php
            $sql ='SELECT 

                disaster_declare.ID, disaster_declare.BRGY, disaster_declare.LAT, disaster_declare.LNG, disaster_declare.STARTED, disaster_declare.RADIUS, disaster_declare.JSON_FACTORS, disaster_type.COLOR, disaster_declare.NICKNAME, disaster_declare.ENDED, disaster_type.NAME AS TYPENAME 

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ACCEPTED = 1
                AND disaster_declare.ISVERIFIED = 1
                AND STARTED >= now() - INTERVAL 1 YEAR';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                 $sql2 ='SELECT barangay_info.MEN + barangay_info.WOMEN AS TOTAL, barangay_info.L_HOUSES + barangay_info.C_HOUSES + barangay_info.CL_HOUSES AS TOTALHOUSES FROM barangay_info WHERE barangay_info.BARANGAY = '.$row['BRGY'].'
                        ORDER BY barangay_info.DATEADDED DESC
                        LIMIT 1';
                $result2 = $db->connection->query($sql2);
                $count2 = mysqli_num_rows($result2);
                $totalpopulation = 0;
                $totalhouses = 0;
                $totalpercentile = 0;
                $totaldead = 0;
                $totalmissing = 0;
                $totalinjured = 0;
                $totalpartialdmg = 0;
                $totaltotaldmg = 0;

                while($row2 = $result2->fetch_array()){
                    $totalpopulation = $row2['TOTAL'];
                    $totalhouses = $row2['TOTALHOUSES'];
                }

                $sql3 ="SELECT * FROM disaster_reports WHERE DECLAREID = ".$row['ID']." ORDER BY disaster_reports.DATEADDED DESC LIMIT 1";

                $result3 = $db->connection->query($sql3);
                $count3 = mysqli_num_rows($result3);

                while($row3 = $result3->fetch_array()){
                    $totaldead += $row3['CSLTDEAD'];
                    $totalmissing += $row3['CSLTMISSING'];
                    $totalinjured += $row3['CSLTINJURED'];
                    $totalpartialdmg += $row3['DMGPARTIALLY'];
                    $totaltotaldmg += $row3['DMGTOTALLY'];
                }

  $totalcasualty = $totaldead + $totalmissing + $totalinjured;
                $categorypopulation = $totalcasualty / $totalpopulation;
                $scorepopulation = 0;

                if($categorypopulation > 0.2)
                {
                    $scorepopulation = 1;
                }
                else if($categorypopulation > 0.1 && $categorypopulation <= 0.2)
                {
                    $scorepopulation = 0.75;
                }
                else if($categorypopulation > 0.05 && $categorypopulation <= 0.1)
                {
                    $scorepopulation = 0.5;
                }
                else if($categorypopulation < 0.05)
                {
                    $scorepopulation = 0.25;
                }


                $totalpropertiesdmg = $totaltotaldmg + $totalpartialdmg;
                $categoryproperties = $totalpropertiesdmg / $totalhouses;
                $scoreproperties;

                if($categoryproperties > 0.2)
                {
                    $scoreproperties = 1;
                }
                else if($categoryproperties > 0.1 && $categorypopulation <= 0.2)
                {
                    $scoreproperties = 0.75;
                }
                else if($categoryproperties > 0.05 && $categorypopulation <= 0.1)
                {
                    $scoreproperties = 0.5;
                }
                else if($categoryproperties < 0.05)
                {
                    $scoreproperties = 0.25;
                }

                $totalscore = ($scorepopulation * 0.7) + ($scoreproperties * 0.3);
        
                ?> ['<?php echo $row['ID']; ?>','<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>','<?php echo $row['RADIUS']; ?>','<?php echo $row['COLOR']; ?>','<?php echo $row['STARTED']; ?>','<?php echo $row['JSON_FACTORS']; ?>','<?php echo $row['NICKNAME']; ?>','<?php echo $row['ENDED']; ?>','<?php echo $row['TYPENAME']; ?>','<?php echo $totalscore; ?>'],
                <?php
            }    
            ?>
               ];
               

            changeMap();        

            break;
           



        }
    });



 function changeMap()
    {       
        layerGroup.clearLayers();

            for (var i = 0; i < disasterName.length; i++) {
             var circle = L.circle([disasterName[i][1],disasterName[i][2]], {
                color: disasterName[i][4],
                fillColor: disasterName[i][4],
                fillOpacity: disasterName[i][10],
                radius: disasterName[i][3]
                })
                .bindPopup('<center><strong>NICKNAME: ' + disasterName[i][7] + '</strong><small><br>Disaster Type: '+ disasterName[i][9] + '<br> STARTED: ' + disasterName[i][5] + '<br> ENDED: ' + disasterName[i][8] + '</small><br><a href="disasterinfo.php?id='+ disasterName[i][0] +'" class="btn btn-info">View Full Info</a></center>')
                .on('click', function() {
                  centerLeafletMapOnMarker(map, this);
                  })
            .addTo(layerGroup);
        }
    }

</script>   