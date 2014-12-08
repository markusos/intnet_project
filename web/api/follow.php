<?php

include_once 'checkAuth.php';
include_once 'utility.php';

if (!isset($_POST["follow"])) {
  echo json_encode(array("status" => -1, "message" => "Unspecified userID"));
  die();
}

try {
  $userId = $_SESSION['userID'];
  $userToFollow = $_POST["follow"];

  $sqlString = 'INSERT INTO followers (userID, followerUserID) VALUES (?, ?);';
  $statement = $db->prepare($sqlString);
  $statement->execute(array($userToFollow, $userId));

  echo json_encode(array("status" => 1, "message" => "Followed userID: " . $userToFollow ));
}
catch(PDOException $e)
{
  logToFile($e->getMessage());
}

closeDB();
