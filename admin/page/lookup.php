<?php
class page_lookup extends Page {
    function init(){
        parent::init();

    }

    function page_index(){
        $crud=$this->add('CRUD');
        
        $model = $this->add('Model_LookupList');
        $crud->setModel($model
                );
        
        if($crud->grid){
            $crud->grid->addColumn('expander','items');
        }
    }

    // "Expander" pages
    function page_items(){
        $this->api->stickyGet('lookup_list_id');
        $model=$this->add('Model_LookupListItem')->addCondition('lookup_list_id',$_GET['lookup_list_id']);
        $crud=$this->add('CRUD');
        $crud->setModel($model,
                array('label','value','sort_order_a','sort_order_b'),
                array('label','value','sort_order_a','sort_order_b')
        );
    }
    
}

