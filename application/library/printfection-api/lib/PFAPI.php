<?php
/**
 * Printfection Platform PHP5 Client
 * Copyright (c) 2010 Printfection LLC
 * 
 * This software code is made available "AS IS" without warranties of any
 * kind.  You may copy, display, modify and redistribute the software
 * code either by itself or as incorporated into your code; provided that
 * you do not remove any proprietary notices. Your use of this software
 * code is at your own risk and you waive any claim against Printfection 
 * LLC or its affiliates with respect to your use of this software code.
 * (c) 2010 Printfection LLC or its affiliates.
 * 
 * For help contact support@printfection.com
 */
 

/**
 * Version: 1.0
 * 
 * Requires: PHP 5.2.0
 * Requires: cURL - http://php.net/manual/en/book.curl.php
 * Requires: JSON - http://www.php.net/manual/en/book.json.php
 * Requires: SimpleXML - http://php.net/manual/en/book.simplexml.php
 */

class PFAPI{
	
  /**
   * The base URL to make an API request
   * 
   * @var string
   */	
  const REQUEST_URL = 'http://api.printfection.com/restserver.php';
  
  /**
   * The base URL to upload an image to
   * 		
   * @var string  
   */
  const UPLOAD_URL = 'http://upload.printfection.com/api_upload.php';
  
  /**
   * The base URL to send a user to authenticate
   * 
   * @var string
   */
  const AUTH_URL = 'http://www.printfection.com/app/authorize.php';
  
  /**
   * The base URL to send a user to checkout
   * 
   * @var string
   */
  const CHECKOUT_URL = 'http://www.printfection.com/app/checkout.php';
   	
  /**
   * The base URL for images
   * 
   * @var string
   */
  const IMAGE_URL = 'http://img.printfection.com'; 
  
	/**
	 * The application's api key
	 * 
	 * @var string
	 */
	private $api_key = "";
	
	/**
	 * The application's secret key
	 * 
	 * @var string
	 */
	private $secret_key = "";
	
	/**
	 * The version of the API to use
	 * 
	 * @var float
	 */
	private $version = '1.0';
	
	/**
	 * The formate we want the response in
	 * 
	 * @var string
	 */
	private $response_format = 'XML';
	
	/**
	 * The session key for a user
	 * 
	 * @var string
	 */
	private $session_key = "";
	
	/**
	 * The last error to occur
	 * 
	 * @var array
	 */
	private $last_error = array();

	

// Special Methods
///////////////////////////////////////////////////////
//////////////////////////////////////////////////////
	
	/**
	 * Sets up the basics to connect to the API
	 * 
	 * @param string $api_key
	 * @param string $secret_key
	 */
  public function __construct($api_key, $secret_key){

		$this->api_key = $api_key;
		$this->secret_key = $secret_key;
  }  
  
  
// General Set/ Clear Methods
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////
  
  /**
   * Clears the last error
   * 
   */
  private function clearLastError(){
  	
  	$this->last_error = array();
  }
  

// General Information Methods
//////////////////////////////////////////////////////  
//////////////////////////////////////////////////////

  /**
   * Has valid session
   * 
   * @return True if a session exists for a customer
   */
  public function hasSession(){
  	
  	if($this->getSessionKey()){
  		return true;
  	}else{
  		return false;
  	}
  }
  
  /**
   * Get the session key
   * 
   * @return string $session_key
   */
  public function getSessionKey(){
  	
  	return $this->session_key;
  }
  
  /**
   * Get the customer's ID
   * 
   * @return int Customer's ID
   */
  public function getCustomerId(){
  	
  	return substr($this->getSessionKey(), strpos($this->getSessionKey(), '-')+1, strlen($this->getSessionKey()));
  }
  
  /**
   * Returns the last error
   * 
   * @return array Last error
   */
  public function getLastError(){
  	
  	return $this->last_error;
  }
  
  
// Image URLs Methods
///////////////////////////////////////////////////////
//////////////////////////////////////////////////////  
  	
  /**
   * Returns the URL of the large product image
   * 
   * @param int $rootcolorid
   * @param int $productsideid
   * @return string Image URL
   */
  public function getLargeProductImageUrl($rootcolorid, $productsideid){
  	
  	$obj = $this->query('productid','product_sides',"productsideid='$productsideid'");
  	
  	if(!$obj){
  		return false;
  	}
  	
  	$productid = (int)$obj->product_side->productid;
  	
  	$obj = $this->query('product_key','products',"productid=$productid");
  	
  	if(!$obj){
  		return false;
  	}
  	
  	$product_key = (string)$obj->product->product_key;
  	
  	return self::IMAGE_URL.'/1/'.$rootcolorid.'/'.$productsideid.'/'.$product_key.'.jpg';
  }
  
  /**
   * Returns the URL of the small product image
   * 
   * @param int $rootcolorid
   * @param int $productsideid
   * @return string Image URL
   */
  public function getSmallProductImageUrl($rootcolorid, $productsideid){
  	
  	$obj = $this->query('productid','product_sides',"productsideid='$productsideid'");
  	
  	if(!$obj){
  		return false;
  	}
  	
  	$productid = (int)$obj->product_side->productid;
  	
  	$obj = $this->query('product_key','products',"productid=$productid");
  	
  	if(!$obj){
  		return false;
  	}
  	
  	$product_key = (string)$obj->product->product_key;
  	
  	return self::IMAGE_URL.'/2/'.$rootcolorid.'/'.$productsideid.'/'.$product_key.'.jpg';
  }  
  
