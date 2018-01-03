<!-- Modal -->
<div style="display: none;">
  <iframe id="login_iframe" name="login_iframe" src=""></iframe>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <script>
    function showpassword(input_fld, indicator_btn)
    {
      var pass_fld = document.getElementById(input_fld);
      var pass_ind = document.getElementById(indicator_btn);

      if(pass_fld.type == "password")
      {
        pass_fld.type = "text";
        pass_ind.innerHTML = "Hide";
      }
      else
      {
        pass_fld.type = "password";
        pass_ind.innerHTML = "Show";
      }
    }
  </script>
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <form action="library/form/loginForm.php" target="login_iframe" method="post" id="login_form"> <!------------------------FORM START-------------------------->

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-log-in"></span> Login</h4>
      </div>
      <div class="modal-body">
        <div id="modalmessagebox" tabindex="0"></div>
        <p style="color: #8c8c8c;"><small>Note: Only a limited amount of Users are allowed for access.</small></p>
        <div class="input-group">
          <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span> </span>
          <input type="text" id="login_form_username_input" onKeyDown="clearallmessagebox();" class="form-control" name="USERNAME" placeholder="Username" aria-describedby="basic-addon1" required />
        </div>
        <br />
        <div class="input-group">
          <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span> </span>
          <input type="password" id="login_form_password_input" onKeyDown="clearallmessagebox();" class="form-control" name="PASSWORD" placeholder="Password" aria-describedby="basic-addon2" required />
          <a href="#" onclick="showpassword('login_form_password_input', 'login_modal_show_toggle'); return false;" id="login_modal_show_toggle" style="color: #3c3c3c;" class="input-group-addon">Show</a>
        </div>
      </div>
      <div class="modal-footer">
        <input type="submit" id="login_form_submit_btn" data-loading-text="Logging in..." class="btn btn-default" value="Login" />
      </div>

      </form><!------------------------FORM END-------------------------->

    </div>
  </div>
</div>
<!--modal end-->