<?php
class Model_ProductProperty extends Model_Auditable {
    public $table='product_property';
    function init(){
        parent::init();

        $this->hasOne('Product','product_id','id')->mandatory('Cannot be empty');
        
        $this->addField('height_mm');
        $this->addField('width_mm');
        $this->addField('depth_mm');
        $this->addField('content_type');
        $this->addField('content_quantity');
        
    }

}