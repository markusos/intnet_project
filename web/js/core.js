
var currentFilter;
var currentFilterId;

function setFilter(filter, id){
	currentFilter = filter;
	currentFilterId = id;
	getMessages(currentFilter, currentFilterId);
	$( "#menueDiv li[class~='active']" ).removeClass( "active" );
	$( "#menueDiv li a[onclick~='setFilter('" + filter + "')']" ).parent().addClass( "active" );
}

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
		if (data.status == -1) {
			$( "#alert" ).empty().append( data.message );
			$( "#alert" ).show();
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
	var $form = $( this );
	var posting = $.post("api/logout.php");

	posting.done(function( response ) {
		var data = jQuery.parseJSON(response);
		if (data.status == -1) {
			$( "#alert" ).empty().append( data.message );
			$( "#alert" ).show();
		}
		else {
			loggedOut();
		}
	});
});

$( "#createAccountButton" ).click(function( event ) {
	$( "#feed" ).hide();
	$( "#info" ).hide();
	$( "#alert" ).hide();
	$( "#createAccount" ).show();
});

function loggedIn() {
	$( "#feed" ).show();
	$( "#info" ).hide();
	$( "#login" ).hide();
	$( "#logout" ).show();
	$( "#createAccount" ).hide();
	$( "#alert" ).hide();
}

function loggedOut() {
	$( "#feed" ).hide();
	$( "#info" ).show();
	$( "#login" ).show();
	$( "#logout" ).hide();
	$( "#createAccount" ).hide();
	$( "#alert" ).hide();
}

function getMessages(filter, id)
{
	$.get( "api/getMessages.php", { filter: filter, id: id } ).done(function( response ) {
		var data = jQuery.parseJSON(response);
		if (data.status == 1) {
			$( "#messagesDiv" ).empty();

			$( "#messagesDiv" ).append("<h1>" + data.title +"</h1>");
			if (data.notFollowed == true) {
				$( "#messagesDiv" ).append("<p><button type='button' class='btn btn-primary' onclick='follow(" + id + ")'>Follow</button></p>");
			}

			var count = 0
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

$( "#createAccountForm" ).submit(function( event ) {
	event.preventDefault();
	var $form = $( this );

	var $form = $( this ),
	user = $form.find( "input[id='inputUsername']" ).val(),
	name = $form.find( "input[id='inputName']" ).val(),
	password = $form.find( "input[id='inputPassword']" ).val();
	password2 = $form.find( "input[id='inputPassword2']" ).val();

	var posting = $.post("api/createAccount.php", { user: user, name: name, password: password, password2: password2 } );

	posting.done(function( response ) {
		var data = jQuery.parseJSON(response);
		if (data.status == -1) {
			$( "#alert" ).empty().append( data.message );
			$( "#alert" ).show();
		}
		else {
			loggedOut();
		}
	});
});

function postComment(id, comment)
{
	var posting = $.post("api/createComment.php", { messageID: id, text: comment } );

	posting.done(function( response ) {
		console.log("Post Comment: " + response)
		var data = jQuery.parseJSON(response);
		if (data.status == -1) {
			$( "#alert" ).empty().append( data.message );
			$( "#alert" ).show();
		}
		else {
			getMessages(currentFilter, currentFilterId);
		}
	});
}

function postMessage(message)
{
	var posting = $.post("api/createMessage.php", { text: message } );

	posting.done(function( response ) {
		console.log("Post Comment: " + response)
		var data = jQuery.parseJSON(response);
		if (data.status == -1) {
			$( "#alert" ).empty().append( data.message );
			$( "#alert" ).show();
		}
		else {
			messageText.value = "";
			setFilter("self");
		}
	});
}

function follow(id)
{
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			//document.getElementById("usersDiv").innerHTML=xmlhttp.responseText;
		}
	}

	xmlhttp.open("POST","api/follow.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("follow="+ id);

	getFollowers();
	getMessages(currentFilter, currentFilterId);

}

function getFollowers(id)
{
	$.get( "api/getFollowers.php", { id: id } )
	.done(function( response ) {
		var data = jQuery.parseJSON(response);
		if (data.status == 1) {
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
