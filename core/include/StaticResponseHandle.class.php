<?php
class StaticResponseHandle extends ResponseHandle{
	public function __construct(){
		$this->request = new RequestModel();
		$this->response = new ResponseModel();
	}
	
	public function getBody(){
		$file = $this->getTrueFile();
		
		$this->body = file_get_contents($file);
		return $this->body;
	}
	
	public function handle($header){
		echo 'static';
		$this->setRequest($header);
		
		$this->isHTTP();
		$this->isMethod();
		
		$this->getBody();

		$this->response->contentLength = strlen($this->body);
		$extension = $this->request->getExtension();
		$mime = Config::getConfig($extension);
		if(!isset($mime) || empty($mime)){
			$message = StatusCodeManager::getTranslation('415');
			throw new ZebraStatusException($message, 415);
		}
		$this->response->contentType = $mime;
		
		$translate = StatusCodeManager::getTranslation('200');
		$this->response->setFirstLine("HTTP", "1.0", "200", $translate);
		
		$response = $this->response->getStaticResponse();
		$body = $this->getBody();
		$packet = $response.chr(13).chr(10).$body;
		
		return $packet;
	}
}