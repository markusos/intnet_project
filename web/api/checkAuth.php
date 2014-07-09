<?
include_once 'utility.php';

session_start();

//Check if already loged in
if(!isset($_SESSION['id']) || isset($_SESSION['id'])
    && checkSessionID($_SESSION['id']) == -1){
  closeDB();
  echo json_encode(array("status" => -1, "message" => "Not Logged In!"));
  die();
}

?>
