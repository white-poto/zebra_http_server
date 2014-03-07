<?php
define('ZEBRA_ROOT', dirname(__FILE__));

require './core/Mpass/Server.php';
require './core/include/HandleFactory.class.php';

require './core/Exception/ZebraException.include.php';

class ZebraPserver implements Mpass_IExecutor {
	function execute(Mpass_Request $client) {
		try{
			$header = '';
			$header = $client->read(1024);
			
			$request = new RequestModel($header);
			$handle = HandleFactory::getResponseHandle($request->isStatic());
			$response = $handle->handle($header);
			$client->write($response);
			Mpass_Log::record($header, __METHOD__);
			return true;
		}catch (Exception $e){
			$EResponse = 'HTTP/1.0 '.$e->getCode().' '.$e->getMessage().chr(13).chr(10);
			$EResponse .= 'Date:'.date("D, d M Y G:i:s \G\M\T").chr(13).chr(10).chr(13).chr(10);
			$client->write($EResponse);
			Mpass_Log::record($EResponse, __METHOD__);
			return true;
 		}
	}
}

$host = "127.0.0.1";
$port = 8080;

$service = new Mpass_Server($host, $port, new ZebraPserver);

$service->run();