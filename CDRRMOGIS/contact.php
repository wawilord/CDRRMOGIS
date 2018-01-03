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
    <meta name="description" content="CPU CCS Thesis">
    <meta name="author" content="">

    <title>Contact Section | City Disaster Risk Management</title>    
    <link rel="icon" href="img/favicon.ico">


    <link rel="stylesheet" href="css/app.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">    
    <link rel="stylesheet" href="css/app.css">

     <!-- MAP CSS -->
     <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"
       integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="
       crossorigin=""/>

</head>

<!-- Navbar Modal -->
  <?php 
  include('library/html/navbar.php'); ?>

<!--  Login Modal -->
  <?php include('library/html/loginmodal.php'); ?>


<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

   
   <div class = "container">
    <section class="light-section" id = "about1">
    <div class = "row">

        <div class="col-lg-12 col-lg-md-12 col-sm-12 col-xs-12">
        <p class="section-contact-header text-center">Contact Directories</p> 

        <div class="col-lg-4 col-lg-md-6 col-sm-12 col-xs-12">
                <center><a href="http://www.iloilocity.gov.ph/iloilocity2016/index.php"><img border="0" class="img-responsive" alt="City of Iloilo" src="img/iloiloseal.jpeg" width="100" height="100"></a></center>
                <h3 class="section-contact-subheader">National Disaster Risk Reduction and Management Office</h3> 
                <p class = "section-contact-subtext"> <strong>Website</strong>: ndrrmc.gov.ph </p>
                <p class = "section-contact-subtext"> <strong>Twitter</strong>: @NDRRMC_Open </p>
                <p class = "section-contact-subtext"> <strong>Facebook</strong>: http://www.facebook.com/NDRRMC </p>
                <p class = "section-contact-subtext"> <strong>Hotlines</strong>: (+632) 911-1406 </p>               
            </div>

        <div class="col-lg-4 col-lg-md-6 col-sm-12 col-xs-12">
                <center><a href="https://web.facebook.com/IloiloCityDRRMO/?_rdc=1&amp;_rdr"><img border="0" class="img-responsive" alt="Iloilo Cdrrmo" src="img/cdrrmoseal.png" width="100" height="100"></a></center>
                <h3 class="section-contact-subheader">National Disaster Risk Reduction and Management Office</h3> 
                <p class = "section-contact-subtext"> <strong>Website</strong>: ndrrmc.gov.ph </p>
                <p class = "section-contact-subtext"> <strong>Twitter</strong>: @NDRRMC_Open </p>
                <p class = "section-contact-subtext"> <strong>Facebook</strong>: http://www.facebook.com/NDRRMC </p>
                <p class = "section-contact-subtext"> <strong>Hotlines</strong>: (+632) 911-1406 </p>               
            </div>
        <div class="col-lg-4 col-lg-md-6 col-sm-12 col-xs-12">
                <center><a href="https://web.facebook.com/IloiloCityDRRMO/?_rdc=1&amp;_rdr"><img border="0" class="img-responsive" alt="Iloilo Cdrrmo" src="img/icer.png" width="100" height="100"></a></center>
                <h3 class="section-contact-subheader">National Disaster Risk Reduction and Management Office</h3> 
                <p class = "section-contact-subtext"> <strong>Website</strong>: ndrrmc.gov.ph </p>
                <p class = "section-contact-subtext"> <strong>Twitter</strong>: @NDRRMC_Open </p>
                <p class = "section-contact-subtext"> <strong>Facebook</strong>: http://www.facebook.com/NDRRMC </p>
                <p class = "section-contact-subtext"> <strong>Hotlines</strong>: (+632) 911-1406 </p> 

                <br /> <br />              
            </div>

        <div class="col-lg-4 col-lg-md-6 col-sm-12 col-xs-12">
                <center><a href="http://www.iloilocity.gov.ph/iloilocity2016/index.php"><img border="0" class="img-responsive" alt="City of Iloilo" src="img/iloiloseal.jpeg" width="100" height="100"></a></center>
                <h3 class="section-contact-subheader">National Disaster Risk Reduction and Management Office</h3> 
                <p class = "section-contact-subtext"> <strong>Website</strong>: ndrrmc.gov.ph </p>
                <p class = "section-contact-subtext"> <strong>Twitter</strong>: @NDRRMC_Open </p>
                <p class = "section-contact-subtext"> <strong>Facebook</strong>: http://www.facebook.com/NDRRMC </p>
                <p class = "section-contact-subtext"> <strong>Hotlines</strong>: (+632) 911-1406 </p>               
            </div>

        <div class="col-lg-4 col-lg-md-6 col-sm-12 col-xs-12">
                <center><a href="http://www.iloilocity.gov.ph/iloilocity2016/index.php"><img border="0" class="img-responsive" alt="City of Iloilo" src="img/iloiloseal.jpeg" width="100" height="100"></a></center>
                <h3 class="section-contact-subheader">National Disaster Risk Reduction and Management Office</h3> 
                <p class = "section-contact-subtext"> <strong>Website</strong>: ndrrmc.gov.ph </p>
                <p class = "section-contact-subtext"> <strong>Twitter</strong>: @NDRRMC_Open </p>
                <p class = "section-contact-subtext"> <strong>Facebook</strong>: http://www.facebook.com/NDRRMC </p>
                <p class = "section-contact-subtext"> <strong>Hotlines</strong>: (+632) 911-1406 </p>               
            </div>

        <div class="col-lg-4 col-lg-md-6 col-sm-12 col-xs-12">
                <center><a href="http://www.iloilocity.gov.ph/iloilocity2016/index.php"><img border="0" class="img-responsive" alt="City of Iloilo" src="img/iloiloseal.jpeg" width="100" height="100"></a></center>
                <h3 class="section-contact-subheader">National Disaster Risk Reduction and Management Office</h3> 
                <p class = "section-contact-subtext"> <strong>Website</strong>: ndrrmc.gov.ph </p>
                <p class = "section-contact-subtext"> <strong>Twitter</strong>: @NDRRMC_Open </p>
                <p class = "section-contact-subtext"> <strong>Facebook</strong>: http://www.facebook.com/NDRRMC </p>
                <p class = "section-contact-subtext"> <strong>Hotlines</strong>: (+632) 911-1406 </p>               
            </div>            
    </div>
    </section>

    <br />

   </div>

