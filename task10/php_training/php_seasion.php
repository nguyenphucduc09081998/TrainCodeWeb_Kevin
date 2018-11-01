<?php
	session_start();
?>

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
			$_SESSION['user'] = $_POST['user'];
			$_SESSION['pass'] = $_POST['pass'];
		
			header("Location: php_showseasion.php");
		}
	?>
</body>
</html>