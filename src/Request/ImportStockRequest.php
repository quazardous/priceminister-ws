<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://developer.fr.shopping.rakuten.com/blog/fr/documentation/inventory-management/import-csv/inventory-import/
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