

<!DOCTYPE html>
<?php
session_start();
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Evacuation Centers | Disaster Risk Management</title>
    <link rel="icon" href="img/favicon.ico">


    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="css/map.css" rel="stylesheet">
    <link href="css/jquery.datetimepicker.css" rel="stylesheet">
</head>
<body role="document">

<!--LOGIN MODAL HERE-->
<?php include('library/html/loginmodal.php'); ?>

<!--Nav Bar-->
<?php include('library/html/navbar.php'); ?>

<!--Site Content-->
<div class="container"> <!--Content starts here-->
    <div class = "row">
       <div class="page-header">
            <div style="margin: auto; text-align: center;" class="pull-right">
                <div class="btn-group" role="group" aria-label="...">
                    <a href="map.php" class="btn btn-secondary">On-Going Disasters</a>
                    <a href="map-evac.php" class="btn btn-secondary active">Evacuation Centers</a>
                    <a href="map-history.php" class="btn btn-secondary">Disaster History</a>
                    <a href="map-analysis.php" class="btn btn-secondary">Heatmap</a>
                </div>
            </div>
            <h3>Evacuation Centers <small>in Iloilo City</small></h3>
        </div>

        <div class="col-lg-9">
            <div id="map" style="height: 70vh; width: 100%;" tabindex="0"> </div>
        </div>
        <div class="col-lg-3" style="max-height: 70vh; overflow-y: auto;">
            <h4>List of Barangays with Their Total Number of Evacuation Centers: </h4>
            <div class="list-group" id="BrgyListHtml">
            </div>
        </div>

   </div>
</div>

<br>
<br>


<?php include('library/html/footer.php'); ?>

