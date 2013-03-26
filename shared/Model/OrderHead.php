<?php
class Model_OrderHead extends Model_Auditable {
    public $table='order_head';
    function init(){
        parent::init();
        
        $this->hasOne('Customer','customer_id','username')->sortable(true);
        $this->hasOne('Partner','partner_id','username');
        $this->addField('items_total')->mandatory('Cannot be empty');
        $this->addField('shipping_charge')->mandatory('Cannot be empty');

        $this->add('Field_Lookup','shipping_method')->setKey('shipping_method');
        
        $this->addField('receipt_html');

        $this->add('Field_Lookup','status')->setKey('order_status');
        
        $this->addField('hold_until_date')->mandatory('Cannot be empty')->type('date');
        $this->addField('local_timestamp')->type('datetime');

        $this->hasMany('OrderItem');
        $this->hasMany('Transaction');

        $this->addField('created_at');
        $this->addField('updated_at');

        $this->addHook('beforeInsert',function($m,$q){
            $q->set('created_at',$q->expr('now()'));
        });

        $this->addHook('beforeSave',function($m){
            $m['updated_at']=date('Y-m-d G:i:s',time());
        });

        $this->addExpression('transaction_amount')->set(function($m,$q){
                return $q->dsql()
                    ->table('transaction')
                    ->field('amount')
                    ->where('transaction.order_head_id',$q->getField('id'))
                ;
        });
        
        $this->addExpression('transaction_code')->set(function($m,$q){
                return $q->dsql()
                    ->table('transaction')
                    ->field('code')
                    ->where('transaction.order_head_id',$q->getField('id'))
                ;
        });
        
        $this->addExpression('customer_partner_id')->set(function($m,$q){
                return $q->dsql()
                    ->table('customer')
                    ->join('partner')
                    ->field('partner.id')
                    ->where('order_head.customer_id',$q->expr('customer.id'))
                ;
        });
        
    }

}