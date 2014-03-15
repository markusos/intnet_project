<?php

include 'utility.php';

session_start();

//Check if already loged in
$db = getDB();
if(isset($_SESSION['id']) && checkSessionID($db, $_SESSION['id']) != -1){

}	
else{
	// redirect to login.php
	header("Location: login.php");
}

?>

<html>
<head>
<script src="api/getMessages.js"></script>
<link rel="stylesheet" type="text/css" href="css/stream.css" />
</head>

<?php

$filter = "'user&id=".$_GET['id'] . "'";

//echo $filter;

echo "<body onload=getMessages("	.$filter.	")>";

echo "<form name='update'><input type='button' value='Load new messages' onClick=getMessages("	.$filter.	")></form>";

echo "<form name='followButton'><input type='button' value='Follow!' onClick=follow(".$_GET['id'].")></form>";

echo "<div id='messagesDiv'><b>Loading...</b></div>";

echo "<div id='followersDiv'><b>Loading...</b></div>";
?>

</body>
</html>