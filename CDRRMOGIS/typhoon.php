<?php

    include('library/form/connection.php');
    include('library/function/functions.php');
    $db = new db();
    session_start();

    $selected_val = 0;
    $profilename = '';

if(isset($_POST['submit'])){
$selected_val = $_POST['demo1'];  
 }

 $typhooname = '';

                $sql ='

                    SELECT disaster_typhoonprofile.NAME

                FROM disaster_typhoonprofile
                
                WHERE disaster_typhoonprofile.ID = '. $selected_val.'';

        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while($row = $result->fetch_assoc()){
            $typhooname = $row['NAME'];
        }

$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 10;

$total_sql = 'SELECT * FROM disaster_typhoonprofile WHERE NAME LIKE "%'.$search.'%"';
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

    <title>Typhoon Profile | City Disaster Risk Management</title>    
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
                <h4 class="modal-title">Typhoon Profile Info</h4>
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



<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

<?php include('library/html/navbar.php');
       include('library/html/loginmodal.php');
    ?>

    <!--Content starts here-->

    <div class="container fluid"> 
    
    <div class = "row no-pad">
        <div class="page-header">
            <div style="margin: auto; text-align: center;" class="pull-right">
                <div class="btn-group" role="group" aria-label="...">
                    <a href="forecast.php" class="btn btn-basic">Forecast</a> 
                    <a href="#" class="btn btn-basic active">Typhoon Profiles</a> 
                </div>
            </div>
            <h3>Typhoon Profiles <small>in Iloilo City </small></h3>
         </div>

    <div class="col-lg-10">
        <div id="map" style="height: 70vh; width: 100%;" tabindex="0"> </div>
    </div>

    <div class="col-lg-2" style="max-height: 70vh; overflow-y: auto; padding-left: 10px">
        <h4>Typhoon Profiles List</h4>
        <div class="list-group" id="disasterProfileListHTML">
        <?php 

            $sql ='SELECT 

               disaster_typhoonprofile.ID, disaster_typhoonprofile.NAME, disaster_typhoonprofile.SIGNALNO

                FROM disaster_typhoonprofile';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                ?> 

                <a href = "#" seq = "<?php echo $row['ID']; ?>" class = "list-group-item" ><span class="label label-info pull-right">SIGNAL NO. <?php echo $row['SIGNALNO']; ?></span><?php echo $row['NAME']; ?></a>
        <?php
            }    


        ?>

        </div>
         <form action="#" method="post">
         <select  class = "form-control hidden" name = "demo1" id = "demo1">
        <option id = "demo" ></option>
        </select>
        <button name="submit" class="btn btn-default" type="submit">Check Map</button>

        </form>

                   <!-- Pagination -->
    <ul class="pager">
        <li style="<?php echo $bottom_page; ?>">
            <a href="DisasterTypeManage.php?search=<?php echo $search; ?>&page=1" style=""><span class="glyphicon glyphicon-menu-left"></span>&nbsp 1</a>
        </li>
        <li class="<?php echo $disable_previous; ?>">
            <a href="DisasterTypeManage.php?search=<?php echo $search; ?>&page=<?php echo $page - 1; ?>" style="<?php echo $disable_previous2; ?>"><span class="glyphicon glyphicon-menu-left"></span>&nbsp Previous</a>
        </li>
        <li class="disabled">
            <span><h4 style="margin-top: 0.3rem; margin-bottom: 0.3rem;">Page <?php echo $page; ?></h4></span>
        </li>
        <li class="<?php echo $disable_next; ?>">
            <a href="DisasterTypeManage.php?search=<?php echo $search; ?>&page=<?php echo $page + 1; ?>" style="<?php echo $disable_next2; ?>">Next&nbsp <span class="glyphicon glyphicon-menu-right"></span></a>
        </li>
        <li style="<?php echo $top_page; ?>">
            <a href="DisasterTypeManage.php?search=<?php echo $search; ?>&page=<?php echo $total_page; ?>" style=""><?php echo $total_page; ?>&nbsp <span class="glyphicon glyphicon-menu-right"></span></a>
        </li>
    </ul>
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
   <script src="js/chart.bundle.min.js"></script>


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

function returntoForecast()
    {
        window.location.href  = "map-forecast.php";
    }

 




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
                WHERE disaster_typhoonprofile.ID = '. $selected_val.'';

            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);

            while($row = $result->fetch_array())
            {
                ?> ['<?php echo $row['LAT']; ?>','<?php echo $row['LNG']; ?>', '10'],
                <?php
            }    
            ?>

               ], {radius: 25}).addTo(map);


 var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
     type: 'doughnut',
        data: {
            datasets: [{
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
                label: 'Dataset 1'
            }],
            labels: [
                "Red",
                "Orange",
                "Yellow",
                "Green",
                "Blue"
            ]
        },
        options: {
            responsive: true
        }

 
});

 var info = L.control({position: 'topright'});

    info.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info');
        this.update();
        return this._div;
    };
    
info.update = function (props) {
        this._div.innerHTML = 
        'Typhoon <?php echo $typhooname; ?> hit areas';         
    };

    info.addTo(map);


</script>