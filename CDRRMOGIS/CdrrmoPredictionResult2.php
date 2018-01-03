<!DOCTYPE html>
<?php
error_reporting(E_ERROR);
    session_start();
    include('library/form/connection.php');
    include ('library/function/functions.php');
    include('Regression/Matrix.php');
    include ('Regression/Regression.php');
    $db = new db();
    $session_USER_FIRSTNAME = htmlspecialchars($_SESSION['USER_FIRSTNAME']);
    $session_USER_MIDDLENAME = htmlspecialchars($_SESSION['USER_MIDDLENAME']);
    $session_USER_LASTNAME = htmlspecialchars($_SESSION['USER_LASTNAME']);
    date_default_timezone_set('Asia/Hong_Kong');
    $time = time();

    $DisasterType = $_GET['DisasterType'];
    $District = $_GET['District'];
    $Barangay = $_GET['Barangay'];
    $Time = $_GET['Time'] . ' 00:00:00';

    $DisasterType_Name = '';
    $PlaceName = '';

    $independent_rating = $_GET['rating'];
    $sql = 'SELECT NAME FROM disaster_type WHERE ID = ' . $DisasterType;
    $result = $db->connection->query($sql);
    $row = $result->fetch_assoc();
    $DisasterType_Name = $row['NAME'];
    $sqlbrgylist = "SELECT ID FROM barangay";
    if($Barangay > 0){
        $sqlbrgylist = 'SELECT ID FROM barangay WHERE ID = ' . $Barangay;
        $sql= ' SELECT  district.NAME AS DISTRICT,
                        barangay.NAME AS BARANGAY
                FROM    district, barangay
                WHERE   barangay.ID = ' . $Barangay . '
                AND     barangay.DISTRICT = district.ID';
        $result = $db->connection->query($sql);
        $row = $result->fetch_assoc();
        $PlaceName = "Brgy. " . $row['BARANGAY'] . ", " . $row['DISTRICT'] . ", Iloilo City";
    }
    else if($District > 0){
        $sqlbrgylist = 'SELECT ID FROM barangay WHERE DISTRICT = ' . $District;
        $sql = 'SELECT  `NAME` 
                FROM    DISTRICT
                WHERE   ID = ' . $District;
        $result = $db->connection->query($sql);
        $row = $result->fetch_assoc();
        $PlaceName = $row['NAME'] . ", Iloilo City";

    }
    else{
        $PlaceName = "All Barangays, and all Districts of Iloilo City";
    }

    $brgylist = array();
    $result = $db->connection->query($sqlbrgylist);
    $count = mysqli_num_rows($result);
    while($row = $result->fetch_assoc()){
        $brgylist[] = $row['ID'];
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
        <h1>Approximation <small> of Casualties of <?php echo $DisasterType_Name; ?> in
            <br /><?php echo $PlaceName; ?></small>
        </h1>
        <h2><small>> <?php echo getdatestring($Time); ?></small></h2>
    </div>
    <div class="container">
        <div class="row">
            <?php
            function converttowholenumber($num){
                if($num < 0){
                    return 0;
                }
                return floor((double)$num);
            }
            $goodtogo = true;
            $DisasterRegression = array("IndependentVariables"=>array(), "DependentVariables"=> array());
            $sql = 'SELECT * FROM	(SELECT disaster_declare.*,
            	      		        barangay_info.MEN,
                                	barangay_info.WOMEN,
                                	barangay_info.MINORS,
                                	barangay_info.ADULTS,
                                	barangay_info.PWD
           		            FROM    disaster_declare,
            	            		barangay_info
           		            WHERE   disaster_declare.ISVERIFIED = 1
            	            AND     disaster_declare.RATING IS NOT NULL
            	            AND     disaster_declare.DISASTER = ' . $DisasterType . '
           		            AND		disaster_declare.BRGY = barangay_info.BARANGAY
            	            ORDER BY barangay_info.DATEADDED DESC) a
            GROUP BY ID';
            $result = $db->connection->query($sql);
            $count = mysqli_num_rows($result);
            while($row = $result->fetch_assoc()){
                $DisasterRegression["IndependentVariables"][] = array($row['MEN'] + $row['WOMEN'], $row['RATING']);
                $sql2 = '   SELECT   * 
                            FROM    disaster_reports
                            WHERE   DECLAREID = ' . $row['ID'] . '
                            AND     ISVERIFIED = 1
                            ORDER BY    DATEADDED DESC
                            LIMIT   0, 1';
                $result2 = $db->connection->query($sql2);
                $totally = 0;
                $partially = 0;
                $dead = 0;
                $injured = 0;
                $missing = 0;
                while($row2 = $result2->fetch_assoc()){
                    $totally = $row2['DMGTOTALLY'];
                    $partially = $row2['DMGPARTIALLY'];
                    $dead = $row2['CSLTDEAD'];
                    $injured = $row2['CSLTINJURED'];
                    $missing = $row2['CSLTMISSING'];
                }
                $DisasterRegression["DependentVariables"][] = array($totally, $partially, $dead, $injured, $missing);
            }
            if($count < 5){
                echo '<h3>Resources under ' . $DisasterType_Name . ' disaster are not enough for prediction. (Data not enough. You must be on  the initialization phase of system.)</h3>';
            }
            else{
                $Total_Men = 0;
                $Total_Women = 0;
                $Total_Minors = 0;
                $Total_Adult = 0;
                $Total_PWD = 0;
                $Total_Totally = 0;
                $Total_Partially = 0;
                $Total_Dead = 0;
                $Total_Injured = 0;
                $Total_Missing = 0;
                foreach ($brgylist as $brgy_id){
                    $SubTotal_Men = 0;
                    $SubTotal_Women = 0;
                    $SubTotal_Minors = 0;
                    $SubTotal_Adult = 0;
                    $SubTotal_PWD = 0;
                    $SubTotal_Totally = 0;
                    $SubTotal_Partially = 0;
                    $SubTotal_Dead = 0;
                    $SubTotal_Injured = 0;
                    $SubTotal_Missing = 0;
                    $sql = 'SELECT * FROM (SELECT  barangay_info.*,
                                    barangay.NAME
                            FROM    barangay_info,
                                    barangay
                            WHERE   barangay_info.BARANGAY = ' . $brgy_id . '
                            AND     barangay.ID = barangay_info.BARANGAY
                            ORDER BY barangay_info.DATEADDED DESC)a
                            GROUP BY DAY(DATEADDED)';
                    $result = $db->connection->query($sql);
                    $count = mysqli_num_rows($result);
                    $row = $result->fetch_assoc();
                    if($count <= 5){
                        echo '<p>Population updates under Brgy. ' . $row['NAME'] . ' is not enough for prediction. (Data not enough. You must be on  the initialization phase of system.)</p>';
                        $goodtogo = false;
                    }
                    else{
                        //Data Mining \m/
                        $PopulationIndependent = array();
                        $Dependent_Men = array();
                        $Dependent_Women = array();
                        $Dependent_Minors = array();
                        $Dependent_Adult = array();
                        $Dependent_PWD = array();

                        $dummy = array();
                        $result = $db->connection->query($sql);
                        while ($row = $result->fetch_assoc()){
                            $dummy[] = $row;
                        }
                        for($i = 1; $i < sizeof($dummy); $i++){
                            $date1=date_create(split(' ', $dummy[$i]['DATEADDED'])[0]);
                            $date2=date_create(split(' ', $dummy[$i-1]['DATEADDED'])[0]);
                            $diff=date_diff($date1,$date2);
                            $PopulationIndependent[] = array((double)$diff->format("%a"));
                            $Dependent_Men[] = array($dummy[$i]['MEN']);
                            $Dependent_Women[] = array($dummy[$i]['WOMEN']);
                            $Dependent_Minors[] = array($dummy[$i]['MINORS']);
                            $Dependent_Adult[] = array($dummy[$i]['ADULTS']);
                            $Dependent_PWD[] = array($dummy[$i]['PWD']);
                        }

                        $date1=date_create(split(' ', $Time)[0]);
                        $date2=date_create(split(' ', $dummy[sizeof($dummy) - 1]['DATEADDED'])[0]);
                        $diff=date_diff($date1,$date2);


                        //Men
                        $Reg = new \Regression\Regression();
                        $Reg->setX(new \Regression\Matrix($PopulationIndependent));
                        $Reg->setY(new \Regression\Matrix($Dependent_Men));
                        $Reg->exec();
                        $PredictedPopulation = $Reg->predict(new \Regression\Matrix(array(array((double)$diff->format("%a")))));
                        $SubTotal_Men = converttowholenumber($PredictedPopulation->getData()[0][0]);

                        //Women
                        $Reg->setY(new \Regression\Matrix($Dependent_Women));
                        $Reg->exec();
                        $PredictedPopulation = $Reg->predict(new \Regression\Matrix(array(array((double)$diff->format("%a")))));
                        $SubTotal_Women = converttowholenumber($PredictedPopulation->getData()[0][0]);

                        //Minors
                        $Reg->setY(new \Regression\Matrix($Dependent_Minors));
                        $Reg->exec();
                        $PredictedPopulation = $Reg->predict(new \Regression\Matrix(array(array((double)$diff->format("%a")))));
                        $SubTotal_Minors = converttowholenumber($PredictedPopulation->getData()[0][0]);

                        //Adult
                        $Reg->setY(new \Regression\Matrix($Dependent_Adult));
                        $Reg->exec();
                        $PredictedPopulation = $Reg->predict(new \Regression\Matrix(array(array((double)$diff->format("%a")))));
                        $SubTotal_Adult = converttowholenumber($PredictedPopulation->getData()[0][0]);

                        //PWD
                        $Reg->setY(new \Regression\Matrix($Dependent_PWD));
                        $Reg->exec();
                        $PredictedPopulation = $Reg->predict(new \Regression\Matrix(array(array((double)$diff->format("%a")))));
                        $SubTotal_PWD = converttowholenumber($PredictedPopulation->getData()[0][0]);


                        $Reg = new \Regression\Regression();
                        $Reg->setX(new \Regression\Matrix($DisasterRegression["IndependentVariables"]));
                        $Reg->setY(new \Regression\Matrix($DisasterRegression["DependentVariables"]));
                        $Reg->exec();
                        $PredictedDisaster = $Reg->predict(new \Regression\Matrix(array(array($SubTotal_Men + $SubTotal_Women, $independent_rating))));
                        //$totally, $partially, $dead, $injured, $missing
                        $SubTotal_Totally = converttowholenumber($PredictedDisaster->getData()[0][0]);
                        $SubTotal_Partially = converttowholenumber($PredictedDisaster->getData()[0][1]);
                        $SubTotal_Dead = converttowholenumber($PredictedDisaster->getData()[0][2]);
                        $SubTotal_Injured = converttowholenumber($PredictedDisaster->getData()[0][3]);
                        $SubTotal_Missing = converttowholenumber($PredictedDisaster->getData()[0][4]);
                    }
                    $Total_Men += $SubTotal_Men;
                    $Total_Women += $SubTotal_Women;
                    $Total_Minors += $SubTotal_Minors;
                    $Total_Adult += $SubTotal_Adult;
                    $Total_PWD += $SubTotal_PWD;
                    $Total_Totally += $SubTotal_Totally;
                    $Total_Partially += $SubTotal_Partially;
                    $Total_Dead += $SubTotal_Dead;
                    $Total_Injured += $SubTotal_Injured;
                    $Total_Missing += $SubTotal_Missing;
                }

                if($goodtogo){
                    //_______________________________________________________________________________________
                    ?>
                        <div class="container-fluid">
                            <div class="alert alert-warning" role="alert">
                                <p>The following approximations are not 100% accurate. The purpose of the approximation is just to give an idea of what might happen. This would help disaster risk management on what should be prepared and where to focus.</p>
                            </div>
                            <div class="panel panel-default">
                                <div class="container-fluid">
                                    <br />
                                    <h4>The Approximate Population of <?php echo $PlaceName; ?> on <?php echo getdatestring($Time); ?></h4>
                                    <div class="container-fluid">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th><h4>Name</h4></th>
                                                <th><h4>Approximation</h4></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th>Total Population</th>
                                                <td><?php echo $Total_Men + $Total_Women; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Men</th>
                                                <td><?php echo $Total_Men; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Women</th>
                                                <td><?php echo $Total_Women; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Minors</th>
                                                <td><?php echo $Total_Minors; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Adult</th>
                                                <td><?php echo $Total_Adult; ?></td>
                                            </tr>
                                            <tr>
                                                <th>PWD</th>
                                                <td><?php echo $Total_PWD; ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <br />
                                    <hr />
                                    <h4>The Approximate Casualties in <?php echo $PlaceName; ?> on <?php echo getdatestring($Time); ?> <br />
                                    <small>Disaster Rating of <?php echo $independent_rating; ?></small></h4>
                                    <div class="container-fluid">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th><h4>Name</h4></th>
                                                <th><h4>Approximation</h4></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <th>Totally Damaged Houses</th>
                                                <td><?php echo $Total_Totally; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Partially Damaged Houses</th>
                                                <td><?php echo $Total_Partially; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dead People</th>
                                                <td><?php echo $Total_Dead; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Injured People</th>
                                                <td><?php echo $Total_Injured; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Missing People</th>
                                                <td><?php echo $Total_Missing; ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    //_______________________________________________________________________________________
                }
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
    $('#PredictionDate').datetimepicker({
        format:'Y-m-d',
        mask:true,
        timepicker: false
    });

    var district = [];
    <?php
        $sql = "SELECT *
                FROM district";
        $result = $db->connection->query($sql);
        while($row = $result->fetch_assoc()){
            ?>
                district.push(
                    {
                        id: <?php echo $row['ID']; ?>,
                        name: "<?php echo $row['NAME']; ?>",
                        brgy:[<?php
                                $sql2 = "SELECT *
                                        FROM barangay
                                        WHERE DISTRICT = " . $row['ID'];
                                $result2 = $db->connection->query($sql2);
                                $brgys = '';
                                while ($row2 = $result2->fetch_assoc()){
                                    $brgys .= ' {id: ' . $row2['ID'] . ', name: "' . $row2['NAME'] . '"},';
                                }
                                $brgys = substr($brgys, 0, strlen($brgys)-1);
                                echo $brgys;
                            ?>]
                    }
                );
            <?php
        }
    ?>
    
    function SwitchPrediction() {
        if(document.getElementById("PredictionDate").disabled){
            document.getElementById("PredictionDate").disabled = false;
            document.getElementById("PredictionBy").disabled = true;
            document.getElementById("PredictionDateBox").style.display = "";
        }
        else{
            document.getElementById("PredictionDate").disabled = true;
            document.getElementById("PredictionBy").disabled = false;
            document.getElementById("PredictionDateBox").style.display = "none";
        }
    }

    function FillInListElement(id, value, name) {
        document.getElementById(id).innerHTML += '' +
        '<option value="' + value + '">' + name + '</option>';
    }

    document.body.onload = function () {
        FillInListElement('District', 0, 'All');
        FillInListElement('Barangay', 0, 'All');
        <?php
            $sql = "SELECT *
                    FROM disaster_type";
            $result = $db->connection->query($sql);
            while($row = $result->fetch_assoc()){
                ?>
                    FillInListElement('DisasterType', <?php echo $row['ID']; ?>, '<?php echo $row['NAME']; ?>');
                <?php
            }
        ?>

        district.forEach(function (item, index) {
            FillInListElement('District', item.id, item.name);
        });

        document.getElementById('District').onchange = function () {
            district.forEach(function (item, index) {
                if(document.getElementById('District').value.toString() == item.id.toString()){
                    document.getElementById('Barangay').innerHTML = '';
                    FillInListElement('Barangay', 0, 'All');
                    item.brgy.forEach(function (brgyname, ind) {
                        FillInListElement('Barangay', brgyname.id, brgyname.name);
                    });
                }
            });
        };

        SwitchPrediction();
        SwitchPrediction();
    };
</script>

</body>
</html>



