<?php

    include('library/form/connection.php');
    include('library/function/functions.php');
    $db = new db();
    session_start();

    $selected_val = 0;

if(isset($_POST['submit'])){
$selected_val = $_POST['rangevalue'];  

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

               disaster_declare.LAT, disaster_declare.LNG, disaster_declare.RADIUS

                FROM disaster_declare
                INNER JOIN disaster_typhoonlist
                ON disaster_declare.ID = disaster_typhoonlist.DECLAREID
                INNER JOIN disaster_typhoonprofile
                ON disaster_typhoonlist.PROFILEID = disaster_typhoonprofile.ID
                WHERE disaster_typhoonprofile.SIGNALNO = '. $selected_val.'';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                ?> ['<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>', '10'],
                <?php
            }    
            ?>

               ], {radius: 25}).addTo(map);

    
   var infomain = L.control();

    infomain.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info');
        this.update();
        return this._div;
    };

    infomain.update = function (props) {
        this._div.innerHTML =    
        'Areas at risk when hit by Signal No. <?php echo $selected_val ?>';    };

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
         ' <div class="input-group-btn">'+
         '<button name="submit" class="btn btn-default" type="submit">Check</button>'+
         '</div>'+
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

    function goToBarangay()
    {
        window.location.href  = "barangays.php";
    }

    var slider = document.getElementById("myRange");
        var output = document.getElementById("demo");
        output.innerHTML = slider.value;

        slider.oninput = function() {
          output.innerHTML = this.value;
        }




</script>