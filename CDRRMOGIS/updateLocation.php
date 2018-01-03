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

    <title>Update Map | City Disaster Risk Management</title>    
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

<!-- Add Barangay Modal -->
<div class="modal fade" id="assignMap" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Enable a Barangay</h4>
            </div>
            
            <!-- Add Barangay Form -->
            <form id="AddBarangayForm" method="post" action="library/form/AddBarangayForm.php">
                <div class="modal-body">
                    <div id="AB_msgbox" tabindex="0"></div>
                    
                    <!-- MAP SOON HERE-->
                    
                    
                    <!-- Infomation -->
                    <div class="panel panel-default">
                        <div class="panel-heading">Information</div>
                        <div class="panel-body">
                            
                            <!-- NAME -->
                            <div class="input-group">
                                <span class="input-group-addon" id="sizing-addon1">Barangay: </span>
                                <select class="form-control" id="AddBarangayForm_NAME" name="NAME">
                                    <?php
                                    $barangay_sql = "SELECT * FROM barangay ORDER BY NAME";
                                    $barangay_result = $db->connection->query($barangay_sql);
                                    while($barangay_row = $barangay_result->fetch_assoc()) {
                                        ?>
                                        <option value="<?php echo $barangay_row["ID"]; ?>" id="add_district_<?php echo $district_row["ID"]; ?>"><?php echo $barangay_row["NAME"]; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <br />

                            
                            <!-- District -->
                            <div class="input-group">
                                <span class="input-group-addon" id="sizing-addon1">District: </span>
                                <select class="form-control" id="AddBarangayForm_DISTRICT" name="DISTRICT">
                                    <?php
                                    $district_sql = "SELECT * FROM district ORDER BY NAME";
                                    $district_result = $db->connection->query($district_sql);
                                    while($district_row = $district_result->fetch_assoc()) {
                                        ?>
                                        <option value="<?php echo $district_row["ID"]; ?>" id="add_district_<?php echo $district_row["ID"]; ?>"><?php echo $district_row["NAME"]; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <br />

                            <!-- City -->
                            <div class="input-group">
                                <span class="input-group-addon" id="sizing-addon1">District: </span>
                                <select class="form-control" id="AddBarangayForm_CITY" name="CITY">
                                    <?php
                                    $city_sql = "SELECT * FROM city ORDER BY NAME";
                                    $city_result = $db->connection->query($city_sql);
                                    while($city_row = $city_result->fetch_assoc()) {
                                        ?>
                                        <option value="<?php echo $city_row["ID"]; ?>" id="add_district_<?php echo $district_row["ID"]; ?>"><?php echo $city_row["NAME"]; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <br />
                            
                        </div>

                    </div>


                    <small> *Demographics are provided after enabling the barangay </small>
                    
                </div>
                
                <!-- Submission -->
                <div class="modal-footer">
                    <button type="submit" id="AddBarangayForm_SUBMIT" data-loading-text="Adding Barangay..." class="btn btn-primary">Add Barangay</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                
            </form>
            
        </div>
    </div>
</div>


<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

<?php include('library/html/navbar.php');
       include('library/html/loginmodal.php');
    ?>

   <div class="container">
    <div class="page-header">
        <button class="btn btn-basic pull-right" data-toggle="modal" data-target = "#AssignMap" >
             Update Map
        </button>
        <h3>Update Map <small> for Barangay Montinola</small></h3>
    </div>
     
                    <div id="map" style="height: 70vh; width: 100%;" tabindex="0"> </div>
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
<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
   integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
   crossorigin=""></script>
<script type="text/javascript" src="city.js"></script>

<script>

   
    var map = L.map('map').setView([10.719950067615137, 122.554175308317468], 13);

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
            '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.light'
    }).addTo(map);

    var geojson = L.geoJson(statesData).addTo(map);


function highlightFeature(e) {
    var layer = e.target;

    layer.setStyle({
        weight: 5,
        color: '#666',
        dashArray: '',
        fillOpacity: 0.7
    });

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
        this._div.innerHTML = '<h4>Update Location of Barangay</h4>' +  (props ?
            '<b>' + props.NAME_3 + '</b>'
            : 'Please Choose a Boundary');
    };

    info.addTo(map);


    // get color depending on population density value
   function getColor(d) {
    return d > 1000 ? '#800026' :
           d > 500  ? '#BD0026' :
           d > 200  ? '#E31A1C' :
           d > 100  ? '#FC4E2A' :
           d > 50   ? '#FD8D3C' :
           d > 20   ? '#FEB24C' :
           d > 10   ? '#FED976' :
                      'lightgrey';
  }

    function style(feature) {
        return {
            weight: 2,
            opacity: 1,
            color: 'white',
            dashArray: '3',
            fillOpacity: 0.8,
            fillColor: getColor(feature.properties.density)

        };
    }

    function highlightFeature(e) {
        var layer = e.target;

        layer.setStyle({
            weight: 5,
            color: '#666',
            dashArray: '',
            fillOpacity: 0.7
        });

        if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
            layer.bringToFront();
        }

        info.update(layer.feature.properties);
    }

    var geojson;

    function resetHighlight(e) {
        geojson.resetStyle(e.target);
        info.update();
    }

    function zoomToFeature(e) {
        map.fitBounds(e.target.getBounds());
    }

    function onEachFeature(feature, layer) {
        layer.on({
            mouseover: highlightFeature,
            mouseout: resetHighlight,
            click: zoomToFeature
        });
    }

    geojson = L.geoJson(statesData, {
        style: style,
        onEachFeature: onEachFeature
    }).addTo(map);

  


</script>

</body>
</html> 