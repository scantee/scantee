<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');

class Controller {

    public function view($page, $arg=FALSE) {
        
        $data = (object)$arg;
        new View($page,$arg);
    }
    
    public function model($model,$method,$arg=false) {
        
        new Model();
    }    

}