<!-- Footer Section -->
<?php include('library/html/footer.php'); ?>

<!-- jQuery -->
    <script src="js/jquery.js"></script>
    <script src="js/jquery.easing.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/1.11.3_jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/app/mylibrary.js"></script>
    <script src="js/app/messagealert.js"></script>
    <script src="js/app/loginscript.js"></script>
     <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
   integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
   crossorigin=""></script>

</body>

</html>

<script type="text/javascript">
     

     var mymap = L.map('mapid').setView([10.69306, 482.57259], 17);

     var marker = L.marker([10.69306, 482.57259]).addTo(mymap);

     marker.bindPopup("<b>City Disaster Risk Reduction Management Office</b><br><center>Freedom Grandstand, Left Wing, Cor. J.M. Basa - Peralta St., City Proper Iloilo City, Philippines 5000</center>").openPopup();
    var popup = L.popup();


L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiY2Rycm1vIiwiYSI6ImNqMzBhZXQxNDAwYWszMnFuejIyNG80cDkifQ.HQTrzbN7u43NV496Hujsnw', {
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox.mapbox-streets-v7',
    accessToken: 'pk.eyJ1IjoiY2Rycm1vIiwiYSI6ImNqMzBhZXQxNDAwYWszMnFuejIyNG80cDkifQ.HQTrzbN7u43NV496Hujsnw'
}).addTo(mymap);


</script>
