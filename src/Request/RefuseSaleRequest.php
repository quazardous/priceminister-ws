<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://developer.fr.shopping.rakuten.com/blog/fr/documentation/new-sales/accept-or-refuse-sales/
 *
 */
class RefuseSaleRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'refusesale',
        'version' => '2010-09-20',
    ];
}