<?php
class Model_FrontendCustomer extends Model_Customer {
	function init(){
		parent::init();
		
        $this->addHook('beforeSave',function($m){
            $m['password']=$m->api->auth->encryptPassword($m['password'],$m['username']);
        });
		
	}
}
