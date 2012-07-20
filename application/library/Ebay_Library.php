<?php  if ( ! defined('LIB_PATH')) exit('No direct script access allowed');
/**
 * Process eBay Feed
 * Read CL_feed for detailed notes on how this works
 * 
 */	
class Ebay_Library {

    private $url;
    private $negative_keywords;
    public $feed_array = array();
    public $feed_title = array();		

    public function __construct($feed) { //assigned keywords are optional

            $eb_search_string = str_replace(" ", "+", trim($feed['keywords'])); //replace space with '+'
            $this->url = 'http://www.ebay.com/sch/rss/?_sacat=0&_nkw='.$eb_search_string.'&_rss=1&rt=nc'; // build eBay Search String

            /*
            $neg_keywords = trim($feed['neg_keywords']);

            if(empty($neg_keywords)) $neg_keywords = array();
            if(is_string($neg_keywords)) $neg_keywords = explode(' ',$neg_keywords);
            if(is_array($neg_keywords)) $this->negative_keywords = $neg_keywords;
             * 
             */			
            //var_dump($this->url);
            $this->buildFeed();

    }

    private function buildFeed() {
            $url = $this->url;
            $ch = curl_init();
            curl_setopt_array($ch, array(
            CURLOPT_URL=>$url,
            CURLOPT_HEADER=>0,
            CURLOPT_RETURNTRANSFER=>1			
            ));

            $rss = curl_exec($ch);
            curl_close($ch);

            $rss = simplexml_load_string($rss);
            //$xml = $rss->getDocNamespaces();

            $this->feed_title['feed_title'] = $rss->channel->title;

            $cnt = count($rss->channel->item);

            for($i=0;$i<$cnt;$i++):

                    $EOL = "<br>";
                    $link = $rss->channel->item[$i]->link;
                    $title = $rss->channel->item[$i]->title;
                    $desc = $rss->channel->item[$i]->description;
                    $pubDate = $rss->channel->item[$i]->pubDate;
                    $BuyItNowPrice = $rss->channel->item[$i]->children('urn:ebay:apis:eBLBaseComponents')->BuyItNowPrice;
                    $CurrentPrice = $rss->channel->item[$i]->children('urn:ebay:apis:eBLBaseComponents')->CurrentPrice;
                    $BidCount = $rss->channel->item[$i]->children('urn:ebay:apis:eBLBaseComponents')->BidCount;
                    $Category = $rss->channel->item[$i]->children('urn:ebay:apis:eBLBaseComponents')->Category;
                    $AuctionType = $rss->channel->item[$i]->children('urn:ebay:apis:eBLBaseComponents')->AuctionType;
                    $ItemCharacteristic = $rss->channel->item[$i]->children('urn:ebay:apis:eBLBaseComponents')->ItemCharacteristic;	


                    $title_str = (string)$title; //typecast title
                    $desc_str = (string)$desc; //typecast description
                    $title_key = false;
                    $desc_key = false;

/*
                    foreach ($this->negative_keywords as $value):  //locate ads that contain negative keywords

                            if(stristr($title_str,$value)) $title_key = true;
                            if(stristr($desc_str,$value)) $desc_key = true;

                    endforeach;
 * 
 */

                    if($title_key==true) continue; // stop the loop if negative keyword found 
                    if($desc_key==true) continue; // stop the loop if negative keyword found 


                    $ad_array = array(
                            'link'=>$link,
                            'title'=>$title,
                            'desc'=>$desc,				
                            'buy_it_now'=>$BuyItNowPrice,
                            'current_price'=>$CurrentPrice,
                            'bids'=>$BidCount,
                            'category'=>$Category,
                            'type'=>$AuctionType,
                            'characteristics'=>$ItemCharacteristic
                    );

                    array_push($this->feed_array, $ad_array);	

                    /**
                    echo '<h4><a href="'.$link.'">'.$title.'</a></h4>'.$desc;
                    echo '<p><h4>Stats</h4>'.
                    'Buy it Now: '.$BuyItNowPrice.$EOL.
                    'Current Price: '.$CurrentPrice.$EOL.
                    'Bids: '.$BidCount.$EOL.
                    'Cat: '.$Category.$EOL.
                    'Type: '.$AuctionType.$EOL.
                    'Characteristics: '.$ItemCharacteristic.$EOL.
                    '</p>';
                    */

            endfor;

    }
}