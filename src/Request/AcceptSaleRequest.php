<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://developer.fr.shopping.rakuten.com/blog/fr/documentation/new-sales/accept-or-refuse-sales/
 *
 */
class AcceptSaleRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'acceptsale',
        'version' => '2010-09-20',
    ];
}