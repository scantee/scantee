<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');
/*
 * Included in the Index Controller
 * Index Page 
 */

class Index extends Controller {

    public function __construct($arg = false) {
        // Arg filter for passed string
        $arg = filter_var($arg, FILTER_SANITIZE_STRING);  
        
        // Primary filter for post array 
        $_POST = array_map('Filter::primaryFilter', $_POST);
        
        // Business logic for this controller
        //$PM = new Page_Model($C);
        
        // Library Curl
        //require(LIB_PATH.'Curl_Library.php');
        
        // Curl Object - pass to model
        //$C = new Curl_Library();

        $data['qr_img']='qr-code';
        
        if(@$_POST['action'] == 'build_qr'):
            
        // Library QR
        require(LIB_PATH.'BarcodeQR.php');        
        $qr = new BarcodeQR();            
            
        $qrType = $_POST['type'];
        $text = $_POST['text']; 
        
            switch($qrType):
                case 'contact':
                    $qr->contact("name", "address", "phone", "email");
                    break;
                case 'content':
                    $qr->content("type", "size", "content");
                    break;
                case 'email':
                    $qr->email("email", "subject", "message");
                    break;
                case 'geo':
                    $qr->geo("lat", "lon", "height");
                    break;
                case 'phone':
                    $qr->phone("phone");
                    break;
                case 'sms':
                    $qr->sms("phone", "text");
                    break;
                case 'text':
                    $qr->text($text);
                    break;
                case 'url':
                    $qr->url("url");
                    break;
                case 'wifi':
                    $qr->wifi("type", "ssid", "password");
                    break;
                default:
                    $qr->text("Hook up with ScanTee");
                    break;           
            endswitch;
            
        //$qr->draw();
        $img_name = uniqid();    
        $qr->draw(493, "public/images/tmp/$img_name.png"); 
        $data['qr_img']=$img_name;
        $data['text']= $_POST['text'];
        
        endif; 
        
        $data['page']='index';
        $this->view('page_view',$data);
    }

}