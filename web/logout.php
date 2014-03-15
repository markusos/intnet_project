<?php
	session_start();
	session_destroy();
?>

<html>
<head>
<script type="text/javascript">
<!--
function redirect(){
	setTimeout("window.location = 'login.php'",1000); 
}

//-->
</script>
</head>
<body onload="redirect();">
Logging out...
</body>
</html>