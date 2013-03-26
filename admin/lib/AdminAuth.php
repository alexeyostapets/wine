<?php
class AdminAuth extends BasicAuth {
    function init(){
        parent::init();
        $this->setModel('UserAdmin','username');
        $this->usePasswordEncryption('sha256/salt');
    }
}
