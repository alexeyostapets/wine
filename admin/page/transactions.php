<?php
class page_transactions extends Page {
    function page_index(){
        parent::init();

        $this->add('HtmlElement')->setElement('h4')->set('Successful Receipt-in transactions');

        $model = $this->add('Model_UserTransaction');
        $model->addCondition('type','receipt-in')->addCondition('refund_id',null);
        
        $success_crud=$this->add('CRUD',array('allow_add'=>false,'allow_edit'=>false,'allow_del'=>false));
        
        $success_crud->setModel($model,
                null,
                array('type','amount','created','response','code','pasref','orderid','authcode','user')
        );
        
        $success_crud->grid->addColumn('button','refund');
        if($_GET['refund']){
            $tr_refund=$this->add('Model_UserTransaction')->load($_GET['refund']);
            $r=$this->add('bill\Controller_Realex');
            $r->rebate($tr_refund);
        }
        
        
        $this->add('HtmlElement')->setElement('h4')->set('All transactions');
        
        $model = $this->add('Model_UserTransaction');
        //$model->addCondition('type','receipt-in');
        
        $full_crud=$this->add('CRUD',array('allow_add'=>false,'allow_edit'=>false,'allow_del'=>false));
        
        $full_crud->setModel($model,
                null,
                array('type','amount','created','response','code','pasref','orderid','authcode','user')
        );
        
    }	
}
