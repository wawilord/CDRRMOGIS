<!DOCTYPE html>
    <?php
        session_start();
        include('library/form/connection.php');
        include ('library/function/functions.php');
        $db = new db();
        $session_USER_FIRSTNAME = htmlspecialchars($_SESSION['USER_FIRSTNAME']);
        $session_USER_MIDDLENAME = htmlspecialchars($_SESSION['USER_MIDDLENAME']);
        $session_USER_LASTNAME = htmlspecialchars($_SESSION['USER_LASTNAME']);
        date_default_timezone_set('Asia/Hong_Kong');
        $time = time();

        $OnGoingDisasters = array();
        $sql = 'SELECT 				disaster_declare.ID,
                                    barangay.NAME AS BARANGAYNAME,
                                    disaster_type.NAME AS DISASTERNAME,
                                    disaster_declare.NICKNAME,
                                    disaster_declare.STARTED,
                                    disaster_type.COLOR
                FROM 				disaster_declare,
                                    disaster_type,
                                    barangay
                WHERE 				ENDED IS NULL
                AND					disaster_type.ID = disaster_declare.DISASTER
                AND                 barangay.ID = disaster_declare.BRGY';
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while($row = $result->fetch_assoc()){
        $OnGoingDisasters[] = $row;
    }
    ?>
<!--This Page is for the admin only -->
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
    <link href="css/jquery.datetimepicker.css" rel="stylesheet">
</head>
<body role="document">
<!--NAVBAR HERE-->
<?php include('library/html/navbar.php'); ?>
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1 class="red_alert">
            On-Going Disaster/s
        </h1>
    </div>
    <div class="container">
        <div class="row">

            <table class="table">
                <thead>
                    <tr>
                        <th>Disaster Type</th>
                        <th>Barangay</th>
                        <th>Started</th>
                        <th>Alias</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    /*
                        ID
                        BARANGAYNAME
                        DISASTERNAME
                        NICKNAME
                        STARTED
                        COLOR
                     */

                    foreach ($OnGoingDisasters as $disaster){
                        ?>
                            <tr>
                                <td><span class="glyphicon glyphicon-stop" style="color: <?php echo $disaster['COLOR']; ?>;"></span> <?php echo $disaster['DISASTERNAME']; ?></td>
                                <td><?php echo $disaster['BARANGAYNAME']; ?></td>
                                <td><?php echo converttoformaldatetimestring($disaster['STARTED']); ?></td>
                                <td><?php echo $disaster['NICKNAME']; ?></td>
                                <td><a href="CdrrmoViewDisasterDetails.php?id=<?php echo $disaster['ID']; ?>" class="btn btn-primary">View More Details</a></td>
                            </tr>
                        <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>

</div> <!--Content ends here-->
<!--FOOTER HERE-->
<?php include('library/html/footer.php'); ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/1.11.3_jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/app/mylibrary.js"></script>
<script src="js/app/messagealert.js"></script>
<script src="js/jquery.datetimepicker.full.min.js"></script>
<script src="js/jquery.form.min.js"></script>
<script>

</script>

</body>
</html>



