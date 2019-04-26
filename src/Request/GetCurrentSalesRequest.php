<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://developer.fr.shopping.rakuten.com/blog/fr/documentation/post-confirmation-of-sale/get-current-sales/
 *
 */
class GetCurrentSalesRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'getcurrentsales',
        'version' => '2017-08-07',
    ];
}