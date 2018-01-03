
<!-- 

OPENSTREETMAP: cdrrmoiloilo / cdrrmo2017
MAPBOX: cdrrmo | cdrrmo2017

**************************************************************************************************************

change everything


OVERVIEW 

>map of iloilo with info of barangays



FOOTER

>about us 
>documentation
>credits


NO NAME

> displays disaster in map


HEATMAP

> displays map datas


> FORECAST

> applied algo to solve tsunami
> estimation of affected disaster


disaster display on map
more on mapping ngd ni
warning forecast


>>>>>>

add logo on navbar
sliding option fade fix at center
change background to open street map of iloilo
change theme
password won't reset
login modal upon close doesn't clear the textbox
buggy dashboard


dashboard form margin i dunno how to remove



AGENDA BEFORE PASS:

FUNCTIONING OVERVIEW
DISASTER DATA ADD
DISASTER DATA DISPLAY
MAP
CASSUALTIES
INSERT BARANGAY DATA
CHOROLOPETH MAP ( DUNNO THE SPELLING) FUNCTIONING

blue upon hover @ footer

TOP PAGE INDETIFIER +  ADD CLASS OF TOP PAGE @ BODY TO HOVER IT BACK <- FIX FOR 2 NAVBAR
 

 -->



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

    <title>City Disaster Risk Management</title>    
    <link rel="icon" href="img/favicon.ico">
   
    <link href="css/bootstrap.min.css" rel="stylesheet">    
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css"
       integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ=="
       crossorigin=""/>

</head>


<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

<!-- Navbar Modal -->
  <?php 
  include('library/html/navbar.php'); ?>

<!--  Login Modal -->
  <?php include('library/html/loginmodal.php'); ?>


  <!-- Intro Background Section -->
<div class="jumbotron-container">
  <div class="jumbotron">
    <div class="jumbotext" id = "jumbotextid">
      <p class="jumbotext-main">Iloilo City Disaster Risk Reduction Management Office</p>
      <p class="jumbotext-sub">A Web-based Geographic Information System for the City Disaster Risk Reduction Management of Iloilo City </p>
    </div>
  </div>
</div>

<!-- <section id = "intro1">
    <div class = "container-fluid">
      <div class = "row no-pad">
        <h1> INTRO </h1>
      </div>
    </div>
</section>


<section id = "intro2">
    <div class = "container-fluid">
      <div class = "row no-pad">

      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
      <span class="glyphicon glyphicon-exclamation-sign" style = "font-size: 55px;"></span>
      <h1><strong>City Emergency Hotline</strong></h1>
      <br>
      </div>

      <div class = "col-lg-2 col-md-2 hidden-sm hidden-xs">
      </div>

      <div class = "col-lg-2 col-md-2 col-sm-12 col-xs-12">
      <p class = "hotlineNumber"> 335-1554</p>
      <p class = "hotlineName"> ICER</p>
      <p class = "hotlineInfo"> Emergency and Response</p>
      </div>

      <div class = "col-lg-2 col-md-2 col-sm-4 col-xs-4">
      <p class = "hotlineNumber"> 117</p>     
      <p class = "hotlineName"> DILG</p>
      <p class = "hotlineInfo"> Patrol</p>
      </div>

      <div class = "col-lg-2 col-md-2 col-sm-4 col-xs-4">
      <p class = "hotlineNumber"> 166</p> 
      <p class = "hotlineName"> PNP</p>
      <p class = "hotlineInfo"> Police</p>
      </div>

      <div class = "col-lg-2 col-md-2 col-sm-4 col-xs-4">
      <p class = "hotlineNumber"> 1600</p> 
      <p class = "hotlineName"> BFP</p>
      <p class = "hotlineInfo"> Fire and Ambulance</p>
      </div>

      <div class = "col-lg-2 col-md-2 hidden-sm hidden-xs">
      </div>

      </div>
    </div>
</section>


 -->


<section id = "intro1">
    <div class = "container-fluid">
      <div class = "row">
      <div class = "col-lg-2 col-md-2 hidden-xs hidden-sm">
      </div>
      <div class = "col-lg-8 col-md-8 col-sm-12 col-xs-12">
          <h1> Our Main Goal</h1>
          <blockquote>
          <h3>We aim to develop a system that offers a radically different way in which it could produce maps required to manage orthorectified image mosaics with extracted vector and client-supplied attribute data to create a single Geographical Information System data-rich image of Iloilo City. </h3>
          <br />
          <p class = "pull-right"> − The Researchers </p>
         </blockquote>

          </div>
          <div class = "col-lg-2 col-md-2 hidden-xs hidden-sm">
      </div>
      </div>
    </div>
</section>



