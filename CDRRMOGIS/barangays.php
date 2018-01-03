<?php
    include('library/form/connection.php');
    include('library/function/functions.php');
    $db = new db();
    session_start();

$disaster_types = array();
$sql = 'SELECT * FROM disaster_type';
$result = $db->connection->query($sql);
while ($row = $result->fetch_assoc()){
    $disaster_types[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="th3515">
    <meta name="author" content="@pablongbuhaymo">

    <title>Overview | City Disaster Risk Management</title>    
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

<?php include('library/html/navbar.php');
       include('library/html/loginmodal.php');
    ?>

     <div class="container"> <!--Content starts here-->
    <div class="page-header">
    <span class = "pull-right">
            <button class="btn btn-basic" onclick="location.href='Overview.php'"><span class="glyphicon glyphicon-menu-left"></span> Return to Map Overview</button>
        </span>
        <h3>Barangay Management</h3>
    </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <?php
                        $districts = array();
                        $sql='SELECT * FROM district ORDER BY NAME';
                        $result = $db->connection->query($sql);
                        while ($row = $result->fetch_assoc()){
                            $districts[] = $row;
                        }
                        $brgynum = 0;
                        foreach ($districts as $district){
                            ?>
                                <div>
                                    <h3><?php echo $district['NAME']; ?></h3>
                                    <div class="container-fluid">
                                        <div class="list-group">
                                            <?php
                                                $sql='SELECT * FROM barangay WHERE DISTRICT = ' . $district['ID'] . ' ORDER BY NAME';
                                                $result = $db->connection->query($sql);
                                                $brgynum += mysqli_num_rows($result);
                                                while ($row = $result->fetch_assoc()){
                                                    ?>
                                                        <a href="barangayview.php?id=<?php echo $row['ID']; ?>" class="list-group-item"><?php echo $row['NAME']; ?></a>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                    ?>
                </div>

                <div class="col-lg-6">
                    <h4>Info:</h4>
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Number of Districts: </th>
                                <td><?php echo sizeof($districts); ?></td>
                            </tr>
                            <tr>
                                <th>Number of Barangays:</th>
                                <td><?php echo $brgynum; ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <hr />
                    <h3>Number of Barangays per District:</h3>
                    <div id="Donut-Chart"></div>
                </div>
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
<script src="js/app/loginscript.js"></script>
<script src="js/app/bargraph.js"></script>
<script src="js/jquery.easing.min.js"></script>
<script>
    var dir = document.URL.substr(0,document.URL.lastIndexOf('/'));
    $(document).ready(function() {
        //Morris charts snippet - js
        $.getScript(dir + '/js/app/raphael-min.js',function(){
            $.getScript(dir + '/js/app/morris.min.js',function(){
                Morris.Donut({
                    element: 'Donut-Chart',
                    data: [
                    <?php
                        $districts = array();
                        $sql='SELECT * FROM district ORDER BY NAME';
                        $result = $db->connection->query($sql);
                        while ($row = $result->fetch_assoc()){
                            $districts[] = $row;
                        }
                        $arg = '';
                        foreach ($districts as $district){
                            $sql='SELECT * FROM barangay WHERE DISTRICT = ' . $district['ID'] . ' ORDER BY NAME';
                            $result = $db->connection->query($sql);
                            $count =  mysqli_num_rows($result);
                            $arg .= "{label: \"" . $district['NAME'] . "\", value: " . $count . "},\n";
                        }
                        $arg = substr($arg, 0, strlen($arg)-2);
                        echo $arg;
                    ?>
                    ]
                });
            });
        });
    });
</script>

</body>
</html>