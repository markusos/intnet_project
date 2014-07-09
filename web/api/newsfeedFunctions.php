<?php
include_once 'checkAuth.php';
include_once 'utility.php';

function getUserName($userID) {
  global $db;

  $sqlString = 'SELECT name FROM users WHERE userID = ?;';

  $statement = $db->prepare($sqlString);
  $statement->execute(array($userID));
  $rows = $statement->fetchAll();

  if (count($rows) == 1) {
    return htmlentities($rows[0][0]);
  }
  else {
    echo json_encode(array("status" => -1, "message" => "UserId: " . $userID . " is not valid user!"));
    die();
  }
}

function addCommentsToMessages($messageRows) {
  global $db;

  $messages = array();
  foreach ($messageRows as $messageRow) {
    $message = array(
      "messageID" => htmlentities($messageRow[3]),
      "userId" => htmlentities($messageRow[4]),
      "userName" => htmlentities($messageRow[0]),
      "text" => htmlentities($messageRow[1]),
      "timestamp" => htmlentities($messageRow[2])
    );

    $comments = array();

    $sqlString = 'SELECT name, text, timestamp, userID
                  FROM comment NATURAL JOIN users
                  WHERE messageID = ?
                  ORDER BY timestamp ASC;';

    $statement = $db->prepare($sqlString);
    $statement->execute(array($messageRow[3]));
    $commentRows = $statement->fetchAll();

    foreach ($commentRows as $commentRow) {
      $comment = array(
        "userId" => htmlentities($commentRow[3]),
        "userName" => htmlentities($commentRow[0]),
        "text" => htmlentities($commentRow[1]),
        "timestamp" => htmlentities($commentRow[2])
      );

      $comments[] = $comment;
    }

    $message["comments"] = $comments;
    $messages[] = $message;
  }

  return $messages;
}

function notFollowed($userID) {
  global $db;

  if ($_SESSION['userID'] != $userID) {
    $sqlString = 'SELECT * FROM followers
                  WHERE userID = ? AND followerUserID = ?;';

    $statement = $db->prepare($sqlString);
    $statement->execute(array($userID, $_SESSION['userID']));

    $rows = $statement->fetchAll();
    $counter = 0;
    foreach ($rows as $row) {
      $counter++;
    }
    if($counter == 0) {
      return True;
    }
  }

  return False;
}

function getGlobalMessages() {
  global $db;

  $sqlString = 'SELECT name, text, timestamp, messageID, userID
                FROM message NATURAL JOIN users
                ORDER BY timestamp DESC;';

  $statement = $db->prepare($sqlString);
  $statement->execute(array());
  $rows = $statement->fetchAll();

  return addCommentsToMessages($rows);
}

function getFollowedMessages() {
  global $db;

  $sqlString = 'SELECT name, text, timestamp, messageID, userID
                FROM message NATURAL JOIN users
                WHERE EXISTS (SELECT * FROM followers
                  WHERE followers.userID = message.userID
                  AND followers.followerUserID = ?)
                  ORDER BY timestamp DESC;';

  $statement = $db->prepare($sqlString);
  $statement->execute(array($_SESSION['userID']));
  $rows = $statement->fetchAll();

  return addCommentsToMessages($rows);
}

function getUserMessages($userID) {
  global $db;

  $sqlString = 'SELECT name, text, timestamp, messageID, userID
                FROM message NATURAL JOIN users
                WHERE userID = ?
                ORDER BY timestamp DESC;';

  $statement = $db->prepare($sqlString);
  $statement->execute(array($userID));
  $rows = $statement->fetchAll();

  return addCommentsToMessages($rows);
}

function getJsonNewsFeed($filter) {
  try {
    $title = "";
    $notFollowed = False;

    if($filter == 'self'){
      $title = getUserName($_SESSION['userID']);
      $messages = getUserMessages($_SESSION['userID']);
    }
    else if($filter == 'follows'){
      $title = "Followed feed";
      $messages = getFollowedMessages();
    }
    else if ($filter == 'user' && isset($_GET['id'])){
      $userID = $_GET['id'];
      $title = getUserName($userID);
      $messages = getUserMessages($userID);
      $notFollowed = notFollowed($userID);
    }
    else{
      $title = "Global feed";
      $messages = getGlobalMessages();
    }

    echo json_encode(array('status' => 1,
                            'message' => 'OK',
                            'title' => $title,
                            'messages' => $messages,
                            'notFollowed' => $notFollowed
                            ));

  }
  catch(PDOException $e){
    echo "Error Connecting to Database";
    logToFile($e->getMessage());
  }
}

?>
