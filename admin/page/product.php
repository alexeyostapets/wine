<?php
class page_product extends Page {
    function init(){
        parent::init();

    }

    function page_index(){
        $tabs = $this->add('Tabs');
        
        $tabs->addTabUrl('./department','Departments');

        $tabs->addTabUrl('./products','Products');
        
        $tabs->addTabUrl('./relation','Relationship');
        
        $tabs->addTabUrl('./pricecat','Price Categories');
        
        $tabs->addTabUrl('./batch','Inventory Batches');
        
    }
    
    function page_department(){
        $crud=$this->add('CRUD');
        
        $model = $this->add('Model_Department');
        $crud->setModel($model,
                array('code','site_id','slug','is_active'),
                array('code','site_id','slug','is_active','created_at','updated_at'));

        
        if($crud->grid){
            $crud->grid->addQuickSearch(array('id'));
        }
    }
    
    function page_products(){
        $crud=$this->add('CRUD');
        
        $model = $this->add('Model_Product');
        $crud->setModel($model,
                array('id','weight_grams','max_online_order_num','is_kit','is_suspended','tags','notes','available_from','available_thru','product_type','unit_of_measure'),
                array('id','weight_grams','is_kit','is_suspended','available_from','available_thru','product_type')
            );

        if($p=$crud->addFrame('Details')){
            $p->add('View_ModelDetails')->setModel($crud->model->newInstance())->load($crud->id);
        }

        if($crud->grid){
            $crud->grid->addQuickSearch(array('id'));
            
            $crud->grid->addColumn('expander','departments');
            $crud->grid->addColumn('expander','properties');
            $crud->grid->addColumn('expander','price');
            $crud->grid->addColumn('expander','variation');
            $crud->grid->addColumn('expander','inventory_item');
        }

        if($crud->form){
            if($crud->form->model->id){
                $crud->form->getElement('id')->js(true)->attr('readonly',true);
            }
        }
    }
    
    function page_relation(){
        $crud=$this->add('CRUD');
        
        $model = $this->add('Model_ProductRelationship');
        $crud->setModel($model,
                array('product_relationship_type','from_product_id','to_product_id'),
                array('product_relationship_type','from_product_id','to_product_id','created_at','updated_at'));
    }
    
    function page_pricecat(){
        $crud=$this->add('CRUD');
        
        $model = $this->add('Model_ProductPriceCategory');
        $crud->setModel($model,
                array('price_category_code','description','currency_code'),
                array('price_category_code','description','created_at','updated_at'));
        
        if($crud->grid){
            $crud->grid->addQuickSearch(array('id'));
        }
    }
    
    function page_batch(){
        $crud=$this->add('CRUD');
        
        $model = $this->add('Model_InventoryBatch');
        $crud->setModel($model,
                array('best_before_date','description'),
                array('best_before_date','description','created_at','updated_at'));
        
        if($crud->grid){
            $crud->grid->addQuickSearch(array('id'));
        }
    }
    
    // "Expander" pages
    function page_products_departments(){
        $this->api->stickyGet('product_id');
        $model=$this->add('Model_DepartmentProduct')->addCondition('product_id',$_GET['product_id']);
        $crud=$this->add('CRUD');
        $crud->setModel($model,
                array('department_id','sort_order'),
                array('department','sort_order','created_at','updated_at')
                );
    }
    
    function page_products_properties(){
        $this->api->stickyGet('product_id');
        $model=$this->add('Model_ProductProperty')->addCondition('product_id',$_GET['product_id']);
        $crud=$this->add('CRUD');
        $crud->setModel($model
                );
    }

    function page_products_price(){
        $this->api->stickyGet('product_id');
        $model=$this->add('Model_ProductBasePrice')->addCondition('product_id',$_GET['product_id']);
        $crud=$this->add('CRUD');
        $crud->setModel($model
                );
    }

    function page_products_variation(){
        $this->api->stickyGet('product_id');
        $model=$this->add('Model_ProductVariation')->addCondition('product_id',$_GET['product_id']);
        $crud=$this->add('CRUD');
        $crud->setModel($model,
                array('product_id','product_variation','is_active'),
                array('product','product_variation','is_active','created_at','updated_at')
                );
    }
    
    function page_products_inventory_item(){
        $this->api->stickyGet('product_id');
        $model=$this->add('Model_InventoryItem')->addCondition('product_id',$_GET['product_id']);
        $crud=$this->add('CRUD');
        $crud->setModel($model,
                array('product_variation_id','inventory_batch_id','base_currency_unit_cost','status','available_from_date','initial_quantity','quantity_on_hand','out_of_stock_buffer','reorder_level','ship_from_warehouse'),
                array('inventory_batch','base_currency_unit_cost','status','available_from_date','initial_quantity','quantity_on_hand','out_of_stock_buffer','reorder_level','created_at','updated_at')
                );
    }
}

