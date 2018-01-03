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
    <meta name="author" content="">

    <title>On-going Disaster | City Disaster Risk Management</title>    
    <link rel="icon" href="img/favicon.ico">

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/leaflet.css" />
    <link href="css/app.css" rel="stylesheet">

    <!-- MAP CSS -->
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

 
<!-- Navbar Modal -->
  <?php include('library/html/navbar.php'); ?>

<!--  Login Modal -->
  <?php include('library/html/loginmodal.php'); ?>
  

<!--Site Content-->
<div class="container"> <!--Content starts here-->
<div class = "row">
    <div class="page-header">
        <div style="margin: auto; text-align: center;" class="pull-right">
            <div class="btn-group" role="group" aria-label="...">
                <a href="map.php" class="btn btn-secondary active">On-Going Disasters</a>
                <a href="map-evac.php" class="btn btn-secondary">Evacuation Centers</a>
                <a href="map-history.php" class="btn btn-secondary">Disaster History</a>
                <a href="map-analysis.php" class="btn btn-secondary">Heatmap</a>
            </div>
        </div>
        <h3>On-Going <small>Disasters in Iloilo City</small></h3>
    </div>

    <div class="col-lg-10">
        <div id="map" style= "height: 70vh; width: 100%;" tabindex="0" ></div>
    </div>

       <div class="col-lg-2">
        <hr />
        <?php
        $sql = 'SELECT * FROM disaster_type';
        $result = $db->connection->query($sql);
        while ($row = $result->fetch_assoc()){
            ?>
            <p><span class="glyphicon glyphicon-stop" style="color: <?php echo $row['COLOR']; ?>;"></span> <?php echo $row['NAME']; ?></p>
            <?php
        }
        ?>
        <div class="row">
            <div class="col-xs-2">
                <img src="img/offcial/map/evac_icon.png" style="width: 15px;" />
            </div>
            <div class="col-sm-10">
                <p>Available Evacuation</p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <img src="img/offcial/map/evac_icon.png" style="width: 15px;" />
            </div>
            <div class="col-sm-10">
                <p>(Bouncing) - Nearest Evacuation</p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <img src="img/offcial/map/evac_icon_f.png" style="width: 15px;" />
            </div>
            <div class="col-sm-10">
                <p>Available Evacuation but not nearest</p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">
                <img src="img/offcial/map/evac_icon_x.png" style="width: 15px;" />
            </div>
            <div class="col-sm-10">
                <p>Risky Evacuation</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-12" style="max-height: 70vh; overflow-y: auto;">
       
        <h4>List of Barangays with On-Going Disaster: </h4>
        <div class="list-group">
            <div class="list-group" id="BrgyListHtml">

            </div>
        </div>
    </div>
</div>
</div>


<?php include('library/html/footer.php'); ?>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Scrolling Nav JavaScript -->
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/app/mylibrary.js"></script>
    <script src="js/app/loginscript.js"></script>


   <script src="js/chart.bundle.min.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&key=AIzaSyAuX4dJw6AKx5rbomKRek679WKUACmI9eE"
            async defer></script>

</body>

