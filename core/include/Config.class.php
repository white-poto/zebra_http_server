<?php
class Config{
	//configer file path
	private static $filename;
	
	//configers
	private static $info;
	
	private function __construct(){}
	
	public static function getConfig($key){
		self::loadfile();
		if(array_key_exists($key, self::$info)){
			return self::$info[$key];
		}else{
			throw new ZebraFileNotFoundException("The 'zebra.ini' can not be found!");
		}
	}
	
	public static function serverInfo(){
		self::$filename = ZEBRA_ROOT."/config/zebra.ini";
		$serverInfo = parse_ini_file(self::$filename, true);
		return $serverInfo;
	}
	
	private static function loadfile(){
		if(!empty(self::$info) && isset(self::$info)){
			return;
		}else{
			self::$filename = ZEBRA_ROOT."/config/zebra.ini";
			self::$info = parse_ini_file(self::$filename);
		}
	}
}