<section id = "intro2">
 <div class="container"> 
  <div class="row no-pad">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" style = "padding-top: 95px; padding-right: 10px">
     

    <h5>Powered by <h5>
    <h2> Openstreetmap x LeafletJS</h2>
    <small class = "pull-right"> openstreetmap.org | leaflet.org </small>
  
    </div>
    
    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
     <center><div id="mapid" style="height: 62vh; width: 100%;" tabindex="0"> </div></center>
    </div>

   
    <br / >
  
</div>
</section>

<section id = "intro3">
 <div class="container-fluid"> 
  <div class="row no-pad">
    <h1 class = "text-center"> System Activity </h1>
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
      <!-- On-going Disasters List -->
      <ul class="list-group" style="margin-bottom:0">
        <div class="list-group-item light-list-header">Newsfeed</div>
        <div id="activityList"></div>
      </ul>

    </div>

    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
      <!-- On-going Disasters List -->
      <ul class="list-group" style="margin-bottom:0">
        <div class="list-group-item light-list-header">Recent Disasters</div>
        <div id="ongoing-list"></div>
        <a href="archive.php" class="list-group-item list-item-lg" style="text-align: center;"><b>See Map</b> <span class="glyphicon glyphicon-chevron-right"></span></a>
      </ul>

    </div>

    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
      <!-- Recent Evac List -->
      <ul class="list-group" style="margin-bottom:0">
        <div class="list-group-item light-list-header">Recently Used Evacuation Centers</div>
        <div id="evac-list"></div>
        <a href="evac.php" class="list-group-item list-item-lg" style="text-align: center;"><b>See Map</b> <span class="glyphicon glyphicon-chevron-right"></span></a>
      </ul>

    </div>
    <br / >
  
  </div>
</div>
</section>




<!-- Footer Section -->

<?php include("library/html/footer.php") ?>

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

<script>
 var NPageComponent = {
      nflist: document.getElementById('newsfeed-list')
      };
    

$("#news-direct").click(function() {
  $("#news-toggle").click();
});

function addOngoing(id, disaster, barangay, started, ended) {
  var color = "text-danger";
  if(ended == "Ended") {
    var color = "text-success";
  }
  document.getElementById('ongoing-list').innerHTML += `
    <a href="disasterinfo.php?id=${ id }" class="list-group-item list-item-lg">
      <p>
        <b>Brgy. ${ barangay }</b>&nbsp&nbsp&nbsp
        <b><span class="glyphicon glyphicon-exclamation-sign"></span> ${ disaster }</b>&nbsp&nbsp&nbsp
        <small><span class="glyphicon glyphicon-time"></span> ${ started }</small>
        <b class="pull-right ${ color }"> ${ ended }</b>
      </p>
    </a>`;
}

function emptyOngoing() {
  document.getElementById('ongoing-list').innerHTML += `
    <div class="list-group-item list-item-lg">
      <p><span class="glyphicon glyphicon-record"></span> No disasters to show right now.</p>
    </div>`;
}

function addEvac(evacid, evacname, dateadded) {
  document.getElementById('evac-list').innerHTML += `
    <a href="evacuationinfo.php?id=${ evacid }" class="list-group-item list-item-lg">
      <p>
        <b>${ evacname }</b>&nbsp&nbsp&nbsp
        <small><span class="glyphicon glyphicon-time"></span> ${ dateadded }</small>
      </p>
    </a>`;
}

