<?php
class Model_AutoshipEvent extends Model_Auditable {
    public $table='autoship_event';
    function init(){
        parent::init();
        
        $this->addField('event_description');
        
        $this->hasOne('Customer','customer_id','username');
        
        $this->add('Field_Lookup','event_type')->setKey('event_type');
        
        $this->addField('created_at');
        $this->addField('updated_at');
        
        $this->addHook('beforeInsert',function($m,$q){
            $q->set('created_at',$q->expr('now()'));
        });
        
        $this->addHook('beforeSave',function($m){
            $m['updated_at']=date('Y-m-d G:i:s',time());
        });

        $this->addExpression('event_type_name')->set(function($m,$q){
            return $q->dsql()
                ->table('lookup_list_item')
                ->field('label')
                ->where('lookup_list_item.id',$q->getField('event_type'))
                ;
        });
            
    }

}