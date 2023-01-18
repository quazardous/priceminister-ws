<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://global.fr.shopping.rakuten.com/developpeur/confirmer-lenvoi-des-commandes-importitemshippingstatus/
 *
 */
class ImportItemShippingStatusRequest extends AbstractStockRequest {
    protected $mode = 'multipart';
    
    protected $parameters = [
        'action' => 'importitemshippingstatus',
        'version' => '2016-05-09',
    ];
    
    public function setFile($filename)
    {
        $this->addPostField('file', new \CURLFile($filename));
    }
}