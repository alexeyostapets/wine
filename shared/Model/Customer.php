<?php
class Model_Customer extends Model_Auditable {
    public $title_filed='username';
    public $table='customer';
    function init(){
        parent::init();
        
        $this->addField('username')->mandatory('Cannot be empty');
        $this->addField('password')->display(array('form'=>'password'))->mandatory('Type your password');
        
        $this->addField('bill_to_name');
        
        $this->addField('ship_to_name');
        
        $this->hasMany('Address');

        $this->addField('contact_phone_day');
        $this->addField('contact_phone_evening');
        $this->addField('contact_email');
        
        $this->hasOne('Card','current_card_id','cc_mask');
        
        $this->addField('payer_ref');
        $this->addField('currency');
        
        $this->add('Field_Lookup','ship_from_warehouse')->setKey('ship_from_warehouse');
        
        $this->hasMany('AutoshipEvent');
        $this->hasMany('AutoshipOrder');
        $this->hasMany('Card');
        
        $this->api->auth->addEncryptionHook($this);
        
        $this->addField('partner_id');

        $this->addField('created_at');
        $this->addField('updated_at');
        
        $this->addField('is_active')->type('boolean');
        
        $this->addHook('beforeInsert',function($m,$q){
            $q->gen_id=$q->add('ID');
            $id=$q->gen_id->generateMixedAndCheck('Model_Customer');
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
                    ->exception('User with this username already exists','ValidityCheck')
                    ->setField('username');
            }
        });
        
        $this->addHook('beforeModify',function($m){
            if($m->dirty['username'])throw $m
                ->exception('Do not change username for existing user','ValidityCheck')
                ->setField('username');
        });

    }
    
    public function toStringSQL($source_field, $dest_fieldname, $expr = 'name') {
        return 'concat(first_name," ",last_name) as ' . $dest_fieldname;
    }
    
    function generatePayerRef(){
        $payer_ref='payer_'.$this->get('id').'_'.rand(1,10000);
        $this->set('payer_ref',$payer_ref);
        
        return $payer_ref;
    }
}
