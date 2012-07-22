<?php  if ( ! defined('LIB_PATH')) exit('No direct script access allowed');
/**
 * Process eBay Feed
 * Read CL_feed for detailed notes on how this works
 * 
 */
/*
switch($arg):
    case 'authenticate':
        require('printfection-api/authentication.php');        
        break;
    case 'data':
        require('printfection-api/pfql.php');
        break;
    case 'product':
        require('printfection-api/product_creation.php');
        break;
    case 'upload':
        print_r($_POST);
        //require('printfection-api/uploading.php');
        //upload?upload_now=1
        break;   
endswitch;
 * 
 */


class Printfection_Library {
	

    private $PFAPI;

    
    public function __construct($qr = false) { //assigned keywords are optional

        //instantiate PFAPI here

    }
    
    public function uploadImg($arg = false){
        

        require "printfection-api/config.php";
	require "printfection-api/lib/PFAPI.php";
        $image_path = 'public/images/tmp/'.$arg.'.png';
        
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
			
        // Get the default image set
        $image_sets = $PFAPI->query('imagesetid,name','image_sets',"custid='{$PFAPI->getCustomerId()}' AND default=1",NULL,1);
        $imagesetid = $image_sets->image_set->imagesetid;


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
                
                return $imageid;
        }
	
    }
    
    
    public function builtTee($arg = false){
        
        require "printfection-api/config.php";
	require "printfection-api/lib/PFAPI.php";
	
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
		
	
	if($arg !== false){
		// Display the created product
		$productid = $arg;
		
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
		echo "<META http-equiv='Refresh' content='2;URL=/build/qrtee/$productid'>";
		echo "</div>";
	}
	        
        
    }   
    
    public function pfqlTest($arg = false){ 
        
        require "printfection-api/config.php";
	require "printfection-api/lib/PFAPI.php";
	
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
	
	
	$image_sets = $PFAPI->query('imagesetid,name,date_created,preview_image_url','image_sets',"custid='{$PFAPI->getCustomerId()}'",'name',"10");
        var_dump($image_sets);
	
	echo "<div style='width:600px;padding:10px'>";
	echo "<b>Your first 10 image sets:</b><br>";
	foreach($image_sets as $image_set){
		echo "&nbsp; {$image_set->name} ({$image_set->imagesetid}) was created on ".date('m/j/y',(int)$image_set->date_created)."<br>";
                
                //$images = $PFAPI->query('imageid,name,url_large,','images',"imagesetid=243571",'name',"10");
                //var_dump($images);
                /*
                foreach($images as $image){
                    
                    var_dump($image);
                    
                }
                 * 
                 */
	}
	echo "</div>";
    
        
        
        
    }    
    
    
    public function pfql($arg = false){ 
        
        
        require "printfection-api/config.php";
	require "printfection-api/lib/PFAPI.php";
	
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
        
        
    }   
        
 

}

