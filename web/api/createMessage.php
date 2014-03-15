<?php
session_start();

if(!isset($_SESSION['id'])){
	die('Not logged in!');
}
else{
	include 'utility.php';
	
	$db = getDB();
	$userID = checkSessionID($db, $_SESSION['id']);
	
	if($userID == -1)
		die('Not logged in!');
}

	$sqlString = 'INSERT INTO message (userID, text) VALUES (?, ?);';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($userID, utf8_decode($_POST['text'])));
	
	echo 'Added comment:<br>';
	echo $_POST['text'];

?>