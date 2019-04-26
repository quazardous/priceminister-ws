<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * @link https://developer.fr.shopping.rakuten.com/blog/fr/documentation/inventory-management/import-csv/inventory-import/
 *
 */
class ImportReportStockRequest extends AbstractStockRequest {
    protected $parameters = [
        'action' => 'importreport',
        'version' => '2017-02-10',
    ];
}