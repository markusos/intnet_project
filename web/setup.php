<?php

include 'api/utility.php';

$db = getDB();

$sqlString =  	"CREATE TABLE IF NOT EXISTS `comment` (
				  `commentID` int(11) NOT NULL AUTO_INCREMENT,
				  `messageID` int(11) NOT NULL,
				  `userID` int(11) NOT NULL,
				  `text` varchar(1000) NOT NULL,
				  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`commentID`)
				);";

$statement = $db->prepare($sqlString);
$statement->execute();

$sqlString =  	"CREATE TABLE IF NOT EXISTS `followers` (
				  `userID` int(11) NOT NULL,
				  `followerUserID` int(11) NOT NULL
				);";

$statement = $db->prepare($sqlString);
$statement->execute();

$sqlString =	 "CREATE TABLE IF NOT EXISTS `message` (
				  `messageID` int(11) NOT NULL AUTO_INCREMENT,
				  `userID` int(11) NOT NULL,
				  `text` varchar(10000) NOT NULL,
				  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`messageID`)
				);";

$statement = $db->prepare($sqlString);
$statement->execute();

$sqlString =	 "CREATE TABLE IF NOT EXISTS `users` (
				  `username` varchar(20) NOT NULL,
				  `passhash` char(128) NOT NULL,
				  `salt` char(128) NOT NULL,
				  `name` varchar(100) NOT NULL,
				  `sessionID` char(32) NOT NULL,
				  `sessionLastUsed` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `userID` int(11) NOT NULL AUTO_INCREMENT,
				  PRIMARY KEY (`userID`)
				);";

$statement = $db->prepare($sqlString);
$statement->execute();

echo "Database Created!"

?>