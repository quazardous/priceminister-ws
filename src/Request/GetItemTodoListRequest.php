<?php

namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://global.fr.shopping.rakuten.com/developpeur/traitement-des-ventes-apres-leur-confirmation-getitemtodolist/
 *
 */
class GetItemTodoListRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'getitemtodolist',
        'version' => '2011-09-01',
    ];
}