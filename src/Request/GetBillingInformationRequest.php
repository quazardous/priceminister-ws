<?php

namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://global.fr.shopping.rakuten.com/developpeur/recuperer-les-elements-comptables-dun-panier-donne-getbillinginformation/
 *
 */
class GetBillingInformationRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'getbillinginformation',
        'version' => '2016-03-16',
    ];
}