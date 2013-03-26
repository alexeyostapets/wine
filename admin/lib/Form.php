<?php
class Form extends Form_Basic {
    public $split_columns=true;
    function init(){
        parent::init();
	// $this->setFormClass('stacked atk-row');
    }
    function recursiveRender(){
        if(count($this->elements)>8 && $this->split_columns){
	    $this->setFormClass('stacked atk-row');
            $o=$this->add('Order')
                ->move($this->addSeparator('noborder span6'),'first')
                ->move($this->addSeparator('noborder span5'),'middle')
                ->now();
        }
        parent::recursiveRender();
    }
}

