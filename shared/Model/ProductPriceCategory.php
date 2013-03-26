<?php
class Model_ProductPriceCategory extends Model_Auditable {
    public $table='product_price_category';
    function init(){
        parent::init();
        
        $this->addField('price_category_code');
        
        $this->add('Field_Lookup','currency_code')->setKey('currency_code');

        $this->addField('description');
        
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