<?php
session_start();
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 8;

$total_sql = 'SELECT 
barangay.ID, 
barangay.NAME,
district.NAME AS DISTRICT, 
city.NAME AS CITY, 
barangay_info.MEN AS MEN, 
barangay_info.WOMEN AS WOMEN
FROM (SELECT * FROM barangay WHERE barangay.NAME LIKE "%'.$search.'%") AS barangay
LEFT JOIN district ON barangay.DISTRICT = district.ID
LEFT JOIN city ON district.CITY = city.ID
LEFT JOIN barangay_info ON barangay.ID = barangay_info.BARANGAY
GROUP BY barangay.ID';

$total_result = $db->connection->query($total_sql);
$total_count = mysqli_num_rows($total_result);
$total_page = ceil($total_count/$limit);

$page = isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $total_page ? $_GET['page'] : '1';
$offset = ($page * $limit) - $limit;

if($page < 2) {
    $disable_previous = 'disabled';
    $disable_previous2 = 'pointer-events: none;';
    $bottom_page = 'display:none';
}
else {
    $disable_previous = '';
    $disable_previous2 = '';
    $bottom_page = '';
}
if($total_page < 2) {
    $top_page = 'display:none';
}
else {
    $top_page = '';
}
if($total_page == $page || $total_page < 1) {
    $disable_next = 'disabled';
    $disable_next2 = 'pointer-events: none;';
    $top_page = 'display:none';
}
else {
    $disable_next = '';
    $disable_next2 = '';
    $top_page = '';
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

    <title>Overview | City Disaster Risk Management</title>    
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

<?php 
include('library/html/navbar.php');
include('library/html/loginmodal.php');
    ?>

<!-- Barangay Modal View -->



 <!--Content starts here-->

<div class="container fluid">
   
    <div class="page-header">
        <h3>Add New Barangay </h3>
        </div>

            <div class="row no-pad">
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div id="map" style="height: 70vh; width: 100%;" tabindex="0"> </div>
                </div>
                    
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div id="AB_msgbox" tabindex="0"></div>

                    <form id="AddBarangayForm" method="post" action="library/form/AddBarangayFormNew.php">
               
                    <div class="input-group">
                        <span class="input-group-addon" id="sizing-addon1">Map ID: </span>
                    <input id="AddBarangayForm_MAPID" name = "MAPID" readonly type="text" class = "form-control" value="Please select a location" />
                     </div>
                    <br />
                            
               
                   <div class="input-group">
                        <span class="input-group-addon" id="sizing-addon1">Barangay Name: </span>
                        <input  id="AddBarangayForm_NAME" name="NAME"  class="form-control" />
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
                                <span class="input-group-addon" id="sizing-addon1">City: </span>
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

                            
                            <div class = "pull-right">
                            <button type="submit" id="AddBarangayForm_SUBMIT" data-loading-text="Adding Barangay..." class="btn btn-primary"><span class="glyphicon glyphicon-check"></span> &nbsp; Add new Barangay</button>
                            <button type = "button" class="btn btn-default" onclick="location.href='BarangayManagement.php'">Cancel</button>
                            </div>

                    </form>
                </div>
             
            </div>
            <small class = "pull-right">*All Demographic Data are sourced-out from the Barangay Database</small>
                            
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
<script src="js/jquery.form.min.js"></script>
<script src="js/app/messagealert.js"></script>
<script type="text/javascript" src="city.js"></script>

<script>

    var ABForm = { 
        form: document.getElementById('AddBarangayForm'),
        name: document.getElementById('AddBarangayForm_NAME'),
        district: document.getElementById('AddBarangayForm_DISTRICT'),
        MAPID: document.getElementById('AddBarangayFORM_MAPID'),
        submit : '#AddBarangayForm_SUBMIT',
        msgbox: 'AB_msgbox'
    };

        ABForm.form.onsubmit = function(e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(ABForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                $(ABForm.submit).button('reset');
                var server_message = data.trim();
                if(server_message == 'success')
                {
                    createmessage(1, 'You have successfully submitted the disaster.', ABForm.msgbox);
                    alert('Successfully Added Barangay');
                    window.location.href = 'BarangayManagement.php';
                   
                }
                else if(server_message == 'error')
                {
                    createmessagein(3, 'There is a problem with submitting your report.', ABForm.msgbox);
                }
                else
                {
                    createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', ABForm.msgbox);
                }
            }
        });
    };



</script>



<!-- MAP SCRIPT -->
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




     function getMapID(props)
     {
            document.getElementById('AddBarangayForm_MAPID').value = JSON.stringify(props.OBJECTID);
     }


    // get color depending on population density value
    function getColor(d) {
        return '#696969';
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


 
    function resetHighlight(e) {
        geojson.resetStyle(e.target);
    }

    function zoomToFeature(e) {
         var layer = e.target;
          layer.setStyle({
            weight: 5,
            color: '#666',
            dashArray: '',
            fillOpacity: 1
        });


        map.fitBounds(e.target.getBounds());

        getMapID(layer.feature.properties);
    }

    function onEachFeature(feature, layer) {
        
        layer.on({
            dblclick: zoomToFeature,
            click: resetHighlight
         });
    }

    geojson = L.geoJson(statesData, {
        style: style,
        onEachFeature: onEachFeature
    }).addTo(map);

    var info = L.control();

    info.onAdd = function (map) {
           var div = L.DomUtil.create('div', 'info ');
          
           div.innerHTML +=
                '<small> • Double click to highlight an area <br> • Click again to remove highlighted area</small>'
                ;
    return div;
    };

   
    info.addTo(map);


</script>

</body>
</html>