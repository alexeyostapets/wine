<?php
namespace bill;
class Exception extends \BaseException {
	static function c($msg){
		$x=new Exception($msg);
		return $x;
	}
	function addVariable($v){
		// get rid of this
	}
	function addSimpleXML($xml){
		// get rid of this
	}
}
