<?php
include_once 'checkAuth.php';
include_once 'utility.php';

	$userID = $_SESSION['userID'];

	$sqlString = 'INSERT INTO message (userID, text) VALUES (?, ?);';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($userID, utf8_decode($_POST['text'])));

	echo json_encode(array("status" => 1, "message" => "Added message:" . $_POST['text']));

?>
