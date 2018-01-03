 <?php

    //session variables
    $session_USER_FIRSTNAME = htmlspecialchars($_SESSION['USER_FIRSTNAME']);
    $session_USER_MIDDLENAME = htmlspecialchars($_SESSION['USER_MIDDLENAME']);
    $session_USER_LASTNAME = htmlspecialchars($_SESSION['USER_LASTNAME']);

   
    // QUERY TO IDENTIFY ADDRESS
    $sql = '
            SELECT  
                    barangay.NAME AS BRGYNAME,
                    district.NAME AS DISTRICTNAME,
                    city.NAME AS CITYNAME
            FROM 
                    barangay, 
                    district, 
                    city
            WHERE   
                    barangay.ID = ' . $_SESSION['USER_BRGY'] . '
            AND 
                    barangay.DISTRICT = district.ID
            AND 
                    district.CITY = city.ID
            ';
    $result = $db->connection->query($sql);
    $count = mysqli_num_rows($result);
    $row = $result->fetch_assoc();

    //variables for the address
    $result_BRGYNAME = htmlspecialchars($row['BRGYNAME']);
    $result_DISTRICTNAME = htmlspecialchars($row['DISTRICTNAME']);
    $result_CITYNAME = htmlspecialchars($row['CITYNAME']);
   
  ?>

<div class="modal fade" id="dashboardmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo htmlspecialchars($username); ?>  <small> | Dashboard</small></h4>
      </div>

      <div class="modal-body">
        <div id="modalmessagebox" tabindex="0"></div>

            <div class = "col-lg-12">
              <center><h5>Account Panel</h5></center>
              <hr>
            </div>

            <div class = "row">

                <div class = "col-lg-4">
                  <div class="media dashboard_item">
                    <a href="AccountUpdate.php" class="thumbnail">
                      <div class="media-left">
                        <img class="media-object img-circle" src="img/offcial/dashboard/account.jpg" alt="..." width="50">
                      </div>
                      <div class="media-body">
                        <div class="centerize1" style="height: 50px;">
                          <div class="centerize2">
                            <h5 class="media-heading"> Account</h5>
                        </div>
                    </div>
                  </div>
                </a>
                </div>
                </div>

                <div class = "col-lg-4">
                  <div class="media dashboard_item">
                    <a href="#" class="thumbnail">
                      <div class="media-left">
                        <img class="media-object img-circle" src="img/offcial/dashboard/account.jpg" alt="..." width="50">
                      </div>
                      <div class="media-body">
                        <div class="centerize1" style="height: 50px;">
                          <div class="centerize2">
                            <h5 class="media-heading"> Settings</h5>
                        </div>
                    </div>
                  </div>
                </a>
                </div>
                </div>

                <div class = "col-lg-4">
                  <div class="media dashboard_item">
                    <a href="#" class="thumbnail">
                      <div class="media-left">
                        <img class="media-object img-circle" src="img/offcial/dashboard/account.jpg" alt="..." width="50">
                      </div>
                      <div class="media-body">
                        <div class="centerize1" style="height: 50px;">
                          <div class="centerize2">
                            <h5 class="media-heading"> Preference</h5>
                        </div>
                    </div>
                  </div>
                </a>
                </div>
                </div>

            </div>  


             <div class = "col-lg-12">
              <center><h5>Barangay Panel</h5></center>
              <hr>
            </div>

              <div class = "row">
          
                  <?php
                      $sql = "SELECT ID FROM disaster_declare WHERE BRGY = " . $_SESSION['USER_BRGY'] . " AND ENDED IS NULL";
                      $result = $db->connection->query($sql);
                      $count = mysqli_num_rows($result);
                      if($count > 0) {
                          ?>
                <div class = "col-lg-4">

                          <div class="media dashboard_item">
                              <a href="BrgyOnGoingDisasterList.php" class="thumbnail">
                                  <div class="media-left">
                                      <div class="centerize1" style="height: 50px; width: 50px; text-align: center;">
                                          <div class="centerize2" id="panel_ongoing_disaster"
                                               style="border-radius: 50px;">
                                              <h2 class="media-heading"><?php echo $count; ?></h2>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="media-body">
                                      <div class="centerize1" style="height: 50px;">
                                          <div class="centerize2">
                                              <h5 class="media-heading">Manage On-Going Disaster</h5>
                                          </div>
                                      </div>
                                  </div>
                              </a>
                          </div>
                          </div>
                  <?php
                      }
                  ?>

          <div class = "col-lg-4">
                  <div class="media dashboard_item">
                  <a href="BrgyDeclareDisaster.php" class="thumbnail">
                    <div class="media-left">
                      <img class="media-object img-circle" src="img/offcial/dashboard/disaster.jpg" alt="..." width="50">
                    </div>
                    <div class="media-body">
                      <div class="centerize1" style="height: 50px;">
                        <div class="centerize2">
                           <h5 class="media-heading"> Declare a Disaster Happened</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
            </div>

              <div class = "col-lg-4">
                  <div class="media dashboard_item">
                  <a href="BrgyPreviousDisasterList.php" class="thumbnail">
                    <div class="media-left">
                      <img class="media-object img-circle" src="img/offcial/dashboard/disasterreport.jpg" alt="..." width="50">
                    </div>
                    <div class="media-body">
                      <div class="centerize1" style="height: 50px;">
                        <div class="centerize2">
                           <h5 class="media-heading">Review Previous Disasters</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
            </div>

            </div>


                 
            
          
          </div>


          <div class="modal-footer">
          
          <span class = "row">
            <span class = "pull-left">
              <h6> Welcome, <strong><?php echo $session_USER_FIRSTNAME . ' '. $session_USER_MIDDLENAME. ' ' . $session_USER_LASTNAME;?> </strong><br>
              <small class = "pull-left" style = "font-size: 12px; padding-top: 5px;" > <?php echo $result_BRGYNAME . ' ' . $result_DISTRICTNAME . ', '. $result_CITYNAME; ?> </small></h6>
            </span>

            <span class = "pull-right">
              <form action="logout.php">
                <input type="submit" data-loading-text="Logging out..." class="btn btn-default" value="Log-out" />
              </form>
            </span>
          
          </span>

          </div>


        </div>
      </div>
  </div>
     

