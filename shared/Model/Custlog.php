<?php
class Model_Custlog extends Model_Table {
	public $table="custlog";
	function init(){
		parent::init();
		
		$this->addField('new_data');
		$this->addField('changed_fields');
		$this->addField('class');
		$this->addField('rec_id');
		$this->addField('created_at');
		$this->hasOne('Customer','customer_id','bill_to_name');
		
        $this->addHook('beforeInsert',function($m,$q){
            $q->set('created_at',$q->expr('now()'));
        });
		
		$this->addHook('beforeSave',function($m){
		    $m['customer_id']=$m->api->auth->model['id'];
		});
		
	}

}