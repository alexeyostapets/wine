<?php
namespace bill;
class Model_Realex_Transaction_cardcancelcard extends Model_Realex_Transaction_generic {
	protected $basicXML='<card>
 <ref><?$card_ref?></ref>
 <payerref><?$payer_ref?></payerref>
</card>';
	protected $mandatory=array(
            'card_ref','payer_ref',
					);

	protected $hash_order2=".payer_ref.card_ref";
    // this hash order is weird but it works
}
