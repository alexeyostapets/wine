<?php
class Model_Transaction extends Model_Auditable {
    public $table='transaction';
    function init(){
        parent::init();
        
        $this->hasOne('OrderHead');
        $this->hasOne('Card','card_id','cc_mask')->mandatory('Cannot be empty');
        
        $this->addField('type');
        $this->addField('amount');
        $this->addField('response');
        $this->addField('code');
        $this->addField('pasref');
        $this->addField('orderid');
        $this->addField('authcode');

        $this->addField('created_at');
        
        $this->addHook('beforeInsert',function($m,$q){
            $q->set('created_at',$q->expr('now()'));
        });
        
    }

    function createTransaction($type,$amount,$transaction,$response,$card_id){
        $this->set('type',$type);
        $this->set('amount',$amount);
        $this->set('code',(string) $transaction->code);
        $this->set('response',(string) $response->message->__toString());
        if ($response->pasref->__toString()!='') $this->set('pasref',$response->pasref->__toString());
        if ($response->orderid->__toString()!='') $this->set('orderid',$response->orderid->__toString());
        if ($response->authcode->__toString()!='') $this->set('authcode',$response->authcode->__toString());
        $this->set('card_id',$card_id);
        $this->save();
        
        if ( ($type=='void') || ($type=='rebate') ){
            $refunded_trans=$this->add('Model_CardTransaction')->addCondition('orderid',$response->orderid->__toString())->addCondition('type',array('receipt-in','auth'));
            $refunded_trans->tryLoadAny();
            if ($refunded_trans->loaded()){
                $refunded_trans['refund_id']=$this->get('id');
                $refunded_trans->saveAndUnload();
            }
        }
    }
}