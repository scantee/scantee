<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');

class Error {
    
   const PAGENOTFOUND       = 1000;
   const INVALIDID          = 1001;
   const INVALIDEMAIL       = 1002;
   const INVALIDPW          = 1003;
   const DOESNOTEXIST       = 1004;
   const NORECORD           = 1005;
   const USERPASSREQUIRED   = 1006;
   const NOTLOGGEDIN        = 1009;
   const UNEXPECTEDERROR    = 1013;
     
    public static function init($code) {
       
        switch($code):
           
         case static::PAGENOTFOUND:
             return PAGENOTFOUND_ERROR;
             break;         
            
         case static::INVALIDID:
             return INVALIDID_ERROR;
             break;
 
          case static::INVALIDEMAIL:
             return INVALIDEMAIL_ERROR;
             break;
 
          case static::INVALIDPW:
             return INVALIDPW_ERROR;
             break;
 
          case static::DOESNOTEXIST:
             return DOESNOTEXIST_ERROR;
             break;
         
          case static::NORECORD:
             return NORECORD_ERROR;
             break;

          case static::USERPASSREQUIRED:
             return USERPASSREQUIRED_ERROR;
             break;
         
          case static::NOTLOGGEDIN:
             return NOTLOGGEDIN_ERROR;
             break;
         
          case static::UNEXPECTEDERROR:
             return UNEXPECTEDERROR_ERROR;
             break;          

          default:
             return UNEXPECTEDERROR_ERROR;
             break;
         
     endswitch;
     
   }  
   
   
   public static function errorLog(Exception $exception, $clear = false, $error_file = "exceptions_log.html") {
      $message = $exception->getMessage();
      $code = $exception->getCode();
      $file = $exception->getFile();
      $line = $exception->getLine();
      $trace = $exception->getTraceAsString();
      $date = date('M d, Y h:iA');
 
      $log_message = "<h3>Exception information:</h3>
         <p>
            <strong>Date:</strong> {$date}
            <br>
            <strong>Message:</strong> {$message}
            <br>
            <strong>Code:</strong> {$code}
            <br>
            <strong>File:</strong> {$file}
            <br>
            <strong>Line:</strong> {$line}
         </p>
 
         <h3>Stack trace:</h3>
         <pre>{$trace}</pre>

         <br /><hr /><br />";
 
      if( is_file(L_PATH.$error_file) === false ):
         file_put_contents(L_PATH.$error_file, '');
      endif;
 
      if( $clear == true ):
         $content = '';
      else:
         $content = file_get_contents(L_PATH.$error_file);
      endif;
 
      $f = file_put_contents(L_PATH.$error_file, $log_message . $content);
      
      if($f == false):
          return false; 
      else:
          return true;
      endif;
      
   }   
   

}
