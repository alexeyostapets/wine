<?php
namespace bill;
class Model_Realex_Transaction_payernew extends Model_Realex_Transaction_generic {
	protected $basicXML='<payer type="<?payer_type?>Business<?/?>" ref="<?$payer_ref?>">
 <firstname><?$first_name?></firstname>
 <surname><?$last_name?></surname>
</payer>';
	protected $mandatory=array(
					'payer_ref',
					'first_name','last_name'
					);

	protected $hash_order2=".orderid.amount.currency.payer_ref";
    // this hash order is weird but it works
}
