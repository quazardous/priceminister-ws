<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://global.fr.shopping.rakuten.com/developpeur/annulation-post-acceptation-cancelitem/
 *
 */
class CancelItemRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'cancelitem',
        'version' => '2011-02-02',
    ];
}