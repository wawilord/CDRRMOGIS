<!DOCTYPE html>
<?php
session_start();
include('library/form/cswdonly.php');
include('library/form/connection.php');
include('library/function/functions.php');
$db = new db();

    $session_USER_FIRSTNAME = htmlspecialchars($_SESSION['USER_FIRSTNAME']);
    $session_USER_MIDDLENAME = htmlspecialchars($_SESSION['USER_MIDDLENAME']);
    $session_USER_LASTNAME = htmlspecialchars($_SESSION['USER_LASTNAME']);
    
    //query for the address
    // $sql = '
    //         SELECT  
    //                 barangay.NAME AS BRGYNAME,
    //                 district.NAME AS DISTRICTNAME,
    //                 city.NAME AS CITYNAME
    //         FROM 
    //                 barangay, 
    //                 district, 
    //                 city
    //         WHERE   
    //                 barangay.ID = ' . $_SESSION['USER_BRGY'] . '
    //         AND 
    //                 barangay.DISTRICT = district.ID
    //         AND 
    //                 district.CITY = city.ID
    //         ';
    // $result = $db->connection->query($sql);
    // $count = mysqli_num_rows($result);
    // $row = $result->fetch_assoc();

    // //variables for the address
    // $result_BRGYNAME = htmlspecialchars($row['BRGYNAME']);
    // $result_DISTRICTNAME = htmlspecialchars($row['DISTRICTNAME']);
    // $result_CITYNAME = htmlspecialchars($row['CITYNAME']);

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
    <link href="css/app.css" rel="stylesheet">
    <link href="css/jquery.datetimepicker.css" rel="stylesheet">

</head>

