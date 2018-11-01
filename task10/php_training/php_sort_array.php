<!DOCTYPE html>
<html>
<head>
	<title>PHP Sorting Arrays</title>
</head>
<body>
	<?php

	$a = array(3,2,9,5,8,7);
	sort($a);
	
	for($b = 0; $b < count($a); $b++){
		echo $a[$b];
		echo"<br>";
	}
	
	?>
</body>
</html>