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
	echo "Nearly all data can be found using the Printfection.PFQL.query method. The syntax of this method is similar to that of SQL where you select various columns from a table. ".
			 "This method even allows basic constraints via a WHERE clause as well as ordering of results using ORDER BY and limiting/ offsets using the LIMIT clause. The PFAPI uses ".
			 "a method (query()) to encapsulate all this logic. This page will show some basic information about your images, stores and products using this method. Take a look at the ".
	 		 "code of this page to see examples as to how this is done.";
	echo "</div>";
	
	
	$image_sets = $PFAPI->query('imagesetid,name,date_created','image_sets',"custid='{$PFAPI->getCustomerId()}'",'name',"10");
	
	echo "<div style='width:600px;padding:10px'>";
	echo "<b>Your first 10 image sets:</b><br>";
	foreach($image_sets as $image_set){
		echo "&nbsp; {$image_set->name} ({$image_set->imagesetid}) was created on ".date('m/j/y',(int)$image_set->date_created)."<br>";
	}
	echo "</div>";
	
	
	$stores = $PFAPI->query('storeid,store_access,name','stores',"custid='{$PFAPI->getCustomerId()}'",'name',"2");
	
	echo "<div style='width:600px;padding:10px'>";
	echo "<b>Your first 2 stores, first 5 sections in each store and first 5 products in each section:</b><br>";
	foreach($stores as $store){
		
		if($store->store_access == 0){
			$store_access = 'open';
		}elseif($store->store_access == 1){
			$store_access = 'closed';
		}else{
			$store_access = 'hidden';
		}
		
		echo "&nbsp;{$store->name} ({$store->storeid}) is $store_access<br>";
		
		// Get sections in this store
		$sections = $PFAPI->query('sectionid,name,parent_sectionid','store_sections',"storeid='{$store->storeid}'",'name',"5");
		
		foreach($sections as $section){
			
			if($section->parent_sectionid == 0){
				$name = "Parent Section";
			}else{
				$name = $section->name;
			}
			
			echo "&nbsp;&nbsp;&nbsp;&nbsp;Store Section: $name ({$section->sectionid})<br>";
			
			// Get products in this section
			$products = $PFAPI->query('productid,title','products',"sectionid='{$section->sectionid}'",'title',"5");
			
			foreach($products as $product){
				if($product->productid){
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Product: {$product->title} ({$product->productid})<br>";
				}	
			}
		}
	}
	echo "</div>";	
	
	
	
	
?>