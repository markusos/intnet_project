<?php

// Global vars
$db = getDB();

function checkSessionID($db, $sessionID){

	if (!isset($sessionID)) {
		return -1;
	}

	try {
		$sessionTimeout = 30; // minutes
		$sqlString = 'SELECT userid from users WHERE sessionID = ? and ? >= (SELECT TIMESTAMPDIFF(MINUTE,sessionLastUsed,CURRENT_TIMESTAMP));';
		$statement = $db->prepare($sqlString);
		$statement->execute(array($sessionID, $sessionTimeout));
		$rows = $statement->fetchAll();

		foreach ($rows as $row) {
			return $row['userid'];
		}
	}
	catch(PDOException $e) {
		echo "Error Connecting to Database";
		file_put_contents('Errors.txt', $e->getMessage(), FILE_APPEND);
	}

	return -1;
}

function randStr($len){
	$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

	$random = '';
	for ($p = 0; $p < $len; $p++) {
		$random = $random . $characters[rand(0, strlen($characters)-1)];
	}

	return $random;
}

function getDB(){
	try {
		$ini = parse_ini_file("config.ini");

		$dbName = $ini['dbName'];
		$dbHost = $ini['dbHost'];
		$dbUser = $ini['dbUser'];
		$dbUserPassword = $ini['dbUserPassword'];
	}
	catch (Exception $e) {
		die('Failed to read config.ini');
		file_put_contents('Errors.txt', $e->getMessage(), FILE_APPEND);
	}

	try {
		return new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbUserPassword);
	}
	catch(PDOException $e) {
		echo "Error Connecting to Database";
		file_put_contents('Errors.txt', $e->getMessage(), FILE_APPEND);
	}
}

?>
