<?php if (!defined('B_PATH')) exit('No direct script access allowed');

/*
 * Included in the Page Controller
 * Index Page Redirect
 * About us Page
 * Contact us Page
 * History Page
 * Instructions Page
 * Login Page
 * Error Page
 */

class Build extends Controller {

    
    // index page 
    public function index() { // index redirect        

                
        if($_POST['action']=='build_t'):
            
        
            require(LIB_PATH.'Printfection_Library.php');        
            $qr = new Printfection_Library;
        
            $qr_upload = $qr->uploadImg($_POST['qr_img']); 
            
            $data['imageid'] = $qr_upload;
        
        endif;        
                
        $data['arg']=$arg;
        $data['page']='index';
        $this->view('build_view',$data);

    } 
    

    public function qrtee($arg = false) { // index redirect        
        
        
        //if($_POST['action']=='build_product'):            
        
            require(LIB_PATH.'Printfection_Library.php');        
            $qr = new Printfection_Library;
        
            $builtTee = $qr->builtTee($arg); 
            
            //$data['imageid'] = $qr_upload;
        
        //endif;
        
        $data['arg']=$arg;
        $data['page']='index';
        $this->view('build_view',$data);

    }

    
    public function pfql($arg = false) { // index redirect        
        
        
        //if($_POST['action']=='build_product'):            
        
            require(LIB_PATH.'Printfection_Library.php');        
            $qr = new Printfection_Library;
        
            //$builtTee = $qr->pfql($arg); 
            $builtTee = $qr->pfqlTest();
            
            //$data['imageid'] = $qr_upload;
        
        //endif;
        
        $data['arg']=$arg;
        $data['page']='index';
        $this->view('build_view',$data);

    }     


    // error page
    public function error($arg = false) { // about us view
        $arg = filter_var($arg, FILTER_SANITIZE_STRING);
        
        $data['arg'] = $arg;
        $data['page'] = 'error';
        parent::view('build_view', $data);
    }

} // end of class