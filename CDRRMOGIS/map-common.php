
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
    <title>Disaster Risk Management</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
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
    <div class="page-header">
        <div style="margin: auto; text-align: center;" class="pull-right">
            <div class="btn-group" role="group" aria-label="...">
                <a href="map.php" class="btn btn-primary">On-Going Disasters</a>
                <a href="map-common.php" class="btn btn-primary active">Top Disasters</a>
				<a href="map-evac.php" class="btn btn-primary">Evacuation Centers</a>
                <a href="map-population.php" class="btn btn-primary">Population</a>
            </div>
        </div>
        <h1>Top Disasters<small> of Barangays in Iloilo City</small></h1>
    </div>
    <div class="col-lg-10">
        <div id="map" style="height: 70vh; width: 100%;" tabindex="0"> </div>
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
    </div>
    <div class="col-lg-12" style="max-height: 70vh; overflow-y: auto;">
        <hr />
        <h4>Top Disaster of Each Barangay: </h4>
        <div class="list-group" id="BrgyListHtml">

        </div>
    </div>
</div>

<!--FOOTER HERE-->
<?php include('library/html/footer.php'); ?>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/1.11.3_jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/app/mylibrary.js"></script>
<script src="js/app/messagealert.js"></script>
<script src="js/app/loginscript.js"></script>
<script src="js/jquery.form.min.js"></script>
<script>
    var BrgyList  = [];
    var map;
    var i;

    function highlight_poly(ii) {
        BrgyList[ii].Polygon.setMap(null);
        BrgyList[ii].Polygon.setMap(map);
    }

    function unhighlight_poly(ii) {
        BrgyList[ii].Polygon.setMap(null);
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
            array_push($brgylist, array($row['ID'], $row['NAME'], '', 'None', 'gray', 0, 'None', 0, 'None', 0));
        }

        foreach ($brgylist as $brgy) {;
        $sql = "SELECT LAT, LNG FROM barangay_coordinates WHERE BARANGAY = " . $brgy[0];
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while ($row = $result->fetch_assoc()) {
            $brgy[2] .= "{lat: " . $row['LAT'] . ", lng: " . $row['LNG'] . "},\n";
        }
        $brgy[2] = substr($brgy[2], 0, -2);

        $sql = "SELECT b.NAME AS BARANGAY, dt.NAME AS DISASTER, dt.COLOR AS COLOR, COUNT(dd.ID) AS COUNTER
				FROM (SELECT * FROM disaster_declare WHERE disaster_declare.BRGY = ".$brgy[0].") AS dd
				INNER JOIN barangay AS b ON b.ID = dd.BRGY
				INNER JOIN disaster_type AS dt ON dt.ID = dd.DISASTER
				GROUP BY b.NAME, dt.NAME
				ORDER BY COUNTER DESC
				LIMIT 3";
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
		$indexer = 0;
        while ($row = $result->fetch_assoc()) {
			if($indexer == 0) {
				$brgy[3] = $row['DISASTER'];
				$brgy[4] = $row['COLOR'];
				$brgy[5] = $row['COUNTER'];
			}
			if($indexer == 1) {
				$brgy[6] = $row['DISASTER'];
				$brgy[7] = $row['COUNTER'];
			}
			if($indexer == 2) {
				$brgy[8] = $row['DISASTER'];
				$brgy[9] = $row['COUNTER'];
			}
			$indexer++;
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
                Disaster: '<?php echo $brgy[3] ?>',
                Color: '<?php echo $brgy[4] ?>',
                Count: <?php echo $brgy[5] ?>,
				Disaster2: '<?php echo $brgy[6] ?>',
				Count2: <?php echo $brgy[7] ?>,
				Disaster3: '<?php echo $brgy[8] ?>',
				Count3: <?php echo $brgy[9] ?>
            }
        );
        <?php
        }
        ?>

        for(i = 0; i < BrgyList.length; i++)
        {
            var TempInfoWindowContent = '<p><h4><b> Brgy. ' + BrgyList[i].Name + ' </h4><small>Disaster Info:</small></b>' +
                '<br /><br /> - <b>Most Affected By:</b> ' + BrgyList[i].Disaster +
                '<br /> - <b>Times:</b> ' + BrgyList[i].Count +
				'<br /><br /> - <b>Second Most:</b> ' + BrgyList[i].Disaster2 +
                '<br /> - <b>Times:</b> ' + BrgyList[i].Count2 +
				'<br /><br /> - <b>Third Most:</b> ' + BrgyList[i].Disaster3 +
                '<br /> - <b>Times:</b> ' + BrgyList[i].Count3 +
                '</p>';
            BrgyList[i].Polygon = new google.maps.Polygon({
                paths: BrgyList[i].Coordinates,
                strokeColor: 'black',
                strokeOpacity: 0.8,
                strokeWeight: .5,
                fillColor: BrgyList[i].Color,
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
                '<a href="#" onclick="openInfoWindow(' + i + '); return false;" class="list-group-item" style="background: linear-gradient(to right, whitesmoke, whitesmoke, whitesmoke, white, white, ' + BrgyList[i].Color + ');"><span class="label label-warning pull-right" style="background-color: rgba(0,0,0,.5)">' + BrgyList[i].Disaster + '</span> Brgy. ' + BrgyList[i].Name + '</a>';
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
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete"
        async defer></script>
</body>
</html>