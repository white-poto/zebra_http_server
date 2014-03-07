<?php
class RequestModel{
	public $protocol;
	
	public $path;
	
	public $method;
	
	public $httpver;
	
	public $accept;
	
	public $date;
	
	public $referer;
	
	public $useragent;
	
	public $parse_url;
	
	public $body;
	
	public function __construct($header=''){
		if(!empty($header)){
			$this->setRequest($header);
		}
		return;
	}
	
	//determine the request is static or dynamic
	public function isStatic(){
		$extension = $this->getExtension();
		$staticExtens = explode(',', Config::getConfig('staticExtension'));
		if(in_array($extension, $staticExtens)){
			return true;
		}
		return false;
	}
	
	public function getExtension(){
		$filename = $this->getTrueFile();
		$extension = pathinfo($filename, PATHINFO_EXTENSION);
		return $extension;
	}
	
	public function setParseUrl(){
		$this->parse_url = parse_url($this->path);
		return true;
	}
	
	public function getParseUrl(){
		if(isset($this->parse_url) && !empty($this->parse_url)){
			return $this->parse_url;
		}
		return false;
	}
	
	protected function isSetFile(){
		$parse_url = $this->getParseUrl();
		$filename = $parse_url['path'];
		$partten = "[^/\\]*$";
		ereg($partten, $filename, $regs);
		if(isset($regs[0]) && !empty($regs[0])){
			return true;
		}
		return false;
	}
	
	protected function getDefaultFile(){
		$defaultFiles = Config::getConfig('defaultFile');
		$defaultFiles = explode(',', $defaultFiles);
		$parse_url = $this->getParseUrl();
		foreach($defaultFiles as $filename){
			$filename = Config::getConfig('root').$parse_url['path'].'/'.$filename;
			if(file_exists($filename) && is_readable($filename))
				return $filename;
		}
		$message = StatusCodeManager::getTranslation('404');
		throw new ZebraStatusException($message, '404');
	}
	
	public function getTrueFile(){
		if($this->isSetFile()){
			$parse_url = $this->getParseUrl();
			$file = Config::getConfig('root').$parse_url['path'];
			$this->isReadable($file);
			return $file;
		}else{
			return $this->getDefaultFile();
		}
	}
	
	protected function isReadable($file){
		if(file_exists($file) && is_readable($file)){
			return true;
		}
		$message = StatusCodeManager::getTranslation('404');
		throw new ZebraStatusException($message, '404');
	}
	
	protected function setBody($header){
		$requests = explode(chr(13).chr(10).chr(13).chr(10), $header);
		if(isset($requests[1]) && !empty($requests[1])){
			$this->body = $requests[1];
		}
	}
	
	//set the $request from the request header string
	public function setRequest($header){
		$this->setBody($header);
		
		$headers = explode(chr(13).chr(10), $header);
		$top = $headers[0];
		$tops = explode(' ', $top);
		$this->method = $tops[0];
		$this->path = $tops[1];
		$protocol = explode('/', $tops[2]);
		$this->protocol = $protocol[0];
		$this->httpver = $protocol[1];
		
		$this->setParseUrl();
	
		$counter = count($headers);
		for($i=1; $i<$counter; $i++){
			$line = explode(':', $headers[$i]);
			switch($line[0]){
				case 'User-Agent' :
					$this->useragent = $line[1];
					break;
				case 'Date' :
					$this->date = $line[1];
					break;
				case 'Referer' :
					$this->referer = $line[1];
					break;
				case 'Accept' :
					$this->accept = $line[1];
					break;
			}
		}
	}
}