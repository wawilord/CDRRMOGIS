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

    $group_id = '';
    $group_name = '';
    $group_date = '';
    $group_type = '';
    $group_typeid = '';
    $group_disaster_list = array();
    $group_date2 = '';

    if(isset($_GET["id"])){
        $group_id = $_GET["id"];
        $sql = "SELECT disaster_profile.*, disaster_type.NAME AS TYPENAME, disaster_type.ID AS TYPEID
                FROM disaster_profile, disaster_type
                WHERE disaster_profile.TYPE = disaster_type.ID
                AND disaster_profile.ID = " . $group_id . "
                GROUP BY disaster_profile.ID";
        $result = $db->connection->query($sql);
        $count = mysqli_num_rows($result);
        while($row = $result->fetch_assoc()){
            $group_name = $row['NAME'];
            $group_date = getdatestring($row['DATESTART']);
            $group_date2 = $row['DATESTART'];
            $group_type = $row['TYPENAME'];
            $group_typeid = $row['TYPEID'];
        }
    }
    else{
        PageNotAvailable();
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
<!--Modal-->
<div class="modal fade" id="EditNameModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Report Name</h4>
            </div>
            <form id="EditNameForm" method="post" action="library/form/CswdEditDisasterName.php">
                <div class="modal-body">
                    <div id="EditNameForm_msgbox" tabindex="0"></div>
                    <input type="text" name="id" style="display: none;" value="<?php echo $group_id; ?>" />
                    <div class="input-group">
                        <span class="input-group-addon">Name</span>
                        <input type="text" name="name" class="form-control" value="<?php echo $group_name; ?>" placeholder="Name" required />
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
                                <option value="<?php echo $row['ID']; ?>" <?php if($row['ID'] == $group_typeid) echo 'selected'; ?>><?php echo $row['NAME']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <br />
                    <div class="input-group">
                        <span class="input-group-addon">Date Start</span>
                        <input id="DateStartInput" name="time" value="<?php echo explode(' ', $group_date2)[0]; ?>" type="text" class="form-control" pattern="[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]" placeholder="Date Start" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="" data-loading-text="Adding" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Add Disaster Modal-->
<div class="modal fade bs-example-modal-lg" tabindex="-1" id="AddDisasterModal" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add a Declared Disaster<small><br />The added Disaster Declarations will be the sources for the report generation.</small></h4>
            </div>
            <div class="modal-body">
                <div id="SearchMsgBox" tabindex="0"></div>
                <form id="SearchForm" method="get" action="library/form/CswdSearchDisaster.php">
                    <input style="display: none;" type="text" name="GROUPID" value="<?php echo $group_id; ?>" />
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1">Barangay</span>
                                <select name="BARANGAY" class="form-control">
                                    <option selected value="">All</option>
                                    <?php
                                    $sql = "SELECT  * 
                                            FROM    barangay";
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
                        </div>

                        <div class="col-lg-6">
                            <div class="input-group">
                                <span class="input-group-addon" >Nickname</span>
                                <input name="NICKNAME" type="text" class="form-control" placeholder="Nickname">
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="input-group">
                                <span class="input-group-addon" >ID</span>
                                <input name="DISASTERID" type="number" class="form-control" placeholder="ID">
                            </div>
                        </div>

                    </div>
                    <br />
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="input-group">
                                <span class="input-group-addon" >Date Filter</span>
                                <span class="input-group-addon" >From</span>
                                <input id="FDateStartInput" name="TIMESTART" value="<?php echo explode(' ', $group_date2)[0]; ?>" type="text" class="form-control" pattern="[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]" placeholder="Date Start" required />
                                <span class="input-group-addon" >To</span>
                                <input id="FDateEndInput" name="TIMEEND" value="" type="text" class="form-control" pattern="[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]" placeholder="Date End" />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <button type="submit" id="SearchBtn" class="btn btn-primary" data-loading-text="Searching..." ><span class="glyphicon glyphicon-search"></span> Search</button>
                        </div>
                    </div>
                    <br />
                </form>

                <div class="container-fluid thumbnail" id="DDisaster_ResultBox">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="RDDisasterContainer.addAllDisaster();">Add All</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="DeleteNameModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Delete</h4>
            </div>
            <form id="DeleteNameForm" method="post" action="library/form/CswdDeleteDisasterName.php">
                <div class="modal-body">
                    <div id="DeleteNameForm_msgbox" tabindex="0"></div>
                    <input type="text" name="id" style="display: none;" value="<?php echo $group_id; ?>" />
                    <p>
                        Do you want to delete this Report name?
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
                    <button type="submit" id="DeleteNameForm_SUBMIT" class="btn btn-danger" data-loading-text="Deleting..."><span class="glyphicon glyphicon-trash"></span> Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!--NAVBAR HERE-->
<?php include('library/html/navbar.php'); ?>
<div class="container"> <!--Content starts here-->
    <div class="page-header">
        <h1>Report Management<small><br />Manage Report Generation for "<?php echo $group_name; ?>"</small></h1>
    </div>

    <br />

    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Details
                    <div class="pull-right">
                        <button class="btn btn-default" data-toggle="modal" data-target="#EditNameModal">
                            <span class="glyphicon glyphicon-edit"></span>
                            Edit
                        </button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#DeleteNameModal">
                            <span class="glyphicon glyphicon-trash"></span>
                            Delete
                        </button>
                    </div>
                    <div style="clear: both;"></div>
                </h3>
            </div>
            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td>Name: </td>
                        <td><h3><?php echo $group_name; ?></h3></td>
                    </tr>
                    <tr>
                        <td>Disaster Type: </td>
                        <td><?php echo $group_type; ?></td>
                    </tr>
                    <tr>
                        <td>Date Started: </td>
                        <td><?php echo $group_date; ?></td>
                    </tr>
                    <tr>
                        <td>Report Name ID: </td>
                        <td><?php echo $group_id; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div id="DDMsgBox" tabindex="0"></div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Involved Disaster Declarations:
                    <button class="btn btn-primary pull-right" onclick="document.getElementById('SearchResultBox').innerHTML = '';" data-toggle="modal" data-target="#AddDisasterModal">
                        <span class="glyphicon glyphicon-plus"></span>
                        Add a Disaster
                    </button>
                    <div style="clear: both;"></div>
                </h3>
            </div>
            <div class="panel-body" id="disaster_declare_list_box">

            </div>
        </div>


        <a href="CswdViewAvailableReports.php?id=<?php echo $group_id; ?>" class="btn btn-primary">View Available Status Reports</a>
    </div>
</div> <!--Content ends here-->

<div style="display: none;">
    <form id="AddForm" method="post" action="library/form/CswdAddToDeclareList.php">
        <input id="AddIdInput" type="text" name="id" />
        <input type="text" name="groupid" value="<?php echo $group_id; ?>" />
    </form>

    <form id="DeleteForm" method="post" action="library/form/CswdRemoveFromDeclareList.php">
        <input id="DeleteIdInput" type="text" name="id" />
        <input type="text" name="groupid" value="<?php echo $group_id; ?>" />
    </form>
</div>

<!--FOOTER HERE-->
<?php include('library/html/footer.php'); ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/1.11.3_jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="js/app/mylibrary.js"></script>
<script src="js/app/messagealert.js"></script>
<script src="js/jquery.form.min.js"></script>
<script src="js/jquery.datetimepicker.full.min.js"></script>
<script src="js/React.js/react.js"></script>
<script src="js/React.js/JSXTransformer.js"></script>
<script>
    $('#DateStartInput').datetimepicker({
        format:'Y-m-d',
        mask:true,
        timepicker: false
    });
    $('#FDateStartInput').datetimepicker({
        format:'Y-m-d',
        mask:true,
        timepicker: false,
        minDate: new Date('<?php echo explode(' ', $group_date2)[0]; ?>')
    }).on("change", function (e) {
        $('#FDateEndInput').datetimepicker({
            format:'Y-m-d',
            timepicker: false,
            minDate: new Date($(this).val())
        });
    });
    $('#FDateEndInput').datetimepicker({
        format:'Y-m-d',
        timepicker: false,
        minDate: new Date('<?php echo explode(' ', $group_date2)[0]; ?>')
    });

    var page = {
        ddlbox: document.getElementById('disaster_declare_list_box')
    };
    var DDisasterContainer = null;
    var RDDisasterContainer = null;


    document.getElementById('EditNameForm').onsubmit = function (e) {
        e.preventDefault();

        $(this).ajaxSubmit({
            beforeSend:function()
            {

            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                DisplayMsg(data, 'EditNameForm_msgbox', function (SuccessMsg) {
                    window.location.reload();
                });
            }
        });
    };
    document.getElementById('DeleteNameForm').onsubmit = function (e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $('#DeleteNameForm_SUBMIT').button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                $('#DeleteNameForm_SUBMIT').button('reset');
                DisplayMsg(data, 'DeleteNameForm_msgbox', function (SuccessMsg) {
                    alert('Name/Group Deleted.');
                    window.location = 'CswdManageDisasterName.php';
                });
            }
        });
    };
    document.getElementById('SearchForm').onsubmit = function (e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSend:function()
            {
                $('#SearchBtn').button('loading');
            },
            uploadProgress:function(event,position,total,percentCompelete)
            {

            },
            success:function(data)
            {
                var currState = RDDisasterContainer.state;
                currState.Disasters = [];
                RDDisasterContainer.setState(currState);

                $('#SearchBtn').button('reset');
                DisplayMsg(data, 'SearchMsgBox', function (SuccessMsg) {
                    document.getElementById('SearchMsgBox').innerHTML = '';
                    var currState = RDDisasterContainer.state;
                    currState.Disasters = JSON.parse(SuccessMsg);
                    RDDisasterContainer.setState(currState);
                });
            }
        });
    };

