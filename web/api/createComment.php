<?php
include_once 'checkAuth.php';
include_once 'utility.php';

	$userID = $_SESSION['userID'];

	$sqlString = 'INSERT INTO comment (messageID, userID, text) VALUES (?, ?, ?);';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($_POST['messageID'], $userID, utf8_decode($_POST['text'])));

	echo json_encode(array("status" => 1, "message" => "Added comment:" . $_POST['text']));
?>
