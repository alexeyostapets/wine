<?php
class Model_Adminlog extends Model_Table {
	public $table="adminlog";
	function init(){
		parent::init();
		
		$this->addField('new_data');
		$this->addField('changed_fields');
		$this->addField('class');
		$this->addField('rec_id');
		$this->addField('created_at');
		$this->hasOne('UserAdmin','user_admin_id','username');
		
        $this->addHook('beforeInsert',function($m,$q){
            $q->set('created_at',$q->expr('now()'));
        });
		
		$this->addHook('beforeSave',function($m){
		    $m['user_admin_id']=$m->api->auth->model['id'];
		});
		
	}

}