</script>
<script type="text/jsx">
    <?php
    $InvolvedList = array();
    $sql = "SELECT 	disaster_declare.ID,
                                        disaster_declare.NICKNAME,
                                        disaster_declare.STARTED,
                                        disaster_type.NAME AS DISASTER,
                                        barangay.NAME AS BARANGAY
                                        
                                FROM 	disaster_declare, disaster_type, barangay, disaster_declarelist
                                
                                WHERE 	disaster_declare.BRGY = barangay.ID
                                AND		disaster_declare.DISASTER = disaster_type.ID
                                AND		disaster_declare.ID = disaster_declarelist.DECLAREID
                                AND		disaster_declarelist.PROFILEID = " . $group_id . "
                                GROUP BY disaster_declare.ID";
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    while($row = $result->fetch_assoc()){
        $row['STARTED'] = converttoformaldatetimestring($row['STARTED']);
        $InvolvedList[] = $row;
    }
    ?>

    var DDisasterItem = React.createClass({
        remove: function () {
            this.props.deleteDisaster(this.props.index);
        },
        render: function () {
            return(
                <div className="col-lg-6">
                    <div className="thumbnail">
                        <div className="list-group-item">
                            <h4 className="list-group-item-heading">
                                {this.props.children}
                                <button className="btn btn-danger pull-right" disabled={this.props.DISABLEBTN} onClick={this.remove}>
                                    <span className="glyphicon glyphicon-minus"></span>
                                </button>
                            </h4>
                            <p className="list-group-item-text">Barangay: {this.props.BARANGAY}</p>
                            <p className="list-group-item-text">ID: {this.props.DISASTERID}</p>
                            <p className="list-group-item-text">Type: {this.props.DISASTER}</p>
                            <p className="list-group-item-text">Started: {this.props.STARTED}</p>
                        </div>
                    </div>
                </div>
            );
        }
    });
    var DDisasterBox = React.createClass({
        getInitialState: function () {
            DDisasterContainer = this;
            return({
                Disasters: JSON.parse('<?php echo json_encode($InvolvedList); ?>'),
                disabled: false
            });
        },
        eachDisaster: function (disaster, i) {
            return(
                <DDisasterItem DISABLEBTN={this.state.disabled} DISASTERID={disaster.ID} STARTED={disaster.STARTED} DISASTER={disaster.DISASTER} BARANGAY={disaster.BARANGAY} index={i} deleteDisaster={this.deleteDisaster}>{disaster.NICKNAME}</DDisasterItem>
            );
        },
        deleteDisaster: function (i) {
            var component = this;
            var DeleteList = [];
            DeleteList.push(this.state.Disasters[i].ID);
            document.getElementById('DeleteIdInput').value = JSON.stringify(DeleteList);

            //Delete Code here.
            $('#DeleteForm').ajaxSubmit({
                beforeSend:function()
                {
                    var state = component.state;
                    state.disabled = true;
                    component.setState(state);
                },
                success:function(data)
                {
                    var state = component.state;
                    DisplayMsg(data, 'DDMsgBox', function (SuccessMsg) {
                        state.Disasters.splice(i, 1);
                    });
                    state.disabled = false;
                    component.setState(state);
                }
            });
        },
        render: function(){
            return(
                <div className="row">
                    {this.state.Disasters.map(this.eachDisaster)}
                </div>
            );
        }
    });

    var RDDisasterItem = React.createClass({
        remove: function () {
            this.props.deleteDisaster(this.props.index);
        },
        render: function () {
            return(
                <div className="col-lg-6">
                    <div className="thumbnail">
                        <div className="list-group-item">
                            <h4 className="list-group-item-heading">
                                {this.props.children}
                                <button className="btn btn-primary pull-right" disabled={this.props.DISABLEBTN} onClick={this.remove}>
                                    <span className="glyphicon glyphicon-plus"></span>
                                </button>
                            </h4>
                            <p className="list-group-item-text">Barangay: {this.props.BARANGAY}</p>
                            <p className="list-group-item-text">ID: {this.props.DISASTERID}</p>
                            <p className="list-group-item-text">Type: {this.props.DISASTER}</p>
                            <p className="list-group-item-text">Started: {this.props.STARTED}</p>
                        </div>
                    </div>
                </div>
            );
        }
    });
    var RDDisasterBox = React.createClass({
        getInitialState: function () {
            RDDisasterContainer = this;
            return({
                Disasters: [],
                disabled: false
            });
        },
        eachDisaster: function (disaster, i) {
            return(
                <RDDisasterItem DISABLEBTN={this.state.disabled} DISASTERID={disaster.ID} STARTED={disaster.STARTED} DISASTER={disaster.DISASTER} BARANGAY={disaster.BARANGAY} index={i} deleteDisaster={this.addDisaster}>{disaster.NICKNAME}</RDDisasterItem>
            );
        },
        addDisaster: function (i) {
            var component = this;
            var AddList = [];
            AddList.push(this.state.Disasters[i].ID);
            document.getElementById('AddIdInput').value = JSON.stringify(AddList);

            $('#AddForm').ajaxSubmit({
                beforeSend:function()
                {
                    var state = component.state;
                    state.disabled = true;
                    component.setState(state);
                },
                success:function(data)
                {
                    var state = component.state;
                    DisplayMsg(data, 'SearchMsgBox', function (SuccessMsg) {
                        var DDisasterState = DDisasterContainer.state;
                        DDisasterState.Disasters.push(state.Disasters[i]);
                        DDisasterContainer.setState(DDisasterState);
                        state.Disasters.splice(i, 1);
                    });
                    state.disabled = false;
                    component.setState(state);
                }
            });
        },
        addAllDisaster: function () {
            if(this.state.Disasters.length > 0){
                var component = this;
                var AddList = [];
                this.state.Disasters.forEach(function (rdisaster) {
                    AddList.push(rdisaster.ID);
                });
                document.getElementById('AddIdInput').value = JSON.stringify(AddList);


                $('#AddForm').ajaxSubmit({
                    beforeSend:function()
                    {
                        var state = component.state;
                        state.disabled = true;
                        component.setState(state);
                    },
                    success:function(data)
                    {
                        var state = component.state;
                        DisplayMsg(data, 'SearchMsgBox', function (SuccessMsg) {
                            var DDisasterState = DDisasterContainer.state;
                            state.Disasters.forEach(function (rdisaster) {
                                DDisasterState.Disasters.push(rdisaster);
                            });
                            DDisasterContainer.setState(DDisasterState);
                            state.Disasters = [];
                        });
                        state.disabled = false;
                        component.setState(state);
                    }
                });
            }
            else{
                alert('Nothing to add. You can use search to produce results.');
            }
        },
        render: function(){
            return(
                <div className="row">
                    {this.state.Disasters.map(this.eachDisaster)}
                </div>
            );
        }
    });

    React.render(<DDisasterBox />, document.getElementById('disaster_declare_list_box'));
    React.render(<RDDisasterBox />, document.getElementById('DDisaster_ResultBox'));
</script>
<script>
    $( document ).ready(function(){
        document.getElementById('SearchBtn').click();
    });
</script>
</body>
</html>



