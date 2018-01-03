<?php
    include('library/form/connection.php');
    include('library/function/functions.php');
    $db = new db();
    session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="th3515">
    <meta name="author" content="@pablongbuhaymo">

    <title>Heatmap | City Disaster Risk Management</title>    
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
<br><br>
   
              
                    <div id="map" style="height: 80vh; width: 100%;" tabindex="0"> </div>
               
    
<br>
<!--FOOTER HERE-->
<?php include('library/html/footer.php'); ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
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
<script type="text/javascript" src="city.js"></script>

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



                 var heat = L.heatLayer([
       
        <?php
            $sql ='SELECT 

               disaster_declare.LAT, disaster_declare.LNG, disaster_declare.RADIUS

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ACCEPTED = 1
                AND disaster_declare.ISVERIFIED = 1';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);
               $totalcount = $count;
                        while($row = $result->fetch_array())
            {
                ?> ['<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>', '10'],
                <?php
            }    
            ?>


               ], {radius: 25}).addTo(map);


 $(document).change(function(){
        switch($('#disasterSelectHTML option:selected').val()) {
            case "A":

    changeDisaster();        
     heat = L.heatLayer([
        <?php
            $sql ='SELECT 

               disaster_declare.LAT, disaster_declare.LNG, disaster_declare.RADIUS

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ACCEPTED = 1
                AND disaster_declare.ISVERIFIED = 1';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                ?> ['<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>', '10'],
                <?php
            }    
            ?>

               ], {radius: 25}).addTo(map);
            break;
       
            case "B":
            changeDisaster();        
        heat = L.heatLayer([
       
        <?php
            $sql ='SELECT 

               disaster_declare.LAT, disaster_declare.LNG, disaster_declare.RADIUS

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ACCEPTED = 1
                AND disaster_declare.ISVERIFIED = 1
                AND disaster_type.ID = 2';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                ?> ['<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>', '10'],
                <?php
            }    
            ?>

               ], {radius: 25}).addTo(map);            
        break;
            case "C":
            changeDisaster();        
        heat = L.heatLayer([
       
        <?php
            $sql ='SELECT 

               disaster_declare.LAT, disaster_declare.LNG, disaster_declare.RADIUS

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ACCEPTED = 1
                AND disaster_declare.ISVERIFIED = 1
                AND disaster_type.ID = 1';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                ?> ['<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>', '10'],
                <?php
            }    
            ?>

               ], {radius: 25}).addTo(map);            
        break;

           case "D":
            changeDisaster();        
        heat = L.heatLayer([
       
        <?php
            $sql ='SELECT 

               disaster_declare.LAT, disaster_declare.LNG, disaster_declare.RADIUS

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ACCEPTED = 1
                AND disaster_declare.ISVERIFIED = 1
                AND disaster_type.ID = 3';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                ?> ['<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>', '10'],
                <?php
            }    
            ?>

               ], {radius: 25}).addTo(map);            
        break;

           case "E":
            changeDisaster();        
        heat = L.heatLayer([
       
        <?php
            $sql ='SELECT 

               disaster_declare.LAT, disaster_declare.LNG, disaster_declare.RADIUS

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ACCEPTED = 1
                AND disaster_declare.ISVERIFIED = 1
                AND disaster_type.ID = 6';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                ?> ['<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>', '10'],
                <?php
            }    
            ?>

               ], {radius: 25}).addTo(map);            
        break;
           case "F":
            changeDisaster();        
        heat = L.heatLayer([
       
        <?php
            $sql ='SELECT 

               disaster_declare.LAT, disaster_declare.LNG, disaster_declare.RADIUS

                FROM disaster_declare
                INNER JOIN disaster_type
                ON disaster_declare.DISASTER = disaster_type.ID
                WHERE disaster_declare.ACCEPTED = 1
                AND disaster_declare.ISVERIFIED = 1
                AND disaster_type.ID = 5';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                ?> ['<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>', '10'],
                <?php
            }    
            ?>

               ], {radius: 25}).addTo(map);            
        break;

            default:

        }
    });



 var info = L.control();

    info.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info');
        this.update();
        return this._div;
    };

    info.update = function (props) {
        this._div.innerHTML = 
        '   <h4><strong>Choose a Hazard Map:</strong></h4>'+
       '<select class="form-control" id = "disasterSelectHTML" name = "disasterSelectHTML">'+
        '   <option value="A">Disaster Prone Areas</option>'+
        '   <option value="B">Typhoon</option>'+
        '   <option value="C">Fire</option>'+
        '   <option value="D">Flood </option>'+
        '   <option value="E">Storm Surge</option>'+
        '   <option value="F">Landslide</option>'+ 
        '   </select>';  
    };

    info.addTo(map);
  
    function changeDisaster()
    {
        map.removeLayer(heat);
    }

</script>

</body>
<html>