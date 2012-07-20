<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');

class Encrypt {

    public static function init($data,$salt) {
        
        $context = hash_init('md5', HASH_HMAC, $salt);
        hash_update($context, $data);
        
        return hash_final($context);
        
    }

}
