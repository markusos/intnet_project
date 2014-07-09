<?php
include_once 'checkAuth.php';
include_once 'utility.php';

if (!isset($_POST['text'])) {
	echo json_encode(array("status" => -1, "message" => "Unspecified Message Text"));
	die();
}

$userID = $_SESSION['userID'];
$message = utf8_decode($_POST['text']);

try {
	$sqlString = 'INSERT INTO message (userID, text) VALUES (?, ?);';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($userID, $message));

	echo json_encode(array("status" => 1, "message" => "Added message:" . $message));
}
catch(PDOException $e)
{
	logToFile($e->getMessage());
}

closeDB();

?>
