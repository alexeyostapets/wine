<?php
namespace bill;
class Controller_Realex extends \AbstractController
{

    public $hash_func='sha1';        // which encryption method to use
    public $version='Agile Toolkit / Billing module 1.0';
    public $model_transaction='Model_Transaction';
    public $user_id='';
    public $type='';
    public $customer='';
    public $card='';
    public $amount='';
    public $order_head_id='';
    
    function init(){
        parent::init();
        $this->hash_func=$this->api->getConfig('billing/realex/hash_func',$this->hash_func);
    }
    
    function setModelTransaction($model){
        $model_transaction=$model;
    }

    function getSupportedCardTypes(){
        return array(
                'VISA'=>'Visa',
                'LASER'=>'Laser',
                'MC'=>'Master Card',
                'AMEX'=>'Amex',
                'SWITCH'=>'Switch',
                'DINERS'=>'Diners',
                );
    }

    function addRequest($type){
        $this->type=$type;
        return $this
            ->add('bill\Model_Realex_Transaction_'.str_replace('-','',$type))
            ->set('type',$type)
            ->addHook('request-complete',array($this,'requestComplete'))
            ;
    }
    function requestComplete($request,$response){
        $transaction=$this->add($this->model_transaction);
        $transaction->createTransaction($this->type,$this->amount,$request,$response,$this->customer->get('current_card_id'),$this->order_head_id);
        $this->hook('request-complete',array($request,$response));
    }
    
    // Interface Functions
    function checkAndCharge($card, $amount, $currency=null, $order_head_id=null, $description=null){
        $this->card=$card;
        $this->customer=$card->getCustomer();
        $this->order_head_id=$order_head_id;
        $card_ref=$card->get('card_ref');
        // Perform charge of amount from $card
        if($this->card->isCardSaved()){
            $this->amount=$amount;
            $r=$this->addRequest('receipt-in')
                ->set('payer_ref',$this->customer->get('payer_ref'))
                ->set('card_ref',$card_ref)
                ->set('amount',round($amount*100))
                ->set('currency',$currency)
                ;
        }else{
            return array('errors'=>array('payer_ref or card_ref error!'));
        }
        $r->process();
        $this->answer($r);
    }
    
    function charge($card, $amount, $return_array=true, $currency=null, $description=null){
        $this->card=$card->getModel();
        $this->customer=$card->model->getCustomer();
        $card_ref=$card->model->get('card_ref');
        // Perform charge of amount from $card
        if($this->card->isCardSaved()){
            $this->amount=$amount;
            $r=$this->addRequest('receipt-in')
                ->set('payer_ref',$this->customer->get('payer_ref'))
                ->set('card_ref',$card_ref)
                ->set('amount',round($amount*100))
                ->set('currency',$currency)
                ;
        }else{
            $this->amount=$amount;
            $r=$this->addRequest('auth')
                ->set($card->data)
                ->set('cc_number',$card->get('cc_number'))
                ->set('cc_cvn',$card->get('cc_cvn'))
                ->set('amount',round($amount*100))
                ->set('currency',$card->get('currency'))
                ->set('exp_month',$card->get('exp_month'))
                ->set('exp_year',$card->get('exp_year'))
                ->set('cc_name',$card->get('first_name').' '.$card->get('last_name'))
                ;
        }
        $r->process();
        if ($return_array) return $this->answer($r); else return $r;
    }
    
    function verify($card){
        $r=$this->charge($card,1,false);
        if ( (is_array($result)) && ($result['errors']) ) return $result;
        return $this->void($r);
    }
    
    function void($data){
        $r=$this->addRequest('void')
                ->set('pasref',$data->pasref)
                ->set('authcode',$data->authcode)
                ->set('orderid',$data->orderid)
                ->process();
        return $this->answer($r);
    }
    
    function rebate($transaction,$card){
        $this->card=$card;
        $this->amount=$transaction->get('amount');
        $r=$this->addRequest('rebate')
            ->set('pasref',$transaction->get('pasref'))
            ->set('authcode',$transaction->get('authcode'))
            ->set('orderid',$transaction->get('orderid'))
            ->set('amount',round($this->amount*100))
            ->set('currency',$card->get('currency'))
            ->process()
            ;
        return $this->answer($r);
    }
    
    function payer_new($customer){
        $this->customer=$customer;
        $this->amount=0;
        $bill_to_name=$customer->get('bill_to_name');
        $a_bill_to_name=explode(' ',$bill_to_name);

        $r=$this->addRequest('payer-new')
            ->set('payer_ref',$customer->generatePayerRef())
            ->set('first_name',$a_bill_to_name[0])
            ->set('last_name',$a_bill_to_name[1])
            ->process()
            ;
        return $this->answer($r);
    }

    function payernew($form_customer){
        $this->customer=$form_customer->getModel();
        $this->amount=0;
        $bill_to_name=$form_customer->get('bill_to_name');
        $a_bill_to_name=explode(' ',$bill_to_name);

        $r=$this->addRequest('payer-new')
            ->set('payer_ref',$form_customer->model->generatePayerRef())
            ->set('first_name',$a_bill_to_name[0])
            ->set('last_name',$a_bill_to_name[1])
            ->process()
            ;
        return $this->answer($r);
    }

    function card_new($card,$customer){
        $this->card=$card;
        $this->customer=$customer;
        $this->amount=0;
        if ($card->get('cc_exp_month')<10) $exp_month="0".$card->get('cc_exp_month'); else $exp_month=$card->get('cc_exp_month');
        $r=$this->addRequest('card-new')
            ->set('card_ref',$card->generateCardRef())
            ->set('payer_ref',$customer->get('payer_ref'))
            ->set('cc_number',$card->get('cc_number'))
            ->set('exp_month',$exp_month)
            ->set('exp_year',$card->get('cc_exp_year'))
            ->set('cc_name',$customer->get('bill_to_name'))
//            ->set('cc_type',$card->get('cc_type'))
            ->set('issueno',1)
            ->process()
            ;
        return $this->answer($r);
    }
    
    function cardnew($form_card,$customer){
        $this->card=$form_card->getModel();
        $this->customer=$customer;
        $this->amount=0;
        if ($form_card->get('cc_exp_month')<10) $exp_month="0".$form_card->get('cc_exp_month'); else $exp_month=$form_card->get('cc_exp_month');
        $r=$this->addRequest('card-new')
            ->set('card_ref',$form_card->model->generateCardRef())
            ->set('payer_ref',$customer->get('payer_ref'))
            ->set('cc_number',$form_card->get('cc_number'))
            ->set('exp_month',$exp_month)
            ->set('exp_year',$form_card->get('cc_exp_year'))
            ->set('cc_name',$customer->get('bill_to_name'))
//            ->set('cc_type',$form_card->get('cc_type'))
            ->set('issueno',1)
            ->process()
            ;
        return $this->answer($r);
    }
    
    function cardcancel($card){
        $this->card=$card;
        $this->amount=0;
        $r=$this->addRequest('card-cancel-card')
            ->set('card_ref',$card->get('card_ref'))
            ->set('payer_ref',$card->get('payer_ref'))
            ->process()
            ;
        return $this->answer($r);
    }

    function answer($r){
        if ($r->code>0) {
            return array('errors'=>array($r->code=>$r->message));
        }else{
            return array('msg'=>$r->message);
        }
    }
}
