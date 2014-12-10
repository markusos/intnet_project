// Handles the feed

function setFilter(filter, id){
  currentFilter = filter;
  currentFilterId = id;
  getMessages(currentFilter, currentFilterId);
  $( "#menueDiv li[class~='active']" ).removeClass( "active" );
  $( "#menueDiv li a[onclick~='setFilter('" + filter + "')']" ).parent().addClass( "active" );
}

function postMessage(message)
{
  var posting = $.post("api/createMessage.php", { text: message } );

  posting.done(function( response ) {
    console.log("Post Comment: " + response);
    var data = jQuery.parseJSON(response);
    if (data.status === -1) {
      showAlert(data.message);
    }
    else {
      $( "#messageText" ).val('');
      setFilter("self");
    }
  });
}

function postComment(id, comment)
{
  var posting = $.post("api/createComment.php", { messageID: id, text: comment } );

  posting.done(function( response ) {
    console.log("Post Comment: " + response);
    var data = jQuery.parseJSON(response);
    if (data.status === -1) {
      showAlert(data.message);
    }
    else {
      getMessages(currentFilter, currentFilterId);
    }
  });
}

function getMessages(filter, id)
{
  $.get( "api/getMessages.php", { filter: filter, id: id } ).done(function( response ) {
    var data = jQuery.parseJSON(response);
    if (data.status === 1) {
      $( "#messagesDiv" ).empty();

      $( "#messagesDiv" ).append("<h1>" + data.title +"</h1>");
      if (data.notFollowed === true) {
        $( "#messagesDiv" ).append("<p><button type='button' class='btn btn-primary' onclick='follow(" + id + ")'>Follow</button></p>");
      }

      var count = 0;
      data.messages.forEach(function(message) {

        var messageHTML = "";

        messageHTML += "<div class='panel panel-primary'>";

        messageHTML += "<div class='panel-heading'>";
        messageHTML += "<div><h2><a style='color:white;' onclick=setFilter('user'," + message.userId + ")>" + message.userName + "</a></h2></div>";
        messageHTML += "<div >" + message.text + "</div>";
        messageHTML += "<div class='text-right'>" + message.timestamp + "</div>";
        messageHTML += "</div>";

        message.comments.forEach(function(comment) {
          messageHTML += "<div class='panel-body'>";
          messageHTML += "<div><h3><a onclick=setFilter('user'," + comment.userId + ")>" + comment.userName + "</a></h3></div>";
          messageHTML += "<p>" + comment.text + "</p>";
          messageHTML += "<div class='text-right'>" + comment.timestamp + "</div>";
          messageHTML += "</div><hr />";
        });

        messageHTML += "<div class='panel-footer text-right'>";
        messageHTML += "<form class='form-inline' role='form'><input type='text' class='form-control' placeholder='Comment' size='100' name='text' id='txt" + count + "' />";
        messageHTML += "<input type='hidden' name='messageID' id='mid" + count + "' value='" + message.messageID + "' />";
        messageHTML += "<button type='button' class='btn btn-primary' value='Post' onclick='postComment(" + message.messageID + ", txt"  + count + ".value)'>Post</button></form>";
        messageHTML += "</div>";

        messageHTML += "</div>";
        $( "#messagesDiv" ).append(messageHTML);
        count += 1;
      });
      loggedIn();
    }
    else {
      loggedOut();
    }
  });
}
