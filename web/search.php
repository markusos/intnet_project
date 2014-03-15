<?php

include 'api/utility.php';

session_start();

//Check if already loged in
$db = getDB();
if(isset($_SESSION['id']) && checkSessionID($db, $_SESSION['id']) != -1){
	//echo "Welcome " . htmlentities($_SESSION['user']) . "!";
	//get message stream
}	
else{
	// redirect to login.php
	header("Location: login.php");
	return;
}

?>

<html>
<head>
<script type="text/javascript">

function searchFor(username)
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
			document.getElementById("usersDiv").innerHTML=xmlhttp.responseText;
		}
	}

	xmlhttp.open("POST","api/search.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("username="+ username);
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
	
}

</script>
</head>
<body>

<form name="search">
<input type="text" value="" id="searchText" />
<input type="button" value="Search!" onClick="searchFor(searchText.value)" />
</form>

<div id="usersDiv"><b>...</b></div>
</body>
</html>