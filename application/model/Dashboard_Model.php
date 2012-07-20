<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');

class Dashboard_Model extends Model {

    public function checkLogin($arg=false) {

        $db = parent::init();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);

        $q = $db->prepare("SELECT * FROM mvc_user WHERE mvc_user_uname = :name 
            AND mvc_user_password = :pass");     
        $q->bindParam(':name', $arg['username']);
        $q->bindParam(':pass', Encrypt::init($arg['password'],HASH_KEY));
        $q->execute();

        if($q->rowCount() == 1){
            return $q->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }         
    }

}
