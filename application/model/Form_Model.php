<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');

class Form_Model extends Model {

    
    public function contactForm($arg=false){
        
        // built in filters are used like this Filter::url($arg)
        // built in filters are slower and more aggressive
        
        $name    = filter_var($arg['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $phone   = filter_var($arg['phone'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $email   = filter_var($arg['email'], FILTER_SANITIZE_EMAIL);
        $message = filter_var($arg['message'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH );
        $body ="
            Name: $name <br>
            phone: $phone <br>
            Email: $email <br><hr>
            $message
            "; 
        
        $ary = array(
            'name'=>$name,
            'phone'=>$phone,
            'email'=>$email,
            'body'=>$body
            );
        
        // phpmailer
        $m = $this->phpMailer($ary); 
        return $m;
        
    }
    
    
    protected function phpMailer($arg=false){
        
        require(CONFIG_PATH.'pass.php');
        
        // phpmailer     
        date_default_timezone_set('America/Toronto');

        require_once(LIB_PATH.'phpmailer/class.phpmailer.php');

        $mail             = new PHPMailer();

        $mail->IsSMTP(); // telling the class to use SMTP
        
        try {
        $mail->Host       = SMTP_HOST;              // SMTP server
        $mail->SMTPDebug  = SMTPDEBUG;              // enables SMTP debug information (for testing)
        $mail->SMTPAuth   = true;                   // enable SMTP authentication
        $mail->Host       = SMTP_HOST;              // sets the SMTP server
        $mail->Port       = 25;                     // set the SMTP port for the GMAIL server
        $mail->Username   = $SMTP_USER;    // SMTP account username
        $mail->Password   = $SMTP_PASS;    // SMTP account password
        $mail->AddReplyTo($arg['email'], $arg['name']); //actual info
        $mail->AddAddress(SYS_EMAIL, SYS_NAME);     // site default
        $mail->SetFrom($arg['email'], $arg['name']); // displayed info
        $mail->AddReplyTo($arg['email'], $arg['name']); // displayed info
        $mail->Subject = EMAIL_SUBJECT;
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
        $mail->MsgHTML($arg['body']);
        //$mail->MsgHTML(file_get_contents('contents.html'));
        //$mail->AddAttachment('images/phpmailer.gif');      // attachment
        //$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
        if($mail->Send()):
            return true;
        else:
            return false;
        endif;

        } catch (phpmailerException $e) {
        //echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
        //echo $e->getMessage(); //Boring error messages from anything else!
        }        
        
        
    }    
    
    

}