function emptyEvac() {
  document.getElementById('evac-list').innerHTML += `
    <div class="list-group-item list-item-lg">
      <p><span class="glyphicon glyphicon-record"></span> No recent data available.</p>
    </div>`;
}

  <?php
  //On-going Panel
  $ongoing_sql = "SELECT dd.ID AS ID,
            dt.NAME AS TYPE, 
            br.NAME AS BRGY,
            dd.STARTED AS STARTED,
            dd.ENDED AS ENDED
          FROM disaster_declare AS dd
          INNER JOIN disaster_type AS dt
            ON dd.DISASTER = dt.ID
          INNER JOIN barangay AS br
            ON dd.BRGY = br.ID
          ORDER BY dd.STARTED DESC
          LIMIT 5";
  $ongoing_result = $db->connection->query($ongoing_sql);
  $ongoing_count = mysqli_num_rows($ongoing_result);
  if($ongoing_count < 1) {
    ?>
    emptyOngoing();
    <?php
  }
  else {
    while($ongoing_row = $ongoing_result->fetch_assoc()) {
      $ongoing_ID = htmlspecialchars($ongoing_row['ID']);
      $ongoing_TYPE = htmlspecialchars($ongoing_row['TYPE']);
      $ongoing_BRGY = htmlspecialchars($ongoing_row['BRGY']);
      $ongoing_STARTED = htmlspecialchars($ongoing_row['STARTED']);
      $ongoing_STARTED2 = strtotime($ongoing_STARTED);
      $ongoing_ENDED = "On-going";
      if(!is_null($ongoing_row['ENDED'])) {
        $ongoing_ENDED = "Ended";
      }
       
      ?>
      addOngoing(<?php echo $ongoing_ID; ?>, "<?php echo $ongoing_TYPE; ?>", "<?php echo $ongoing_BRGY; ?>", "<?php echo date('H:i M d', $ongoing_STARTED2); ?>", "<?php echo $ongoing_ENDED; ?>");
      <?php
    }
  }

  //Recent Evac Panel
  $evacuation_sql = "SELECT el.EVACNAME AS EVACNAME,
              er.EVACID AS EVACID,
              er.DATEADDED AS DATEADDED
            FROM evacuation_report AS er
            INNER JOIN evacuation_list AS el
              ON er.EVACID = el.ID
            WHERE er.ISVERIFIED = 1
            GROUP BY er.DECLAREID
            ORDER BY er.DATEADDED DESC
            LIMIT 5";
  $evacuation_result = $db->connection->query($evacuation_sql);
  $evacuation_count = mysqli_num_rows($evacuation_result);
  if($evacuation_count < 1) {
    ?>
    emptyEvac();
    <?php
  }
  else {
    while($evacuation_row = $evacuation_result->fetch_assoc()) {
      $evacuation_NAME = htmlspecialchars($evacuation_row['EVACNAME']);
      $evacuation_EVACID = htmlspecialchars($evacuation_row['EVACID']);
      $evacuation_DATE = htmlspecialchars($evacuation_row['DATEADDED']);
      $evacuation_DATE2 = strtotime($evacuation_DATE);
      ?>
      addEvac(<?php echo $evacuation_EVACID; ?>, "<?php echo $evacuation_NAME; ?>", "<?php echo date('H:i M d', $evacuation_DATE2); ?>");
      <?php
    }
  }
  ?>

  var nav_bar_shown = false;
  $(document).ready(function(){
    $("#news-toggle").click(function() {
      if(!nav_bar_shown) {
        nav_bar_shown = true;
        $("#newsfeed").animate({ right: "+=18%" }, 200);
      }
      else {
        nav_bar_shown = false;
        $("#newsfeed").animate({ right: "-=18%" }, 200);
        
      }
    });
  });
  

    var NPageComponent = {
      nflist: document.getElementById('activityList')
      };
    
    //Add post to list
    function AddPost(id, username, content, timestamp, declareid, colour) {
      if(declareid > 0) {
        NPageComponent.nflist.innerHTML += '<a href="disasterinfo.php?id='+ declareid +'"><li class="list-group-item" id="post_'+ id +'" style="padding-left:2.2rem;">' +
                  '<div class="media"><div class="media-body">' +
                  '<h6><span class="text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp<b>'+ username +'</b></span> <small>'+ timestamp +'</small></h6>' +
                  '<span style="color:#3e3e3e">' + content + '</span></div></div></li></a>';
      }
      else {
        NPageComponent.nflist.innerHTML += '<li class="list-group-item" id="post_'+ id +'" style="padding-left:2.2rem;">' +
              '<div class="media"><div class="media-body">' +
              '<h6><span class="text-info"><span class="glyphicon glyphicon-info-sign"></span>&nbsp<b>'+ username +'</b></span> <small>'+ timestamp +'</small></h6>' +
              content + '</div></div></li>';
      }
    }
  
  //Fill post list with posts
  <?php
  $news_sql = "SELECT * FROM newsfeed ORDER BY POSTDATE DESC LIMIT 4";
  $news_result = $db->connection->query($news_sql);
  $news_count = mysqli_num_rows($news_result);
  
  if($news_count < 1) {
    //echo 'createmessagein(4, "No news found.", "news-message");';
  }
  else {
    while ($news_row = $news_result->fetch_assoc())
    {
      $news_result_ID = htmlspecialchars($news_row['ID']);
      $news_result_CONTENT = htmlspecialchars($news_row['CONTENT']);
      $news_result_POSTDATE = htmlspecialchars($news_row['POSTDATE']);
      $news_result_POSTBY = htmlspecialchars($news_row['POSTBY']);
      $news_result_POSTDATE = strtotime($news_result_POSTDATE);
      if(empty($news_row['DECLAREID']))
        $news_result_DECLARE = -1;
      else
      $news_result_DECLARE = htmlspecialchars($news_row['DECLAREID']);
  ?>
      AddPost(<?php echo $news_result_ID; ?>, '<?php echo $news_result_POSTBY; ?>', "<?php echo $news_result_CONTENT; ?>", '<?php echo date('M d, Y H:i', $news_result_POSTDATE); ?>', <?php echo $news_result_DECLARE; ?>, "red");
  <?php
    }
  }
  ?>


    var mymap = L.map('mapid').setView([10.7035, 122.5594], 14);



L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
   attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 16
}).addTo(mymap);

  $( document ).ready(function(){
        document.getElementById('dashboard').click();
    });

</script>

</body>

</html>

