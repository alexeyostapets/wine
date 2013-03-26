<?php
class page_users extends Page {
    function init(){
        parent::init();

    }
    
    function page_index(){
        $tabs = $this->add('Tabs');
        
        $tabs->addTabUrl('./customer','Customers');

        $tabs->addTabUrl('./partner','Partners');

        $tabs->addTabUrl('./admin','Admins');
        
    }
    
    function page_customer(){
        $crud=$this->add('CRUD');
        
        $model = $this->add('Model_FrontendCustomer');

        $crud->setModel($model,
                		array('username','password','bill_to_name','ship_to_name','contact_phone_day','contact_phone_evening','contact_email','payer_ref','currency','ship_from_warehouse'),
                		array('username','bill_to_name','contact_phone_day','contact_phone_evening','contact_email','current_card'));
        
        if($p=$crud->addFrame('Details')){
            $p->add('View_ModelDetails')->setModel($crud->model->newInstance())->load($crud->id);
        }
        
        if($crud->grid){
        	$crud->grid->addQuickSearch(array('username','bill_to_name'));
        	
        	$crud->grid->addColumn('expander','addresses');
        	$crud->grid->addColumn('expander','cards');
        }
    }

    function page_partner(){
        $crud=$this->add('CRUD');
        
        $model = $this->add('Model_FrontendPartner');

        $crud->setModel($model,
                		array('username','password','bill_to_name','ship_to_name','contact_email','ship_from_warehouse'),
                		array('username','bill_to_name','contact_email'));
        
        if($p=$crud->addFrame('Details')){
            $p->add('View_ModelDetails')->setModel($crud->model->newInstance())->load($crud->id);
        }
        
        if($crud->grid){
        	$crud->grid->addQuickSearch(array('username','bill_to_name'));
        }
    }

    function page_admin(){
        $crud=$this->add('CRUD');
    
        $model = $this->add('Model_FrontendCustomer');
    
        $model = $this->add('Model_Adminuser');
        $crud->setModel($model,
                        array('username','password','type'),
                		array('username','type','created_at','updated_at'));
        
        if($crud->grid){
        	$crud->grid->addQuickSearch(array('username'));
	    }
    }
    
    // "Expander" pages
    function page_customer_addresses(){
        $this->api->stickyGet('customer_id');
        $model=$this->add('Model_Address')->addCondition('customer_id',$_GET['customer_id']);
        $crud=$this->add('CRUD');
        $crud->setModel($model,
                array('address_1','address_2','address_3','city','district','postal_code','normalised_postal_code','country','type'),
                array('address_1','address_2','address_3','city','district','postal_code','normalised_postal_code','country','type','created_at','updated_at')
                );
    }
    
    function page_customer_cards(){
        $this->api->stickyGet('customer_id');
        $model=$this->add('Model_Card')->addCondition('customer_id',$_GET['customer_id']);
        $crud=$this->add('CRUD');
        $crud->setModel($model,
                array('cc_mask','cc_exp_month','cc_exp_year','card_ref','customer_id','status'),
                array('cc_mask','cc_exp_month','cc_exp_year','card_ref')
                );
        if ($crud->grid){
            $crud->grid->addColumn('button','current','Set current');
            if($_GET['current']){
                $customer=$this->add('Model_Customer')->load($_GET['customer_id']);
                $customer->set('current_card_id',$_GET['current']);
                $customer->saveAndUnload();
                $this->js()->univ()->redirect('users')->execute();
                $this->js()->univ()->successMessage('Success')->execute();
            }
        }
    }

}