  /**
   * Returns the URL of the large design image
   * 
   * @param int $productsideid
   * @return string Image URL
   */
  public function getLargeDesignUrl($productsideid){
  	
  	$obj = $this->query('side_key','product_sides',"productsideid='$productsideid'");
  	
  	if(!$obj){
  		return false;
  	}
  	
  	$side_key = (string)$obj->product_side->side_key;
  	
  	return self::IMAGE_URL.'/9/'.$productsideid.'/'.$side_key.'.jpg';
  }  
  
  /**
   * Returns the URL of the small design image
   * 
   * @param int $productsideid
   * @return string Image URL
   */
  public function getSmallDesignUrl($productsideid){
  	
  	$obj = $this->query('side_key','product_sides',"productsideid='$productsideid'");
  	
  	if(!$obj){
  		return false;
  	}
  	
  	$side_key = (string)$obj->product_side->side_key;
  	
  	return self::IMAGE_URL.'/10/'.$productsideid.'/'.$side_key.'.jpg';
  }  

  /**
   * Returns the URL of the large root image
   * 
   * @param int $rootcolorid
   * @param int $side_order Starting at 0 which is usually the front of the product
   * @return string Image URL
   */
  public function getLargeRootImageUrl($rootcolorid, $side_order){
  	
  	return self::IMAGE_URL.'/60/'.$rootcolorid.'/'.$side_order.'/blank.jpg';
  }

  /**
   * Returns the URL of the small root image
   * 
   * @param int $rootcolorid
   * @param int $side_order Starting at 0 which is usually the front of the product
   * @return string Image URL
   */
  public function getSmallRootImageUrl($rootcolorid, $side_order){
  	
  	return self::IMAGE_URL.'/61/'.$rootcolorid.'/'.$side_order.'/blank.jpg';
  }  
  
  /**
   * Returns the URL of the large root product mask image
   * 
   * @param int $rootid
   * @param int $side_order Starting at 0 which is usually the front of the product
   * @return string Image URL
   */
  public function getLargeRootProductMaskUrl($rootid, $side_order){
  	
  	return self::IMAGE_URL.'/62/'.$rootid.'/'.$side_order.'/mask.jpg';
  }    
  
  /**
   * Returns the URL of the small root product mask image
   * 
   * @param int $rootid
   * @param int $side_order Starting at 0 which is usually the front of the product
   * @return string Image URL
   */
  public function getSmallRootProductMaskUrl($rootid, $side_order){
  	
  	return self::IMAGE_URL.'/63/'.$rootid.'/'.$side_order.'/mask.jpg';
  }    
  
  
// Session Methods
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////
  
  /**
   * Sets the session key and ensures it actually exists
   * 
   * @param string $session_key
   * @return bool True if set
   */
  public function setSessionKey($session_key){
  	
		// Session was verified (Must set first as the PFQL requres it)
		$this->session_key = $session_key;
		  	
	  // Verify the session really exists
		$success = $this->query("custid","sessions","session_key='{$session_key}'");

		if(!$success){
			// Invalid session key
		  $this->session_key = "";
		  return false;
		}
	  
	  return true;
  }  
  
  /**
   * Sets a session key which allows this application access to the user's account based on an auth token
   * 
   * @param string $auth_token Used to get the session key
   * @return True if set
   */
  public function setSessionFromAuthToken($auth_token){
  	
  	if(!$auth_token){
  		return false;
  	}
  	
	  $params[] = "auth_token={$auth_token}";
  	
  	$xml = $this->makeRequest("Printfection.Auth.getSession", $params);

  	if(!$xml){
  		return false;
  	}
  	
		// Set the session key
		return $this->setSessionKey($xml->session_key, false);
  }
    
	/**
	 * Deletes a session
	 * 
	 * @param string $session_key
	 * @return bool True if success
	 */
	public function deleteSession($session_key){
		
		$params[] = "session_key=$session_key";
		
		$obj = $this->makeRequest("Printfection.Auth.deleteSession", $params);
		
		if(!$obj){
			// Could not delete session
			return false;
		}
		
		// Deleted session
		$this->session_key = "";
		return true;
	}    
	
  /**
   * Creates and returns the auth URL
   * 
   * @param array $required_permissions Required permissions array('image_set'=>'write',...)
   * @param array $suggested_permissions Suggested permissions array('image_set'=>'delete',...)
   * @return string URL
   */
  public function getAuthUrl($required_permissions='',$suggested_permissions=''){
  	
  	if(!is_array($required_permissions)){
  		// Set permissions to require everything
  		$required_permissions = array('image_sets'=>'delete','add_image_set'=>'write','stores'=>'delete','add_store'=>'write');
  	}
  	if(!is_array($suggested_permissions)){
  		// Set permissions to suggest everything
  		$suggested_permissions = array('image_sets'=>'delete','add_image_set'=>'write','stores'=>'delete','add_store'=>'write');
  	}
  	
	  $params[] = "api_key=".$this->api_key;
	  $params[] = "response_format=".$this->response_format;
	  $params[] = "version=".$this->version;
	  $params[] = "permissions=".json_encode(array('required'=>$required_permissions,'suggested'=>$suggested_permissions));

	  return self::AUTH_URL.'?'.$this->createQueryString($params);
  }	
	

// PFQL Methods
//////////////////////////////////////////////////////  
//////////////////////////////////////////////////////

