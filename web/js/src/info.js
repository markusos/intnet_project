// Handles displaying of info and error messages 

function showSuccess(message){
  $( "#success" ).empty().append( message );
  $( "#success" ).fadeIn();

  // Hide after 2 sec
  setTimeout(function(){$( "#success" ).fadeOut();},2000);
}

function showAlert(message){
  $( "#alert" ).empty().append( message );
  $( "#alert" ).fadeIn();
}
