<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>PHP Cookies</title>
</head>
<body>
	
	<?php
		if(isset($_SESSION['user']) && isset($_SESSION['pass'])){
			echo ("ten truy cap:". $_SESSION['user']);
			echo ("<br>mat khau:". $_SESSION['pass']);
		}else{
			echo ("session ko ton tai");
		}
	?>
	<br><a href="php_seasion.php">Tro ve trang session</a>
	<a href="php_deleteseasion.php">Huy session</a>
	?>
</body>
</html>