	/**
	 * Runs a PF query and returns the results in an array
	 * 
	 * @param mixed $columns List of columns either 'column_name1,column_name2' or array(column_name1,column_name2)
	 * @param string $table_name
	 * @param string $where Where clause without the WHERE
	 * @param string $order_by Order By clause with the ORDER BY
	 * @param string $limit Limit such as '1' or '5,10' for offsets
	 * @return array Results as an array
	 */
	public function query($columns, $table_name, $where='1', $order_by='', $limit=''){
		
		if(!$columns || !$table_name){
			return false;
		} 
		
	  if(is_array($columns)){
	    // Join all the column names into a single string
	    
	    $temp = "";
	    foreach($columns as $a=>$b){
	      
	      if(is_numeric($a)){
	        // This is an indexed array so just append the values
	        $temp .= "$b,";
	        
	      }else{
	        // This is an associative array so set $a AS $b
	        $temp .= "$a AS $b,";
	      }
	    }
	    $columns = rtrim($temp, "\x2C");
	  }	  
	  
	  if($order_by){
	  	$order_by = "ORDER BY $order_by";
	  }
	  
	  if($limit){
	  	$limit = "LIMIT $limit";
	  }
	  
    // Setup the whole query
    $query="SELECT $columns FROM $table_name WHERE $where $order_by $limit";

    // Run the query
    $params[] = "query={$query}";
  	return $this->makeRequest("Printfection.PFQL.query", $params);
	}	  
  
  
// Image Methods
//////////////////////////////////////////////////////  
//////////////////////////////////////////////////////
  
	/**
	 * Uploads an image and returns the image's ID
	 * 
	 * @param string $image_path The path to the image on the local file system
	 * @param string $name The name of the image once uploaded
	 * @param string $imagesetid The image set to put the image into
	 * @param string $keywords Comma separated list of keywords
	 * @return int The image ID, false if fail
	 */
	public function upload($image_path, $name, $imagesetid, $keywords=NULL){
		
		// Clear the last error
		$this->clearLastError();		

		if(!$name){
			return false;
		}
		
		if(!$imagesetid){
			return false;
		}
		
		if(!is_readable($image_path)){
			return false;
		}
		
		// Add required params
	  $params[] = "api_key=".$this->api_key;
	  $params[] = "response_format=".$this->response_format;
	  $params[] = "version=".$this->version;
  	$params[] = "session_key=".$this->getSessionKey();

		$params[] = "method=Printfection.Images.upload";
		$params[] = "name=$name";
		$params[] = "imagesetid=$imagesetid";
		
		if(isset($keywords)){
			$params[] = "keywords=$keywords";
		}

	  $params[] = "api_sig={$this->createSignature($params)}";

		foreach($params as $a=>$b){
		  $key = substr($b, 0,strpos($b, "="));
		  $value = substr($b,strpos($b,"=")+1,strlen($b));
		  $data[$key] = $value;
		}

		$data['file'] = "@$image_path";

		// Setup the curl settings
		$curlHandle = curl_init();
		curl_setopt($curlHandle,CURLOPT_POST,1);
		curl_setopt($curlHandle,CURLOPT_URL, self::UPLOAD_URL);
		curl_setopt($curlHandle,CURLOPT_VERBOSE, 0);
		curl_setopt($curlHandle,CURLOPT_POSTFIELDS, $data);
		curl_setopt($curlHandle,CURLOPT_TIMEOUT, 120);
		curl_setopt($curlHandle,CURLOPT_RETURNTRANSFER, true);
		
		$content = curl_exec($curlHandle);
		
		if(curl_errno($curlHandle)){
			// Curl error
			$this->last_error = array('Curl Error ('.curl_errno($curlHandle).') '.curl_error($curlHandle));
			return false;
		}
		
		curl_close($curlHandle);
		
		$obj = $this->parseResponse($content);
		
		
		return (int)$obj->imageid[0];
	}

	/**
	 * Sets information for an image
	 * 
	 * @param int $imageid 
	 * @param int $imagesetid Moves the image to the specified image set
	 * @param string $name
	 * @param string $keywords
	 * @return bool True if everything was set
	 */
	public function setImageInfo($imageid,$imagesetid=NULL,$name=NULL,$keywords=NULL){

		if(!$imageid){
			return false;
		}
		
		$params[] = "imageid=$imageid";
		
		if(isset($imagesetid)){
			$params[] = "imagesetid=$imagesetid";
		}
		if(isset($name)){
			$params[] = "name=$name";
		}
		if(isset($keywords)){
			$params[] = "keywords=$keywords";
		}
		
		$obj = $this->makeRequest('Printfection.Images.set',$params);
		
		if(!$obj){
			return false;
		}
		
		// Everything set successfully
		return true;
	}
	
	/**
	 * Delets an image
	 * 
	 * @param $imageid
	 * @return bool True if deleted
	 */
	public function deleteImage($imageid){
		
		if(!$imageid){
			return false;
		}
		
		$params[] = "imageid=$imageid";
		
		$obj = $this->makeRequest('Printfection.Images.delete', $params);

		if(!$obj){
			return false;
		}
		
		// Successfully deleted
		return true;
	}	

	
// Image Set Methods
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////
	
