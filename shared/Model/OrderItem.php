<?php
class Model_OrderItem extends Model_Auditable {
    public $table='order_item';
    function init(){
        parent::init();
        
        $this->hasOne('OrderHead','order_id','id')->mandatory('Cannot be empty');
        $this->hasOne('Product','product_id','id')->mandatory('Cannot be empty');
        $this->hasOne('ProductVariation');
        $this->hasOne('AutoshipOrder');
        $this->hasOne('ProductPriceCategory');
        $this->hasOne('InventoryItem');
        
        $this->addField('quantity')->mandatory('Cannot be empty');
        $this->addField('price');
        
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