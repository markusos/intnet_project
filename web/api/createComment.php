<?php
session_start();

if(!isset($_SESSION['id'])){
	die('Not logged in!');
}
else{
	include 'utility.php';
	
	$db = getDB();
	$userid = checkSessionID($db, $_SESSION['id']);
	
	if($userID == -1)
		die('Not logged in!');
}
	
	$sqlString = 'INSERT INTO comment (messageID, userID, text) VALUES (?, ?, ?);';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($_POST['messageID'], $userid, utf8_decode($_POST['text'])));
	
	echo 'Added comment:<br>';
	echo $_POST['text'];
?>

