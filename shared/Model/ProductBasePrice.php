<?php
class Model_ProductBasePrice extends Model_Auditable {
    public $table='product_base_price';
    function init(){
        parent::init();

        $this->hasOne('Product','product_id','id')->mandatory('Cannot be empty');
        $this->hasOne('ProductPriceCategory','product_price_category_id','description');
        
        $this->addField('price');
        $this->addField('min_quantity');
        
    }

}