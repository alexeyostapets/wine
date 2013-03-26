<?php
namespace bill;
class Exception_PaymentFailed extends Exception {
	static function c($msg){
		$x=new Exception_PaymentFailed($msg);
		return $x;
	}
    function shortenVariable($v){
        if(is_array($v))return "Array(..)";
        if(is_object($v)){
            $t=get_class($v);
            if(method_exists($v,'__toString'))return $v->__toString();
            return "($t)[object]";
        }
        if(strlen($v)>1000)return '"'.htmlspecialchars(substr($v,0,600).'..'.substr($v,-300)).'"';
        return "'".htmlspecialchars($v)."'";
    }
    function setVariable($name,$value,$description=null){
        $this->additional_info[]=array($name,$this->shortenVariable($value),$description);
        return $this;
    }
}
