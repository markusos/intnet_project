// Handles the pages state

$( "#createAccountButton" ).click(function() {
  changeState('newAccount');
});

function loggedIn() {
  changeState('login');
}

function loggedOut() {
  changeState('logout');
}

function changeState(state) {
  switch(state) {
    case 'login':
      hideAll();
      $( "#feed" ).show();
      $( "#logout" ).show();
    break;
    case 'logout':
      hideAll();
      $( "#info" ).show();
      $( "#login" ).show();
      break;
    case 'newAccount':
      hideAll();
      $( "#login" ).show();
      $( "#createAccount" ).show();
      break;
    }
}

function hideAll() {
  $( "#info" ).hide();
  $( "#login" ).hide();
  $( "#feed" ).hide();
  $( "#logout" ).hide();
  $( "#createAccount" ).hide();
  $( "#alert" ).hide();
}
