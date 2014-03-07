<?php 
class ResponseModel{
	public $protocol;
	
	public $httpver;
	
	public $statusCode;
	
	public $codeTranslate;
	
	public $contentType;
	
	public $contentLength;
	
	public $date;
	
	//get the HTTP Response string.
	public function getStaticResponse(){
		$response = '';
		
		$line = $this->protocol.'/'.$this->httpver.' '.$this->statusCode.' '.$this->codeTranslate.chr(13).chr(10);
		$response .= $line;
		
		if(isset($this->contentType) && !empty($this->contentType)){
			$line = 'Content-Type:'.$this->contentType.chr(13).chr(10);
			$response .= $line;
		}
		
		if(isset($this->contentLength)){
			$line = 'Content-Length:'.$this->contentLength.chr(13).chr(10);
			$response .= $line;
		}
		
		$line = 'Date:'.date("D, d M Y G:i:s \G\M\T").chr(13).chr(10);
		$response .= $line;
		
		return $response;
	}
	
	public function getDynamicResponse(){
		$response = '';
		
		$line = $this->protocol.'/'.$this->httpver.' '.$this->statusCode.' '.$this->codeTranslate.chr(13).chr(10);
		$response .= $line;
		
		$line = 'Date:'.date("D, d M Y G:i:s \G\M\T").chr(13).chr(10);
		$response .= $line;
		return $response;
	}
	
	public function setFirstLine($protocol, $httpver, $statusCode, $codeTranslate){
		$this->protocol = $protocol;
		$this->httpver = $httpver;
		$this->statusCode = $statusCode;
		$this->codeTranslate = $codeTranslate;
	}
}