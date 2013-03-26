<?php
class Model_LookupListItem extends Model_Auditable {
    public $table='lookup_list_item';
    public $title_field='value';
    function init(){
        parent::init();
        
        $this->hasOne('LookupList','lookup_list_id','label');
        
        $this->addField('label');
        $this->addField('value');
        $this->addField('sort_order_a');
        $this->addField('sort_order_b');
            
    }

}