	/**
	 * Create a new image set
	 * 
	 * @param $name The name of the image set
	 * @return int The image set ID or false
	 */
	public function createImageSet($name){
		
		if(!$name){
			return false;
		}
		
		$params[] = "name=$name";
		
		$obj = $this->makeRequest('Printfection.ImageSets.create', $params);
		
		if(!$obj){
			return false;
		}
		
		return (int)$obj->imagesetid;
	}
	
	/**
	 * Sets information for an image set
	 * 
	 * @param int $imagesetid 
	 * @param string $name
	 * @return bool True if everything was set
	 */
	public function setImageSetInfo($imagesetid,$name){

		if(!$imagesetid){
			return false;
		}
		
		$params[] = "imagesetid=$imagesetid";
		
		if($name){
			$params[] = "name=$name";
		}
		
		$obj = $this->makeRequest('Printfection.ImageSets.set',$params);
		
		if(!$obj){
			return false;
		}
		
		// Everything set successfully
		return true;
	}	

	/**
	 * Delets an image set
	 * 
	 * @param $imagesetid
	 * @return bool True if deleted
	 */
	public function deleteImageSet($imagesetid){
		
		if(!$imagesetid){
			return false;
		}
		
		$params[] = "imagesetid=$imagesetid";
		
		$obj = $this->makeRequest('Printfection.ImageSets.delete', $params);

		if(!$obj){
			return false;
		}
		
		// Successfully deleted
		return true;
	}
	

// Root Product Methods
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////

	/**
	 * Gets the price of a root product
	 * 
	 * @param int $rootid
	 * @param int $rootcolorid
	 * @param int $rootsizeid
	 * @param int qty
	 * @param int $num_sides_customized
	 * @return bool True if set
	 */
	public function getRootProductPrice($rootid, $rootcolorid, $rootsizeid, $qty, $num_sides_customized){
		
		if(!$rootid){
			return false;
		}
		if(!$rootcolorid){
			return false;
		}
		if(!$rootsizeid){
			return false;
		}
		if(!$qty){
			return false;
		}
		if(!$num_sides_customized){
			return false;
		}
		
		$params[] = "rootid=$rootid";
		$params[] = "rootcolorid=$rootcolorid";
		$params[] = "rootsizeid=$rootsizeid";
		$params[] = "qty=$qty";
		$params[] = "num_sides_customized=$num_sides_customized";
		
		$obj = $this->makeRequest('Printfection.RootProducts.getPrice',$params);
		
		if(!$obj){
			return false;
		}
		
		return (float)$obj->price;
	}		
	
	
// Product Methods
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////	
	
	/**
	 * Create a new product
	 * 
	 * @param int $rootid
	 * @param int $sectionid The section to put the product into
	 * @param array $rootcolorids Root color IDs for this product Array(colorid, colorid, etc...)
	 * @param array $specs An array of specs for this product array(array('side'=>$side, 'imageid'=>$imageid, 'position'=>$position,
	 * 																														  'center_percent_x'=>$center_percent_x, 'center_percent_y'=>$center_percent_y, 'rotation'=>$rotation, 'height'=>$height),
	 * 																															array(...)
	 * 																															)
	 * @return int Product ID, false if failed
	 */
	public function createProduct($rootid, $sectionid, $rootcolorids, $specs){
		
		if(!$rootid){
			return false;
		}
		if(!$sectionid){
			return false;
		}
		if(!is_array($rootcolorids)){
			return false;
		}
		if(!is_array($specs)){
			return false;
		}
		
		$params[] = "rootid=$rootid";
		$params[] = "sectionid=$sectionid";
		$params[] = "rootcolorids=".implode(",",$rootcolorids);
		$params[] = "specs=".json_encode($specs);

		$obj = $this->makeRequest('Printfection.Products.create',$params);
		
		if(!$obj){
			return false;
		}
		
		return (int)$obj->productid;
	}
	
	/**
	 * Sets information about a product
	 * 
	 * @param int $productid
	 * @param string $title
	 * @param string $description
	 * @param int $primary_rootcolorid
	 * @param int $primary_productsideid
	 * @param array $commission array(array('range' => x, 'price'=> x),...)
	 * @param int $product_order The order of this product in relation to all other products in this section
	 * @param int $sectionid The section to move this product into
	 * @return bool True if success else false
	 */
	public function setProductInfo($productid, $title=NULL, $description=NULL, $primary_rootcolorid=NULL, $primary_productsideid=NULL, $commission=array(), $product_order=NULL, $sectionid=NULL){
		
		if(!$productid){
			return false;
		}
		
		$params[] = "productid=$productid";
		
		if(isset($title)){
			$params[] = "title=$title";
		}
		if(isset($description)){
			$params[] = "description=$description";
		}
		if(isset($primary_rootcolorid)){
			$params[] = "primary_rootcolorid=$primary_rootcolorid";
		}
		if(isset($primary_productsideid)){
			$params[] = "primary_productsideid=$primary_productsideid";
		}
		if(is_array($commission) && count($commission)){
			$params[] = "commission=".json_encode($commission);
		}
		if(isset($product_order)){
			$params[] = "product_order=$product_order";
		}
		if(isset($sectionid)){
			$params[] = "sectionid=$sectionid";
		}

		$obj = $this->makeRequest('Printfection.Products.set',$params);
		
		if(!$obj){
			return false;
		}
		
		// Everything set successfully
		return true;
	}
	
