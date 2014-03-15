<?php

include 'utility.php';

session_start();
$db = getDB();
if(isset($_SESSION['id']) && checkSessionID($db, $_SESSION['id']) != -1){
	$sqlString = 'INSERT INTO followers (userID, followerUserID) VALUES (?, ?);';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($_POST["follow"], checkSessionID($db, $_SESSION['id'])));	
}	
else{
	return;
}

?>