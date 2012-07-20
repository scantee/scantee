<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');

class Filter {

    
    public static function primaryFilter($arg=false) {
        
        $arg = strip_tags($arg);
        $arg = preg_replace('/[^(\x20-\x7F)\x0A]*/', '', $arg); //Remove Non ASCII Characters 
        $arg = str_replace(array('`','^','%','#','*','$'), '', $arg);
        $arg = filter_var($arg, FILTER_SANITIZE_STRING);
        $arg = trim($arg);
        return $arg;
        
    }    
    
    
    public static function url($arg) {
        
        $arg = strip_tags($arg);
        $arg = preg_replace('/[^(\x20-\x7F)\x0A]*/', '', $arg); //Remove Non ASCII Characters 
        $arg = str_replace(array('`','^','javascript','JAVASCRIPT','\\','%'), '', $arg);
        $arg = filter_var($arg, FILTER_SANITIZE_URL);
        $arg = trim($arg);
        return $arg;
        
    }
    
    public static function email($arg) {
        
        $arg = filter_var($arg, FILTER_SANITIZE_EMAIL);
        
        return $arg;
        
    }
    
    public static function string($arg) {
        
        $arg = strip_tags($arg);
        $arg = preg_replace('/[^(\x20-\x7F)\x0A]*/', '', $arg); //Remove Non ASCII Characters 
        $arg = str_replace(array('`','^','%','#','*'), '', $arg);
        $arg = filter_var($arg, FILTER_SANITIZE_STRING);
        $arg = trim($arg);
        return $arg;
        
    }    

}