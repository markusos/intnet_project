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

	$follows = array();
	$follower = array();		

	$sqlString = 'SELECT name, userID FROM users NATURAL JOIN followers WHERE followerUserID = ? GROUP BY userID;';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($id));
	
	$rows = $statement->fetchAll();
	foreach ($rows as $row) {
		$follows[] = array("id" => htmlentities($row[1]), "name"	=> htmlentities($row[0]));	
	}
	
	$sqlString = 'SELECT name, followerUserID FROM users JOIN followers WHERE followers.userID = ? AND followers.followerUserID = users.userID GROUP BY followerUserID;';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($id));
	
	$rows = $statement->fetchAll();
	foreach ($rows as $row) {
		$follower[] = array("id" => htmlentities($row[1]), "name" => htmlentities($row[0]));
	}
	
	echo json_encode(array("status" => 1,  "message" => "OK", 'follows' => $follows, 'follower' => $follower));
    /*** close the database connection ***/
    $db = null;
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }
}		
else{
	echo json_encode(array("status" => -1, "message" => "Not valid user!"));
}

?>