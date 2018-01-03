

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
              <center><h5>Admin Panel</h5></center>
              <hr>
            </div>

            <div class = "row">
          
          <div class = "col-lg-4">
                  <div class="media dashboard_item">
                  <a href="AccountManagement.php" class="thumbnail">
                    <div class="media-left">
                      <img class="media-object img-circle" src="img/offcial/dashboard/account.jpg" alt="..." width="50">
                    </div>
                    <div class="media-body">
                      <div class="centerize1" style="height: 50px;">
                        <div class="centerize2">
                           <h5 class="media-heading"> Manage Account</h5>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
            </div>

            

          <div class = "col-lg-4">
            <div class="media dashboard_item">
                <a href="DisasterTypeManage.php" class="thumbnail">
                <div class="media-left">
                  <img class="media-object img-circle" src="img/offcial/dashboard/disaster.jpg" alt="..." width="50">
                </div>
                <div class="media-body">
                  <div class="centerize1" style="height: 50px;">
                    <div class="centerize2">
                       <h5 class="media-heading"> Manage Disaster Types</h5>
                    </div>
                  </div>
                </div>
                </a>
             </div>
            </div>
               
               <div class = "col-lg-4">
              <div class="media dashboard_item">
                <a href="BarangayManage.php" class="thumbnail">
                  <div class="media-left">
                    <img class="media-object img-circle" src="img/offcial/dashboard/disasterreport.jpg" alt="..." width="50">
                    </div>
                    <div class="media-body">
                    <div class="centerize1" style="height: 50px;">
                    <div class="centerize2">
                      <h5 class="media-heading">Manage Barangays</h5>
                    </div>
                  </div>
                  </div>
                </a>
              </div>
            </div>

             <div class = "col-lg-4">
              <div class="media dashboard_item">
                <a href="barangayManagement.php" class="thumbnail">
                  <div class="media-left">
                    <img class="media-object img-circle" src="img/offcial/dashboard/disasterreport.jpg" alt="..." width="50">
                    </div>
                    <div class="media-body">
                    <div class="centerize1" style="height: 50px;">
                    <div class="centerize2">
                      <h5 class="media-heading">View Barangay List</h5>
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
                    <img class="media-object img-circle" src="img/offcial/dashboard/updatedata.jpg" alt="..." width="50">
                    </div>
                    <div class="media-body">
                    <div class="centerize1" style="height: 50px;">
                    <div class="centerize2">
                      <h5 class="media-heading">System Activity</h5>
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
              <h6> Welcome, <strong><?php echo $session_USER_FIRSTNAME . ' '. $session_USER_MIDDLENAME. ' ' . $session_USER_LASTNAME;?> </strong></h6>
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
          
     

