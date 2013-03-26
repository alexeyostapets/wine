<?php
class Model_InventoryItem extends Model_Auditable {
    public $table='inventory_item';
    function init(){
        parent::init();
        
        $this->hasOne('Product','product_id','id')->mandatory('Cannot be empty');
        $this->hasOne('ProductVariation','product_variation_id','product_variation');
        $this->hasOne('InventoryBatch','inventory_batch_id','description');
        
        $this->addField('base_currency_unit_cost');
        $this->addField('status');
        $this->addField('available_from_date')->type('date');
        $this->addField('initial_quantity');
        $this->addField('quantity_on_hand');
        $this->addField('out_of_stock_buffer');
        $this->addField('reorder_level');
        
        $this->hasMany('OrderItem');
        $this->add('Field_Lookup','ship_from_warehouse')->setKey('ship_from_warehouse');
        
        $this->addField('created_at');
        $this->addField('updated_at');
        
        $this->addHook('beforeInsert',function($m,$q){
            $q->set('created_at',$q->expr('now()'));
        });
        
        $this->addHook('beforeSave',function($m){
            $m['updated_at']=date('Y-m-d G:i:s',time());
        });
    
    }

    function changeQuantity($quantity){
        $this->set('quantity_on_hand',$this->get('quantity_on_hand')+$quantity);
        $this->save();
    }
}