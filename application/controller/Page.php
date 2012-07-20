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

class Page extends Controller {

    
    // index page 
    public function index($arg = false) { // index redirect        
        
        // protect against manipulation of url
        header("location:/");

    }    

    // about page
    public function about($arg = false) { // about us view
        $arg = filter_var($arg, FILTER_SANITIZE_STRING);

        $data['arg'] = $arg;
        $data['page'] = 'about';
        $this->view('page_view', $data);
    }
    

    // instructions page
    public function instructions($arg = false) { // instructions view
        $arg = filter_var($arg, FILTER_SANITIZE_STRING);
        
        $data['arg'] = $arg;
        $data['page'] = 'instructions';
        $this->view('page_view', $data);
    }    
    
    // contact page
    public function contact($arg = false) { // contact us view
        $arg = filter_var($arg, FILTER_SANITIZE_STRING);
        
        // Primary filter for post array 
        $_POST = array_map('Filter::primaryFilter', $_POST);

        // Store post array in new var. You can validate here 
        if (!empty($_POST) && $_POST['action'] == 'process_contact'):
            $ary = array();

            foreach ($_POST as $k => $v):
                // validate here - filter in Form_model
                $ary[$k] = $v;
            endforeach;

            $FM = new Form_model();
            $process = $FM->contactForm($ary);

            if ($process == false)
                $data['error'] = 'There was an error sending your message. Please try again.';
            else
                $data['error'] = 'Your message has been sent. Thanks!';

        endif;
        
        /*
         * email test script
         * remove as needed
        */
        if($arg=='test' && EMAIL_TEST == 1):
            
            $ary = array(
                'name'=>'Test Testington',
                'email'=>'help@testington.com',
                'phone'=>'(102)192.1122',
                'message'=>'This is a test message');
            
            $FM = new Form_model();
            $process = $FM->contactForm($ary);
            
            if ($process == false)
                $data['error'] = 'There was an error sending your message. Please try again.';
            else
                $data['error'] = 'Your message has been sent. Thanks!';
            
        endif;
        // test script end        

        $data['page'] = 'contact';
        $this->view('page_view', $data);
        
    }
    
    

    // login page
    public function login($arg = false) { // login page view
        $arg = filter_var($arg, FILTER_SANITIZE_STRING);
        
        try {

            // If already logged in, redirect. 
            if (!empty($_SESSION['u_name']))
                header('location:/dashboard/');

            // Primary filter for login post array 
            $_POST = array_map('Filter::primaryFilter', $_POST);


            // post from login form?
            if (!empty($_POST) && $_POST['action'] == 'check_login'):


                // username and password entered?   
                if (empty($_POST['username']) || empty($_POST['password'])):
                    // field empty error
                    throw new Exception(Error::init(1006), 1006);

                else:

                    if (strlen($_POST['password']) <= 5)
                    // less than 6 chars in password field
                        throw new Exception(Error::init(1003), 1003);

                    // store post array in a new array
                    $ary = array();

                    foreach ($_POST as $key => $val):
                        $ary[$key] = $val;
                    endforeach;

                    $CL = new Dashboard_Model; // login class
                    $var = $CL->checkLogin($ary); // validate login

                    if ($var == false):
                        $session = new Session(); 
                        $session->destroy();
                        // password and username invalid error
                        throw new Exception(Error::init(1004), 1004);

                    else:
                        $ses_ary = array(
                            'u_name' => $var['mvc_user_uname'],
                            'f_name' => $var['mvc_user_fname'],
                            'l_name' => $var['mvc_user_lname']
                        );
                        $session = new Session(); 
                        $session->setVal($ses_ary);                        
                        header('location:/dashboard/');

                    endif; // test login

                endif; // test if fields were completed
            endif; // test if form was submitted

            // not logged in error
            if ($arg == 'error')
                throw new Exception(Error::init(1009), 1009);

            $data['page'] = 'login';
            $this->view('page_view', $data);
            
        } catch (Exception $e) {

            $code = $e->getCode();
            $f = Error::errorLog($e);
            $data['error'] = Error::init($e->getCode());

            $data['page'] = 'login';
            $this->view('page_view', $data);
        }
        
    } // login method

    
    
    // error page
    public function error($arg = false) { // about us view
        $arg = filter_var($arg, FILTER_SANITIZE_STRING);
        
        $data['arg'] = $arg;
        $data['page'] = 'error';
        parent::view('page_view', $data);
    }

} // end of class