<?php
//this should be deleted when this project released
define('__ROOT__', '/home/hyp/design');
class StatusCodeManager{
	//status code file path
	private static $filename;
	
	//the array of code translation
	private static $translation;
	
	//get the translation of the HTTP status code
	public static function getTranslation($code){
		self::loadfile();
		if(array_key_exists($code, self::$translation)){
			return self::$translation[$code];
		}else{
			throw new StatuscdoeFileNotFoundException('The statuscode.ini can not be found!');
		}
	}
	
	private static function loadfile(){
		if(!empty(self::$translation) && isset(self::$translation)){
			return;
		}else{
			self::$filename = __ROOT__."/config/statuscode.ini";
			self::$translation = parse_ini_file(self::$filename);
		}
	}
}

//echo StatusCodeManager::getTranslation(200);