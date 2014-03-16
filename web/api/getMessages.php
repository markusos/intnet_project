<?php
include 'utility.php';

session_start();

//Check if already loged in
$db = getDB();
if(isset($_SESSION['id']) && checkSessionID($db, $_SESSION['id']) != -1){
	//get messages
	if (isset($_GET['filter'])) {
		$filter = $_GET['filter'];
	}
	else {
		$filter = "";		
	}
	
	try {
		$db = getDB();
		$title = "";
		$notFollowed = False;
		if($filter == 'self'){
			$sqlString = 'SELECT name FROM users WHERE userID = ?;';
			$statement = $db->prepare($sqlString);
			$statement->execute(array($_SESSION['userID']));
			$rows = $statement->fetchAll();
			$title = htmlentities($rows[0][0]);

			$sqlString = 'SELECT name, text, timestamp, messageID, userID FROM message NATURAL JOIN users WHERE username = ? ORDER BY timestamp DESC;';
			$statement = $db->prepare($sqlString);
			$statement->execute(array($_SESSION['user']));
			$rows = $statement->fetchAll();
			
		}
		else if($filter == 'follows'){
			$title = "Followed feed";
			$sqlString = 'SELECT name, text, timestamp, messageID, userID FROM message NATURAL JOIN users WHERE EXISTS (SELECT * FROM followers WHERE followers.userID = message.userID AND followers.followerUserID = ?) ORDER BY timestamp DESC;';
			$statement = $db->prepare($sqlString);
			$statement->execute(array($_SESSION['userID']));
			$rows = $statement->fetchAll();
		}
		else if ($filter == 'user' && isset($_GET['id'])){
			$sqlString = 'SELECT * FROM followers WHERE userID = ? AND followerUserID = ?;';
			$statement = $db->prepare($sqlString);
			$statement->execute(array($_GET['id'], $_SESSION['userID']));
			
			$rows = $statement->fetchAll();
			$counter = 0;
			foreach ($rows as $row) {
				$counter++;
			}
			if($counter == 0) $notFollowed = True;
			
			$sqlString = 'SELECT name FROM users WHERE userID = ?;';
			$statement = $db->prepare($sqlString);
			$statement->execute(array($_GET['id']));
			$rows = $statement->fetchAll();
			$title = htmlentities($rows[0][0]);

			$sqlString = 'SELECT name, text, timestamp, messageID, userID FROM message NATURAL JOIN users WHERE userID = ? ORDER BY timestamp DESC;';
			$statement = $db->prepare($sqlString);
			$statement->execute(array($_GET['id']));
			$rows = $statement->fetchAll();
		}
		else{
			$title = "Global feed";
			$sqlString = 'SELECT name, text, timestamp, messageID, userID FROM message NATURAL JOIN users ORDER BY timestamp DESC;';
			$statement = $db->prepare($sqlString);
			$statement->execute(array());
			$rows = $statement->fetchAll();
		}
		
		$messages = array();
		foreach ($rows as $row) {
			$message = array("messageID" => htmlentities($row[3]), "userId" => htmlentities($row[4]), "userName" => htmlentities($row[0]), "text" => htmlentities($row[1]), "timestamp" => htmlentities($row[2]));
			$comments = array();

			$sqlString = 'SELECT name, text, timestamp, userID FROM comment NATURAL JOIN users WHERE messageID = ? ORDER BY timestamp ASC;';
			$statement = $db->prepare($sqlString);
			$statement->execute(array($row[3]));
			$rows2 = $statement->fetchAll();

			foreach ($rows2 as $row2) {
				$comment = array("userId" => htmlentities($row2[3]), "userName" => htmlentities($row2[0]), "text" => htmlentities($row2[1]), "timestamp" => htmlentities($row2[2]));
				$comments[] = $comment;
			}

			$message["comments"] = $comments;
			$messages[] = $message;
		}

		echo json_encode(array("status" => 1, "message" => "OK", 'title' => $title, 'messages' => $messages, "notFollowed" => $notFollowed));
		
	    /*** close the database connection ***/
	    $db = null;
	}
	catch(PDOException $e){
	    echo $e->getMessage();
	}
}		
else{
	echo json_encode(array("status" => -1, "message" => "Not valid user!"));
}

?>