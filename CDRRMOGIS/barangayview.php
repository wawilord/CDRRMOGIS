<?php
    include('library/form/connection.php');
    include('library/function/functions.php');
    $db = new db();
    session_start();

    $sql = 'SELECT * FROM barangay WHERE ID =' . $_GET['id'];
    $result = $db->connection->query($sql);
    $info = $result->fetch_assoc();

    $evaclist = array();
    $sql = 'SELECT          *
            FROM            evacuation_list
            WHERE           BARANGAY = ' . $_GET['id'];
    $result = $db->connection->query($sql);
    while($row = $result->fetch_assoc()){
        $evaclist[] = $row;
    }

    $disasterhistory = array();
    $sql = 'SELECT 				disaster_declare.ID,
                                disaster_declare.STARTED,
                                disaster_type.NAME,
                                disaster_type.COLOR
                                
            FROM 				disaster_declare,
                                disaster_type
            
            WHERE				disaster_declare.DISASTER = disaster_type.ID
            AND					disaster_declare.ISVERIFIED = 1
            AND					disaster_declare.BRGY = ' . $_GET['id'] . '
            ORDER BY			disaster_declare.STARTED DESC';
    $result = $db->connection->query($sql);
    while($row = $result->fetch_assoc()){
        $disasterhistory[] = $row;
    }

    $brgyinfo_sql = 'SELECT * FROM barangay_info 
    WHERE BARANGAY = ' . $info['ID'] . ' 
    ORDER BY DATEADDED ASC';
    $brgyinfo_result = $db->connection->query($brgyinfo_sql);
    $brgyinfo_arr = array();
    while ($brgyinfo_row = $brgyinfo_result->fetch_assoc()){
        array_push($brgyinfo_arr, array('ID' => $brgyinfo_row['ID'],
                                        'DATEADDED' => $brgyinfo_row['DATEADDED'],
                                        'MEN' => $brgyinfo_row['MEN'],
                                        'WOMEN' => $brgyinfo_row['WOMEN'],
                                        'MINORS' => $brgyinfo_row['MINORS'],
                                        'ADULTS' => $brgyinfo_row['ADULTS'],
                                        'PWD' => $brgyinfo_row['PWD'],
                                        'T_HOUSES' => $brgyinfo_row['T_HOUSES'],
                                        'C_HOUSES' => $brgyinfo_row['C_HOUSES'],
                                        'L_HOUSES' => $brgyinfo_row['L_HOUSES'],
                                        'CL_HOUSES' => $brgyinfo_row['CL_HOUSES'],
                                        'AREA' => $brgyinfo_row['Area'],
                                        'ISFLOODPRONE' => $brgyinfo_row['isFloodProne']
                                        ));
    }
    $brgyinfo_json = json_encode($brgyinfo_arr);
