<?php

// $model->add('Field_Lookup','country_id')->setKey('country');
//
//$model->ref('country_id') = 

class Field_Lookup extends Field_Reference {
    public $key;

    function setKey($key){
        $this->key=$key;
        $m=$this->add('Model_LookupListItem');
        $m2=$m->ref('lookup_list_id','model');
        $m2->addCondition('label',$key);
        $subquery=$m2->fieldQuery('id');
        $m->addCondition('lookup_list_id',$subquery);
        $this->model=$m;
        return $this;
    }

    function ref($mode=null){
        $m=parent::ref($mode);
        $m->addCondition('key',$this->key);
    }
}
