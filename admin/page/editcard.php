<?php
class page_editcard extends Page {
    function page_index(){
        parent::init();

        $this->api->stickyGET('id');

        $form = $this->add('Form');
        $form->setFormClass('vertical');
        $model = $this->add('Model_Card')->load($_GET['id']);

        $form->setModel($model,
                array(null)
                );
        $form->addField('line','payer_ref');
        $form->addField('line','card_ref');
        
        $form->addSubmit('Submit');

        if($form->isSubmitted()){
            $r=$this->add('bill\Controller_Realex');

            $r->model_transaction='Model_UserTransaction';
            $r->user_id=$_GET['id'];
            // Verifying the card
            //$result=$r->verify($form);
            $r->charge($form,2);
            /*
            if ($result->code==0){

                // Adding payer
                if ($form->model->get('payer_ref')==''){
                    $payer_ref=$form->model->generatePayerRef();
                    $result=$r->payernew($form);
                    
                    if ($result->code==0){
                        // Adding card
                        $result=$r->cardnew($form);
                        
                        if ($result->code==0){
                            $form->getModel()->set('card_final_digits',substr($form->get('cc_number'),strlen($form->get('cc_number'))-4,4));
                            $form->update();
                        }else{
                            $this->js()->univ()->successMessage($result->message)->execute();
                        }
                    }else{
                        $this->js()->univ()->successMessage($result->message)->execute();
                    }
                }
            }else{
                $this->js()->univ()->successMessage($result->message)->execute();
            }
            
            $this->api->redirect('realex');
            */
        }

    }	
}