?>
<!DOCTYPE html>
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
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"
    integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="
   crossorigin=""/>

  <style>
      .morris-hover{position:absolute;z-index:1000}.morris-hover.morris-default-style{border-radius:10px;padding:6px;color:#666;background:rgba(255,255,255,0.8);border:solid 2px rgba(230,230,230,0.8);font-family:sans-serif;font-size:12px;text-align:center}.morris-hover.morris-default-style .morris-hover-row-label{font-weight:bold;margin:0.25em 0}
      .morris-hover.morris-default-style .morris-hover-point{white-space:nowrap;margin:0.1em 0}
  </style>
</head>
<body role="document">

<!--LOGIN MODAL HERE-->
<?php include('library/html/loginmodal.php'); ?>
<!--NAVBAR HERE-->
<?php include('library/html/navbar.php'); ?>
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1>Barangay <small><?php echo $info['NAME']; ?></small>
        <span class = "pull-right">
            <button class="btn btn-basic" onclick="location.href='barangays.php'"><span class="glyphicon glyphicon-menu-left"></span> Return to Barangay</button>
        </span>
        </h1>

    </div>

    <div class="row">
    <div class = "col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">Basic Info</div>
        <div class="panel-body">
            <?php
                $sql = 'SELECT  city.NAME AS CITY, 
                                district.NAME AS DISTRICT
                        FROM    city,
                                district
                        WHERE   district.CITY = city.ID
                        AND     district.ID = ' . $info['DISTRICT'];
                $result = $db->connection->query($sql);
                $brgyinfo = $result->fetch_assoc();
            ?>
            <table class="table">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td><?php echo $info['NAME']; ?></td>
                    </tr>
                    <tr>
                        <th>District</th>
                        <td><?php echo $brgyinfo['DISTRICT']; ?></td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td><?php echo $brgyinfo['CITY']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>

        <div class = "col-lg-6">
        <div class="panel panel-default">
                <div class="panel-heading">Location</div>
                <div class="panel-body">
                           <div id="map" style="height: 40vh; width: 100%;" tabindex="0"> </div>
                </div>
        </div>
        </div>
        </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">Disaster History</div>
                <div class="panel-body">
                    <?php
                        if(sizeof($disasterhistory) > 0){
                            ?>
                            <div class="list-group">
                                <?php
                                foreach ($disasterhistory as $disaster){
                                    ?>
                                    <a href="disasterinfo.php?id=<?php echo $disaster['ID']; ?>" class="list-group-item"><?php echo $disaster['NAME']; ?> <span class="label label-info pull-right" style="background-color: <?php echo $disaster['COLOR']; ?>;"><?php echo converttoformaldatetimestring($disaster['STARTED']); ?></span></a>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                        else{
                            ?>
                            <p>No Available Disaster</p>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">Evacuation Centers</div>
                <div class="panel-body">
                    <?php
                        if(sizeof($evaclist) > 0){
                            ?>
                                <div class="list-group">
                                    <?php
                                        foreach ($evaclist as $evac){
                                            ?>
                                                <a href="evacuationinfo.php?id=<?php echo $evac['ID']; ?>" class="list-group-item"><?php echo $evac['EVACNAME']; ?></a>
                                            <?php
                                        }
                                    ?>
                                </div>
                            <?php
                        }
                        else{
                            ?>
                                <p>No Available Evacuation Center</p>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">More Info</div>
        <div class="panel-body">
            <?php
            $sql = 'SELECT * 
                    FROM barangay_info 
                    WHERE BARANGAY = ' . $info['ID'] . ' 
                    ORDER BY DATEADDED DESC
                    LIMIT 0, 1';
            $result = $db->connection->query($sql);
            $brgyinfo = $result->fetch_assoc();
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <p class="text-center">Population Growth</p>
                    <div id="population-line"></div>
                    <br />
                    <div class="page-header">
                        <h3 id="demo-date">Unknown</h3>
                    </div>
                </div>
                <div class="col-lg-6">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>Men</th>
                                <td id="demotable-men">0</td>
                            </tr>
                            <tr>
                                <th>Women</th>
                                <td id="demotable-women">0</td>
                            </tr>
                            <tr>
                                <th>Minors</th>
                                <td id="demotable-minors">0</td>
                            </tr>
                            <tr>
                                <th>Adults</th>
                                <td id="demotable-adults">0</td>
                            </tr>
                            <tr>
                                <th>Total Population</th>
                                <td id="demotable-pop">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>PWD</th>
                                <td id="demotable-pwd">0</td>
                            </tr>
                            <tr>
                                <th>Light Houses</th>
                                <td id="demotable-light">0</td>
                            </tr>
                            <tr>
                                <th>Concrete Houses</th>
                                <td id="demotable-concrete">0</td>
                            </tr>
                            <tr>
                                <th>Light with Concrete Houses</th>
                                <td id="demotable-both">0</td>
                            </tr>
                            <tr>
                                <th>Total Houses</th>
                                <td id="demotable-totalh">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--<div class="col-lg-6">
                    <div id="population-bar" style="height: 200px;"></div>
                </div>-->
            </div>
            <br />

            <div class="row">
                <!-- Men and Women -->
                <div class="col-lg-4">
                    <p class="text-center">Men and Women</p>
                    <div id="men-and-women" style="height:250px"></div>
                </div>
                <!-- Minors and Adults -->
                <div class="col-lg-4">
                    <p class="text-center">Minors and Adults</p>
                    <div id="minors-and-adults" style="height:250px"></div>
                </div>
                <!-- House Types -->
                <div class="col-lg-4">
                    <p class="text-center">House Types</p>
                    <div id="house-types" style="height:250px"></div>
                </div>
            </div>

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
<script src="js/app/bargraph.js"></script>
<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
   integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
   crossorigin=""></script>


<script>
    var dir = document.URL.substr(0,document.URL.lastIndexOf('/'));
    $(document).ready(function() {
        var brgyinfo_array = JSON.parse('<?php echo $brgyinfo_json; ?>');
        
        function getPopGrowthData(arr) {
            var result = [];

            arr.forEach(function (row) {
                result.push({
                    y: row.DATEADDED,
                    a: parseInt(row.MEN) + parseInt(row.WOMEN)
                });
            });

            return result;
        }

        function getDemoBarData(arr, index) {
            var row = arr[index];
            var result = [
                {label: "Men", value: row.MEN},
                {label: "Women", value: row.WOMEN},
                {label: "Minors", value: row.MINORS},
                {label: "Adults", value: row.ADULTS},
                {label: "PWD", value: row.PWD}
            ];
            return result;
        }
        
        function getMWData(arr, index) {
            var row = arr[index];
            var result = [
                {label: "Men", value: row.MEN, total: parseInt(row.MEN) + parseInt(row.WOMEN)},
                {label: "Women", value: row.WOMEN, total: parseInt(row.MEN) + parseInt(row.WOMEN)}
            ];
            return result;
        }

        function getAMData(arr, index) {
            var row = arr[index];
            var result = [
                {label: "Minors", value: row.MINORS, total: parseInt(row.MINORS) + parseInt(row.ADULTS)},
                {label: "Adults", value: row.ADULTS, total: parseInt(row.MINORS) + parseInt(row.ADULTS)}
            ];
            return result;
        }

        function getHouseData(arr, index) {
            var row = arr[index];
            var result = [
                {label: "Concrete", value: row.C_HOUSES, total: parseInt(row.C_HOUSES) + parseInt(row.L_HOUSES) + parseInt(row.CL_HOUSES)},
                {label: "Light", value: row.L_HOUSES, total: parseInt(row.C_HOUSES) + parseInt(row.L_HOUSES) + parseInt(row.CL_HOUSES)},
                {label: "Both", value: row.CL_HOUSES, total: parseInt(row.C_HOUSES) + parseInt(row.L_HOUSES) + parseInt(row.CL_HOUSES)}
            ];
            return result;
        }

        function setTableData(arr, index) {
            var row = arr[index];
            
            document.getElementById('demotable-men').innerHTML = row.MEN;
            document.getElementById('demotable-women').innerHTML = row.WOMEN;
            document.getElementById('demotable-minors').innerHTML = row.MINORS;
            document.getElementById('demotable-adults').innerHTML = row.ADULTS;
            document.getElementById('demotable-pwd').innerHTML = row.PWD;
            document.getElementById('demotable-pop').innerHTML = parseInt(row.MEN) + parseInt(row.WOMEN);
            document.getElementById('demotable-light').innerHTML = row.L_HOUSES;
            document.getElementById('demotable-concrete').innerHTML = row.C_HOUSES;
            document.getElementById('demotable-both').innerHTML = row.CL_HOUSES;
            document.getElementById('demotable-totalh').innerHTML = parseInt(row.L_HOUSES) + parseInt(row.C_HOUSES) + parseInt(row.CL_HOUSES);
        }

        //Morris charts snippet - js
        $.getScript(dir + '/js/app/raphael-min.js',function(){
            $.getScript(dir + '/js/app/morris.min.js',function(){
				
				// // Demographics (Bar) //
                // var demoBar = Morris.Bar({
                //     element: 'population-bar',
                //     data: getDemoBarData(brgyinfo_array, brgyinfo_array.length - 1),
                //     xkey: 'label',
                //     ykeys: ['value'],
                //     labels: ['Count']
                // });
				
				// Men and Women (Donut) //
				var mwDonut = Morris.Donut({
				  element: 'men-and-women',
				  data: getMWData(brgyinfo_array, brgyinfo_array.length - 1),
                  total: 1,
				  formatter: function (y, data) { return y + '(' + parseFloat((y/data.total) * 100).toFixed(1) + '%)'; }
				});
				
				// Minors and Adults (Donut) //
				var amDonut = Morris.Donut({
				  element: 'minors-and-adults',
				  data: getAMData(brgyinfo_array, brgyinfo_array.length - 1),
				  formatter: function (y, data) { return y + '(' + parseFloat((y/data.total) * 100).toFixed(1) + '%)'; }
				});

                // House Types (Donut) //
				var hsDonut = Morris.Donut({
				  element: 'house-types',
				  data: getHouseData(brgyinfo_array, brgyinfo_array.length - 1),
				  formatter: function (y, data) { return y + '(' + parseFloat((y/data.total) * 100).toFixed(1) + '%)'; }
				});

				// Population Growth (Line) //
                var popLine = Morris.Line({
                    element: 'population-line',
                    data: getPopGrowthData(brgyinfo_array),
                    xkey: 'y',
                    ykeys: ['a'],
                    labels: ['Population'],
					ymin: 'auto',
					dateFormat: function (x) { return new Date(x).toDateString(); },
                    // hoverCallback: function (index, options, content, row) {
                    //     return content;
                    // }
                    //lineColors: ['#0b62a4'],
                }).on('click', function(index, row) {
                    document.getElementById('demo-date').innerHTML = new Date(row.y).toDateString();
                    //demoBar.setData(getDemoBarData(brgyinfo_array, index));
                    mwDonut.setData(getMWData(brgyinfo_array, index));
                    amDonut.setData(getAMData(brgyinfo_array, index));
                    hsDonut.setData(getHouseData(brgyinfo_array, index));
                    setTableData(brgyinfo_array, index);
                });

                document.getElementById('demo-date').innerHTML = new Date(brgyinfo_array[brgyinfo_array.length - 1].DATEADDED).toDateString();
                setTableData(brgyinfo_array, brgyinfo_array.length - 1);
				
            });
        });
    });


   var map = L.map('map').setView([10.7199, 122.55417], 12);

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
            '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.light'
    }).addTo(map);

      
            barangay = L.polygon([
         <?php
            $sql ='SELECT LAT, LNG FROM barangay_coordinates WHERE BARANGAY =  ' . $_GET['id'];

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                ?> ['<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>'],
                <?php
            }    
            ?>


        ]).addTo(map);



</script>

</body>
</html>