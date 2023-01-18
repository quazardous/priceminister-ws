<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://global.fr.shopping.rakuten.com/developpeur/87040/
 *
 */
class ImportStockRequest extends AbstractStockRequest {
    use HasMultipartFileRequestTrait;
    
    protected $mode = 'multipart';
    
    protected $parameters = [
        'action' => 'import',
        'version' => '2010-09-20',
    ];
}