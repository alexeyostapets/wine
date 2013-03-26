<?php
class Model_Department extends Model_Auditable {
    public $table='department';
    function init(){
        parent::init();
        
        $this->addField('code');
        $this->addField('site_id');
        $this->addField('slug');
        $this->addField('is_active')->type('boolean');
        
        $this->hasMany('DepartmentProduct');
        
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