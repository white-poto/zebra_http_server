<?php
class DynamicResponseHandle extends ResponseHandle{
	private $file;
	public function __construct(){
		$this->request = new RequestModel();
		$this->response = new ResponseModel();
	}
	
	public function getBody(){
		$this->file = $this->getTrueFile();
		if($this->request->method == 'GET'){
			$body = $this->handleGet();
		}elseif($this->request->method == 'POST'){
			$body = $this->handlePost();
		}
		return $body;
	}
	
	public function handle($header){
		$this->setRequest($header);
		
		$this->isHTTP();
		$this->isMethod();
		
		$translate = StatusCodeManager::getTranslation('200');
		$this->response->setFirstLine("HTTP", "1.0", "200", $translate);
		
		$response = $this->response->getDynamicResponse();
		$body = $this->getBody();
		$packet = $response.$body;
		
		return $packet;
	}
	
	protected function handleGet(){
		$this->setEnv();
		
		ob_start();
		passthru('php-cgi');
		$body = ob_get_clean();
		
		return $body;
	}
	
	protected function handlePost(){
		$this->setEnv();
		
		ob_start();
		passthru('echo '.$this->request->body.' | php-cgi');
		echo 'echo '.$this->request->body.' | php-cgi';
		$body = ob_get_clean();
		
		return $body;
	}
	
	protected function setEnv(){
		putenv("REDIRECT_STATUS=true");
		$filename = $this->file;
		putenv("SCRIPT_FILENAME=$filename");
		putenv("REQUEST=".$this->request->method);
		putenv("GATEWAY_INTERFACE=CGI/1.1");
		if(isset($this->request->parse_url['query']) && !empty($this->request->parse_url)){
			putenv("QUERY_STRING=".$this->request->parse_url['query']);
		}
		//putenv("HTTP_COOKIE=");
	}
}






























