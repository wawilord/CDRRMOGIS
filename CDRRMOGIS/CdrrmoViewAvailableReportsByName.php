<!DOCTYPE html>
    <?php
    session_start();
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
<!--NAVBAR HERE-->
<?php include('library/html/navbar.php'); ?>
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1>View<small> Avaialable Reports from CSWD</small></h1>
    </div>

    <div class="input-group">
        <input type="text" class="form-control" aria-label="...">
        <div class="input-group-btn">
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Search</button>
        </div>
    </div>
    <br />

    <div class="container-fluid">
        <div class="list-group" id="group_box">

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
    var page = {
        group_box: document.getElementById('group_box')
    };
    function putS(num) {
        if(parseInt(num) > 1){
            return 's';
        }
        return'';
    }
    function AddNewGroupName(id, type, name, date, count) {
        page.group_box.innerHTML += '' +
        '<a href="CswdViewAvailableReports.php?id=' + id + '" class="list-group-item">' +
        '   <h3>[' + type + '] ' + name + '</h3>' +
        '   <p>' + date + ' ⚫ [Includes ' + count + ' Declared Disaster' + putS(count) + ']</p>' +
        '</a>';
    }



    <?php
        $data = array();
        $dummy = '';
        $sql = "SELECT disaster_profile.*, disaster_type.NAME AS TYPENAME
                FROM disaster_profile, disaster_type
                WHERE disaster_profile.TYPE = disaster_type.ID
                GROUP BY disaster_profile.ID
                ORDER BY disaster_profile.ID DESC";
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while($row = $result->fetch_assoc()){
            $data[] = $row;
        }

        foreach ($data as $dat){
        $arg = '';
        $sql = " SELECT DECLAREID
                  FROM disaster_declarelist
                  WHERE PROFILEID	= " . $dat['ID'];
        $result = $db->connection->query($sql);
        $count2 = mysqli_num_rows($result);
        while($row = $result->fetch_assoc()){
            $arg .= $row['DECLAREID'] . ',';
        }
        $arg = substr($arg, 0, strlen($arg)-1);
        if($count2 > 0){
            $sql = 'SELECT DATEADDED FROM  (SELECT DATEADDED 
                                                            FROM evacuation_report
                                                            WHERE DECLAREID IN(' . $arg . ')
                                                            AND ISVERIFIED = 1
                                                        UNION
                                                            SELECT DATEADDED 
                                                            FROM disaster_reports
                                                            WHERE DECLAREID IN(' . $arg . ')
                                                            AND ISVERIFIED = 1
                                                        UNION
                                                            SELECT DATEADDED 
                                                            FROM disaster_cost
                                                            WHERE DECLAREID IN(' . $arg . ')) a
                                    GROUP BY HOUR(DATEADDED)
                                    ORDER BY DATEADDED DESC';
            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);
            if($count > 0){
            ?>
            AddNewGroupName('<?php echo $dat["ID"]; ?>', '<?php echo $dat["TYPENAME"]; ?>', '<?php echo $dat["NAME"]; ?>', '<?php echo getdatestring($dat["DATESTART"]); ?>', '<?php echo $count2; ?>');
            <?php
            }
        }
    }
    ?>


</script>

</body>
</html>



