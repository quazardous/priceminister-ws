<?php

namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://global.fr.shopping.rakuten.com/developpeur/envoi-dun-message-post-vente-a-lacheteur-contactuseraboutitem/
 *
 */
class ContactUserAboutItemRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'contactuseraboutitem',
        'version' => '2011-02-02',
    ];
}