<?php
define("RESPONSEHANDLE_ROOT", dirname(__FILE__));
require_once(RESPONSEHANDLE_ROOT. "/ResponseModel.class.php");
require_once(RESPONSEHANDLE_ROOT. "/RequestModel.class.php");
require_once(RESPONSEHANDLE_ROOT. "/StaticResponseHandle.class.php");
require_once(RESPONSEHANDLE_ROOT. "/DynamicResponseHandle.class.php");
require_once(RESPONSEHANDLE_ROOT. "/Config.class.php");
require_once(RESPONSEHANDLE_ROOT. "/StatusCodeManager.class.php");

//include 'StatusCodeManager.class.php';
class ResponseHandle{
	
	protected $request;
	
	protected $response;
	
	protected $body;
	
	//abstract public function getBody();
	
	/*
	 * handle the request
	 * and return the reponse
	 */
	//abstract public function handle();
	
	public function setRequest($header){
		$this->request->setRequest($header);
	}
	
	protected function fileExists(){
		$filename = $this->request->getFileName();
		if(!file_exists($filename)){
			$message = StatusCodeManager::getTranslation('404');
			throw new ZebraStatusException($message, '404');
		}
	}
	
	//check the protocol
	protected function isHTTP(){
		if(strtolower($this->request->protocol) != 'http'){
			$message = StatusCodeManager::getTranslation('400');
			throw new ZebraStatusException($message, '400');
		}
	}
	
	protected function isMethod(){
		$str = Config::getConfig('method');
		$methods = explode(',', $str);
		if(!in_array($this->request->method, $methods)){
			$message = StatusCodeManager::getTranslation('405');
			throw new ZebraStatusException($message, '405');
		}
	}
	
	protected function getTrueFile(){
		return $this->request->getTrueFile();
	}
}























