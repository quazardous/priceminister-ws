<?php

namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://global.fr.shopping.rakuten.com/developpeur/obtenir-les-informations-de-livraison-getshippinginformation/
 *
 */
class GetShippingInformationRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'getshippinginformation',
        'version' => '2017-09-12',
    ];
}