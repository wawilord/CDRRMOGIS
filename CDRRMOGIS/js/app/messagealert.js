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

  function GetServerMsg(lettr){
    var patt = /msg:/;
    var message = "";
    var letter = lettr.trim();
    try {
      if(patt.test(letter.substring(0,4)))
      {
        message = letter.substring(4, letter.length);
      }
    }
    catch(err) {

    }
    message = message.trim();
    return message;
  }

  function GetSuccessMsg(lettr){
    var patt = /success:/;
    var message = "";
    var letter = lettr.trim();
    try {
      if(patt.test(letter.substring(0,8)))
      {
        message = letter.substring(8, letter.length);
      }
    }
    catch(err) {

    }
    message = message.trim();
    return message;
  }

  function GetErrorMsg(lettr){
    var patt = /error:/;
    var message = "";
    var letter = lettr.trim();
    try {
      if(patt.test(letter.substring(0,6)))
      {
        message = letter.substring(6, letter.length);
      }
    }
    catch(err) {

    }
    message = message.trim();
    return message;
  }

  function GetWarningMsg(lettr){
    var patt = /warning:/;
    var message = "";
    var letter = lettr.trim();
    try {
      if(patt.test(letter.substring(0,8)))
      {
        message = letter.substring(8, letter.length);
      }
    }
    catch(err) {

    }
    message = message.trim();
    return message;
  }

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

  function DisplayMsg(data, msgbox, myaction) {
    var server_message = data.trim();
    if(!isWhitespace(GetSuccessMsg(server_message)))
    {
      createmessagein(1, GetSuccessMsg(server_message), msgbox);
      myaction(GetSuccessMsg(server_message));
    }
    else if(!isWhitespace(GetWarningMsg(server_message)))
    {
      createmessagein(2, GetWarningMsg(server_message), msgbox);
    }
    else if(!isWhitespace(GetErrorMsg(server_message)))
    {
      createmessagein(3, GetErrorMsg(server_message), msgbox);
    }
    else if(!isWhitespace(GetServerMsg(server_message)))
    {
      createmessagein(4, GetServerMsg(server_message), msgbox);
    }
    else
    {
      createmessagein(3, '<b>Oh Snap!</b> There is a problem with the server or your connection.', msgbox);
    }
  }