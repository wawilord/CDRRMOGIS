<?php

    include('library/form/connection.php');
    include('library/function/functions.php');
    $db = new db();
    session_start();

    $selected_val = 0;

if(isset($_POST['submit'])){
$selected_val = $_POST['rangevalue'];  

 }

$totaldeaths = '0';
$totalinjured = '0';
$totalmissing = '0';
$totalassistance = '0';
$totalservedfamily = '0';
$totalservedpersons = '0';
    
    for($i = 1; $i !== 10; $i++)
    {

        $togtotaldeaths = '0';
        $togtotalinjured = '0';
        $togtotalmissing = '0';
        $togtotalassistance = '0';
        $togtotalservedfamily = '0';
        $togtotalservedpersons = '0';

          if($i == 1)
        {
            $tog = 1;
        }
        else if($i == 2)
        {
            $tog = 0.9;
        }
        else if($i == 3)
        {
            $tog = 0.8;
        }
        else if($i == 4)
        {
            $tog = 0.7;
        }
        else if($i == 5)
        {
            $tog = 0.6;
        }
        else if($i == 6)
        {
            $tog = 0.5;
        }
        else if($i == 7)
        {
            $tog = 0.4;
        }
        else if($i == 8)
        {
            $tog = 0.3;
        }
        else if($i == 9)
        {
            $tog = 0.2;
        }
        else if($i == 10)
        {
            $tog = 0.1;
        }
            
        $sqlarg = 'STARTED >= now() - INTERVAL ' . $i . ' YEAR';

                $sql ='

                    SELECT disaster_reports.ID, disaster_reports.DECLAREID, disaster_typhoonprofile.SIGNALNO, disaster_reports.CSLTDEAD, disaster_reports.CSLTINJURED, disaster_reports.CSLTMISSING
                
                FROM disaster_reports
                
                LEFT JOIN disaster_declare
                ON disaster_reports.DECLAREID = disaster_declare.ID
                LEFT JOIN disaster_typhoonlist
                ON disaster_declare.ID = disaster_typhoonlist.DECLAREID
                LEFT JOIN disaster_typhoonprofile
                ON disaster_typhoonlist.PROFILEID = disaster_typhoonprofile.ID
                WHERE disaster_typhoonprofile.DATESTART >= now() - INTERVAL 10 YEAR
                AND disaster_typhoonprofile.SIGNALNO = '. $selected_val.'
                AND disaster_declare.ISVERIFIED = 1
                AND disaster_declare.ACCEPTED = 1
                AND '. $sqlarg .'
                AND disaster_reports.ISVERIFIED = 1
                ';

        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while($row = $result->fetch_assoc()){
            $togtotaldeaths += $row['CSLTDEAD'];
            $togtotalinjured += $row['CSLTINJURED'];
            $togtotalmissing += $row['CSLTMISSING'];
        }


        $sql2 =
            '
            SELECT disaster_cost.ID, disaster_typhoonprofile.SIGNALNO, disaster_cost.DSWD, disaster_cost.LGU, disaster_cost.NGO FROM disaster_cost LEFT JOIN disaster_declare
                ON disaster_cost.DECLAREID = disaster_declare.ID
                LEFT JOIN disaster_typhoonlist
                ON disaster_declare.ID = disaster_typhoonlist.DECLAREID
                LEFT JOIN disaster_typhoonprofile
                ON disaster_typhoonlist.PROFILEID = disaster_typhoonprofile.ID
                WHERE disaster_typhoonprofile.DATESTART >= now() - INTERVAL 10 YEAR
                AND disaster_typhoonprofile.SIGNALNO = '. $selected_val.'
                AND disaster_declare.ISVERIFIED = 1
                AND '. $sqlarg .'
                AND disaster_declare.ACCEPTED = 1';

        $result2 = $db->connection->query($sql2);
        $count2 = mysqli_num_rows($result2);
        while($row2 = $result2->fetch_assoc()){
            $togtotalassistance += $row2['DSWD'];
            $togtotalassistance += $row2['LGU'];
            $togtotalassistance += $row2['NGO'];
        }


        $sql3 = '

            SELECT evacuation_report.ID, evacuation_report.DECLAREID, disaster_typhoonprofile.SIGNALNO, evacuation_report.SRVFAMILIES, evacuation_report.SRVPERSONS
                
                FROM evacuation_report
                
                LEFT JOIN disaster_declare
                ON evacuation_report.DECLAREID = disaster_declare.ID
                LEFT JOIN disaster_typhoonlist
                ON disaster_declare.ID = disaster_typhoonlist.DECLAREID
                LEFT JOIN disaster_typhoonprofile
                ON disaster_typhoonlist.PROFILEID = disaster_typhoonprofile.ID
                WHERE disaster_typhoonprofile.DATESTART >= now() - INTERVAL 10 YEAR
                AND disaster_typhoonprofile.SIGNALNO = '. $selected_val.'
                AND disaster_declare.ISVERIFIED = 1
                AND disaster_declare.ACCEPTED = 1
                AND '. $sqlarg .'
                AND evacuation_report.ISVERIFIED = 1';
          
        $result3 = $db->connection->query($sql3);
        $count3 = mysqli_num_rows($result3);
        while($row3 = $result3->fetch_assoc()){
            $togtotalservedfamily += $row3['SRVFAMILIES'];
            $togtotalservedpersons += $row3['SRVPERSONS'];
        }  



        $totaldeaths = $togtotaldeaths * $tog;
        $totalinjured = $togtotalinjured * $tog;
        $totalmissing = $togtotalmissing * $tog; 
        $totalassistance = $togtotalassistance * $tog;
        $totalservedfamily = $togtotalservedfamily * $tog;
        $totalservedpersons = $togtotalservedpersons * $tog;
     
       
   
        }


        
     
  

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="th3515">
    <meta name="author" content="@pablongbuhaymo">

    <title>Disaster Profile | City Disaster Risk Management</title>    
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


