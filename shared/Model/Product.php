<?php
class Model_Product extends Model_Auditable {
    public $table='product';
    function init(){
        parent::init();

        $this->getField('id')->editable(true)->visible(true);

        $this->addField('weight_grams');
        $this->addField('max_online_order_num');
        $this->addField('is_kit')->type('boolean');
        $this->addField('is_suspended')->type('boolean');
        $this->addField('tags');
        $this->addField('notes');
        $this->addField('available_from')->type('date');
        $this->addField('available_thru')->type('date');
        $this->addField('product_type');
        
        $this->add('Field_Lookup','unit_of_measure')->setKey('unit_of_measure');
        
        $this->hasMany('AutoshipOrder');
        $this->hasMany('DepartmentProduct');
        $this->hasMany('InventoryItem');
        $this->hasMany('OrderItem');
        $this->hasMany('ProductBasePrice');
        $this->hasMany('ProductProperty');
        $this->hasMany('ProductRelationship','from_product_id');
        $this->hasMany('ProductRelationship','to_product_id');
        
        $this->addField('created_at');
        $this->addField('updated_at');

        $this->addHook('beforeInsert',function($m,$q){
            $q->set('created_at',$q->expr('now()'));
        });
        
        $this->addHook('beforeSave',function($m){
            $m['updated_at']=date('Y-m-d G:i:s',time());
        });

        $this->addExpression('price')->set(function($m,$q){
                return $q->dsql()
                    ->table('product_base_price')
                    ->field('price')
                    ->where('product_base_price.product_id',$q->getField('id'))
                ;
        });
        
        // beforeUpdate
        // if(in_array($this->dirty,'id')) throw $this->exception('Do not change ID'))
    
    }

    function tree($order='id'){
        $result=array();
        $products=$this->setOrder($order);
        foreach($products as $product){
            $result[$product['id']]=$product['id'].' - '.$product['price'];
        }
        return $result;
    }
}
