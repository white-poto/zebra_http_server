<?php
define('CONFIGCLASS_PATH', dirname(__FILE__)."/../include");
require_once CONFIGCLASS_PATH . '/Config.class.php';

/*
----------------------------------------------------
Mpass - Multi-Process Socket Server for PHP

copyright (c) 2010 Laruence
http://www.laruence.com

If you have any questions or comments, please email:
laruence@yahoo.com.cn

*/

/**
 * A loger for Mpass simple use 
 * @package Mpass
 */
class Mpass_Log {

    private static $last_error = NULL;
    
    private static $recordMode = NULL;

    public static function record($str, $priority, $scope = "") {
		$pid = getmypid();
		
		if(self::$recordMode == NULL){
			$mode = Config::getConfig('mode');
      	self::$recordMode = RecordModeFactory::getRecord($mode);
		}
      self::$recordMode->record($str, "WARN", $scope);
    }
    

	public static function log($str, $scope = "") {
        if (!MPASS_DEBUG) {
            return TRUE;
        }
        self::record($str, "DEBUG", $scope);
    }

	public static function warn($str,  $scope = "") {
        self::record($str, "WARN", $scope);
    }

    public static function err($str, $scope = "") {
        self::$last_error = $str;

        self::record($str, "ERROR", $scope);
    }


    public static function getLastError() {
        return self::$last_error;
    }
}

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
class RecordModeFactory{
	public static function getRecord($mode){
		if($mode === 'file'){
			return new RecordFile();
		}elseif($mode === 'console'){
			return new RecordConsole();
		}
	}
}

interface RecordMode{
	public function record($str, $priority, $scope = "");
}

class RecordFile implements RecordMode{
	public function record($str, $priority, $scope = ""){
		$pid = getmypid();
		
		$logPath = dirname(__FILE__)."/../Log/Zebra.log";
		
		if(is_writable($logPath)){
			
			if(!$handle = fopen($logPath, 'a')){
				die('Log file can not open!');
			}
			
			$string = '';
			if (!empty($scope))  {
				$string = "[" . date("Y-m-d H:i:s") . "]-[PID:" . $pid . "]-[". $priority ."][" . $scope . "]" . $str . "\n";
			} else {
				$string =  "[" . date("Y-m-d H:i:s") . "]-[PID:" . $pid . "]-[". $priority ."]" . $str . "\n";
			}
			
			if(fwrite($handle, $string) === false){
				die('Log file can not be written!');
			}
			fclose($handle);
		}
		
		
	}
}

class RecordConsole implements RecordMode{
	public function record($str, $priority, $scope = ""){
		$pid = getmypid();
	
		if (!empty($scope))  {
			print "[" . date("Y-m-d H:i:s") . "]-[PID:" . $pid . "]-[". $priority ."][" . $scope . "]" . $str . "\n";
		} else {
			print "[" . date("Y-m-d H:i:s") . "]-[PID:" . $pid . "]-[". $priority ."]" . $str . "\n";
		}
	}
}