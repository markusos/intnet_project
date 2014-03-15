<?php

include 'utility.php';

function insertNewUser($user, $pwHash, $salt, $fullname){

try {
    $db = getDB();

    /*** INSERT data ***/
	$sqlString = 'INSERT INTO users (username, passhash, salt, name) VALUES (?, ?, ?, ?);';
	$statement = $db->prepare($sqlString);
	$statement->execute(array($user, $pwHash, $salt, $fullname));

    /*** close the database connection ***/
    $db = null;
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }
}

//Read in data from form with POST

if (isset($_POST["user"]) && $_POST["user"] != "" && isset($_POST["name"]) && $_POST["name"] != "" && isset($_POST["password"]) && $_POST["password"] != "" && isset($_POST["password2"]) && ($_POST["password"] == $_POST["password2"]))
{
	//Generate password salt

	$salt = randStr(128);

	//Calculate passwordhash

	$passWithSalt = $_POST['password'] . $salt;
	$passwordHash = sha1($passWithSalt);

	//Insert into database

	insertNewUser($_POST['user'], $passwordHash, $salt, $_POST['name']);

	//Redirect to newSession
	header("Location: ../login.php");

}
else{
	//Redirect to createAccount.php, all data not correct!
	header("Location: ../createAccount.php?fail=1");
}

?>