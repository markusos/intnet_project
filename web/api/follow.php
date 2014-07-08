<?php

include_once 'checkAuth.php';
include_once 'utility.php';

	if (!isset($_POST["follow"])) {
		echo json_encode(array("status" => -1, "message" => "Unspecified userID"));
		die();
	}

	$sqlString = 'INSERT INTO followers (userID, followerUserID) VALUES (?, ?);';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($_POST["follow"], checkSessionID($db, $_SESSION['id'])));

?>