</html>
<script>
    var dir = document.URL.substr(0,document.URL.lastIndexOf('/'));
    var BrgyList = [];
    var MyBounds = null;
    var mapInfoWindow = null;
    var DisasterInfoWindow = null;
    var EvacuationCenterInfoWindow = null;
    var directionsDisplay = null;
    var map = null;
    var mapIcons = {
        GreenEvacuationCenter: dir + '/img/offcial/map/evac_icon.png',
        YellowEvacuationCenter: dir + '/img/offcial/map/evac_icon_f.png',
        RedEvacuationCenter: dir + '/img/offcial/map/evac_icon_x.png'
    };
    var focusedCircle = null;
    function rad(x) {
        return x * Math.PI / 180;
    }
    function getDistance(p1, p2) {
        var R = 6378137; // Earth’s mean radius in meter
        var dLat = rad(p2.lat() - p1.lat());
        var dLong = rad(p2.lng() - p1.lng());
        var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(rad(p1.lat())) * Math.cos(rad(p2.lat())) *
            Math.sin(dLong / 2) * Math.sin(dLong / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var d = R * c;
        return d; // returns the distance in meter
    }
    function getPolygonBounds(polygon) {
        var paths = polygon.getPaths();
        var bounds = new google.maps.LatLngBounds();
        paths.forEach(function(path) {
            var ar = path.getArray();
            for(var i = 0, l = ar.length;i < l; i++) {
                bounds.extend(ar[i]);
            }
        });
        return bounds;
    }
    function OpenInfoWindowFromDisasterCircle(circle) {
        ShowDisasters(false);
        ResetEvacuationCentersIcon();
        focusedCircle = circle;
        ShowEvacuationCenters(true);
        EvacuationCenterInfoWindow.close();
        var DisasterData = circle.DisasterData;
        var BrgyData = circle.BrgyData;
        var NearestEvacuationName = 'None';
        var SortedEvacuationCenters = [];
        DisasterData.DisasterCircle.setVisible(true);
        DisasterData.DisasterDot.setVisible(true);
        getAvailableEvacuationCenters().forEach(function (EvacuationCenter) {
            SortedEvacuationCenters.push({distance: getDistance(EvacuationCenter.EvacuationMarker.getPosition(), DisasterData.DisasterMarker.getPosition()), info: EvacuationCenter});
        });
        SortedEvacuationCenters.sort(function(a, b){
            return a.distance-b.distance;
        });
        var near = true;
        SortedEvacuationCenters.forEach(function (Evac) {
            if(near && (Evac.distance <= 500)){
                near = false;
                Evac.info.EvacuationMarker.setIcon(mapIcons.GreenEvacuationCenter);
                Evac.info.EvacuationMarker.setAnimation(google.maps.Animation.BOUNCE);
                NearestEvacuationName = Evac.info.EvacuationName;
            }else{
                Evac.info.EvacuationMarker.setIcon(mapIcons.YellowEvacuationCenter);
            }
        });
        DisasterInfoWindow.setContent(
            '<h4>' +
            '   <a href="disasterinfo.php?id=' + DisasterData.DisasterID + '">' + DisasterData.DisasterTypeName + '</a>' +
            '   <br />' +
            '   <small>Disaster</small>' +
            '   <br />' +
            '   <small>Started: ' + DisasterData.TimeStarted + '</small>' +
            '</h4>' +
            '<p>Brgy. ' + BrgyData.BrgyName + '</p>' +
            '<p>Nearest Evacuation Center: <b>' + NearestEvacuationName + '</b></p>'
        );
        DisasterInfoWindow.open(map, DisasterData.DisasterMarker);
    }
    function OpenInfoWindowFromEvacuationMarker(marker) {
        var EvacuationCenterData = marker.EvacuationCenterData;
        var BrgyData = marker.BrgyData;
        EvacuationCenterInfoWindow.setContent(
            '<h4>' +
            '   <a href="evacuationinfo.php?id=' + EvacuationCenterData.EvacuationID +'">' + EvacuationCenterData.EvacuationName + '</a>' +
            '   <br />' +
            '   <small>Evacuation Center</small>' +
            '</h4>' +
            '<p>Brgy. ' + BrgyData.BrgyName + '</p>' +
            '<p>Address: ' + EvacuationCenterData.EvacuationAddress + '</p>'
        );
        EvacuationCenterInfoWindow.open(map, marker);
        displayDirection(focusedCircle.getCenter(), marker.getPosition());
    }
    function isInsideOfADisaster(point) {
        var inside = false;
        BrgyList.forEach(function (Brgy) {
            Brgy.BrgyDisasters.forEach(function (Disaster) {
                if (Disaster.DisasterCircle.getBounds().contains(point))
                    inside = true;
            });
        });
        return inside;
    }
    function ResetEvacuationCentersIcon() {
        clearDirection();
        BrgyList.forEach(function (Brgy) {
            Brgy.EvacuationCenters.forEach(function (EvacuationCenter) {
                EvacuationCenter.EvacuationMarker.setAnimation(null);
                if(isInsideOfADisaster(EvacuationCenter.EvacuationPosition))
                    EvacuationCenter.EvacuationMarker.setIcon(mapIcons.RedEvacuationCenter);
                else
                    EvacuationCenter.EvacuationMarker.setIcon(mapIcons.GreenEvacuationCenter);
            });
        });
    }
    function getAvailableEvacuationCenters() {
        var AvailableList = [];
        BrgyList.forEach(function (Brgy) {
            Brgy.EvacuationCenters.forEach(function (EvacuationCenter) {
                if(!isInsideOfADisaster(EvacuationCenter.EvacuationPosition))
                    AvailableList.push(EvacuationCenter);
            });
        });
        return AvailableList;
    }
    function clearDirection() {
        try{
            directionsDisplay.setMap(null);
        }catch(err){}
    }
    function displayDirection(pointA, pointB) {
        clearDirection();
        var directionsService = new google.maps.DirectionsService;
        directionsDisplay = new google.maps.DirectionsRenderer({
            map: map,
            markerOptions: {
                visible: false
            },
            preserveViewport: true
        });
        directionsService.route({
            origin: pointA,
            destination: pointB,
            travelMode: google.maps.TravelMode.WALKING
        }, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                EvacuationCenterInfoWindow.setContent(EvacuationCenterInfoWindow.getContent() + '<p>Distance: ' + response.routes[0].legs[0].distance.text + ' | ' + response.routes[0].legs[0].distance.value + ' meters</p>');
            } else {
                alert('Directions request failed due to ' + status);
            }
        });
    }
    function focusBrgy(Brgy) {
        ShowDisasters(true);
        ShowEvacuationCenters(false);
        DisasterInfoWindow.close();
        EvacuationCenterInfoWindow.close();
        ResetEvacuationCentersIcon();
        map.fitBounds(getPolygonBounds(Brgy.BrgyPolygon));
    }
    function ShowEvacuationCenters(arg) {
        BrgyList.forEach(function (Brgy) {
            Brgy.EvacuationCenters.forEach(function (EvacuationCenter) {
                EvacuationCenter.EvacuationMarker.setVisible(arg);
            });
        });
    }
    function ShowDisasters(arg) {
        BrgyList.forEach(function (Brgy) {
            Brgy.BrgyDisasters.forEach(function (Disaster) {
                Disaster.DisasterCircle.setVisible(arg);
                Disaster.DisasterDot.setVisible(arg);
            });
        });
    }
    

    <?php
        function getDummyBrgy(){
            return array(
                "BrgyID"=>null,
                "BrgyName"=>null,
                "BrgyPolygon"=>null,
                "BrgyPath"=>array(),
                "BrgyDisasters"=>array(),
                "EvacuationCenters"=>array()
            );
        }
        function getDummyPath(){
            return array(
                "lat"=>null,
                "lng"=>null
            );
        }
        function getDummyDisaster(){
            return array(
                "DisasterID"=>null,
                "DisasterNickname"=>null,
                "TimeStarted"=>null,
                "DisasterCenter"=>null,
                "DisasterRadius"=>null,
                "DisasterTypeID"=>null,
                "DisasterTypeName"=>null,
                "DisasterTypeColor"=>null,
                "DisasterCircle"=>null,
                "DisasterMarker"=>null
            );
        }
        function getDummyEvacuation(){
            return array(
                "EvacuationID"=>null,
                "EvacuationName"=>null,
                "EvacuationPosition"=>null,
                "EvacuationAddress"=>null,
                "EvacuationMarker"=>null

            );
        }
        $BrgyList = array();

        $sql = "SELECT * FROM barangay";
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while($row = $result->fetch_assoc()){
            $dummy = getDummyBrgy();
            $dummy["BrgyID"] = (int)$row['ID'];
            $dummy["BrgyName"] = $row['NAME'];
            $BrgyList[] = $dummy;
        }

        $i = 0;
        foreach ($BrgyList as $Brgy){
            $sql = "SELECT * FROM barangay_coordinates WHERE BARANGAY=" . $Brgy['BrgyID'];
            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);
            while($row = $result->fetch_assoc()){
                $dummypath = getDummyPath();
                $dummypath['lat'] = (double)$row['LAT'];
                $dummypath['lng'] = (double)$row['LNG'];
                $BrgyList[$i]['BrgyPath'][] = $dummypath;
            }
            $sql = "SELECT      disaster_declare.*,
                                disaster_type.ID AS TYPEID,
                                disaster_type.NAME AS TYPENAME,
                                disaster_type.COLOR
                    FROM        disaster_declare,
                                disaster_type
                    WHERE       disaster_type.ID = disaster_declare.DISASTER
                    AND         disaster_declare.ENDED IS NULL
                    AND         BRGY = " . $Brgy['BrgyID'];
            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);
            while($row = $result->fetch_assoc()){
                $dummydisaster = getDummyDisaster();
                $dummypath = getDummyPath();
                $dummypath['lat'] = (double)$row['LAT'];
                $dummypath['lng'] = (double)$row['LNG'];
                $dummydisaster['DisasterID'] = (int)$row['ID'];
                $dummydisaster['DisasterNickname'] = $row['NICKNAME'];
                $dummydisaster['DisasterCenter'] = $dummypath;
                $dummydisaster['DisasterRadius'] = (double)$row['RADIUS'];
                $dummydisaster['DisasterTypeID'] = (int)$row['TYPEID'];
                $dummydisaster['DisasterTypeName'] = $row['TYPENAME'];
                $dummydisaster['DisasterTypeColor'] = $row['COLOR'];
                $dummydisaster['TimeStarted'] = date('M. d, o | g:i a', strtotime($row['STARTED']));
                $BrgyList[$i]['BrgyDisasters'][] = $dummydisaster;
            }
            $sql = "SELECT * FROM evacuation_list WHERE BARANGAY = " . $Brgy['BrgyID'];
            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);
            while($row = $result->fetch_assoc()){
                $dummyevacuation = getDummyEvacuation();
                $dummypath = getDummyPath();
                $dummypath['lat'] = (double)$row['LAT'];
                $dummypath['lng'] = (double)$row['LNG'];
                $dummyevacuation['EvacuationID'] = $row['ID'];
                $dummyevacuation['EvacuationName'] = $row['EVACNAME'];
                $dummyevacuation['EvacuationPosition'] = $dummypath;
                $dummyevacuation['EvacuationAddress'] = $row['EVACADDRESS1'];
                $BrgyList[$i]['EvacuationCenters'][] = $dummyevacuation;
            }
            $i++;
        }
    ?>
    //Data Initialization
    BrgyList = JSON.parse('<?php echo json_encode($BrgyList); ?>');
    function initAutocomplete() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 10.7149629, lng: 122.5476471},
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [{"featureType": "poi", "stylers": [{ "visibility": "off" }]}] //Remove Labels
        });
        //_________________________________________________________
        //Set InfoWindow
        mapInfoWindow = new google.maps.InfoWindow({
            content: ''
        });
        DisasterInfoWindow = new google.maps.InfoWindow({
            content: ''
        });
        EvacuationCenterInfoWindow = new google.maps.InfoWindow({
            content: ''
        });

        //Set Map Click Listener
        map.addListener('click', function (event) {
            ShowDisasters(true);
            mapInfoWindow.close();
            DisasterInfoWindow.close();
            EvacuationCenterInfoWindow.close();
            ResetEvacuationCentersIcon();
            ShowEvacuationCenters(false);
            //prompt('', event.latLng);
        });

        //set MyBounds
        MyBounds = new google.maps.LatLngBounds();

        //Scan barangays
        BrgyList.forEach(function (Brgy, Brgyindex) {
            var htmltext = '' +
                '<a href="#" onclick="focusBrgy(BrgyList[' + Brgyindex + ']); return false;" class="list-group-item">' +
                '   <h4 class="list-group-item-heading">Brgy. ' + Brgy.BrgyName + '</h4>' +
                '   <div class="list-group-item-text">' +
                '       <ul>';
            //Draw polygon for the baranagay
            Brgy.BrgyPolygon = new google.maps.Polygon({
                paths: Brgy.BrgyPath,
                strokeColor: 'black',
                strokeOpacity: 0.2,
                strokeWeight: 2,
                fillColor: 'black',
                fillOpacity: .2,
                clickable: false
            });
            Brgy.BrgyPolygon.setMap(map);

            //Add Polygon to MyBounds
            if(Brgy.BrgyDisasters.length > 0){
                var paths = Brgy.BrgyPolygon.getPaths();
                paths.forEach(function(path) {
                    var ar = path.getArray();
                    for(var i = 0, l = ar.length;i < l; i++) {
                        MyBounds.extend(ar[i]);
                    }
                });

                //Set map bounds
                map.fitBounds(MyBounds);
            }

            //Scan Disasters
            Brgy.BrgyDisasters.forEach(function (Disaster, index) {
                htmltext += '<li>' + Disaster.DisasterTypeName + '</li>';
                //Set Marker
                Disaster.DisasterMarker = new google.maps.Marker({
                    position: Disaster.DisasterCenter,
                    map: map,
                    title: Disaster.DisasterTypeName,
                    visible: false
                });

                //Set Circle
                Disaster.DisasterCircle = new google.maps.Circle({
                    strokeColor: Disaster.DisasterTypeColor,
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: Disaster.DisasterTypeColor,
                    fillOpacity: 0.35,
                    map: map,
                    center: Disaster.DisasterCenter,
                    radius: Disaster.DisasterRadius,
                    BrgyData: Brgy,
                    DisasterData: Disaster
                });
                //Make a dot circle
                Disaster.DisasterDot = new google.maps.Circle({
                    strokeColor: Disaster.DisasterTypeColor,
                    strokeOpacity: 1,
                    strokeWeight: 5,
                    fillColor: 'white',
                    fillOpacity: 1,
                    map: map,
                    center: Disaster.DisasterCenter,
                    radius: 0,
                    BrgyData: Brgy,
                    DisasterData: Disaster
                });
                //Set Circle Click Listener
                Disaster.DisasterCircle.addListener('click', function () {
                    OpenInfoWindowFromDisasterCircle(this);
                });
                //Set Dot Click Listener
                Disaster.DisasterDot.addListener('click', function () {
                    OpenInfoWindowFromDisasterCircle(this);
                });
            });

            //Scan Evacuation Centers
            Brgy.EvacuationCenters.forEach(function (EvacuationCenter) {
                EvacuationCenter.EvacuationMarker = new google.maps.Marker({
                    position: EvacuationCenter.EvacuationPosition,
                    map: map,
                    title: EvacuationCenter.EvacuationName,
                    icon: mapIcons.GreenEvacuationCenter,
                    BrgyData: Brgy,
                    EvacuationCenterData: EvacuationCenter
                });
                //Set Marker Click Listener
                EvacuationCenter.EvacuationMarker.addListener('click', function () {
                    OpenInfoWindowFromEvacuationMarker(this);
                });

            });

            htmltext += '       </ul>' +
                '   </div>' +
                '</a>';
            if(Brgy.BrgyDisasters.length > 0){
                document.getElementById('BrgyListHtml').innerHTML += htmltext;
            }
        });

        ResetEvacuationCentersIcon();
        ShowEvacuationCenters(false);
        //_________________________________________________________
    }

</script>

