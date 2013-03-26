<?php
class Grid extends Grid_Advanced {
	function init(){
		parent::init();
		$this->addClass('zebra bordered');
		$this->addPaginator($this->api->getConfig('paginator/page/count',10));
	}
}