<!-- Update Barrangay Modal -->
<div class="modal fade" id="UpdateBarangayModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Disaster Profile Info</h4>
            </div>

            <!-- Update Barangay Form -->
                <div class="modal-body">
                        <div class = "col-lg-8">
                        <canvas id="myChart" width="400" height="400"></canvas>
                        </div>
                        <div class = "col-lg-4">
                        <h4> Overall Statistics </h4>
                        <h5><b> Casualties </b></h5>
                        <h6> Deaths: 25 </h6>
                        <h6> Deaths: 25 </h6>
                        <h6> Deaths: 25 </h6>
                        <hr>
                        <h5><b>Properties </b></h5>
                        <h6> Deaths: 25 </h6>
                        <h6> Deaths: 25 </h6>

                        </div>
                </div>
                    
                <!-- Submission -->
                <div class="modal-footer">

                </div>
            </form>
        </div>
    </div>
</div>



<body role = "document" id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

<?php include('library/html/navbar.php');
       include('library/html/loginmodal.php');
    ?>

    <!--Content starts here-->

    <div class="container fluid"> 
    
    <div class = "row no-pad">
        <div class="page-header">
            <div style="margin: auto; text-align: center;" class="pull-right">
                <div class="btn-group" role="group" aria-label="...">
                    <a href="#" class="btn btn-basic active">Forecast</a>  
                    <a href="typhoon.php" class="btn btn-basic ">Typhoon Profiles</a>
                    
                </div>
            </div>
            <h3>Forecast a Typhoon <small>in Iloilo City</small></h3>
         </div>

        <div class="col-lg-12">
        <div id="map" style="height: 70vh; width: 100%;" tabindex="0"> </div>
    </div>


      </div>


    </div>
  
<br>
<?php include('library/html/footer.php'); ?>

    <script src="js/1.11.3_jquery.min.js"></script>
    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/app/mylibrary.js"></script>
    <script src="js/app/loginscript.js"></script>
    <script src="js/jquery.easing.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
       integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
       crossorigin=""></script>
    <script src="js/leaflet-heat.js"></script>
    <script src="js/jquery.form.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script>


</body>

