<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');

class Dashboard extends Controller {

    public function index() {
       
        if(!empty($_SESSION)){
            $data['page']='dashboard';
            $this->view('dashboard_view',$data);
        
        }else{
            header('location:'.URL_PATH.'page/login/error');
            
        }
  
    }
    
    public function logout() {
        
        $session = new Session(); 
        $session->destroy();
        header('location:'.URL_PATH.'page/login');

    }

} 
