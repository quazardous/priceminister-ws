<?php

namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://developer.fr.shopping.rakuten.com/blog/fr/documentation/new-sales/get-new-sales/
 *
 */
class GetNewSalesRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'getnewsales',
        'version' => '2017-08-07',
    ];
}