<!DOCTYPE html>
    <?php
    session_start();
    include ('library/form/CswdOnly.php');
    include('library/form/connection.php');
    include ('library/function/functions.php');
    $db = new db();
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
    <link href="css/jquery.datetimepicker.css" rel="stylesheet">
</head>
<body role="document">
<!--Modal--><!--NAVBAR HERE-->
<?php include('library/html/navbar.php'); ?>
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1>Declare Disaster</h1>
    </div>

    <div class="container-fluid">
        <h3>Declare Disaster in?</h3>
        <br />
        <div class="row">
            <?php
                $sql = 'SELECT * FROM barangay ORDER BY NAME ASC';
                $result = $db->connection->query($sql);
                while($row = $result->fetch_assoc()) {
            ?>
                <div class="col-lg-4">
                    <a href="CswdDeclareDisaster.php?brgy=<?php echo $row['ID']; ?>" class="thumbnail" style="text-align: center;">
                        <h4>Brgy. <?php echo $row['NAME']; ?></h4>
                    </a>
                </div>
            <?php
                }
            ?>
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



