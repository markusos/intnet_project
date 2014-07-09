<?php

include 'utility.php';

function insertNewUser($user, $pwHash, $salt, $fullname) {
  try {
    global $db;

    $sqlString = 'INSERT INTO users (username, passhash, salt, name) VALUES (?, ?, ?, ?);';
    $statement = $db->prepare($sqlString);
    $statement->execute(array($user, $pwHash, $salt, $fullname));

    echo json_encode(array("status" => 1, "message" => "OK"));
  }
  catch(PDOException $e)
  {
    logToFile($e->getMessage());
  }
}

//Read in data from form with POST
if (isset($_POST["user"]) && $_POST["user"] != "" &&
    isset($_POST["name"]) && $_POST["name"] != "" &&
    isset($_POST["password"]) && $_POST["password"] != "" &&
    isset($_POST["password2"]) && ($_POST["password"] == $_POST["password2"])) {

  $salt = randStr(128);
  $passWithSalt = $_POST['password'] . $salt;
  $passwordHash = sha1($passWithSalt);
  insertNewUser($_POST['user'], $passwordHash, $salt, $_POST['name']);
}
else {
  echo json_encode(array("status" => -1, "message" => "Error creating user!"));
}

closeDB();

?>
