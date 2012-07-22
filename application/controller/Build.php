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
           
            $data['qr_img'] = $_POST['qr_img'];
        
        /*
        //remove border from image
        //load the image
        $img = imagecreatefrompng("public/images/tmp/500b36a3c03e8.png");

        //find the size of the border.
        $border = 0;
        while(imagecolorat($img, $border, $border) == 0xFFFFFF) {
        $border++;
        }
        $border = $border + 1;
        //copy the contents, excluding the border
        //This code assumes that the border is the same size on all sides of the image.
        $newimg = imagecreatetruecolor(imagesx($img)-($border*2), imagesy($img)-($border*2));
        imagecopy($newimg, $img, 0, 0, $border, $border, imagesx($newimg), imagesy($newimg));

        //finally, if you want, overwrite the original image
        imagejpeg($newimg, "public/images/tmp/500b36a3c03e8.png");
         * 
         */

        
        endif;        
                
        $data['arg']=$arg;
        $data['page']='index';
        $this->view('build_view',$data);

    } 
    

    public function order($arg = false) { // index redirect        
        
        
        $data['arg']=$arg;
        $data['page']='order';
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