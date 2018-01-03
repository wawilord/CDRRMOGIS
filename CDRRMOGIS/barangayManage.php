<?php
session_start();
include ('library/form/AdminOnly.php');
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

    <title>Archive | City Disaster Risk Management</title>    
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

<?php include('library/html/navbar.php');
       include('library/html/loginmodal.php');
    ?>

    <!--Content starts here-->

    <div class="container fluid"> 
    
    <div class = "row no-pad">
        <div class="page-header">
              <h1 class = "pull-left"> Barangay Management </h1>

            <div class ="col-md-12 text-center" style = "padding: 50px;">
              <br />
             
              
              <button class="btn btn-success dbSync"><span class="glyphicon glyphicon-download-alt"></span> &nbsp; Run Database Sync</button>
              <button class="btn btn-primary" onclick="location.href='barangayManagement.php'"><span class="glyphicon glyphicon-eye-open"></span> &nbsp; View Barangay Data </button> 
              <br />

          </div>             
                  <?php
                  $sql = "SELECT DATEADDED FROM barangay_info ORDER BY DATEADDED DESC LIMIT 1";
                  $result = $db->connection->query($sql);
                  while($row = $result->fetch_assoc()) {
                    ?>
                    <text class = "pull-right">Last Database Sync: <?php echo $row["DATEADDED"]; ?></text>
                    <?php
                  }
                  ?>


      </div>
      </div>
</div>
<br>

  

<?php include('library/html/footer.php'); ?>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/app/mylibrary.js"></script>
    <script src="js/app/loginscript.js"></script>

    <script src="js/chart.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
   integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
   crossorigin=""></script>
   <script type="text/javascript" src="city.js"></script>
    
</body>

</html>


<script>

$(".dbSync").click(function(){
       var response = confirm("Are you sure you want to run a Database Sync?!");
       if(response == true){

        $.ajax({url: "library/form/BrgyInfoUpdate.php", success: function(result){
            alert('Succesfully sync from the Barangay Database');
            location.reload();
            }});
        }
        else
        {
            alert('Database sync Canceled');
        }
        });

</script>

