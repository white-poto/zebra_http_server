<?php
define("ZEBRA_INCLUDE", dirname(__FILE__));
require_once(ZEBRA_INCLUDE . "/ResponseHandle.class.php");

class HandleFactory{
	public static function getResponseHandle($isStatic){
		if($isStatic){
			return new StaticResponseHandle();
		}else{
			return new DynamicResponseHandle();
		}
	}
}