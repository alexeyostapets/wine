<?php
class Model_DepartmentProduct extends Model_Auditable {
    public $table='department_product';
    function init(){
        parent::init();
        
        $this->hasOne('Department','department_id','code')->mandatory('Cannot be empty');
        $this->hasOne('Product','product_id','id')->mandatory('Cannot be empty');
        
        $this->addField('sort_order');
        
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