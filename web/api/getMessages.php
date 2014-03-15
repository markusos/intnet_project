<?php
include 'utility.php';

session_start();

//Check if already loged in
$db = getDB();
if(isset($_SESSION['id']) && checkSessionID($db, $_SESSION['id']) != -1){
	//get messages
	$filter = $_GET['filter'];
try {
	$db = getDB();

	if($filter == 'self'){
	
		$sqlString = 'SELECT name, text, timestamp, messageID, userID FROM message NATURAL JOIN users WHERE username = ? ORDER BY timestamp DESC;';
		$statement = $db->prepare($sqlString);
		$statement->execute(array($_SESSION['user']));
		$rows = $statement->fetchAll();
		echo "<div class='title'>" . $_SESSION['user'] . "</div>";
	}
	else if($filter == 'follows'){
	
		echo "<div class='title'>Followed feed</div>";
	
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
		if($counter == 0) echo "<form name='followButton'><input type='button' value='Follow!' onClick=follow(".$_GET['id'].")></form>";
	
		$sqlString = 'SELECT name, text, timestamp, messageID, userID FROM message NATURAL JOIN users WHERE userID = ? ORDER BY timestamp DESC;';
		$statement = $db->prepare($sqlString);
		$statement->execute(array($_GET['id']));
		$rows = $statement->fetchAll();
		echo "<div class='title'>" .htmlentities($rows[0][0]). "</div>";
	}
	else{
		echo "<div class='title'>Global feed</div>";
	
		$sqlString = 'SELECT name, text, timestamp, messageID, userID FROM message NATURAL JOIN users ORDER BY timestamp DESC;';
		$statement = $db->prepare($sqlString);
		$statement->execute(array());
		$rows = $statement->fetchAll();
	}
	
	$count = 0;
	foreach ($rows as $row) {
	
		echo "<div class='messagebox'>";
						
		echo "<div class='message'>";
		echo "<div class='messageName'><b><a onclick=setFilter('user&id=".htmlentities($row[4])."')>" .htmlentities($row[0]). "</a></b></div>";
		echo "<div class='messageText'>" .htmlentities($row[1]). "</div>";
		echo "<div class='messageTime'>" .htmlentities($row[2]). "</div>";
		echo "</div>";
		
		$sqlString = 'SELECT name, text, timestamp, userID FROM comment NATURAL JOIN users WHERE messageID = ? ORDER BY timestamp ASC;';
		$statement = $db->prepare($sqlString);
		$statement->execute(array($row[3]));
		$rows2 = $statement->fetchAll();
		foreach ($rows2 as $row2) {
		
			echo "<div class='comment'>";
			echo "<div class='commentName'><b><a onclick=setFilter('user&id=".htmlentities($row2[3])."')>" .htmlentities($row2[0]). "</a></b></div>";
			echo "<div class='commentText'>" .htmlentities($row2[1]). "</div>";
			echo "<div class='commentTime'>" .htmlentities($row2[2]). "</div>";
			echo "</div>";
		}
		
		echo "<div class='newComment'>";
		echo "<form> Comment: <input type='text' size='40' name='text' id='txt" . $count ."' />";
		echo "<input type='hidden' name='messageID' id='mid" . $count ."' value='". $row[3] ."' />";
		echo "<input type='button' value='Post' onclick='postComment(mid" . $count .".value, txt" . $count .".value)'/></form>";
		echo "</div>";
		echo "</div>";
		
		echo "<br />";
		
		$count = $count +1;
	}
	
    /*** close the database connection ***/
    $db = null;
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }
}		
else{
	// redirect to login.php
	header("Location: login.php");
}

?>