	/**
	 * Delets a product
	 * 
	 * @param int $productid
	 * @return bool True if deleted
	 */
	public function deleteProduct($productid){
		
		if(!$productid){
			return false;
		}
		
		$params[] = "productid=$productid";
		
		$obj = $this->makeRequest('Printfection.Products.delete', $params);

		if(!$obj){
			return false;
		}
		
		// Successfully deleted
		return true;
	}		
	
	/**
	 * Adds a color to a product
	 * 
	 * @param int $productid
	 * @param int $rootcolorid
	 * @return bool True if added
	 */
	public function addColor($productid, $rootcolorid){
		
		if(!$productid){
			return false;
		}
		if(!$rootcolorid){
			return false;
		}
		
		$params[] = "productid=$productid";
		$params[] = "rootcolorid=$rootcolorid";
		
		$obj = $this->makeRequest('Printfection.Products.addColor', $params);

		if(!$obj){
			return false;
		}
		
		return true;
	}			
	
	/**
	 * Removes a color from a product
	 * 
	 * @param int $productid
	 * @param int $rootcolorid
	 * @return bool True if removed
	 */
	public function removeColor($productid, $rootcolorid){
		
		if(!$productid){
			return false;
		}
		if(!$rootcolorid){
			return false;
		}
		
		$params[] = "productid=$productid";
		$params[] = "rootcolorid=$rootcolorid";
		
		$obj = $this->makeRequest('Printfection.Products.removeColor', $params);

		if(!$obj){
			return false;
		}
		
		return true;
	}		
	
	
	/**
	 * Gets the pricing for a product
	 * 
	 * @param string $productid
	 * @param int $rootcolorid
	 * @param int $range Optional price range starting at 1
	 * @return array The pricing for the range(s)
	 */
	public function getProductPricing($productid, $rootcolorid, $range=NULL){
		
		$params[] = "productid=$productid";
		$params[] = "rootcolorid=$rootcolorid";
		
		if($range){
			$params[] = "range=$range";
		}
		
		$obj = $this->makeRequest('Printfection.Products.getPricing', $params);
		
		if(!$obj){
			return false;
		}
		
		if($range){
			return $obj->range;
		}else{
			return $obj;
		}
	}		
	
	/**
	 * Gets the final price of a product
	 * 
	 * @param string $productid
	 * @param int $rootcolorid
	 * @param int $rootsizeid
	 * @param int $qty The quantity which will be purchased
	 * @return float $price
	 */
	public function getProductPrice($productid, $rootcolorid, $rootsizeid, $qty){
		
		if(!$productid){
			return false;
		}
		if(!$rootcolorid){
			return false;
		}
		if(!$rootsizeid){
			return false;
		}
		if(!$qty){
			return false;
		}
		
		$params[] = "productid=$productid";
		$params[] = "rootcolorid=$rootcolorid";
		$params[] = "rootsizeid=$rootsizeid";
		$params[] = "qty=$qty";
		
		$obj = $this->makeRequest('Printfection.Products.getPrice', $params);
		
		if(!$obj){
			return false;
		}
		
		return (float)$obj->price;
	}	

	
// Product Side Methods
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////

	/**
	 * Sets information about a product's side
	 * 
	 * @param int $productsideid
	 * @param bool $vertical
	 * @return bool True if success else false
	 */
	public function setProductSideInfo($productsideid, $vertical=NULL){
		
		if(!$productsideid){
			return false;
		}
		
		$params[] = "productsideid=$productsideid";
		
		if(isset($vertical)){
			$params[] = "vertical=$vertical";
		}

		$obj = $this->makeRequest('Printfection.Products.Sides.set',$params);
		
		if(!$obj){
			return false;
		}
		
		// Everything set successfully
		return true;
	}	
	
	
// Product Layer Methods
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////
	
	/**
	 * Sets information for the layer on a product
	 * 
	 * @param int $productlayerid
	 * @param int $imageid
	 * @param float $center_percent_x As a decimal so 10% would be .1
	 * @param float $center_percent_y As a decimal so 10% would be .1
	 * @param float $height In inches
	 * @param int $position Starting at 1
	 * @param int $rotation Can be 0,90,180,270
	 * @return bool True if added
	 */
	public function setLayerInfo($productlayerid, $imageid=NULL, $center_percent_x=NULL,$center_percent_y=NULL,$height=NULL,$position=NULL,$rotation=NULL){
		
		if(!$productlayerid){
			return false;
		}
		
		$params[] = "productlayerid=$productlayerid";
		
		if(isset($imageid)){
			$params[] = "imageid=$imageid";
		}
		if(isset($center_percent_x)){
			$params[] = "center_percent_x=$center_percent_x";
		}
		if(isset($center_percent_y)){
			$params[] = "center_percent_y=$center_percent_y";
		}
		if(isset($height)){
			$params[] = "height=$height";
		}
		if(isset($position)){
			$params[] = "position=$position";
		}
		if(isset($rotation)){
			$params[] = "rotation=$rotation";
		}
		
		$obj = $this->makeRequest('Printfection.Products.Sides.Layers.set', $params);

		if(!$obj){
			return false;
		}
		
		return true;
	}
		
