<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://global.fr.shopping.rakuten.com/developpeur/87040/
 *
 */
class ImportStockRequest extends AbstractStockRequest {
    protected $mode = 'multipart';
    
    protected $parameters = [
        'action' => 'import',
        'version' => '2010-09-20',
    ];
    
    public function setFile($filename)
    {
        $this->addPostField('file', new \CURLFile($filename));
    }
}