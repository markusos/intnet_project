// Handles followers

function follow(id)
{
  var posting = $.post("api/follow.php", { follow: id } );

  posting.done(function( response ) {
    console.log("Follow id: " + response);
    var data = jQuery.parseJSON(response);
    if (data.status === -1) {
      showAlert(data.message);
    }
    else {
      getFollowers();
      getMessages(currentFilter, currentFilterId);
    }
  });
}

function getFollowers(id)
{
  $.get( "api/getFollowers.php", { id: id } )
  .done(function( response ) {
    var data = jQuery.parseJSON(response);
    if (data.status === 1) {
      $( "#followersDiv" ).empty();

      $( "#followersDiv" ).append("<h3>Follows</h3>");
      data.follows.forEach(function(entry) {
        $( "#followersDiv" ).append("<a onclick=setFilter('user'," + entry.id + ")>" + entry.name +"</a><br />");
      });

      $( "#followersDiv" ).append("<h3>Followers</h3>");
      data.follower.forEach(function(entry) {
        $( "#followersDiv" ).append("<a onclick=setFilter('user'," + entry.id + ")>" + entry.name +"</a><br />");
      });
      loggedIn();
    }
    else {
      loggedOut();
    }
  });
}
