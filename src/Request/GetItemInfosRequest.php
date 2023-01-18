<?php

namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://global.fr.shopping.rakuten.com/developpeur/recuperer-le-detail-des-elements-lies-a-un-article-getiteminfos/
 *
 */
class GetItemInfosRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'getiteminfos',
        'version' => '2017-08-07',
    ];
}