<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');

class View {

    public function __construct($page,$arg=FALSE) {
        
        $data = (object)$arg;
        require V_PATH.$page.'.php'; 
        
    }

}
