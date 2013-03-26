<?php
class Model_UserAdmin extends Model_Auditable {
    public $title_filed='username';
    public $table='user_admin';
    function init(){
        parent::init();
        
        $this->addField('username')->mandatory('Cannot be empty');
        $this->addField('password')->display(array('form'=>'password'))->mandatory('Type your password');
        $this->addField('password_reminder');
        $this->addField('type')->setValueList(
                array(
                        0=>'admin',
                        1=>'manager'
                )
        )->mandatory('Cannot be empty');
        
        $this->hasMany('OrderRebate');
        $this->hasMany('Adminlog');
        
        $this->api->auth->addEncryptionHook($this);

        $this->addField('created_at');
        $this->addField('updated_at');
        
        $this->addHook('beforeInsert',function($m,$q){
            $q->set('created_at',$q->expr('now()'));
        });
        
        $this->addHook('beforeSave',function($m){
            $m['updated_at']=date('Y-m-d G:i:s',time());
        });
    
    }

}