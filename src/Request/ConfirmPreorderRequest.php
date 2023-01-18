<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://global.fr.shopping.rakuten.com/developpeur/confirmer-la-reception-dune-precommande-confirmpreorder/
 *
 */
class ConfirmPreorderRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'confirmpreorder',
        'version' => '2013-01-09',
    ];
}