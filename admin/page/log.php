<?php
class page_log extends Page {
    function init(){
        parent::init();

    }
    
    function page_index(){

        $model=$this->add('Model_Adminlog');
        $model->setOrder('created_at DESC');
        $crud=$this->add('CRUD',array('allow_add'=>false,'allow_edit'=>false,'allow_del'=>false));
        $crud->setModel($model);
        
        if($p=$crud->addFrame('Details')){
            $p->add('View_ModelDetails')->setModel($crud->model->newInstance())->load($crud->id);
        }

        if($crud->grid){
            $crud->grid->addQuickSearch(array('new_data','class'));
        }
        
    }
    
}
