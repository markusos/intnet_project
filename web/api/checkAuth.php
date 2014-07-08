<?
include_once 'utility.php';

session_start();

//Check if already loged in
if(!isset($_SESSION['id']) || isset($_SESSION['id']) && checkSessionID($db, $_SESSION['id']) == -1){
  echo json_encode(array("status" => -1, "message" => "Login Failed!"));
  die();
}

?>
