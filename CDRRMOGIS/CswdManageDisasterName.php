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
<!--Modal-->
<div class="modal fade" id="AddGroupModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Report Name</h4>
            </div>
            <form id="AddDisasterNameForm" method="post" action="library/form/CswdAddNewDisasterName.php">
                <div class="modal-body">
                    <div id="AddDisasterNameForm_msgbox" tabindex="0"></div>
                    <div class="input-group">
                        <span class="input-group-addon">Name</span>
                        <input type="text" name="name" class="form-control" placeholder="Name" required />
                    </div>
                    <br />
                    <div class="input-group">
                        <span class="input-group-addon">Disaster Type</span>
                        <select class="form-control" name="disaster">
                            <?php
                            $sql = "SELECT  * 
                                    FROM    disaster_type";
                            $result = $db->connection->query($sql);
                            $count = mysqli_num_rows($result);
                            while($row = $result->fetch_assoc()){
                                ?>
                                <option value="<?php echo $row['ID']; ?>"><?php echo $row['NAME']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <br />
                    <div class="input-group">
                        <span class="input-group-addon">Date Start</span>
                        <input id="DateStartInput" name="time" type="text" class="form-control" pattern="[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]" placeholder="Date Start" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="" data-loading-text="Adding" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--NAVBAR HERE-->
<?php include('library/html/navbar.php'); ?>
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1>Report Management</h1>
    </div>

    <div class="input-group">
        <input type="text" class="form-control" aria-label="...">
        <div class="input-group-btn">
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Search</button>
            <button class="btn btn-primary" data-toggle="modal" data-target="#AddGroupModal">
                <span class="glyphicon glyphicon-plus"></span> Add new Report Name
            </button>
        </div>
    </div>
    <br />

    <div class="col-lg-7">
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

    $('#DateStartInput').datetimepicker({
        format:'Y-m-d',
        mask:true,
        timepicker: false
    });
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
        '<a href="CswdManageDisasterNameView.php?id=' + id + '" class="list-group-item">' +
        '   <h3>[' + type + '] ' + name + '</h3>' +
        '   <p>' + date + ' ⚫ [Includes ' + count + ' Declared Disaster' + putS(count) + ']</p>' +
        '</a>';
    }

    function AddNewGroupName2(id, type, name, date, count) {
        page.group_box.innerHTML = '' +
            '<a href="CswdManageDisasterNameView.php?id=' + id + '" class="list-group-item">' +
            '   <h3>[' + type + '] ' + name + '</h3>' +
            '   <p>' + date + ' ⚫ [Includes ' + count + ' Declared Disaster' + putS(count) + ']</p>' +
            '</a>' + page.group_box.innerHTML;
    }

    document.getElementById('AddDisasterNameForm').onsubmit = function (e) {
        e.preventDefault();

        $(this).ajaxSubmit({
            beforeSend:function()
            {
                //$('#SearchBtn').button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                DisplayMsg(data, 'AddDisasterNameForm_msgbox', function (SuccessMsg) {
                    var obj = JSON.parse(SuccessMsg);
                    AddNewGroupName2(obj.ID, obj.TYPE, obj.NAME, obj.DATE, obj.COUNT);
                    createmessagein(1, 'Report Name added successfully.', 'AddDisasterNameForm_msgbox');
                    document.getElementById('AddDisasterNameForm').reset();
                });
            }
        });
    };


    <?php
        $sql = "SELECT disaster_profile.*, disaster_type.NAME AS TYPENAME
                FROM disaster_profile, disaster_type
                WHERE disaster_profile.TYPE = disaster_type.ID
                GROUP BY disaster_profile.ID
                ORDER BY disaster_profile.ID DESC";
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
		if($count > 0) {
			while($row = $result->fetch_assoc()){
				$sql2 = "SELECT ID
							FROM disaster_declarelist
							WHERE PROFILEID	= " . $row['ID'];
				$result2 = $db->connection->query($sql2);
				$count = mysqli_num_rows($result2);
				?>
					AddNewGroupName('<?php echo $row["ID"]; ?>', '<?php echo $row["TYPENAME"]; ?>', '<?php echo $row["NAME"]; ?>', '<?php echo getdatestring($row["DATESTART"]); ?>', '<?php echo $count; ?>');
				<?php
			}
		}
    ?>


</script>

</body>
</html>



