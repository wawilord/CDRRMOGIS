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

    <title>Heatmap | Disaster Risk Management</title>
    <link rel="icon" href="img/favicon.ico">

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/app.css" rel="stylesheet">

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

<!--Site Content-->
<div class="container"> 
<div class = "row">
    <div class="page-header">
        <div style="margin: auto; text-align: center;" class="pull-right">
            <div class="btn-group" role="group" aria-label="...">
                <a href="map.php" class="btn btn-secondary">On-Going Disasters</a>
                <a href="map-evac.php" class="btn btn-secondary">Evacuation Centers</a>
                <a href="map-history.php" class="btn btn-secondary ">Disaster History</a>
                <a href="map-analysis.php" class="btn btn-secondary active">Heatmap</a>
            </div>
        </div>
        <h3>Heatmap <small>of Iloilo City</small></h3>
    </div>
</div>

    <div class="col-lg-9">
        <br><br><br><br><br><br><br><br>
        <center><button role = "button" onclick="Click()">Run the Algorithm</button>
    </div>

    <div class = "col-lg-3">
        <div class = "panel panel-default">
        <div class = "panel-heading">
            <p class = "panel-title">Choose an Option </p>
            </div>
            <div class = "panel-body">
                <div class="list-group"> 
                  <a href="#" class="list-group-item active">Regression Algorithm</a>
                  <hr>
                  <a href="#" class="list-group-item">Population Density</a>
                  <a href="#" class="list-group-item">Projected Population Growth</a>
                  <a href="#" class="list-group-item">Properties</a>
                  <a href="#" class="list-group-item">Earthquake Zone</a>
                  <a href="#" class="list-group-item">Typhoon Vulnerable Areas</a>
                  <a href="#" class="list-group-item">Typhoon History Path Zones</a>
                  <a href="#" class="list-group-item">Storm-Surge Vulnerable Areas</a>
                  <a href="#" class="list-group-item">Flood Prone Areas</a>
                  <a href="#" class="list-group-item">Landslide Prone</a>
                  <a href="#" class="list-group-item">Fire Prone Areas</a>
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
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/app/mylibrary.js"></script>
    <script src="js/app/loginscript.js"></script>

</body>
</html>


<script>

function Click()
{
    window.alert('joke lang');
}


</script>