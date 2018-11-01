<!DOCTYPE html>
<html>
<head>
	<title>PHP Cookies</title>
</head>
<body>
	<form method="post">
		Tên Đăng Nhâp: <input type="text" name="user"><br><br>
		Mật Khẩu: <input type="password" name="pass"><br><br>
		<input type="submit" name="login" value="Đăng Nhập Hệ Thống">	
	</form>
	<?php
		if(isset($_POST['login'])){
			setcookie('user',$_POST['user'], time()+3);
			setcookie('pass',$_POST['pass'], time()+3);
			header("Location: php_showcookie.php");
		}
	?>
</body>
</html>