<!DOCTYPE html>
<?php
session_start();
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();

//session variables
$session_USER_FIRSTNAME = htmlspecialchars($_SESSION['USER_FIRSTNAME']);
$session_USER_MIDDLENAME = htmlspecialchars($_SESSION['USER_MIDDLENAME']);
$session_USER_LASTNAME = htmlspecialchars($_SESSION['USER_LASTNAME']);

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
</head>
<body role="document">
<?php include('library/html/navbar.php'); ?>
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1>
            Confirm Disaster
            <small><br />Grouped by Barangay</small>
        </h1>
    </div>
    <div id="pagemessagebox" tabindex="0"></div>


            <div class="list-group">
                <div class="row">
                    <?php
                    $brgylist = array();
                    $sql = "SELECT 
                                    barangay.ID, 
                                    barangay.NAME 
                            FROM 
                                    barangay, 
                                    disaster_declare 
                            WHERE 
                                    disaster_declare.BRGY = barangay.ID
                            GROUP BY 
                                    barangay.ID";
                    $result = $db->connection->query($sql);
                    $count = mysqli_num_rows($result);
                    while($row = $result->fetch_assoc()){
                        array_push($brgylist, array($row['ID'], $row['NAME'], 0));
                    }

                    foreach ($brgylist as $brgy) {
                        $sql = "SELECT disaster_declare.ID, disaster_type.NAME, disaster_type.COLOR 
                                FROM disaster_type, disaster_declare 
                                WHERE disaster_declare.DISASTER = disaster_type.ID 
                                AND disaster_declare.ENDED IS NOT NULL
                                AND disaster_declare.BRGY = " . $brgy[0] . "
                                AND disaster_declare.ISVERIFIED = 0
                                GROUP BY disaster_declare.ID";
                        $result = $db->connection->query($sql);
                        $count = mysqli_num_rows($result);
                        $brgy[2] = $count;
                        if($count > 0) {
                            ?>

                            <div class="col-lg-6">
                                <div class="media dashboard_item">
                                    <a href="CswdConfirmDisasterListofBrgy.php?id=<?php echo $brgy[0]; ?>"
                                       class="thumbnail">
                                        <div class="media-left">
                                            <div class="centerize1"
                                                 style="height: 100px; width: 100px; text-align: center;">
                                                <div class="centerize2 dashboard_grey"
                                                     style="border-radius: 100px;">
                                                    <h1 class="media-heading"><?php echo $brgy[2]; ?></h1>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <div class="centerize1" style="height: 100px;">
                                                <div class="centerize2">
                                                    <h4 class="media-heading">Brgy. <?php echo $brgy[1]; ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>

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

</body>
</html>



