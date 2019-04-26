<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * Product Listing request class.
 * 
 * @link https://developer.priceminister.com/blog/en/documentation/product-data/product-listing-secure
 *
 */
class ProductListingRequest extends AbstractRequest {
    protected $url = 'https://ws.fr.shopping.rakuten.com/listing_ssl_ws';
    
    protected $parameters = [
        'action' => 'listing',
        'version' => '2015-07-05',
    ];
}