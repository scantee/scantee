<?php
//a nice simple socket class cuse php is kinda not OO-y in that regard.
	
class Socket_Library { 

	private $error_str;
	private $error_code;
	
	public $port;
	public $host;
	
	private $socket;
	private $writing_socket;
	private $maxBytes;
	
	public function __construct(){
			$this->error_code = 0;
			$this->maxBytes = 10 * 1024;
	}	

	public function close($close_writing = FALSE) {
		if ($close_writing) {
			socket_close($this->writing_socket);
		} else socket_close($this->socket);
		
	}

	public function listen($host, $port) {
			$this->host = '';
			$this->port = '';
					
			if (!$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) {
				$this->setErr();
				return false;
			}
			
			if (!socket_bind($this->socket, $host, $port) ) {
				$this->setErr();
				return false;			
			}
			
			//we dont want blocking...cuse yeah...
			if (!socket_set_nonblock($this->socket) ) {  
				$this->setErr();
				return false;						
			}
			
			if (!socket_listen($this->socket) ) {
				$this->setErr();
				return false;						
			}
			
			$this->host = $host;
			$this->port = $port;
			
	}

	//returns false if no waiting connections.
	//returns true if there is a connection. Will also connect. 
	public function newConnection() {
			$this->writing_socket = @socket_accept($this->socket);
			return ($this->writing_socket !== FALSE);
	
	}
	
	public function getMsg() {
		//no need to return here, as $msg would equal false...and we`d return false on a fail.
		if (!$msg = socket_read($this->writing_socket, $this->maxBytes) ) $this->setErr();
		
		return $msg;
	}
	
	public function sendMsg($msg) {
		if (!socket_send($this->writing_socket, $msg, strlen($msg), MSG_EOF) ) {
			$this->setErr();
			return false;
		}
		return true;
	}
	
	//sets the error information for this object.
	private function setErr() {
		//we can derive method_name calling the error from a stack trace....
		$stack = debug_backtrace();
		$method_name = $stack[1]['function'];
		
		$this->error_str = "Failure on {$method_name}. Reason: ". socket_strerror(socket_last_error($this->socket));
		$this->error_code = socket_last_error($this->socket);	
	}
	
	public function getErrStr(){
		return "Error Code: {$this->error_code} -\n {$this->error_str}\n";
	}

}
