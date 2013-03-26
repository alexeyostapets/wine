<?php
class Model_Card extends Model_Auditable {
    public $table='card';
    function init(){
        parent::init();
        
        $this->addField('cc_number');
        $this->addField('cc_mask');
        $this->addField('cc_exp_month');
        $this->addField('cc_exp_year');
        $this->addField('card_ref');
        
        $this->hasOne('Customer');

        $this->add('Field_Lookup','status')->setKey('card_status');
        
        $this->hasMany('OrderPayment');

        $this->addHook('beforeSave',function($m){
            if($m['cc_number']!=''){
                $m['cc_mask']=substr($m['cc_number'],strlen($m['cc_number'])-4,4);
                $m['cc_number']='';
            }
        });
        
    }

    function generateCardRef(){
        $card_ref='card_'.$this->get('id').'_'.rand(1,10000);
        $this->set('card_ref',$card_ref);
        
        return $card_ref;
    }

    function getCustomer(){
        return $this->add('Model_Customer')->load($this->get('customer_id'));
    }
    function isCardSaved(){
        if ( ($this->get('card_ref')!='') && ($this->getCustomer()->get('payer_ref')!='') ){
            return true;
        } else {
            return false;
        }
    }
}