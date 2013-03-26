<?php

class Model_Auditable extends Model_Table {
	public $modify_action;
	function init(){
		parent::init();

        if (get_class($this->api->auth->model)=='Model_UserAdmin'){
            $this->addHook('beforeModify',function($m){
                $m->modify_action = $m->refLog()
                    ->set('new_data',json_encode($m->get()))
                    ->set('changed_fields',json_encode($m->dirty))
                    ;
            });

            $this->addHook('afterModify',function($m){
                $m->modify_action->save();
            });

            $this->addHook('afterInsert',function($m){
                $m->refLog()
                    ->set('new_data',json_encode($m->get()))
                    ->set('changed_fields',json_encode(array('inserted'=>true)))
                    ->save();
            });

            $this->addHook('beforeDelete',function($m){
                $m->delete_action = $m->refLog('Model_Adminlog',false)
                    ->set('new_data',json_encode($m->get()))
                    ->set('changed_fields',json_encode(array('deleted'=>true)))
                    ;
            });

            $this->addHook('afterDelete',function($m){
                $m->delete_action->save();
            });
    
        }elseif (get_class($this->api->auth->model)=='Model_Customer'){
            if( (get_class($this)=='Model_Customer') || (get_class($this)=='Model_Address') || (get_class($this)=='Model_Card')
                || (get_class($this)=='Model_AutoshipOrder') ){

                $this->addHook('beforeModify',function($m){
                    $m->modify_action = $m->refLog('Model_Custlog')
                    ->set('new_data',json_encode($m->get()))
                    ->set('changed_fields',json_encode($m->dirty))
                    ;
                });
		        
                $this->addHook('afterModify',function($m){
                    $m->modify_action->save();
                });
		        
                $this->addHook('afterInsert',function($m){
                    $m->refLog('Model_Custlog')
                    ->set('new_data',json_encode($m->get()))
                    ->set('changed_fields',json_encode(array('inserted'=>true)))
                    ->save();
                });
        
                $this->addHook('beforeDelete',function($m){
                    $m->delete_action = $m->refLog('Model_Custlog',false)
                    ->set('new_data',json_encode($m->get()))
                    ->set('changed_fields',json_encode(array('deleted'=>true)))
                    ;
                });
    
                $this->addHook('afterDelete',function($m){
                    $m->delete_action->save();
                });
		        
                // Sending account changes
                $this->addHook('beforeModify',function($m){
                    $message="";
                    foreach($m->dirty as $field=>$key){
                        if ($field!='updated_at'){
                            $message=$message."FIELD ".$field.": ".$m->get($field)."\n";
                        }
                    }
                    $mail = $m->add('TMail');
                    $mail->loadTemplate('customerchanged');
                    $mail->setTag('from',$m->api->getConfig('tmail/from','test@test.com'));
                    $mail->setTag('tdata',$message);
                    $mail->send($m->get('contact_email'));
                });
            }
        }
        
    }

    function refLog($model='Model_Adminlog',$need_rec_id=true){
        $m= $this->add($model);
        $m->addCondition('class',get_class($this));
        if ($need_rec_id) $m->addCondition('rec_id',$this->id);
        return $m;
    }

	/* delete records one by one (for audit) */
/**
	function deleteAll(){
  		foreach($this as $junk){ $this->delete(); }
	}
*/
}
