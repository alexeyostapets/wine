<?php
class Model_ProductVariation extends Model_Auditable {
    public $table='product_variation';
    function init(){
        parent::init();
        
        $this->hasOne('Product','product_id','id');

        $this->add('Field_Lookup','product_variation')->setKey('product_variation');
        
        $this->addField('is_active')->type('boolean');
        
        $this->hasMany('AutoshipOrder');
        $this->hasMany('InventoryItem');
        $this->hasMany('OrderItem');

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