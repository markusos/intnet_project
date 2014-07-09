<?php
include_once 'checkAuth.php';
include_once 'utility.php';
include_once 'newsfeedFunctions.php';

if (isset($_GET['filter'])) {
	$filter = $_GET['filter'];
}
else {
	$filter = "global";
}

getJsonNewsFeed($filter);

closeDB();
?>
