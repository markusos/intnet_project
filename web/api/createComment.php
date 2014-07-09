<?php
include_once 'checkAuth.php';
include_once 'utility.php';

if (!isset($_POST['messageID'])) {
	echo json_encode(array("status" => -1, "message" => "Unspecified Message ID"));
	die();
}
if (!isset($_POST['text'])) {
	echo json_encode(array("status" => -1, "message" => "Unspecified Message Text"));
	die();
}

$userID = $_SESSION['userID'];
$messageID = $_POST['messageID'];
$messageText = utf8_decode($_POST['text']);

try {
	$sqlString = 'INSERT INTO comment (messageID, userID, text) VALUES (?, ?, ?);';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($messageID, $userID, $messageText));

	echo json_encode(array("status" => 1, "message" => "Added comment:" . $messageText));
}
catch(PDOException $e)
{
	logToFile($e->getMessage());
}

closeDB();

?>