	/**
	 * Adds a layer to the side of a product
	 * 
	 * @param int $productsideid
	 * @param int $imageid
	 * @param float $center_percent_x As a decimal so 10% would be .1
	 * @param float $center_percent_y As a decimal so 10% would be .1
	 * @param float $height In inches
	 * @param int $position Starting at 1
	 * @param int $rotation Can be 0,90,180,270
	 * @return bool True if added
	 */
	public function addLayer($productsideid, $imageid=NULL, $center_percent_x=NULL,$center_percent_y=NULL,$height=NULL,$position=NULL,$rotation=NULL){
		
		if(!$productsideid){
			return false;
		}
		
		$params[] = "productsideid=$productsideid";
		
		if(isset($imageid)){
			$params[] = "imageid=$imageid";
		}
		if(isset($center_percent_x)){
			$params[] = "center_percent_x=$center_percent_x";
		}
		if(isset($center_percent_y)){
			$params[] = "center_percent_y=$center_percent_y";
		}
		if(isset($height)){
			$params[] = "height=$height";
		}
		if(isset($position)){
			$params[] = "position=$position";
		}
		if(isset($rotation)){
			$params[] = "rotation=$rotation";
		}
		
		$obj = $this->makeRequest('Printfection.Products.Sides.Layers.add', $params);

		if(!$obj){
			return false;
		}
		
		return true;
	}		
	
	/**
	 * Removes a layer from the side of a product
	 * 
	 * @param int $productlayerid
	 * @return bool True if removed
	 */
	public function removeLayer($productlayerid){
		
		if(!$productlayerid){
			return false;
		}
		
		$params[] = "productlayerid=$productlayerid";
		
		$obj = $this->makeRequest('Printfection.Products.Sides.Layers.remove', $params);

		if(!$obj){
			return false;
		}
		
		return true;
	}	
	
	/**
	 * Fits a layer within the position
	 * 
	 * @param int $productlayerid
	 * @return bool True if set
	 */
	public function fitLayer($productlayerid){
		
		$params[] = "productlayerid=$productlayerid";
		
		$obj = $this->makeRequest('Printfection.Products.Sides.Layers.fit',$params);

		if(!$obj){
			return false;
		}
		
		return true;
	}
	
	/**
	 * Fills a layer within the position
	 * 
	 * @param int $productlayerid
	 * @return bool True if set
	 */
	public function fillLayer($productlayerid){
		
		$params[] = "productlayerid=$productlayerid";
		
		$obj = $this->makeRequest('Printfection.Products.Sides.Layers.fill',$params);

		if(!$obj){
			return false;
		}
		
		return true;
	}	

	
// Store Methods
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////		
	
	/**
	 * Creates a new store
	 * 
	 * @param string $name
	 * @param string $url The relative URL of the store without the leading slash
	 * @param int $store_access Can be 0=open, 1=closed, 2=hidden
	 * @return int $storeid
	 */
	public function createStore($name, $url, $store_access=0){

		if(!$name){
			return false;
		}
		if(!$url){
			return false;
		}
		
		// Strip slashes from URL
		$url = str_replace("/","",$url);
		
		$params[] = "name=$name";
		$params[] = "url=$url";
		$params[] = "store_access=$store_access";

		$obj = $this->makeRequest('Printfection.Stores.create', $params);
		
		if(!$obj){
			return false;
		}				
		
		return (int)$obj->storeid;		
	}
	
	/**
	 * Sets information for a store
	 * 
	 * @param int $storeid The ID of the store
	 * @param string $name The name of the store
	 * @param string $description The description of the store
	 * @param string $url The url of the store
	 * @param int $store_access The access level of the store (0=open, 1=close, 2=hidden)
	 * @param int $logo_imageid Set to 0 to remove the logo
	 * @param int $logo_width The width of the logo in pixels
	 * @return bool True if everything was set
	 */
	public function setStoreInfo($storeid, $name=NULL,$description=NULL,$url=NULL,$store_access=NULL, $logo_imageid=NULL,$logo_width=NULL){
		
		if(!$storeid){
			return false;
		}
		
		$params[] = "storeid=$storeid";
		
		if(isset($name)){
			$params[] = "name=$name";	
		}
		if(isset($description)){
			$params[] = "description=$description";	
		}
		if(isset($url)){
			$params[] = "url=$url";	
		}
		if(isset($store_access)){
			$params[] = "store_access=$store_access";	
		}				
		if(isset($logo_imageid)){
			$params[] = "logo_imageid=$logo_imageid";
		}
		if(isset($logo_width)){
			$params[] = "logo_width=$logo_width";
		}

		$obj = $this->makeRequest('Printfection.Stores.set',$params);
		
		if(!$obj){
			return false;
		}
		
		// Everything set successfully
		return true;
	}	
	
	/**
	 * Delets a store
	 * 
	 * @param $storeid
	 * @return bool True if deleted
	 */
	public function deleteStore($storeid){
		
		if(!$storeid){
			return false;
		}
		
		$params[] = "storeid=$storeid";
		
		$obj = $this->makeRequest('Printfection.Stores.delete', $params);

		if(!$obj){
			return false;
		}
		
		// Successfully deleted
		return true;
	}	
	
	
// Store Section Methods
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////		
	
	/**
	 * Creates a new section
	 * 
	 * @param int $parent_sectionid The ID of the section to make the new section within
	 * @param string $name The name of the section
	 * @return int The ID of the new section
	 */
	public function createSection($parent_sectionid, $name){
		
		if(!$parent_sectionid){
			return false;
		}
		if(!$name){
			return false;
		}
		
		$params[] = "parent_sectionid=$parent_sectionid";
		$params[] = "name=$name";

		$obj = $this->makeRequest('Printfection.Sections.create', $params);
		
		if(!$obj){
			return false;
		}
		
		return (int)$obj->sectionid;
	}	
	
