<?php

include 'api/utility.php';

session_start();

//Check if already loged in
$db = getDB();
if(isset($_SESSION['id']) && checkSessionID($db, $_SESSION['id']) != -1){
	//get message stream
}	
else{
	// redirect to login.php
	header("Location: login.php");
}

?>

<html>
<head>
<script src="js/getMessages.js"></script>
<link rel="stylesheet" type="text/css" href="css/stream.css" />
<link rel="stylesheet" type="text/css" href="css/layout.css" />
</head>
<body onload="init();">

<div id="topDiv">
<form name="newMessage">
New Message: <input type="text" value="" size="50" id="messageText" />
<input type="button" value="Post" onClick="postMessage(messageText.value)" /><br /><br />
<input type="button" value="Load new messages" onClick="getMessages();" />
</form>

</div>

<div id="menueDiv">
<b><a onclick=setFilter('self')>Profile</a></b><br />
<b><a onclick=setFilter('all')>Global Feed</a></b><br />
<b><a onclick=setFilter('follows')>Followed Feed</a></b><br />
<b><a href='logout.php'>Logout</a></b><br />
</div>
<div id="messagesDiv"><b>Loading...</b></div>
<div id="followersDiv"><b>Loading.....</b></div>

</body>
</html>

