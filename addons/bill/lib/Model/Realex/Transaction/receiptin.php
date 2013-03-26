<?php
namespace bill;
class Model_Realex_Transaction_receiptin extends Model_Realex_Transaction_generic {
	protected $basicXML='<amount currency="<?currency?>EUR<?/?>"><?amount?>123<?/?></amount>
<payerref><?$payer_ref?></payerref>
<paymentmethod><?$card_ref?></paymentmethod>';
	protected $mandatory=array(
            'card_ref','payer_ref','amount'
					);

	protected $hash_order2=".orderid.amount.currency.payer_ref";
    // this hash order is weird but it works
}
