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


 <!--Content starts here-->

<div class="container fluid">
   
    <div class="page-header">
        <div style="margin: auto; text-align: center;" class="pull-right">
            <div class="btn-group" role="group" aria-label="...">
                <a href="#" class="btn btn-basic active">Barangays</a>
                <a href="evac.php" class="btn btn-basic">Evacuation Centers</a>   
            </div>
        </div>
        <h3>Map Overview <small>of Iloilo City</small></h3>
        </div>

            <div class="row no-pad">
               <div class="col-lg-12">
        <div id="map" style="height: 70vh; width: 100%;" tabindex="0"> </div>
    </div>
   
        <div class="list-group hidden" id="BrgyListHtml">

    </div>
 
     </div>
     <br/>
           <button class="btn btn-primary pull-right" onclick="goToBarangay()"><span class="glyphicon glyphicon-menu-right"></span> View Full Barangay Details</button>
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
  
</body>

</html>
<script>

 function goToBarangay()
    {
        window.location.href  = "barangays.php";
    }
 
      var BrgyList  = [];
    var map;
    var i;
    var default_poly_color = 'gray';

    function highlight_poly(ii) {
        BrgyList[ii].Polygon.setMap(null);
        BrgyList[ii].Polygon.fillColor = 'red';
        BrgyList[ii].Polygon.setMap(map);
    }

    function unhighlight_poly(ii) {
        BrgyList[ii].Polygon.setMap(null);
        BrgyList[ii].Polygon.fillColor = default_poly_color;
        BrgyList[ii].Polygon.setMap(map);
    }

    function openInfoWindow(indx) {

        document.getElementById('map').focus();
        for(i = 0; i < BrgyList.length; i++)
        {
            unhighlight_poly(i);
            BrgyList[i].InfoWindow.close();
        }
        map.setCenter(polycenter(BrgyList[indx].Coordinates));
        map.setZoom(16);
        BrgyList[indx].InfoWindow.open(map, BrgyList[indx].Marker);
        highlight_poly(indx);
    }

    function geocodeLatLng(geocoder,latlng) {
        geocoder.geocode({'location': latlng}, function(results, status) {
            document.getElementById('AddEvacuationForm_ADDRESS2').value = "Getting Location...";
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[1]) {

                } else {
                    alert("There is no address for this area.");
                }
            } else {
                if(status == "ZERO_RESULTS")
                {
                    alert("There is no address for this area.");
                }
            }
        });
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

        map.addListener('click', function(event) {
            //prompt('', event.latLng);
            for(i = 0; i < BrgyList.length; i++)
            {
                unhighlight_poly(i);
                BrgyList[i].InfoWindow.close();
            }
        });


        <?php
        $brgylist = array();
        $sql = "SELECT ID, NAME 
                FROM barangay";
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while($row = $result->fetch_assoc()){
            array_push($brgylist, array($row['ID'], $row['NAME'], '', 0, 0, 0, 0, 0, 0));
        }

        foreach ($brgylist as $brgy) {;
        $sql = "SELECT LAT, LNG FROM barangay_coordinates WHERE BARANGAY = " . $brgy[0];
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while ($row = $result->fetch_assoc()) {
            $brgy[2] .= "{lat: " . $row['LAT'] . ", lng: " . $row['LNG'] . "},\n";
        }
        $brgy[2] = substr($brgy[2], 0, -2);

        $sql = "SELECT MEN, WOMEN, MINORS, ADULTS, PWD FROM barangay_info WHERE BARANGAY = " . $brgy[0] . " ORDER BY DATEADDED DESC";
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        if ($row = $result->fetch_assoc()) {
            $brgy[3] = $row['MEN'] + $row['WOMEN'];
            $brgy[4] = $row['MEN'];
            $brgy[5] = $row['WOMEN'];
            $brgy[6] = $row['MINORS'];
            $brgy[7] = $row['ADULTS'];
            $brgy[8] = $row['PWD'];
        }

        ?>
        BrgyList.push(
            {
                ID: <?php echo $brgy[0]; ?>,
                Name: '<?php echo $brgy[1]; ?>',
                Coordinates: [<?php echo $brgy[2]; ?>],
                Polygon: '',
                Marker: '',
                InfoWindow: '',
                Population: <?php echo $brgy[3] ?>,
                Men: <?php echo $brgy[4] ?>,
                Women: <?php echo $brgy[5] ?>,
                Minors: <?php echo $brgy[6] ?>,
                Adults: <?php echo $brgy[7] ?>,
                Pwd: <?php echo $brgy[8] ?>
            }
        );
        <?php
        }
        ?>

        for(i = 0; i < BrgyList.length; i++)
        {
            var TempInfoWindowContent = '<p><b> Brgy. ' + BrgyList[i].Name + ' | <small>Population Info:</small></b>' +
                '<br /><br /> - <b>Total:</b> ' + BrgyList[i].Population +
                '<br /> - <b>Men:</b> ' + BrgyList[i].Men +
                '<br /> - <b>Women:</b> ' + BrgyList[i].Women +
                '<br /> - <b>Minor:</b> ' + BrgyList[i].Minors +
                '<br /> - <b>Adult:</b> ' + BrgyList[i].Adults +
                '<br /> - <b>PWD:</b> ' + BrgyList[i].Pwd +
                '</p>';
            BrgyList[i].Polygon = new google.maps.Polygon({
                paths: BrgyList[i].Coordinates,
                strokeColor: 'black',
                strokeOpacity: 0.8,
                strokeWeight: .5,
                fillColor: default_poly_color,
                fillOpacity: 0.35,
                index: i
            });
            BrgyList[i].Polygon.setMap(map);

            BrgyList[i].Marker = new google.maps.Marker({
                position: polycenter(BrgyList[i].Coordinates),
                map: map,
                title: BrgyList[i].Name,
                visible: false,
                index: i
            });

            BrgyList[i].InfoWindow = new google.maps.InfoWindow({
                content: TempInfoWindowContent
            });

            BrgyList[i].Polygon.addListener('click', function() {
                for(var d = 0; d < BrgyList.length; d++)
                {
                    unhighlight_poly(d);
                    BrgyList[d].InfoWindow.close();
                }
                BrgyList[this.index].InfoWindow.open(map, BrgyList[this.index].Marker);
                highlight_poly(this.index);
            });

            document.getElementById('BrgyListHtml').innerHTML += '' +
                '<a href="#" onclick="openInfoWindow(' + i + '); return false;" class="list-group-item"><span class="label label-info pull-right">' + BrgyList[i].Population + '</span> Brgy. ' + BrgyList[i].Name + '</a>';
        }
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

</script>

