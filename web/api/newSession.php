<?php
include 'utility.php';

function checkUser($user, $pw){

	try {
		$db = getDB();

		$sqlString = 'SELECT salt FROM users WHERE username=?';
		$statement = $db->prepare($sqlString);
		$statement->execute(array($user));

		$rows = $statement->fetchAll();
		foreach ($rows as $row) {
			$salt= $row[0];
		}
		
		$passWithSalt = $pw . $salt;
		$passwordHash = sha1($passWithSalt);
		
		$sqlString = 'SELECT username, userID FROM users WHERE username=? && passhash=?;';
		$statement = $db->prepare($sqlString);
		$statement->execute(array($user, $passwordHash));

		$rows = $statement->fetchAll();
		$count = 0;
		foreach ($rows as $row) {
			$count = $count + 1;
			$user= $row[0];
			$userID = $row[1];
		}
		if($count == 1){

			$_SESSION['id'] = randStr(32);
			$_SESSION['user'] = $user;
			$_SESSION['userID'] = $userID;
			//Save Session id in database
			$sqlString = 'UPDATE users SET sessionID = ? WHERE username=? && passhash=?;';
			$statement = $db->prepare($sqlString);
			$statement->execute(array($_SESSION['id'], $user, $passwordHash));

			echo "Logged in!";
		}
		else echo "ERROR!!! ";
		
		
		/*** close the database connection ***/
		$db = null;
    }
	catch(PDOException $e)
    {
		echo $e->getMessage();
    }
}

//Create new session
session_start();

//Check if already loged in
$db = getDB();
if(isset($_SESSION['id']) && checkSessionID($db, $_SESSION['id']) != -1)
    echo "Already logged in!";
else{
	checkUser($_POST['user'], $_POST['password']);
	echo checkSessionID($db, $_SESSION['id']);
}

//redirect to index.php
header("Location: ../index.php");
?>