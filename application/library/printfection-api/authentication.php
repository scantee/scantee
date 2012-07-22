<?php

	require "config.php";
	require "lib/PFAPI.php";
	
	// Instantiate the PFAPI class
	$PFAPI = new PFAPI($api_key,$secret_key);	
	
	if($session_key){
		// Set the current session from the config file
		$PFAPI->setSessionKey($session_key);
	}

	if($_GET['auth_token'] && !$session_key){
		// Successfully authenticated so set the session
		$PFAPI->setSessionFromAuthToken($_GET['auth_token']);
	}
	
	if($PFAPI->hasSession()){
		// This application has been authenticated to an account
		
		// Get permissions granted to this session
		$permissions = $PFAPI->query('image_sets,stores,add_store,add_image_set','permissions',"session_key='{$PFAPI->getSessionKey()}'");
		
		echo "<div style='width:600px;padding:10px'>";
		echo "Congratulations, you have successfully authenticated your application to your account!".
				 "<br><br>Your session key is <b>{$PFAPI->getSessionKey()}</b>";
		
		if($session_key){
			echo "<br><br>You can now move on to the other examples:".
					 "<ul><li><a href='pfql.php'>Viewing data/ PFQL</a></li>".
					 "<li><a href='uploading.php'>Uploading Images</a></li>".
					 "<li><a href='product_creation.php'>Product Creation</a></li></ul>";
		}else{
			echo " To use the other examples you will need to edit the config.php file and uncomment then set the \$session_key variable with the session key specified here. ".
					 "Once you've set the \$session_key variable refresh this page to verify it has been set correctly as well as see the next set of examples.<br><br>";
		}
		
		echo "Here are the permissions you have given your application:";
		echo "</div>";
		
		echo "<div style='width:600px;padding:10px'>";
		echo "<b>Add Store:</b> ".$permissions->add_store." access<br>";
		echo "<b>Add Image Set:</b> ".$permissions->add_image_set." access<br>";
		echo "<b>Image Sets:</b><br>";
		
		foreach($permissions->image_sets->image_set as $image_set){
			echo "&nbsp;$image_set->imagesetid: $image_set->permission access<br>";
		} 
		
		echo "<b>Stores:</b><br>";
		
		foreach($permissions->stores->store as $store){
			echo "&nbsp;$store->storeid: $store->permission access<br>";
		} 		
	
	}else{
		// Need to authenticate
		
		// Get the authorization URL (No parameters defaults to requiring the max permissions)
		$auth_url = $PFAPI->getAuthUrl();
		
		$this_page_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		
		if($session_key){
			// Invalid session key in the config file
			echo "<div style='width:600px;padding:10px;color:red'>";
			echo "It looks like you tried to set the \$session_key variable in the config file to <b>$session_key</b> but that session key is invalid. ";
			echo "The error you recieved when trying to set the session key is: ";
			echo "<b>Error code: ".$PFAPI->getLastError()->code." Error Description: ".$PFAPI->getLastError()->description."</b> ";
			echo "Comment out the session key in the config file and authenticate again see a valid session key to use.";
			echo "</div>";
		}
				
		echo "<div style='width:600px;padding:10px'>";
		echo "In order to use the Printfection API you must first authenticate your application to an account. You can authenticate an application to multiple accounts which you might ".
				 "do if you're building a bulk upload application or something similiar. These examples instead will work with a single session which authenticates your application to your own account.".
				 "<br><br>Before you click the link below ensure you've set up the post-back URL for your application by logging into your account on Printfection. The post-back URL will include an ".
		 		 "auth token which you will then use to get the session for the authenticated user; in this case that will be you. Use <b>$this_page_url</b> as your post back URL.".
				 "<br><br>Once you have set up your post-back URL, click the link below which will take you to the Printfection authorization page.";
		echo "</div>";
		
		echo "<div style='width:600px;word-wrap:break-word;padding:10px'>";
		echo "<a href='$auth_url'>$auth_url</a>";
		echo "</div>";
		
	}
	
?>