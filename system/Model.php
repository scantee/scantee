<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');

class Model extends Database{
    

    public function init($db = false) {
        
        // instantiates PDO object in Database
        return parent::init($db);
        
    }
  

}
