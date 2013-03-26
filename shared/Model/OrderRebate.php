<?php
class Model_OrderRebate extends Model_Auditable {
    public $table='order_rebate';
    function init(){
        parent::init();
        
        $this->hasOne('OrderPayment','order_payment_id','realex_order_id')->mandatory('Cannot be empty');
        $this->hasOne('UserAdmin','user_admin_id','username')->mandatory('Cannot be empty');
        
        $this->addField('realex_order_id')->mandatory('Cannot be empty');
        $this->addField('pasref')->mandatory('Cannot be empty');
        $this->addField('authcode')->mandatory('Cannot be empty');
        $this->addField('rebate_amount')->mandatory('Cannot be empty');

        $this->addField('created_at');
        $this->addField('updated_at');

        $this->addHook('beforeInsert',function($m,$q){
            $q->set('created_at',$q->expr('now()'));
        });
        
        $this->addHook('beforeSave',function($m){
            $m['updated_at']=date('Y-m-d G:i:s',time());
            $m['user_admin_id']=$m->api->auth->model['id'];
        });

    }

}