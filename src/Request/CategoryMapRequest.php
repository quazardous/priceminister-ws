<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * Category Listing request class.
 * 
 * @link https://developer.priceminister.com/blog/en/documentation/product-data/product-listing-secure
 *
 */
class CategoryMapRequest extends AbstractRequest {
    protected $url = 'https://ws.fr.shopping.rakuten.com/categorymap_ws';
    
    protected $parameters = [
        'action' => 'categorymap',
        'version' => '2011-10-11',
    ];
}