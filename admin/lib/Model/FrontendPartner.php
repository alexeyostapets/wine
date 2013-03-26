<?php
class Model_FrontendPartner extends Model_Partner {
	function init(){
		parent::init();
		
        $this->addHook('beforeSave',function($m){
            $m['password']=$m->api->auth->encryptPassword($m['password'],$m['username']);
        });
		
	}
}
