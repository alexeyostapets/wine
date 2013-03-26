<?php
class Model_Admin extends Model_Table {
    public $title_filed='email';
	public $table="admin";
	function init(){
		parent::init();
		
		$this->addField('email')->mandatory('Email cannot be empty')->sortable(true);
		$this->addField('password')->display(array('form'=>'password'))->mandatory('Type your password');
		$this->addField('created')->sortable(true);
		$this->addField('updated')->sortable(true);

		$this->api->auth->addEncryptionHook($this);
		
		$this->addHook('beforeInsert',function($m,$q){
			$q->set('created',$q->expr('now()'));
		});
		
		$this->addHook('beforeSave',function($m){
			$m['updated']=date('Y-m-d G:i:s',time());
			$m['email']=strtolower($m['email']);
		});
		
	}

}