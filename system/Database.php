<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');

class Database{
    
    private $DB_TYPE = 'mysql';
    private $DB_HOST = 'mysql.scantee.com';
    private $DB_NAME = 'scantee_main';

    public function init($db = false) { //$db is the database you wish to search
        
        require(CONFIG_PATH.'pass.php');
        
        if($db):
            $dbn = $db;
        else:
            $dbn = $this->DB_NAME;        
        endif;
        
        $data = new PDO('mysql:host=mysql.gitlw.com;dbname='.$dbn, $DB_USER, $DB_PASS);
        $data->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        return $data;

    }
   

}