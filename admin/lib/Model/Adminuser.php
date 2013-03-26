<?php
class Model_Adminuser extends Model_UserAdmin {
	function init(){
		parent::init();
		
        $this->addHook('beforeSave',function($m){
            $m['password']=$m->api->auth->encryptPassword($m['password'],$m['username']);
        });
		
	}
}
