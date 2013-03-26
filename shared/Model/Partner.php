<?php
class Model_Partner extends Model_Auditable {
    public $title_filed='username';
    public $table='partner';
    function init(){
        parent::init();
        
        $this->addField('username')->mandatory('Cannot be empty');
        $this->addField('password')->display(array('form'=>'password'))->mandatory('Type your password');
        
        $this->addField('bill_to_name');
        
        $this->addField('ship_to_name');
        
        $this->hasMany('Address');

        $this->addField('contact_email');
        
        $this->add('Field_Lookup','ship_from_warehouse')->setKey('ship_from_warehouse');
        
        $this->api->auth->addEncryptionHook($this);
        
        $this->addField('created_at');
        $this->addField('updated_at');
        
        $this->addField('is_active')->type('boolean');
        
        $this->addHook('beforeInsert',function($m,$q){
            $q->gen_id=$q->add('ID');
            $id=$q->gen_id->generateMixedRevAndCheck('Model_Partner');
            $q->set('id',$id);
            $m['id']=$id;
            $q->set('created_at',$q->expr('now()'));
        });
        
        $this->addHook('beforeSave',function($m){
            $m['updated_at']=date('Y-m-d G:i:s',time());
        });
        
        $this->addHook('beforeInsert',function($m){
            if($m->getBy('username',$m['username'])){
                throw $m
                    ->exception('Partner with this username already exists','ValidityCheck')
                    ->setField('username');
            }
        });
        
        $this->addHook('beforeModify',function($m){
            if($m->dirty['username'])throw $m
                ->exception('Do not change username for existing partner','ValidityCheck')
                ->setField('username');
        });
        
        $this->addExpression('income')->set(function($m,$q){
                return $q->dsql()
                        ->field('SUM(amount)')
                        ->table('transaction')
                        ->join('card')
                        ->join('customer','card.customer_id')
                        ->join('partner','customer.partner_id')
                        ->where('type','receipt-in')
                        ->where('code',0)
//                        ->where('partner.id',$q->get('id'))
                ;
        });

        $this->addExpression('spent')->set(function($m,$q){
                return $q->dsql()
                        ->field('SUM(amount)')
                        ->table('transaction')
                        ->join('order_head')
                        ->join('partner','order_head.partner_id')
                        ->where('type','partner-in')
                        ->where('code',0)
                ;
        });
    }
    
    function getMyIncome(){
        return round($this->get('income')*$this->api->getConfig('refferal/percent')/100,2);
    }
    
}