	/**
	 * Sets information for a section
	 *
	 * @param int $sectionid
	 * @param string $name
	 * @param string $teaser
	 * @param string $description
	 * @param int $imageid
	 * @param bool $image_transparency
	 * @param int $image_width
	 * @param int $section_order
	 * @param bool $hidden
	 * @return bool True if everything was set
	 */
	public function setSectionInfo($sectionid,$name=NULL,$teaser=NULL,$description=NULL,$imageid=NULL,$image_transparency=NULL,$image_width=NULL,$section_order=NULL,$hidden=NULL){
		
		if(!$sectionid){
			return false;
		}
		
		$params[] = "sectionid=$sectionid";
		
		if(isset($name)){
			$params[] = "name=$name";	
		}
		if(isset($teaser)){
			$params[] = "teaser=$teaser";
		}
		if(isset($description)){
			$params[] = "description=$description";	
		}
		if(isset($imageid)){
			$params[] = "imageid=$imageid";	
		}
		if(isset($image_transparency)){
			$params[] = "image_transparency=$image_transparency";
		}
		if(isset($image_width)){
			$params[] = "image_width=$image_width";
		}
		if(isset($section_order)){
			$params[] = "section_order=$section_order";
		}
		if(isset($hidden)){
			$params[] = "hidden=$hidden";
		}

		$obj = $this->makeRequest('Printfection.Sections.set',$params);
		
		if(!$obj){
			return false;
		}
		
		// Everything set successfully
		return true;
	}	
		
	/**
	 * Delets a section
	 * 
	 * @param $sectionid
	 * @return bool True if deleted
	 */
	public function deleteSection($sectionid){
		
		if(!$sectionid){
			return false;
		}
		
		$params[] = "sectionid=$sectionid";
		
		$obj = $this->makeRequest('Printfection.Sections.delete', $params);

		if(!$obj){
			return false;
		}
		
		// Successfully deleted
		return true;
	}	
	
			
// Cart Methods
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////		
		
	/**
	 * Creates a new cart
	 * 
	 * @return string The cart key
	 */
	public function createCart(){
		
		$obj = $this->makeRequest('Printfection.Carts.create');
		
		if(!$obj){
			return false;
		}
		
		return (string)$obj->cart_key;
	}	
	
	/**
	 * Adds a product to the cart
	 * 
	 * @param string $cart_key
	 * @param int $productid
	 * @param int $rootcolorid
	 * @param int $rootsizeid
	 * @param int $qty
	 * @return bool True if added
	 */
	public function addProductToCart($cart_key, $productid, $rootcolorid, $rootsizeid, $qty){
		
		if(!$cart_key){
			return false;
		}
		if(!$productid){
			return false;
		}
		if(!$rootcolorid){
			return false;
		}
		if(!$rootsizeid){
			return false;
		}
		if(!$qty){
			return false;
		}
		
		$params[] = "cart_key=$cart_key";
		$params[] = "productid=$productid";
		$params[] = "rootcolorid=$rootcolorid";
		$params[] = "rootsizeid=$rootsizeid";
		$params[] = "qty=$qty";
		
		$obj = $this->makeRequest('Printfection.Carts.addProduct', $params);

		if(!$obj){
			return false;
		}
		
		return true;
	}

	/**
	 * Sets the quantity of a product in the cart
	 * 
	 * @param string $cart_key
	 * @param int $productid
	 * @param int $rootcolorid
	 * @param int $rootsizeid
	 * @param int $qty
	 * @return bool True if set
	 */
	public function setProductQuantity($cart_key, $productid, $rootcolorid, $rootsizeid, $qty){
		
		if(!$cart_key){
			return false;
		}
		if(!$productid){
			return false;
		}
		if(!$rootcolorid){
			return false;
		}
		if(!$rootsizeid){
			return false;
		}
		if(!$qty){
			return false;
		}
		
		$params[] = "cart_key=$cart_key";
		$params[] = "productid=$productid";
		$params[] = "rootcolorid=$rootcolorid";
		$params[] = "rootsizeid=$rootsizeid";
		$params[] = "qty=$qty";
		
		$obj = $this->makeRequest('Printfection.Carts.setProductQuantity', $params);

		if(!$obj){
			return false;
		}
		
		return true;
	}	

	/**
	 * Removes a product from a cart
	 * 
	 * @param string $cart_key
	 * @param int $productid
	 * @param int $rootcolorid
	 * @param int $rootsizeid
	 * @return bool True if removed
	 */
	public function removeProductFromCart($cart_key, $productid, $rootcolorid, $rootsizeid){
		
		if(!$cart_key){
			return false;
		}
		if(!$productid){
			return false;
		}
		if(!$rootcolorid){
			return false;
		}
		if(!$rootsizeid){
			return false;
		}

		$params[] = "cart_key=$cart_key";
		$params[] = "productid=$productid";
		$params[] = "rootcolorid=$rootcolorid";
		$params[] = "rootsizeid=$rootsizeid";
		
		$obj = $this->makeRequest('Printfection.Carts.removeProduct', $params);

		if(!$obj){
			return false;
		}
		
		return true;
	}	
	
	/**
	 * Clears all products from a cart
	 * 
	 * @param string $cart_key
	 * @return bool True if cleared
	 */
	public function clearCart($cart_key){
		
		if(!$cart_key){
			return false;
		}

		$params[] = "cart_key=$cart_key";
		
		$obj = $this->makeRequest('Printfection.Carts.clear', $params);

		if(!$obj){
			return false;
		}
		
		return true;
	}	

