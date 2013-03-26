<?php
class Model_ProductRelationship extends Model_Auditable {
    public $table='product_relationship';
    function init(){
        parent::init();
        
        $this->add('Field_Lookup','product_relationship_type')->setKey('product_relationship_type');

        $this->hasOne('Product','from_product_id');
        $this->hasOne('Product','to_product_id');
        
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