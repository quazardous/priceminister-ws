<?php
namespace Quazardous\PriceministerWs\Request;

/**
 * Product Listing request class.
 * 
 * @link https://developer.priceminister.com/blog/en/documentation/product-data/product-listing-secure
 *
 */
class ProductListingRequest extends AbstractRequest {
    protected $path = 'listing_ssl_ws';
    
    protected $parameters = array(
        'action' => 'listing',
//         'login' => null,
//         'pwd' => null,
//         'version' => null,
//         'scope' => null,
//         'kw' => null,
//         'nav' => null,
//         'refs' => null,
//         'productids' => null,
//         'nbproductsperpage' => null,
//         'pagenumber' => null,
    );
    
    public function validate() {
        if (empty($this->parameters['login'])
            ||empty($this->parameters['pwd'])
            ||empty($this->parameters['version']))
        {
            throw new \InvalidArgumentException("Missing mandatory parameter");
        }
    }
}