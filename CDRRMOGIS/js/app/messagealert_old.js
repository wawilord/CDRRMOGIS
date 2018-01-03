  function clearallmessagebox()
  {
    $(".alertbox").alert('close');
  }

  $('#myModal').on('hidden.bs.modal', function (e) {
    clearallmessagebox();
  });

  $('.modal').on('hidden.bs.modal', function (e) {
    clearallmessagebox();
  });

  function createmessage(messagetype, messagetext, inmodal)
  {
    var inputs = document.getElementsByClassName('form-control');
    for(var i = 0; i < inputs.length; i++){
      inputs[i].onchange = function () {
        clearallmessagebox();
      };

      inputs[i].onkeypress = function () {
        clearallmessagebox();
      };

      inputs[i].onkeydown = function () {
        clearallmessagebox();
      };
    }
    
    var msgtype = "info";
    switch(messagetype)
    {
      case 1:
        msgtype = "success";
        break;
      case 2:
        msgtype = "warning";
        break;
      case 3:
        msgtype = "danger";
        break;
      case 4:
        msgtype = "info";
        break;
      default:
        break;
    }
    var themessage = '<div class="alert alert-' + msgtype + ' alertbox alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> ' + messagetext + '</div>';
    if(inmodal)
    {
      document.getElementById('modalmessagebox').innerHTML = themessage;
      document.getElementById('modalmessagebox').focus();
    }
    else
    {
      document.getElementById('pagemessagebox').innerHTML = themessage;
      document.getElementById('pagemessagebox').focus();
    }
  }

  function createmessagein(messagetype, messagetext, componentid)
  {
    var inputs = document.getElementsByClassName('form-control');
    for(var i = 0; i < inputs.length; i++){
      inputs[i].onchange = function () {
        clearallmessagebox();
      };

      inputs[i].onkeypress = function () {
        clearallmessagebox();
      };

      inputs[i].onkeydown = function () {
        clearallmessagebox();
      };
    }

    var msgtype = "info";
    switch(messagetype)
    {
      case 1:
        msgtype = "success";
        break;
      case 2:
        msgtype = "warning";
        break;
      case 3:
        msgtype = "danger";
        break;
      case 4:
        msgtype = "info";
        break;
      default:
        break;
    }
    var themessage = '<div class="alert alert-' + msgtype + ' alertbox alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> ' + messagetext + '</div>';
    document.getElementById(componentid).innerHTML = themessage;
    document.getElementById(componentid).focus();
  }