<?php
class Model_LookupList extends Model_Auditable {
    public $table='lookup_list';
    public $title_field='label';
    function init(){
        parent::init();
        
        $this->addField('label');
        
    }

}