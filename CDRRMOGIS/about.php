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

    <title>About | City Disaster Risk Management</title>    
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
        <p class="section-header text-center">
                <img src = "img/iloiloseal.jpeg" width = "120" height = "120">
                <img src = "img/cdrrmoseal.png" width = "180" height = "180">
                <img src = "img/icer.png" width = "120" height = "120">
                <br><br /> The CDRRMO
        </p> 
        <p class="section-text">
            We cater to all types of emergency around our area of responsibility in the City of Iloilo in the Republic of the Philippines.
            From vehicular accidents, trauma, medical, emergency transport, fire, drowning, search and rescue, collapse structures and any emergency, we respond.
        </p>
   
    </div>
    </section>

    <section class="light-section" id = "about2">
    <div class = "row">

            <p class="section-header"><span class="glyphicon glyphicon-cog"></span><br /> Brief History</p><br /> 
        <p class="section-text">
            CDRRMO (City Disaster Risk Reduction Management Office) is a government institutional body specializing in the disaster reduction and response. Established by the Government to provide integrated direction and control in a City level for its manpower, material, monitoring, and other necessary resources that will be made available and responsive to an upcoming disaster and calamities which may occur in the locality. <br> <br> Made Available through Republic ACT 10121, an ACT Strengthening the Philippine Risk Reduction and Management System, providing for the national disaster risk reduction and management framework and institutionalizing the National Disaster Risk Reduction and Management Plan, appropriating funds therefor and for other purposes. <br> <br> CDRRMO Iloilo organized by the Government of Iloilo in 2003, was one of the pioneering LGU to have a local response team in the country. In accord with the Iloilo City Emergency Responders (ICER) they formed the organization to combat the disaster that have been lambasting the province. <br> <br> As of 2016, the Iloilo CDRRMO oversees 90 personnel, which include staff assigned to ICER, though the ideal is reported to be 110 personnel. CDRRMO also provides other services, such as: Trainings in First Aid, Evacuation Drills, Disaster Preparedness, ICC, Monitoring the weather and provide early warnings to the barangay if there is an upcoming disaster.
        </p>
        </div>
    </section>

    <section class = "light-section" id = "about3">
        <div class="row">
            <div class="col-lg-6 col-lg-md-6 col-sm-12 col-xs-12">
                <h3 class="section-subheader"><span class="glyphicon glyphicon-flag"></span><br /> Our Mission</h3> <br />
                <p class="section-subtext">To provide timely and efficient medical and trauma care to victims of disasters and emergencies, and to alleviate the grief suffered by those left by letting them know that someone cares, and are prepared to respond to them should the need arise</p>
            </div>
            <div class="col-lg-6 col-lg-md-6 col-sm-12 col-xs-12">
                <h3 class="section-subheader"><span class="glyphicon glyphicon-eye-open"></span><br /> Our Vision</h3> <br >
                <p class="section-subtext">To provide the City of Iloilo with an efficient, timely, and state of the art Emergency Medical Services system, that is at par with the best in the world</p>
            </div>
            </div>
        
    </section>

    <section class = "light-section" id = "about4">
        <div class = "row">
            <p class="section-header"><span class="glyphicon glyphicon-map-marker"></span><br /> Office Location</p><br />
        <p class="section-text">
            <p class = "section-subtext"> Freedom Grandstand, Left Wing, Cor. J.M. Basa - Peralta St., City Proper
            Iloilo City, Philippines 5000
            </p>
            <br>
        <div class = "col-lg-12 col-md-12 col-sm-12 col-xs12">
            <center><div id="mapid" style="height: 55vh; width: 100%;" tabindex="0"> </div></center>
        </div>
        </p>
         </div>
    </section>
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


L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
   attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 16
}).addTo(mymap);



</script>