</html>
<script>

 $('.list-group a').click(function(e) {
        e.preventDefault()

        $that = $(this);
        var a = $(this).attr('seq');
        document.getElementById('demo').innerHTML = a;
        $that.parent().find('a').removeClass('active');
        $that.addClass('active');
    });




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

        
           
           
   var heat = L.heatLayer([
       
        <?php
            $sql ='SELECT 

               disaster_declare.LAT, disaster_declare.LNG, disaster_declare.RADIUS, disaster_typhoonprofile.DATESTART

                FROM disaster_declare
                INNER JOIN disaster_typhoonlist
                ON disaster_declare.ID = disaster_typhoonlist.DECLAREID
                INNER JOIN disaster_typhoonprofile
                ON disaster_typhoonlist.PROFILEID = disaster_typhoonprofile.ID
                WHERE disaster_typhoonprofile.DATESTART >= now() - INTERVAL 5 YEAR
                AND disaster_typhoonprofile.SIGNALNO = '. $selected_val.'
                ORDER BY disaster_typhoonprofile.DATESTART ASC';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);
            $totalcount = $count;
            while($row = $result->fetch_array())
            {
                $counter = $count / $totalcount;
                ?> ['<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>', '<?php echo $counter; ?>'],
                <?php
                $count--;  
            }    
            ?>

               ], {radius: 25, minOpacity: 0.15}).addTo(map);

    
   var infomain = L.control();

    infomain.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info');
        this.update();
        return this._div;
    };

    infomain.update = function (props) {
        this._div.innerHTML =    
        'Signal No. <?php echo $selected_val ?>'+
        '<br><text style = "font-size: 9px;">Data Forecast</text><hr><small class = "pull-right">Deaths: <?php echo(round($totaldeaths,0)); ?>'+
        '<br>Missing: <?php echo(round($totalmissing,0)); ?>'+
        '<br>Injury: <?php echo(round($totalinjured,0)); ?>'+
        '<br>Evacuated Family: <?php echo(round($totalservedfamily,0)); ?> '+
        '<br>Evacuated Persons: <?php echo(round($totalservedpersons,0)); ?> '+
        '<br>Cost of Assistance: ₱<?php echo(round($totalassistance,0)); ?> </small>';        };

    infomain.addTo(map);
  

 var info = L.control({position: 'bottomright'});

    info.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info');
        this.update();
        return this._div;
    };

    // info.update = function (props) {
    //     this._div.innerHTML = 
    //     '<form id="DisplayTyphoon" method="post" action="library/form/DisplayTyphoon.php">'+
    //     '<p>Signal no. <span id="demo"></p>'+
    //     '  <div class="input-group pull-right" style = "width: 200px;">'+
    //      '<input name = "rangevalue" type="range" min="1" max="5" value="1" class="slider" id="myRange">'+
    //      ' <div class="input-group-btn">'+
    //      '<button name="submit" class="btn btn-default" type="submit">Check</button>'+
    //      '</div>'+
    //      '</div>'+
    //      '</form>';
             
    // };


      info.update = function (props) {
        this._div.innerHTML = 
        '<form action="#" method="post">'+
        '<p>Signal no. <span id="demo"></p>'+
        ' <div class="input-group pull-right" style = "width: 200px;">'+
         '<input name = "rangevalue" type="range" min="1" max="5" value="<?php echo $selected_val ?>" class="slider" id="myRange">'+
         '<br> <p><span id="SIGNALNO"></span><button name="submit" class="btn btn-default pull-right" type="submit">Check</button></p>'+
         '</div>'+
         '</form>';
             
    };

//     window.onload = function(){ 
//       document.getElementById('DisplayTyphoon').onsubmit = function (e) {
//         e.preventDefault();

//         $(this).ajaxSubmit({
//             beforeSend:function()
//             {
                
//             },
//             uploadProgress:function(event,position,total,percentCompelete)
//             {

//             },
//             success:function(data)
//             {

//              alert(data);  
//             }
//         });
//     };
   
// };
    info.addTo(map);

  

    var slider = document.getElementById("myRange");
        var output = document.getElementById("demo");
        output.innerHTML = slider.value;

        slider.oninput = function() {
          output.innerHTML = this.value;
        }

    var slider = document.getElementById("myRange");
        var output = document.getElementById("demo");

        var display = document.getElementById("SIGNALNO");
        output.innerHTML = slider.value;


        slider.onchange = function() {
          output.innerHTML = this.value;
          if(this.value == 1)
          {
            display.innerHTML = '30-60 km/h';
          }
          else if(this.value == 2)
          {
            display.innerHTML = '61-120 km/h';
          }
          else if(this.value == 3)
          {
            display.innerHTML = '121-170 km/h';
          }
          else if(this.value == 4)
          {
            display.innerHTML = '171-220 km/h';
          }
          else if(this.value == 5)
          {
            display.innerHTML = 'more than 220 km/h';
          }
        }


</script>