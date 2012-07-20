<?php
/**
 * ClicknPay Curl Wrapper
 *
 *
 * Version 0.1.0
 * Date 2012/03/26
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * @category   Curl
 * @package    Curl Wrapper
 * @author     Steve Mahana <steve@clicknpay.com>
 * @copyright  2012-2015 ClicknPay
 * @license    http://www.gnu.org/licenses/gpl-3.0.html  LGPL License 3.0
 * @version    GIT: $Id$
 * @link       https://github.com/clicknpay
 * @see        N/A
 * @since      N/A
 * @deprecated N/A
 *
 * Dependencies:  None
 *
 *
 */
class Curl_Library
{

	private $headers;
	private $user_agent;
	private $cookie; //path to cookie, otherwise FALSE
	private $referer;
	private $bindip;
	private $timeout;
	private $response;
	private $last_error;
	private $compression;
	private $proxy;
	
	public function __construct()
	{
					//defaults!
					$this->headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
					$this->headers[] = 'Connection: Keep-Alive';
					$this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
					$this->headers[] = 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7';
					$this->headers[] = 'Accept-Language: en-us,en;q=0.5';
					$this->user_agent = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';
					$this->compression = 'gzip';
					$this->timeout = 15;
					$this->cookie = FALSE;	
	} // function curl
	
	public function setReferer($refer) {
		$this->referer = $refer;
	}
	
	public function setBindIP($IP) {
		$this->bindip = $IP;
	}
	
	//tests if the connection is working externally by checking a site we know`s content is legit.....
	public function test() {
		
		$this->fetch('http://livewiresupply.com/robots.txt');
		
		$response = $this->response;
		$this->response = '';
		
		if (strpos($response, "User-agent") !== FALSE) return TRUE;

		return FALSE;		
			
	}
	
	public function setCookie($cookiefile) {
		$this->cookie = $cookiefile;
	}
	
	public function setTimeout($seconds) {
		$this->timeout = $seconds;
	}
	
	public function setUserAgent($UA) {
		$this->user_agent = $UA;
	}
	
	public function getError() {
		return $this->last_error;
	}
	
	public function getResponse() {
		return $this->response;
	}
	
	public function setProxy($ip, $port, $type = '') {
		$this->proxy['ip'] = $ip;
		$this->proxy['port'] = $port;
		$type = strtolower($type);
		
		
		if ($type == "socks4") {
			$this->proxy['type'] = CURLPROXY_SOCKS4;
		} else if ($type == "socks5") {
			$this->proxy['type'] = CURLPROXY_SOCKS5;
		} else { 
			$this->proxy['type'] = $type;
		}
	
	}
	
	
	//$url = is the url we want to fetch
	//post is either an array of post key=values 
	// or post can be a string like this key=value&key2=value2&...
	//TODO: Clean this function up a bit....
	public function fetch($url, $post = FALSE){
	
			//child
			$mode = ($post == FALSE) ? 'GET' : 'POST';
			
			//assume its always a get unless post array is set... in which case, its clearly a post
			$process = curl_init();
			
			curl_setopt($process, CURLOPT_URL, $url);
			
			if ($this->referer != '') curl_setopt($process, CURLOPT_REFERER, $this->referer);
			
			
			if ($this->bindip != '') curl_setopt($process,CURLOPT_INTERFACE,$this->bindip);
					 
			
			if ($this->cookie != FALSE){
				curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie);
				curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie);
			}
			
			
			curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
			curl_setopt($process, CURLOPT_HEADER, 0);
			curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
			curl_setopt($process, CURLOPT_ENCODING , $this->compression);
			
			
			curl_setopt($process, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
			
			
			//proxeh
			if ($this->proxy != '') { 
					if ($this->proxy['type'] != '') curl_setopt($process, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
					curl_setopt($process, CURLOPT_PROXY, "{$this->proxy['ip']}:{$this->proxy['port']}");
			}
			
			if ($mode == "POST"){
			
					curl_setopt($process, CURLOPT_POST, 1);
			
					if (is_array($post) ){
						$data = '';
						foreach ($post as $key => $value)
							$data .= urlencode($key) . '='. urlencode($value) . '&';
					
					} else {
						$data = $post; //just a string....
					}
			
					curl_setopt($process, CURLOPT_POSTFIELDS, $data);
			}
			
			curl_setopt($process, CURLOPT_LOW_SPEED_LIMIT, 10000); //bug: this HAS to be here...
			curl_setopt($process, CURLOPT_LOW_SPEED_TIME, $this->timeout); 		
			
			curl_setopt($process, CURLOPT_TIMEOUT, $this->timeout); //bug: this HAS to be here...
			curl_setopt($process, CURLOPT_CONNECTTIMEOUT, $this->timeout); 
			$return = curl_exec($process);
			
			if (curl_errno($process) != 0 ){
					$this->response = '';
					$this->last_error = curl_errno ($process) . ' ' . curl_error  ($process);
					curl_close($process);
					return FALSE;
			}
			
			$this->last_error  = '';
			$this->response = $return;
			curl_close($process);
			return TRUE;				

	} //public fetch


} //class
