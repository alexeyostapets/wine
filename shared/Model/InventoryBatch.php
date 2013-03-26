<?php
class Model_InventoryBatch extends Model_Auditable {
    public $table='inventory_batch';
    function init(){
        parent::init();
        
        $this->addField('supplier_batch_id');
        $this->addField('best_before_date')->type('date');
        $this->addField('description');
        
        $this->hasMany('InventoryItem');
        
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