<div class="modal fade" id="disasterAdd" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Disaster Profile</h4>
            </div>
            
            <!-- Add Barangay Form -->
            <form id="disasterAddForm" method="post" action="library/form/disasterAddForm.php">
                <div class="modal-body">
                    <div id="disasterAdd_msgbox" tabindex="0"></div>
                    
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">Information</div>
                        <div class="panel-body">
                        
                            <!-- Disaster Type -->
                            <div class="input-group">
                                <span class="input-group-addon" id="sizing-addon1">What Disaster?</span>
                                <select class="form-control" id="disasterAdd_DISASTER" name="DISASTER" required>
                                    <?php
                                    $sql = "SELECT * FROM disaster_type WHERE ENABLED=1 ORDER BY NAME ASC";
                                    $result = $db->connection->query($sql);
                                    while($row = $result->fetch_assoc()) {
                                    ?>
                                    <option value="<?php echo $row["ID"]; ?>" id="disaster_type_<?php echo $row["ID"]; ?>"><?php echo $row["NAME"]; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <br />


                        <div class="input-group input-group">
                          <span class="input-group-addon" id="sizing-addon1">Alias</span>
                          <input type="text" id="disasterAdd_NICKNAME" maxlength="50" name="NICKNAME" class="form-control" aria-describedby="sizing-addon1" required>
                            <span class="input-group-btn">
                                  <button class="btn btn-default" type="button" data-placement="bottom" data-toggle="popover" data-content="_____________________ This is just to differentiate disasters declared in your barangay. You can make any alias."><span class="glyphicon glyphicon-question-sign"></span> </button>
                            </span>
                        </div>                            

                        <br />

                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon1">Select Barangay:</span>
                                <select class="form-control" id="disasterAdd_BARANGAY" name="BARANGAY" required>
                                    <?php
                                    $sql = "SELECT * FROM barangay ORDER BY NAME ASC";
                                    $result = $db->connection->query($sql);
                                    while($row = $result->fetch_assoc()) {
                                    ?>
                                    <option value="<?php echo $row["ID"]; ?>" id="disaster_type_<?php echo $row["ID"]; ?>"><?php echo $row["NAME"]; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <p> *soon to be changed as mapping identifies automatic all the barangay affected</p>
                            

                            <?php

                            date_default_timezone_set('Asia/Hong_Kong');
                            $time = time();

                            ?>

                            <div class="input-group input-group">
                              <span class="input-group-addon" id="sizing-addon1">When did it start?</span>
                              <input type="text" class="form-control" value="<?php echo date("Y/m/d H:i", $time); ?>" id="disasterAdd_STARTED" name="STARTED" required/>
                            </div>
                            
                            <br />


                            <div class="input-group input-group">
                              <span class="input-group-addon" id="sizing-addon1">When did it end?</span>
                              <input type="text" class="form-control" value="<?php echo date("Y/m/d H:i", $time); ?>" id="disasterAdd_ENDED" name="ENDED" required/>
                            </div>

                            <br />

                            <div class="input-group input-group">
                              <span class="input-group-addon" id="sizing-addon1">Note/Comment</span>
                              <textarea rows="5" class="form-control" id="disasterAdd_COMMENT" name="COMMENT" maxlength="300" placeholder="Place your note or comment here regarding to the disaster."></textarea>
                            </div>

                            <br />                            

                        </div>
                    </div>
                    

                    <div class="panel panel-default">
                        <div class="panel-heading">Location</div>
                        <div class="panel-body">
                                    
                    <div class="input-group">
                        <span class="input-group-addon" id="basicasdsaasdon1"><span class="glyphicon glyphicon-pushpin"></span></span>
                        <div class="form-control" style="width: 100%; height:300px;">
                            <div id="map"></div>
                        </div>
                        <!-- <input id="pac-input" class="controls" onkeydown="if(event.keyCode == 13) {event.preventDefault(); return false; }" type="text" placeholder="Search Box" /> -->
                    </div>

                        </div>
                    </div>
                    
                </div>
                
                <!-- Submission -->
                <div class="modal-footer">
                    <button type="submit" id="disasterAdd_SUBMIT" data-loading-text="Adding Disaster..." class="btn btn-primary">Add Disaster</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
                
            </form>
            
        </div>
    </div>
</div>
<?php include('library/html/navbar.php'); ?>

<body role="document">
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h3>
           Manage Verified Disaster Profile 
           <span class = "pull-right">
                <div class="btn-group"><button id="Barangay_BTN_' + id + '" value="' + id + '" class="btn btn-secondary" data-toggle="modal" data-target="#disasterAdd" ><span class="glyphicon glyphicon-plus"></span> Add new Disaster Profile</button><input type="hidden" class="btn" /></div>
            </span>
        </h3>
    </div>
    <div id="pagemessagebox" tabindex="0"></div>

            <div class="list-group">
                <div class="row">
                 <?php

                        $sql = 'SELECT disaster_declare.NICKNAME, disaster_declare.STARTED, disaster_declare.ID, disaster_type.COLOR, disaster_type.NAME AS DISASTERNAME 
                                FROM disaster_declare, disaster_type 
                               
                                WHERE  
                                disaster_type.ID = disaster_declare.DISASTER
                                AND disaster_declare.ACCEPTED = 0
                                AND disaster_declare.ISVERIFIED = 1
                                ORDER BY disaster_declare.STARTED DESC';
                        $result = $db->connection->query($sql);
                        $count = mysqli_num_rows($result);
                        while($row = $result->fetch_assoc()) {

                            $result_NICKNAME = htmlspecialchars($row['NICKNAME']);
                            $result_STARTED = htmlspecialchars($row['STARTED']);
                            $result_ID = htmlspecialchars($row['ID']);
                            $result_DISASTERNAME = htmlspecialchars($row['DISASTERNAME']);
                            $result_COLOR = $row['COLOR'];

                            ?>

                            <div class="col-lg-4">
                                <a href="confirmDisaster.php?id=<?php echo $row['ID']; ?>" class="list-group-item">
                                    <p class="list-group-item-text pull-right"><?php echo converttoformaldatetimestring($result_STARTED); ?></p>
                                    <h2 class="list-group-item-heading"><?php echo $result_NICKNAME; ?></h2>
                                    <h4><span class="glyphicon glyphicon-stop" style="color: <?php echo $result_COLOR;   ?>"></span> <?php echo $result_DISASTERNAME; ?></h4>

                                    <?php
                                    $sql2 = 'SELECT ID
                                                 FROM disaster_reports
                                                 WHERE ISVERIFIED = 0
                                                 AND DECLAREID = ' . $result_ID;
                                    $result2 = $db->connection->query($sql2);
                                    $count2 = mysqli_num_rows($result2);

                                    $sql2 = 'SELECT ID
                                                     FROM evacuation_report
                                                     WHERE ISVERIFIED = 0
                                                     AND DECLAREID = ' . $result_ID;
                                    $result2 = $db->connection->query($sql2);
                                    $count2 += mysqli_num_rows($result2);
                                    if($count2 > 0){
                                    ?>
                                    <h4><span class="label label-danger"><?php echo $count2; ?> Pending Report<?php if($count2 > 1) {echo 's';} ?></span></h4>
                                    <?php }  ?>
                                    <div style="clear: both;"></div>
                                </a>
                                <br/>
                            </div>
                            <?php
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
<script src="js/jquery.datetimepicker.full.min.js"></script>
<script src="js/jquery.form.min.js"></script>

</body>
</html>


<script>

    $(function () {
        $('[data-toggle="popover"]').popover()
    });

    $('#disasterAdd_STARTED').datetimepicker({
        mask:'9999/19/39 29:59'
    });

    $('#disasterAdd_ENDED').datetimepicker({
        mask:'9999/19/39 29:59'
    });

    var DAForm = {
        id: document.getElementById('disasterAddForm'),
        disaster: document.getElementById('disasterAdd_DISASTER'),
        nickname: document.getElementById('disasterAdd_NICKNAME'),
        started: document.getElementById('disasterAdd_STARTED'),
        ended: document.getElementById('disasterAdd_ENDED'),
        comment: document.getElementById('disasterAdd_COMMENT'),
        barangay: document.getElementById('disasterAdd_BARANGAY'),
        city: document.getElementById('disasterAdd_City'),
        submit: 'disasterAdd_SUBMIT',
        msgbox: 'disasterAdd_msgbox'
    };

    DAForm.id.onsubmit = function (e) {
        e.preventDefault();
        DAForm.nickname.value = DAForm.nickname.value.trim();

        if(isWhitespace(DAForm.disaster.value))
        {
            createmessagein(3, 'Please select a disaster.', DAForm.msgbox);
            return false;
        }
        else if(isWhitespace(DAForm.nickname.value))
        {
            createmessagein(3, 'Nickname cannot be whitespace.', DAForm.msgbox);
            return false;
        }
        else if(haswrongspaces(DAForm.nickname.value))
        {
            createmessagein(3, 'Please Check your nickname. Double space or more is not allowed.', DAForm.msgbox);
            return false;
        }
        else if(isbelow(2, DAForm.nickname.value))
        {
            createmessagein(3, 'Nickname must be at least 2 characters.', DAForm.msgbox);
            return false;
        }
        else if(isWhitespace(DAForm.started.value))
        {
            createmessagein(3, 'Please enter the date and time when the disaster happened', DAForm.msgbox);
            return false;
        }
        else if(isWhitespace(DAForm.ended.value))
        {
            createmessagein(3, 'Please enter the date and time when the disaster ended', DAForm.msgbox);
            return false;
        }


        if (confirm('Please confirm the form to submit: \n' +
            '\nDisaster: ' + document.getElementById('disaster_type_' + DAForm.disaster.value).innerHTML +
            '\nNickname: ' + DAForm.nickname.value +
            '\nDate Started: ' + DAForm.started.value+
            '\nDate Ended: ' + DAForm.ended.value))
        {

        }
        else
        {
            return false;
        }

        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $(DAForm.submit).button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                $(DAForm.submit).button('reset');
                var server_message = data.trim();
                if(server_message == 'success')
                {
                    createmessage(1, 'You have successfully submitted the disaster.', false);
                    location.reload();
                    DAForm.id.reset();

                }
                else if(server_message == 'error')
                {
                    createmessagein(3, 'There is a problem with submitting your report.', DAForm.msgbox);
                }
                else
                {
                    createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', DAForm.msgbox);
                }
            }
        });
    };
</script>
