<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://global.fr.shopping.rakuten.com/developpeur/liste-des-commandes-en-cours-getcurrentsales/
 *
 */
class GetCurrentSalesRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'getcurrentsales',
        'version' => '2021-10-29',
    ];
}