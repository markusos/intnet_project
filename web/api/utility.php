<?php
	function checkSessionID($db, $sessionID){
		$sessionTimeout = 30; // minutes
		$sqlString = 'SELECT userid from users WHERE sessionID = ? and ? >= (SELECT TIMESTAMPDIFF(MINUTE,sessionLastUsed,CURRENT_TIMESTAMP));';
		$statement = $db->prepare($sqlString);
		$statement->execute(array($sessionID, $sessionTimeout));
		$rows = $statement->fetchAll();
		
		foreach ($rows as $row) {
			return $row['userid'];
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
		$dbName = "intnet";
		$dbUser = "intnet";
		$dbUserPassword = "4RPyfbN46CHn9ASs";
		return new PDO('mysql:host=localhost;dbname='. $dbName , $dbUser, $dbUserPassword);
	}
?>