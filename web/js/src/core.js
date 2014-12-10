// Core File, handles triggers

var currentFilter;
var currentFilterId;

$( document ).ready(function() {
  getMessages(currentFilter, currentFilterId);
  getFollowers();
});

$( "#login" ).submit(function( event ) {
  event.preventDefault();

  var $form = $( this ),
    user = $form.find( "input[name='user']" ).val(),
    password = $form.find( "input[name='password']" ).val();

  var posting = $.post("api/newSession.php", { user: user, password: password } );

  posting.done(function( response ) {
    console.log("Login: " + response);
    var data = jQuery.parseJSON(response);
    if (data.status === -1) {
      showAlert(data.message);
    }
    else {
      $form.find( "input[name='user']" ).val("");
      $form.find( "input[name='password']" ).val("");
      getMessages(currentFilter, currentFilterId);
      getFollowers();
    }
  });
});

$( "#logout" ).submit(function( event ) {
  event.preventDefault();
  var posting = $.post("api/logout.php");

  posting.done(function( response ) {
    var data = jQuery.parseJSON(response);
    if (data.status === -1) {
      showAlert(data.message);
    }
    else {
      loggedOut();
    }
  });
});

$( "#createAccountForm" ).submit(function( event ) {
  event.preventDefault();

  var $form = $( this ),
    user = $form.find( "input[id='inputUsername']" ).val(),
    name = $form.find( "input[id='inputName']" ).val(),
    password = $form.find( "input[id='inputPassword']" ).val(),
    password2 = $form.find( "input[id='inputPassword2']" ).val();

  var posting = $.post("api/createAccount.php", { user: user, name: name, password: password, password2: password2 } );

  posting.done(function( response ) {
    console.log(response);
    var data = jQuery.parseJSON(response);
    if (data.status === -1) {
      showAlert(data.message);
    }
    else {
      user.value = "";
      name.value = "";
      password.value = "";
      password2.value = "";
      loggedOut();
      showSuccess(data.message);
    }
  });
});
