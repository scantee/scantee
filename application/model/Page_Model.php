<?php if ( ! defined('B_PATH')) exit('No direct script access allowed');
/**
 * METHOD LIST
 * searchTicketQueue
 * searchWebsitePrice
 * buildRelectricFeed
 * buildSuperBreakerFeed
 * buildBreakerStoreFeed
 * buildSupplylineElectricFeed
 * buildDiscountFuseFeed
 * buildTemcoFeed 
 * buildDrillSpotFeed
 * searchPartHistory
 * searchPartHistoryAll
 * getBetween
 * getRecords
 * salesRecord
 * purchaseOrderRecord
 * getInventory
 * updatePrices
 *  
 */

class Page_Model extends Model{
    
    private $C;    
    
    public function __construct($C=false) {
        
        $this->C = $C;
        
    }    
    
    
    public function test($arg=false) {
        
        return 'page model :'.$arg;
        
    }

    

    public function getInventory($id){        

        $db = parent::init('lws_ns_reporting');
        $st = $db->prepare("SELECT * FROM ns_inventory 
            WHERE ns_inventory_item = :ID");
        $st->bindParam(':ID',$id);
        $st->execute();
        $feed_info = $st->fetchAll(PDO::FETCH_ASSOC);
        
        return $feed_info;

    } 
    

    //returns text between start and end... noninclusive.
    public static function getBetween($start, $end, $str) { 
        
        $a = strpos($str,$start);
        if ($a === FALSE) return FALSE;
        $z = strpos($str,$end, $a+strlen($start)+1);
        if ($z === FALSE) return FALSE;

        $str = substr($str,$a + strlen($start), $z - ($a + strlen($start)) );

        return $str;

    }    
    
/*
    $this->C->setProxy
    $this->C->test();

    echo '<pre>';
    print_r($var);
    echo '<pre>';
* 
*/
    

}


