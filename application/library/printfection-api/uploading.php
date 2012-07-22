<?php

	require "config.php";
	require "lib/PFAPI.php";
	
	
	// Set this to the direct path of the image on your web server
	// Your webserver must be able to access this image so ensure the permissions are set correctly on the image file
	$image_path = 'public/images/tmp/qr-code.png'; // Set your image path here
	
	
	
	// Instantiate the PFAPI class
	$PFAPI = new PFAPI($api_key,$secret_key);	
	
	// Set the current session from the config file
	$PFAPI->setSessionKey($session_key);

	if(!$PFAPI->hasSession()){
		echo "<div style='width:600px;padding:10px'>";
		echo "You must first set the \$session_key variable in the config.php file before you can use this page. If you do not have a \$session_key go to the ".
				 "<a href='authentication.php'>Authentication page</a> to get one.";
		echo "</div>";
		exit;
	}
	
	echo "<div style='width:600px;padding:10px'>";
	echo "<a href='authentication.php'>Back to the home page</a>";
	echo "</div>";
	
	echo "<div style='width:600px;padding:10px'>";
	echo "A key part of creating a product is uploading the image which will be shown and eventually printed on that product. This example assumes you already have direct access ".
			 "to the image on the web server. You will need to set the \$image_path variable in this file (uploading.php) with the path to the image you would like to upload. Once this has been ".
			 "set refresh this page to see options allowing you to upload that image.";
	echo "</div>";
	
	
	if(!is_readable($image_path)){
		echo "<div style='width:600px;padding:10px;color:red'>";
		echo "You have not set a valid path for the \$image_path variable. Please edit this file (uploading.php) and insert the correct path to your image.";
		
		if($image_path){
			echo " <b>$image_path</b> is not a valid file or is not accessable by your webserver.";
		}
		
		echo "</div>";
	}else{
		
		// Get the default image set
		$image_sets = $PFAPI->query('imagesetid,name','image_sets',"custid='{$PFAPI->getCustomerId()}' AND default=1",NULL,1);
		$imagesetid = $image_sets->image_set->imagesetid;
		
		if($_GET['upload_now']){
			// Upload the image
			
			$image_name = "PFAPI Test Image - ".mktime();

			echo "<div style='width:600px;padding:10px;color:green'>";
			echo "Uploading <b>$image_path</b> to your image set $imagesetid with the name '$image_name'...";
			echo "</div>";
			
			// Upload the image
			$imageid = $PFAPI->upload($image_path,$image_name,$imagesetid);
			
			if(!$imageid){
				echo "<div style='width:600px;padding:10px;color:red'>";
				echo "There was an error uploading your image. ";
				
				if($PFAPI->getLastError()->code){
					echo "The error response was: <b>Error code: ".$PFAPI->getLastError()->code." Error Description: ".$PFAPI->getLastError()->description."</b> ";
				}else{
					echo "This is most likely due to incorrect permissions on your file. Please verify the web service has permission to access your image.";
				}
				echo "</div>";
			}else{
				echo "<div style='width:600px;padding:10px;color:green'>";
				echo "Your image was successfully uploaded to your account and was given an imageid of $imageid. You can login to your Printfection account to verify this if you'd like.";
				echo "</div>";
			}
		}
		
		
		echo "<div style='width:600px;padding:10px;'>";
		echo "<a href='uploading.php?upload_now=1'>Click here</a> to upload your image located at <b>$image_path</b>. Your image will be uploaded to your default image set ".
				 "called your '{$image_sets->image_set->name}' image set.";
		echo "</div>";		
	}
	
	
?>