<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://global.fr.shopping.rakuten.com/developpeur/envoyer-le-numero-de-tracking-settrackingpackageinfos/
 *
 */
class SetTrackingPackageInfosRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'settrackingpackageinfos',
        'version' => '2016-03-16',
    ];
}