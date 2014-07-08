<?php
include_once 'checkAuth.php';
include_once 'utility.php';

$sqlString = 'SELECT name, text, timestamp, messageID, userID FROM message NATURAL JOIN users WHERE username = ? ORDER BY timestamp DESC;';
$statement = $db->prepare($sqlString);
$statement->execute(array($_POST['username']));

$rows = $statement->fetchAll();

$count = 0;
foreach ($rows as $row) {
	if($count == 0){
		echo "<form name='followButton'>";
		echo "<input type='button' value='Follow!' onClick='follow(". $row['userID'] .")'/>";
		echo "</form>";
	}

	echo "<table border='1' width='400'>";
	echo "<tr>";
	echo "<th>User: " .  htmlentities($row['name']). "</th><th>Time: " . htmlentities($row['timestamp']) ."</th>";
	echo " </tr><tr><th colspan='2'>Message: " . wordwrap(htmlentities($row['text']), 40, "<br />\n", true)  . "</th></tr>";

	$sqlString = 'SELECT name, text, timestamp FROM comment NATURAL JOIN users WHERE messageID = ? ORDER BY timestamp ASC;';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($row['messageID']));
	$rows2 = $statement->fetchAll();
	foreach ($rows2 as $row2) {
		echo "<tr>";
		echo "<th>User: " .  htmlentities($row2['name']). "</th><th>Time: " . htmlentities($row2['timestamp']) ."</th>";
		echo " </tr><tr><th colspan='2'>Comment: " .  wordwrap(htmlentities($row2['text']), 40, "<br />\n", true)  . "</th></tr>";
	}

	echo "</table>";
	echo "<br />";

	$count = $count +1;
}

?>
