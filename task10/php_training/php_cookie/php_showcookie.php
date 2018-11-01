<!DOCTYPE html>
<html>
<head>
	<title>PHP ShowCookie</title>
</head>
<body>
	
	
	<?php
		if(isset($_COOKIE['user']) && isset($_COOKIE['pass'])){
			echo ("ten truy cap:". $_COOKIE['user']);
			echo ("<br>mat khau:". $_COOKIE['pass']);
		}else{
			echo ("cookie ko ton tai");
		}
	?>
	<br><a href="php_cookies.php">Tro ve trang cookies</a>
	<a href="php_delete.php">Huy cookies</a>
</body>
</html>