</body>
</html>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/1.11.3_jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/app/mylibrary.js"></script>
<script src="js/app/messagealert.js"></script>
<script src="js/app/loginscript.js"></script>
<script src="js/jquery.form.min.js"></script>
<script>
    var dir = document.URL.substr(0,document.URL.lastIndexOf('/'));
    var BrgyList  = [];
    var map;
    var mapIcons = {
        GreenEvacuationCenter: dir + '/img/offcial/map/evac_icon.png',
        YellowEvacuationCenter: dir + '/img/offcial/map/evac_icon_f.png',
        RedEvacuationCenter: dir + '/img/offcial/map/evac_icon_x.png'
    };
    var i;
    var mapInfoWindow = null;
    var hiddenMarker = null;
    var polygon_default_color = 'black';
    <?php
        $BrgyList = array();
        $sql = "SELECT * FROM barangay";
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while($row = $result->fetch_assoc()){
            $BrgyList[] = array(
                "id"=>$row['ID'],
                "name"=>$row['NAME'],
                "polygon"=>null,
                "path"=>array(),
                "evacs"=>array()
            );
        }

        $i = 0;
        foreach ($BrgyList as $brgy){
            $sql = "SELECT * FROM barangay_coordinates WHERE BARANGAY=" . $brgy["id"];
            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);
            while($row = $result->fetch_assoc()){
                $BrgyList[$i]['path'][] = array("lat"=>(double)$row["LAT"], "lng"=>(double)$row["LNG"]);
            }


            $sql = "SELECT * FROM evacuation_list WHERE BARANGAY=" . $brgy["id"];
            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);
            while($row = $result->fetch_assoc()){
                $BrgyList[$i]['evacs'][] = array(
                    "id"=>$row["ID"],
                    "name"=>$row["EVACNAME"],
                    "address1"=>$row["EVACADDRESS1"],
                    "address2"=>$row["EVACADDRESS2"],
                    "position"=>array("lat"=>(double)$row["LAT"], "lng"=>(double)$row["LNG"]),
                    "marker"=>null,
                    "capacity"=>$row["CAPACITY"]
                );
            }
            $i++;
        }
    ?>
    BrgyList = JSON.parse('<?php echo json_encode($BrgyList); ?>');

    function OpenInfoWindow(content, marker) {
        mapInfoWindow.setContent(content);
        mapInfoWindow.open(map, marker);
    }
    function ResetPolygonColors() {
        BrgyList.forEach(function (brgy) {
            brgy.polygon.setOptions({fillColor: polygon_default_color});
        });
    }
    function ShowEvacuationCenters(arg) {
        BrgyList.forEach(function (brgy) {
            brgy.evacs.forEach(function (evac) {
                evac.marker.setVisible(arg);
            });
        });
    }
    function HighlightPolygon(polygon) {
        mapInfoWindow.close();
        ShowEvacuationCenters(false);
        ResetPolygonColors();
        polygon.setOptions({fillColor: 'red'});
        if(polygon.brgy.evacs.length < 1){
            hiddenMarker.setPosition(polycenter(polygon.brgy.path));
            OpenInfoWindow(
                '<h4>Brgy. ' + polygon.brgy.name + '</h4>' +
                '<p>There are no available Evacuation Center in this area.</p>'
                ,hiddenMarker);
        }
        else{
            polygon.brgy.evacs.forEach(function (evac) {
                evac.marker.setVisible(true);
            });
        }
    }
    function initAutocomplete() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 10.7149629, lng: 122.5476471},
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles:[{
                featureType:"poi",
                elementType:"labels",
                stylers:[{
                    visibility:"off"
                }]
            }]
        });

        var geocoder = new google.maps.Geocoder;

        mapInfoWindow = new google.maps.InfoWindow({
            content: ''
        });
        hiddenMarker = new google.maps.Marker({
            position: map.getCenter(),
            map: map,
            visible: false
        });

        map.addListener('click', function(event) {
            ShowEvacuationCenters(true);
            ResetPolygonColors();
            mapInfoWindow.close();
        });

        BrgyList.forEach(function (brgy) {
            //Add Polygons
            brgy.polygon = new google.maps.Polygon({
                paths: brgy.path,
                strokeColor: 'black',
                strokeOpacity: 0.2,
                strokeWeight: 2,
                fillColor: polygon_default_color,
                fillOpacity: .2,
                brgy: brgy
            });
            brgy.polygon.addListener('click', function () {
                HighlightPolygon(this);
            });
            brgy.polygon.setMap(map);

            brgy.evacs.forEach(function (evac) {
                //Add Evacuation
                evac.marker = new google.maps.Marker({
                    position: evac.position,
                    map: map,
                    title: evac.name,
                    icon: mapIcons.GreenEvacuationCenter,
                    evac: evac,
                    brgy: brgy
                });
                evac.marker.addListener('click', function () {
                    OpenInfoWindow(
                        '<h4>' +
                        '   <a href="evacuationinfo.php?id=' + this.evac.id + '">' + this.evac.name + '</a>' +
                        '   <br />' +
                        '   <small>Evacuation Center</small>' +
                        '</h4>' +
                        '<p>Brgy. ' + this.brgy.name + '</p>' +
                        '<p>Address: ' + this.evac.address1 + '</p>' +
                        '<p>Capacity: ' + this.evac.capacity + '</p>'
                        , this);
                });
            });


            var link = document.createElement("A");
            link.href = "#";
            link.className="list-group-item";
            link.innerHTML =
                '<span class="label label-default pull-right">' + brgy.evacs.length + '</span>' +
                'Brgy. ' + brgy.name;
            link.onclick = function () {
                document.getElementById('map').focus();
                var MyBounds = new google.maps.LatLngBounds();
                var paths = brgy.polygon.getPaths();
                paths.forEach(function(path) {
                    var ar = path.getArray();
                    for(var i = 0, l = ar.length;i < l; i++) {
                        MyBounds.extend(ar[i]);
                    }
                });
                map.fitBounds(MyBounds);

                HighlightPolygon(brgy.polygon);
                return false;
            };
            document.getElementById('BrgyListHtml').appendChild(link);
        });

    }

    function polycenter(coords) {
        var llng = coords[0].lng;
        var hlng = coords[0].lng;
        var llat = coords[0].lat;
        var hlat = coords[0].lat;

        for(var i = 0; i < coords.length; i++)
        {
            if(coords[i].lat < llat)
            {
                llat = coords[i].lat;
            }

            if(coords[i].lat > hlat)
            {
                hlat = coords[i].lat;
            }

            if(coords[i].lng < llng)
            {
                llng = coords[i].lng;
            }

            if(coords[i].lng > hlng)
            {
                hlng = coords[i].lng;
            }
        }
        var f_lat;
        var f_lng;
        f_lat = (hlat + llat)/2;
        f_lng = (hlng + llng)/2;

        return {lat: f_lat, lng: f_lng};
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&key=AIzaSyAuX4dJw6AKx5rbomKRek679WKUACmI9eE"
        async defer></script>
