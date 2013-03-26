<?php
class Model_Address extends Model_Auditable {
    public $table='address';
    function init(){
        parent::init();
        
        $this->addField('address_1')->mandatory('Cannot be empty');
        $this->addField('address_2');
        $this->addField('address_3');
        $this->addField('city');
        $this->addField('district');
        $this->addField('postal_code');
        $this->addField('normalised_postal_code');
        $this->addField('country');
        $this->addField('type')->setValueList(
                array(
                        1=>'billing',
                        2=>'shipping'
                )
        );
        
        $this->hasOne('Customer','customer_id','username');
        $this->hasOne('Partner','partner_id','username');
        
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