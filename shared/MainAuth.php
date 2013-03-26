<?php
class MainAuth extends BasicAuth {
    function init(){
        parent::init();
        $this->setModel('Customer','username');
        $this->usePasswordEncryption('sha256/salt');
    }
}
