
var currentFilter;

function setFilter(filter){
	currentFilter = filter;
	getMessages(currentFilter);
}

function init(){
	getMessages();
	getFollowers();
}

function getMessages(filter)
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
			document.getElementById("messagesDiv").innerHTML=xmlhttp.responseText;
		}
	}
	if(filter==undefined) xmlhttp.open("GET","api/getMessages.php?filter=" + currentFilter,true);
	else xmlhttp.open("GET","api/getMessages.php?filter=" + filter,true);
	
	xmlhttp.send();
}

function postComment(id, comment)
{
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.open("POST","api/createComment.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded;charset=ISO-8859-1");
	xmlhttp.send("messageID="+ id +"&text=" + comment);
	
	getMessages();
}

function postMessage(message)
{
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.open("POST","api/createMessage.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded;charset=ISO-8859-1");
	xmlhttp.send("text="+ message);
	
	messageText.value = "";
	getMessages();
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
	getMessages();
	
}

function getFollowers(id)
{

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp1=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp1.onreadystatechange=function()
	{
		if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
		{
			document.getElementById("followersDiv").innerHTML=xmlhttp1.responseText;
		}
	}
	if(id==undefined){
		xmlhttp1.open("GET","api/getFollowers.php",true);
	}
	else xmlhttp1.open("GET","api/getFollowers.php?id=" + id,true);
	xmlhttp1.send();
}
