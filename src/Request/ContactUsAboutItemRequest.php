<?php

namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://global.fr.shopping.rakuten.com/developpeur/envoyer-repondre-a-un-mail-du-sav-contactusaboutitem/
 *
 */
class ContactUsAboutItemRequest extends AbstractSalesRequest {
    protected $parameters = [
        'action' => 'contactusaboutitem',
        'version' => '2011-09-01',
    ];
}