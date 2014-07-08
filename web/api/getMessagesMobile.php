<?php
include_once 'checkAuth.php';
include_once 'utility.php';

//get messages

if (!isset($_GET['filter'])) {
	echo json_encode(array("status" => -1, "message" => "Undefined filter!"));
	die();
}

$filter = $_GET['filter'];
try {
	$db = getDB();

	if($filter == 'self'){
		$sqlString = 'SELECT messageID, name, text, timestamp, userID FROM message NATURAL JOIN users WHERE username = ? ORDER BY timestamp DEASC;';
		$statement = $db->prepare($sqlString);
		$statement->execute(array($_SESSION['user']));

		$rows = $statement->fetchAll();
		foreach ($rows as $row) {
			echo urlencode($row[0]). ";" . urlencode($row[1]) . ";" . urlencode($row[2]) . ";" . urlencode($row[3]) . ";" . urlencode($row[4]) . "\n";
		}

	}
	else if($filter == 'follows'){

		$sqlString = 'SELECT messageID, name, text, timestamp, userID FROM message NATURAL JOIN users WHERE EXISTS (SELECT * FROM followers WHERE followers.userID = message.userID AND followers.followerUserID = ?) ORDER BY timestamp DESC;';
		$statement = $db->prepare($sqlString);
		$statement->execute(array($_SESSION['userID']));

		$rows = $statement->fetchAll();
		foreach ($rows as $row) {
			echo urlencode($row[0]). ";" . urlencode($row[1]) . ";" . urlencode($row[2]) . ";" . urlencode($row[3]) . ";" . urlencode($row[4]) . "\n";
		}
	}
	else if ($filter == 'user' && isset($_GET['userID'])){

		$sqlString = 'SELECT messageID, name, text, timestamp, userID FROM message NATURAL JOIN users WHERE userID = ? ORDER BY timestamp DESC;';
		$statement = $db->prepare($sqlString);
		$statement->execute(array($_GET['userID']));

		$rows = $statement->fetchAll();
		foreach ($rows as $row) {
			echo urlencode($row[0]). ";" . urlencode($row[1]) . ";" . urlencode($row[2]) . ";" . urlencode($row[3]) . ";" . urlencode($row[4]) . "\n";
		}

	}
	else if ($filter == 'comment' && isset($_GET['messageID'])){

		$sqlString = 'SELECT commentID, messageID, name, text, timestamp, userID FROM comment NATURAL JOIN users WHERE messageID = ? ORDER BY timestamp ASC;';
		$statement = $db->prepare($sqlString);
		$statement->execute(array($_GET['messageID']));

		$rows = $statement->fetchAll();
		foreach ($rows as $row) {
			echo urlencode($row[0]). ";" . urlencode($row[1]) . ";" . urlencode($row[2]) . ";" . urlencode($row[3]) . ";" . urlencode($row[4]) . ";" . urlencode($row[5]) . "\n";
		}
	}
	else{
		$sqlString = 'SELECT messageID, name, text, timestamp, userID FROM message NATURAL JOIN users ORDER BY timestamp DESC;';
		$statement = $db->prepare($sqlString);
		$statement->execute(array());

		$rows = $statement->fetchAll();
		foreach ($rows as $row) {
			echo urlencode($row[0]). ";" . urlencode($row[1]) . ";" . urlencode($row[2]) . ";" . urlencode($row[3]) . ";" . urlencode($row[4]) . "\n";
		}
	}

	/*** close the database connection ***/
	$db = null;
}
catch(PDOException $e)
{
	echo $e->getMessage();
}


?>
