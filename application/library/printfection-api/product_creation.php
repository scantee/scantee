<?php

	require "config.php";
	require "lib/PFAPI.php";
	
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
	echo "Once you have uploaded an image you can then create a product using that image. This example finds an image in your unorganized image set (it may not necessarily be the image ".
			 "you uploaded on the uploading.php page) and creates a T-shirt in your personal products store. It attempts to create a product every time the page is loaded if a product ID has not ".
			 "been specified in the URL.";
	echo "</div>";
		
	
	if($_GET['productid']){
		// Display the created product
		$productid = $_GET['productid'];
		
		echo "<div style='width:600px;padding:10px;color:green'>";
		echo "Your product was created successfully and was given a productid of $productid. The product images are displayed below. ".
				 "You can login to your Printfection account to verify this if you'd like.";
		echo "</div>";
		
		echo "<div style='width:600px;padding:10px;'>";
		echo "<a href='product_creation.php'>Click here</a> to create another product. Feel free to edit this example to change the root product as well as the image used on the product.";
		echo "</div>";		
		
		// Get the primary color
		$products = $PFAPI->query('primary_rootcolorid','products',"productid=$productid");
		$rootcolorid = (int)$products->product->primary_rootcolorid;
		
		// Get both sides
		$product_sides = $PFAPI->query('productsideid','product_sides',"productid=$productid");
		
		// Display the new product images
		foreach($product_sides as $product_side){
			$image_url = $PFAPI->getLargeProductImageUrl($rootcolorid,$product_side->productsideid);
			echo "<img src='$image_url'>";
		}
		exit;
	}	
			
	
	// Ensure at least one image exists in the default set
	$image_sets = $PFAPI->query('imagesetid','image_sets',"custid='{$PFAPI->getCustomerId()}' AND default=1",NULL,1);
	$imagesetid = (int)$image_sets->image_set->imagesetid;
	
	$images = $PFAPI->query('imageid','images',"imagesetid=$imagesetid",NULL,1);
	$imageid = (int)$images->image->imageid;
	
	if(!$imageid){
		// Couldn't fine an image
		echo "<div style='width:600px;padding:10px;color:red'>";
		echo "An image does not exist in your default unorganized image set so we cannot make a product. Please view the <a href='uploading.php'>Uploading page</a> to upload an image into ".
				 "this image set. Once you have uploaded an image return to this page to create a product.";
		echo "</div>";		
		exit;
	}
	
	echo "<div style='width:600px;padding:10px;color:green'>";
	echo "Starting product creation...";
	echo "</div>";
	
	
	// Get the root section in personal products store
	$stores = $PFAPI->query('root_sectionid','stores',"custid='{$PFAPI->getCustomerId()}' AND personal=1");
	$sectionid = (int)$stores->store->root_sectionid;
	
	// Get the root product info
	$root_products = $PFAPI->query('rootid','root_products',"rootid=7");
	$rootid = (int)$root_products->root_product->rootid;
	
	// Get colors for this root product
	$root_colors = $PFAPI->query('rootcolorid','root_product_colors',"rootid={$rootid}");

	// Create the root colors array
	$root_colors_array = array();
	foreach($root_colors as $root_color){
		array_push($root_colors_array,(int)$root_color->rootcolorid);
	}
	
	// Create the product
	$specs[] = array('side'=>0,'imageid'=>$imageid);
	$specs[] = array('side'=>1,'imageid'=>$imageid,'center_percent_y'=>.2,'rotation'=>90,'height'=>5);
	$productid = $PFAPI->createProduct($rootid,$sectionid,$root_colors_array,$specs);

	if(!$productid){
		echo "<div style='width:600px;padding:10px;color:red'>";
		echo "There was an error creating your product. ";
		echo "The error response was: <b>Error code: ".$PFAPI->getLastError()->code." Error Description: ".$PFAPI->getLastError()->description."</b> ";
		echo "</div>";
	}else{
		echo "<div style='width:600px;padding:10px;color:green'>";
		echo "Your product was successfully created. You'll now be redirected to view the new product.";
		echo "<META http-equiv='Refresh' content='2;URL=product_creation.php?productid=$productid'>";
		echo "</div>";
	}
	
	
?>