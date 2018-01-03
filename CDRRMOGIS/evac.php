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

    <title>Overview | City Disaster Risk Management</title>    
    <link rel="icon" href="img/favicon.ico">

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/app.css" rel="stylesheet">

   <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7/leaflet.css"/>

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

   <div class="container fluid">
   
    <div class="page-header">
        <div style="margin: auto; text-align: center;" class="pull-right">
            <div class="btn-group" role="group" aria-label="...">
                <a href="overview.php" class="btn btn-basic ">Barangays</a>
                <a href="#" class="btn btn-basic active">Evacuation Centers</a>   
            </div>
        </div>
        <h3>Evacuation Ceneters <small>in the City of Iloilo</small></h3>
        </div>

            <div class="row no-pad">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="map" style="height: 70vh; width: 100%;" tabindex="0"> </div>
                </div>

             
            </div>
        
    </div>
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
 <script src="http://cdn.leafletjs.com/leaflet-0.7/leaflet.js"></script>


<script>

    var evacList = [
       
        <?php
            $sql ='SELECT ID, EVACNAME, EVACADDRESS1, CAPACITY, LAT, LNG FROM evacuation_list';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                ?> ['<?php echo $row['ID']; ?>','<?php echo $row['EVACNAME']; ?>','<?php echo $row['EVACADDRESS1']; ?>','<?php echo $row['CAPACITY']; ?>','<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>'],
                <?php
            }    
            ?>

               ];


    
       
        var greenIcon = L.icon({
            iconUrl: 'img/offcial/map/evac_icon.png',

            iconSize:     [20, 40], // size of the icon
            iconAnchor:   [10, 30], // point of the icon which will correspond to marker's location
            popupAnchor:  [-3, -50] // point from which the popup should open relative to the iconAnchor
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

      
        for (var i = 0; i < evacList.length; i++) {
            marker = new L.marker([evacList[i][4],evacList[i][5]], {icon: greenIcon})
                .bindPopup('<center><strong>' + evacList[i][1] + '</strong><br>'+ evacList[i][2] + '<br> Capacity:' + evacList[i][3] + '</center>')
                 .on('click', function() {
                  centerLeafletMapOnMarker(map, this);
                  })
                .addTo(map);

        }
 
     function centerLeafletMapOnMarker(map, marker) {
      var latLngs = [ marker.getLatLng() ];
      var markerBounds = L.latLngBounds(latLngs);
      map.fitBounds(markerBounds);
    }
        function highlightFeature(e) {
    var layer = e.target;

   

    if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
        layer.bringToFront();
    }
}

    var info = L.control();

    info.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info');
        this.update();
        return this._div;
    };

    info.update = function (props) {
        this._div.innerHTML = '<h4>Evacuation Centers in the City of Iloilo</h4>' +  (props ?
            '<b>' + props.NAME_3 + '</b>'
            : 'Evacuation info is displayed upon click');
    };

    info.addTo(map);



</script>

</body>
<html>
