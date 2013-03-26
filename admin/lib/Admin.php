<?php
class Admin extends ApiFrontend {
    function init(){
        parent::init();
        $this->dbConnect();
        $this->requires('atk','4.2.0');


        $this->pathfinder->addLocation('..',array(
        		'addons'=>array('atk4-addons','addons'),
        		'php'=>array('shared','billing/lib'),
        		'js'=>'templates/js',
	        ))
	        ->setParent($this->pathfinder->base_location);
        
        $this->add('jUI');
        
        $this->auth=$this->add('AdminAuth');
        
        $this->auth->allowPage(array('login'));
        
        // Create different menus
        if($this->auth->isLoggedIn()){
        	// For admin separated menu
       		$this->add('Menu',null,'Menu')
	        	//->addMenuItem('lookup','Lookup')
       		    ->addMenuItem('users','Users')
	        	->addMenuItem('product','Products')
	        	//->addMenuItem('realex','Realex')
	        	->addMenuItem('translation','Translation')
       		    ->addMenuItem('log','Log')
	        	->addMenuItem('logout')
	       		;
        }else{
        	$this->add('Menu',null,'Menu')
   		    	->addMenuItem('login')
	        	;
        }
        
        if(!$this->auth->isPageAllowed($this->page)){
        	if(!$this->auth->isLoggedIn()){
        		$this->api->redirect('login');
        	}
        }
        
    }
}
