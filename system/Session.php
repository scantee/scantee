<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');

class Session {
    
    
    private $_session = false;

    public function init() {
        
        if($this->_session == false && empty($_SESSION)):
            session_start();
            $this->_session = true;            
        endif;
    }    

    public function setVal($ary) {
        
        foreach ($ary as $key => $value) {
            $_SESSION[$key] = $value;
        }
        
        
    }
    
    public function getVal($key) {
        
        if(!empty($_SESSION[$key]))
            return $_SESSION[$key];
        
        else 
            return false;            
        
    }
    
    public function display() {
        
        echo '<pre>';
         print_r($_SESSION);
        echo '</pre>';
        
    }  
    
    public function destroy() {
        
        $_SESSION = array();
        session_destroy();          
        
    }    

}