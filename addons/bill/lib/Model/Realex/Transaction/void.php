<?php
namespace bill;
class Model_Realex_Transaction_void extends Model_Realex_Transaction_generic {
	protected $url="https://epage.payandshop.com/epage-remote.cgi";
	protected $basicXML='<pasref><?$pasref?></pasref>
<authcode><?$authcode?></authcode>';
	protected $mandatory=array(
            'authcode','orderid'    // you should set original orderid too
					);

	protected $hash_order2=".orderid.amount.currency.payer_ref";
    // this hash order is weird but it works
}
