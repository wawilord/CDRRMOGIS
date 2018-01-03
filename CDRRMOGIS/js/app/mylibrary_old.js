  function isWhitespace(text){
    return (text.trim().length == 0);
  }

  function hasSpace(text) {
    return (text.split(' ').length > 1);
  }

  function hasvalueof(text, gg) {
    return (text.split(gg).length > 1);
  }

  function pausecomp(millis)
  {
    var date = new Date();
    var curDate = null;

    do { curDate = new Date(); }
    while(curDate-date < millis);
  }

  function pohibitClosing(id, arguu)
  {
    $('#' + id).on('hide.bs.modal', function (e) {
      return arguu;
    });
  }

  function haswrongspaces(txt) {
    var z = false;
    var x = txt.split(' ');
    var y = x.length;
    for(var i = 0; i < y; i++ )
    {
      if(x[i].length < 1)
      {
        z = true;
      }
    }
    return z;
  }

  function invalidNaming(txt) {
    return (haswrongspaces(txt) || isWhitespace(txt));
  }

  function isbelow(num, txt) {
    return txt.length < num;
  }

  function setSpaceToZero(the_input) {
    if(isWhitespace(the_input.value))
    {
      the_input.value = 0;
    }
  }

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

  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }