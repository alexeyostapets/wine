<?php
class page_translation extends Page {
    function init(){
        parent::init();

    }
    
    function page_index(){
        $crud=$this->add('CRUD');
        $model=$this->add('Model_Translation');
        $crud->setModel($model,
                array('catalogue_id','group_id','key','processor_id','blacklist','en_gb','de_de'),
                array('catalogue_id','group_id','key','processor_id','blacklist','en_gb','de_de','created_at','updated_at')
            );
        
        if($crud->grid){
            $crud->grid->addQuickSearch(array('id'));
        }
        
    }
    
}
