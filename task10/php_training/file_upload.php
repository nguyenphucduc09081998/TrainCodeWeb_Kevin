<?php

	 if(isset($_POST['submit'])){
		 
            $file = $_FILES['myfile']['tmp_name'];
            $path = "abc/".$_FILES['myfile']['name'];
			
            if(move_uploaded_file($file, $path)){
                echo "Tải tập tin thành công";
            }else{
            	echo "Tải tập tin thất bại";
            }
        }
	
	
	

		
?>