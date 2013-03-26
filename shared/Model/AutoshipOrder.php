<?php
class Model_AutoshipOrder extends Model_Auditable {
    public $table='autoship_order';
    function init(){
        parent::init();
        
        $this->addField('quantity')->mandatory('Cannot be empty');
        $this->addField('next_shipment_date')->mandatory('Cannot be empty')->type('date');
        $this->addField('interval_type')->mandatory('Cannot be empty')->enum(array('month','day'));
        $this->addField('shipment_interval_days');
        $this->addField('shipment_monthly_frequency');
        $this->addField('shipment_day_of_month');
        $this->addField('last_action_date')->mandatory('Cannot be empty')->type('date');
        $this->addField('status');
        $this->addField('is_active')->mandatory('Cannot be empty')->type('boolean');        
        
        $this->hasOne('Customer','customer_id','username')->mandatory('Cannot be empty');
        $this->hasOne('Product','product_id','id')->mandatory('Cannot be empty');
        $this->hasOne('ProductVariation');
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