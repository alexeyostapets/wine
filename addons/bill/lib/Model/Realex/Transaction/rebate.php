<?php
namespace bill;
class Model_Realex_Transaction_rebate extends Model_Realex_Transaction_generic {
	protected $url="https://epage.payandshop.com/epage-remote.cgi";
	protected $basicXML='<pasref><?$pasref?></pasref>
<authcode><?$authcode?></authcode>
<amount currency="<?currency?>EUR<?/?>"><?amount?>123<?/?></amount>
<refundhash><?refundhash?>738e83....3434ddae662a<?/?></refundhash>
<autosettle flag="<?autosettle?>1<?/?>" />';
	
	protected $mandatory=array(
				'pasref','authcode','amount','refundhash','autosettle'
					);

	protected $hash_order2=".orderid.amount.currency.payer_ref";
    // this hash order is weird but it works


    function init(){
        parent::init();
		$this->set('autosettle',1);
		$this->set('refundhash',sha1($this->api->getConfig('billing/realex/rebate_password')));
    }
}
