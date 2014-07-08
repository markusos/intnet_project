<?php
include_once 'utility.php';

function checkUser($user, $pw){

	try {
		global $db;

		$sqlString = 'SELECT salt FROM users WHERE username=?';
		$statement = $db->prepare($sqlString);
		$statement->execute(array($user));

		$salt = "";

		$rows = $statement->fetchAll();
		foreach ($rows as $row) {
			$salt = $row[0];
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

			$message = "Logged in!";
			$status = 1;
		}
		else {
			$message = "Username or password is not valid!";
			$status = -1;
		}

		echo json_encode(array('status' => $status, 'message' => $message));
		$db = null;
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
}

session_start();

//Check if already loged in
if(isset($_SESSION['id']) && checkSessionID($db, $_SESSION['id']) != -1) {
	echo json_encode(array('status' => 1, 'message' => "Logged in!"));
}
else if (isset($_POST['user']) && isset($_POST['password'])){
	checkUser($_POST['user'], $_POST['password']);
}
else {
	echo json_encode(array("status" => -1, "message" => "No username and/or password set"));
	die();
}

?>
