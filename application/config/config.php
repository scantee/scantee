<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');
/**
 * Scan Tee 
 *
 * Scan Tee QR Generator 
 *
 * Version 0.1.0
 * Date 2012/07/20
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * @category   CategoryName
 * @package    PackageName
 * @author     Steve Mahana <steve@clicknpay.com>
 * @copyright  2012-2015 ScanTee
 * @license    http://www.gnu.org/licenses/gpl-3.0.html  LGPL License 3.0
 * @repo       GIT: https://github.com/scantee/scantee.git
 * @link       http://livewiresupply.com/package/ComingSoon
 * @see        N/A
 * @since      N/A
 * @deprecated N/A
 *
 * Dependencies:  ClicknPay Micro MVC - https://github.com/clicknpay
 *
 *
 */


ini_set('display_errors',1); // turn errors off after install
error_reporting(E_ALL ^ E_NOTICE); // turn errors off after install

/*
 * PAGELOAD TIMER
 * remove as needed
 * rest of code in footer
 */
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
define('START',$time);
////////////////////

/*
 * SET SYSTEM PATHS
 */
define('URL_PATH','http://scantee.me/');
define('SECURE_PATH','https://scantee.me/');
define('IMG_PATH',URL_PATH.'public/images/'); // path to images folder
define('JS_PATH',URL_PATH.'public/js/'); // path to js folder
define('CSS_PATH',URL_PATH.'public/css/'); // path to css folder
define('S_PATH',B_PATH.'system/'); // path to system folder
define('C_PATH',B_PATH.'application/controller/'); // path to controller folder
define('M_PATH',B_PATH.'application/model/'); // path to model folder
define('V_PATH',B_PATH.'application/view/'); // path to view folder
define('CONFIG_PATH',B_PATH.'application/config/'); // path to config folder
define('LIB_PATH',B_PATH.'application/library/'); // path to view folder
define('L_PATH',B_PATH.'logs/'); // path to view folder
define('GET_URL', empty($_GET['url'])?'':$_GET['url']); // provides current url


/*
 * EMAIL SETTINGS
 * email error message is in controller that handles the form you're using 
 * smtp user and pass set in Form_model
 */
define('SMTP_HOST','mail.clicknpay.com');
define('SMTPDEBUG',0); // enables SMTP debug 1 = errors and messages 2 = messages only
define('EMAIL_TEST',0); // enables test email button 1 = on
define('SYS_EMAIL','steve@clicknpay.com'); // system default 
define('SYS_NAME','Customer Service'); // system default 
define('CC_EMAIL','steve@clicknpay.com'); // not active
define('CC_NAME','Customer Service'); // not active
define('EMAIL_SUBJECT','ClicknPay Micro-MVC Customer Contact');

/*
 * SECURITY SETTINGS
 * algo = md5
 * hash method = HMAC
 * system/Encrypt
 */
define('HASH_KEY','good4now');


/*
 * SYSTEM DEFINED ERRORS
 * These are user defined exceptions
 * 
 * Error::init(1009) // calls custom message;
 * throw new Exception(Error::init(1009), 1009); // stores error details in log
 * 
 * see system/Error.php for codes and errors. 
 * see logs/exceptions_log.html for errors
 * these error can be called directly (through define) as well
 *   
 */

// 1000
define('PAGENOTFOUND_ERROR',"PAGE NOT FOUND ERROR! The page you've requested can't be found.");
// 1001
define('INVALIDID_ERROR','Whoa! I think there\'s something wrong with the user id');
// 1002
define('INVALIDEMAIL_ERROR','Invalid email address. Please re-enter and try again.');
// 1003
define('INVALIDPW_ERROR','The password is shorter than 6 characters!');
// 1004
define('DOESNOTEXIST_ERROR','Username or Password was incorrect. Please try again.');
// 1005
define('NORECORD_ERROR','No record found, Please try again.');
// 1006
define('USERPASSREQUIRED_ERROR','Username and Password are required.');
// 1009
define('NOTLOGGEDIN_ERROR','You are not logged in. Please login.');
// 1013
define('UNEXPECTEDERROR_ERROR','An unexpected error has occurred');


/*
 * AUTO LOADER FUNCTION
 */

spl_autoload_register(null, false);
spl_autoload_extensions('.php');

function MVC_autoload($class){
    
    if(file_exists(S_PATH.$class.'.php')){
        require S_PATH.$class.'.php';
        
    } elseif(file_exists(C_PATH.$class.'.php')){
        require C_PATH.$class.'.php';
        
    } elseif(file_exists(V_PATH.$class.'.php')){
        require V_PATH.$class.'.php';
        
    } elseif(file_exists(M_PATH.$class.'.php')){
        require M_PATH.$class.'.php';
        
    } else {
        require S_PATH.'Error.php';
    
    }    

}
spl_autoload_register('MVC_autoload');