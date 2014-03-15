<?php
include 'utility.php';

session_start();

//Check if already loged in
$db = getDB();
if(isset($_SESSION['id']) && checkSessionID($db, $_SESSION['id']) != -1){
	//get messages
	if(isset($_GET['id'])) $id = $_GET['id'];
	else $id = $_SESSION['userID'];
try {
	$db = getDB();

	$sqlString = 'SELECT name, userID FROM users NATURAL JOIN followers WHERE followerUserID = ? GROUP BY userID;';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($id));
	
	$rows = $statement->fetchAll();
	echo "<div class='followerH1'>Follows:</div>";
	foreach ($rows as $row) {
		echo "<div class='follower'><b><a onclick=setFilter('user&id=".htmlentities($row[1])."')>" .htmlentities($row[0]). "</a></b></div>";
	}
	
	$sqlString = 'SELECT name, followerUserID FROM users JOIN followers WHERE followers.userID = ? AND followers.followerUserID = users.userID GROUP BY followerUserID;';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($id));
	
	$rows = $statement->fetchAll();
	echo "<div class='followerH1'>Followers:</div>";
	foreach ($rows as $row) {
		echo "<div class='follower'><b><a onclick=setFilter('user&id=".htmlentities($row[1])."')>" .htmlentities($row[0]). "</a></b></div>";
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