	/**
	 * Get all shipping methods applicable to a cart
	 * 
	 * @param string $cart_key
	 * @return array Shipping Methods
	 */
	public function getCartShippingMethods($cart_key){
		
		if(!$cart_key){
			return false;
		}

		$params[] = "cart_key=$cart_key";
		
		$obj = $this->makeRequest('Printfection.Carts.getShippingMethods', $params);

		if(!$obj){
			return false;
		}
		
		return true;
	}	
	
	/**
	 * Creates and returns a URL to redirect a user to the PF checkout
	 * 
	 * @param string $cart_key
	 * @param int $storeid
	 * @param int $shippigmethodid
	 * @param string $landing_page The page to send the user to in the checkout. Can be 'checkout' or 'cart'
	 * @return string URL
	 */
	public function getCheckoutUrl($cart_key, $storeid, $shippingmethodid=NULL, $landing_page='checkout'){
		
	  $params[] = "api_key=".$this->api_key;
	  $params[] = "session_key=".$this->getSessionKey();
	  $params[] = "response_format=".$this->response_format;
	  $params[] = "version=".$this->version;
	  
	  $params[] = "cart_key=".$cart_key;
	  $params[] = "storeid=".$storeid;
	  $params[] = "landing_page=$landing_page";
	  
	  if(isset($shippingmethodid)){
	  	$params[] = "shippingmethodid=".$shippingmethodid;
	  }

	  $query = $this->createQueryString($params);
	  
	  return self::CHECKOUT_URL.'?'.$query;		
	}
		

// Private Methods
//////////////////////////////////////////////////////
//////////////////////////////////////////////////////
	
	/**
	 * Makes a request to the API
	 * 
	 * @param string $method
	 * @param array $params Array(param_name => $value)
	 * @return Array holding the results or False if error
	 */
	private function makeRequest($method, $params=array()){
		
		// Clear the last error
		$this->clearLastError();
		
		// Add required params
	  $params[] = "api_key=".$this->api_key;
	  $params[] = "response_format=".$this->response_format;
	  $params[] = "version=".$this->version;
	  $params[] = "method=$method";

	  if($this->hasSession()){

	  	// See if the session key as already been added
	  	$add_session_key = true;
	  	foreach($params as $a=>$b){
	  		if(strtolower(substr($b, 0,12)) == 'session_key='){
	  			$add_session_key = false;
	  			break;
	  		}
	  	}
	  	
	  	if($add_session_key){
	  		$params[] = "session_key=".$this->getSessionKey();
	  	}
	  }
	  
	  // Create the query string and build the url
	  $url = self::REQUEST_URL.'?'.$this->createQueryString($params);

	  // Send the request
		$curlHandle = curl_init(); 
		curl_setopt($curlHandle, CURLOPT_URL, $url);
		curl_setopt($curlHandle, CURLOPT_HEADER, 0);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
		curl_setopt($curlHandle, CURLOPT_VERBOSE, 1);
		
		$content = curl_exec($curlHandle);
		
		if(curl_errno($curlHandle)){
			// Curl error
			$this->last_error = array('Curl Error ('.curl_errno($curlHandle).') '.curl_error($curlHandle));
			return false;
		}
		
		curl_close($curlHandle);

		return $this->parseResponse($content);
	}
	
	
	/**
	 * Creates the query string from an array of parameters
	 * 
	 * @param array $params Example: Array(0 => api_key='', 1 => response_format=XML ...)
	 * @return string The parameters which can be sent with the api_sig
	 */
	private function createQueryString($params){
		
		// Must create the signature before URL encoding the params
	  $api_sig = $this->createSignature($params);

	  foreach($params as $a=>$b){
	    $params[$a] = substr($b, 0,strpos($b, "="))."=".urlencode(substr($b,strpos($b,"=")+1,strlen($b)));
	  }

	  $params = implode("&",$params);
	  
	  return "{$params}&api_sig={$api_sig}";		
	} 
	
	/**
	 * Creates the signature for a set of parameters
	 * 
	 * @param array $params Must NOT be urlencoded
	 * @return string $signature
	 */
	private function createSignature($params){
		
		sort($params);
		
	  $params_string = implode("", $params);

	  return md5($params_string.$this->secret_key);		
	}
	
	/**
	 * Parses the response of a call to the API
	 * Checks for global, method and attribute level errors
	 * 
	 * @param string $xml
	 * @return object SimpleXMLElement
	 */
	private function parseResponse($xml){
		
		if(!$xml){
			return false;
		}

		// Create the objext
		$xmlObject = simplexml_load_string($xml);
		$xmlObject = $xmlObject->children();
		
		if($xmlObject->error){
			// Global error
			$this->last_error = $xmlObject->error;
			return false;
		}
		
		if(!$xmlObject->children()){
			// No more children so return what we have
			return $xmlObject;
		}		
		
		// Drop into the method element
		$xmlObject = $xmlObject->children();
		
		if($xmlObject->error){
			// Method level error
			$this->last_error = $xmlObject->error;
			return false;
		}		
		
		// Find attribute level errors
		foreach($xmlObject as $a=>$b){
			if($b->error){
				array_push($this->last_error,array($a=>$b->error));
			}
		} 

		if(count($this->last_error)){
			// Partial errors occured
			return false;
		}		
		
		// Response was okay
		return $xmlObject;
	}
	
}

?>