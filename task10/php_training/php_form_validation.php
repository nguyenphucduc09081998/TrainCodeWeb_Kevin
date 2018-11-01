<?php
	if($_POST){
		$a = array();
		
		if (empty($_POST['username'])){
			$a['username1'] = "you can not username"; 
		}
		if (empty($_POST['password'])){
			$a['password1'] = "you can not password"; 
		}
		
		
		if(count($a) == 0){
			header("Location: success.php");
			exit();
		}
			
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>PHP Form Validation</title>
</head>
<body>

	<form method="post" target="">
		<p>
		<label>User Name:<label>
		<input type="text" name="username" id="username" >
		</p>
		<p><?php if(isset($a['username1'])) echo $a['username1']; ?></p>
		<p>
		<label>Password:<label>
		<input type="password" name="password" id="password" >
		</p>
		
		<p><?php if(isset($a['password1'])) echo $a['password1']; ?></p>
		
		
		<input type="submit" value="Submit">
	</form>
</body>
</html>