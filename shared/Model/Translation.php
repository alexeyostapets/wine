<?php
class Model_Translation extends Model_Auditable {
    public $table='translation';
    function init(){
        parent::init();
        
        $this->addField('catalogue_id')->mandatory('Cannot be empty');
        $this->addField('group_id')->mandatory('Cannot be empty');
        $this->addField('key')->mandatory('Cannot be empty');
        $this->addField('processor_id');
        $this->addField('blacklist');
        $this->addField('en_gb')->mandatory('Cannot be empty');
        $this->addField('de_de');
        
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