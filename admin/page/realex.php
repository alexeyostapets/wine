<?php
class page_realex extends Page {
    function init(){
        parent::init();

        $model = $this->add('Model_Card');

        $crud=$this->add('CRUD',array('allow_add'=>false,'allow_edit'=>false,'allow_del'=>false));
        
        $crud->setModel($model,
            		array('cc_exp_month','card_exp_year','card_final_digits'),
            		array('first_name','last_name','cc_exp_month','card_exp_year','card_final_digits','payer_ref','card_ref')
            );
        
        if ($crud->grid){
            $crud->grid->addColumn('button','editcard','Edit Card');
			if($_GET['editcard']){
			    $this->js()->univ()->redirect('editcard?id='.$_GET['editcard'])->execute();
			}

			$crud->grid->addColumn('button','transactions','Transactions');
			if($_GET['transactions']){
			    $this->js()->univ()->redirect('transactions?id='.$_GET['transactions'])->execute();
			}
				
        }
    }
}
